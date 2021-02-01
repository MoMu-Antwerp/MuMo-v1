<?php

//REQUEST ALL USERS email
$users = null;
$query = "SELECT user_ID, email FROM users";
$result = mysqli_query($con, $query);
while($row = mysqli_fetch_assoc($result)){
    $users[$row["user_ID"]] = $row;
}

//REQUEST ALL KNOWN NOTIFICATIONS
$notifications = null;
$notification_ids = null;
$query = "SELECT * FROM notifications WHERE device_ID = $ID AND active != '0' AND HOUR(TIMEDIFF(NOW(), timestamp))<24";
$result = mysqli_query($con, $query);
while($row = mysqli_fetch_assoc($result)){
    $notifications[$row["alert_ID"]][$row["measurement_ID"]] = $row;
    $notification_ids[$row["notification_ID"]] = $row;
}

//REQUEST ALL LIMITS

$alerts = get_sensor_tresholds($ID);
foreach($alerts as $alert_ID => $row){

    $scope = ($row["scope"])?"sensor":"globally";

    //check if any of the values are above their alert values.
    if($alert_ID == 0){ //General alerts (battery and timeing)
        //If battery alert is set, check it
        if(!is_null($json[0]) && $json[0] < $row["json"][1]){
            if(isset($notifications[$row["alert_ID"]][0])){ // if known, remove from list
                unset($notifications[$row["alert_ID"]][0]);
                unset($notification_ids[ $notifications[$row["alert_ID"]][0]["notification_ID"] ]);
            }else{ //if unknown, add to list
                $emails[json_encode($row["email"])][] = "A battery fell below the ".$scope." set minimal value (".$json[0]."%)";
                $database[] = array($row["alert_ID"], 0, $json[0], "A battery fell below the ".$scope." set minimal value (".$json[0]."% with a limit at ".$row["json"][1]."%)", json_encode($row["email"]));
            }
        }
    }elseif($alert_ID == 1){ //maximal value alerts
        foreach($measurements_array as $measurement_id => $measurement){
            if($measurement_id != 0){
                if(isset($json[$measurement_id]) && isset($row["json"][$measurement_id]) && $json[$measurement_id] > $row["json"][$measurement_id]){ 
                    if(isset($notifications[$row["alert_ID"]][$measurement_id])){ // if known, remove from list
                        unset($notification_ids[ $notifications[$row["alert_ID"]][$measurement_id]["notification_ID"] ]);
                    }else{ //if unknown, add to list
                        $emails[json_encode($row["email"])][] = "A ".$measurement["print_name"]." value exceeded the ".$scope." set maximal value (".$json[$measurement_id].$measurement["unit"]." with a limit at ".$row["json"][$measurement_id].$measurement["unit"].")";
                        $database[] = array($row["alert_ID"], $measurement_id, $json[$measurement_id], "A ".$measurement["print_name"]." value exceeded the ".$scope." set maximal value (".$json[$measurement_id].$measurement["unit"]." with a limit at ".$row["json"][$measurement_id].$measurement["unit"].")", json_encode($row["email"]));
                    }
                } 
            }
        }
    }elseif($alert_ID == 2){ //minimal value alerts
        foreach($measurements_array as $measurement_id => $measurement){
            if($measurement_id != 0){
                if(isset($json[$measurement_id]) && isset($row["json"][$measurement_id]) && $json[$measurement_id] < $row["json"][$measurement_id]){ 
                    if(!isset($notifications[$row["alert_ID"]][$measurement_id])){ // if known, remove from list
                        unset($notifications[$row["alert_ID"]][$measurement_id]);
                        unset($notification_ids[ $notifications[$row["alert_ID"]][$measurement_id]["notification_ID"] ]);
                    }else{ //if unknown, add to list
                        $emails[json_encode($row["email"])][] = "A ".$measurement["print_name"]." value dropped below the ".$scope." set minimal value (".$json[$measurement_id].$measurement["unit"]." with a limit at ".$row["json"][$measurement_id].$measurement["unit"].")";
                        $database[] = array($row["alert_ID"], $measurement_id, $json[$measurement_id], "A ".$measurement["print_name"]." value dropped below the ".$scope." set minimal value (".$json[$measurement_id].$measurement["unit"]." with a limit at ".$row["json"][$measurement_id].$measurement["unit"].")", json_encode($row["email"]));
                    }
                } 
            }
        }
    }
}


if(!isset($emails)){
    echo "<br/> No alerts!";
    if(isset($notification_ids) && count($notification_ids)){ //There are still open alerts, but the new values are fine, so email about that
        foreach($notification_ids as $notification_content){
            if($notification_content["active"] != "2"){
                //If alert is not cleared already
                $subject = "Some alerts are cleared in the Mumo dashboard";
                $message = "<h3>All previous alerts reported on sensor ".$name." have cleared!</h3>";
                foreach(json_decode($notification_content["notified_users"], true) as $action_user){
                    if(isset($users[$action_user])){
                        universal_email($users[$action_user], $subject, $message);
                    }
                }
            //set active to 2, and set updated timestamp?
            //print_r($notification_content);
            $note_id = $notification_content["notification_ID"];
            //UPDATE notifications SET active = '2', notification = CONCAT(notification, ' | ', 'The issue was cleared '), duration = TIMESTAMPDIFF(minute, notifications.timestamp, now() ) WHERE notification_ID = '6'
            mysqli_query($con, "UPDATE notifications SET active = '2', notification = CONCAT(notification, ' | ', 'The issue was cleared '), duration = TIMESTAMPDIFF(minute, notifications.timestamp, now() ) WHERE notification_ID = '$note_id';");
            }
        }
    }
}else{
    foreach($emails as $email_to => $email_content){
        //email($email_content, $users, );
        $subject = "Some alerts are reported in the Mumo dashboard";

        $message = "<h3>Following alerts have been reported on sensor: ".$name."</h3><hr/>";
        $message .= implode("<hr/>", $email_content);

        foreach(json_decode($email_to, true) as $action_user){
            if(isset($users[$action_user])){
                universal_email($users[$action_user], $subject, $message);
            }
        }
    }
}

if(isset($database)){
    foreach($database as $entry){
        $alert_ = $entry[0];
        $measurement_ = $entry[1];
        //2 = value?
        $notification_ = "On device <strong>".$name.":</strong> ".$entry[3];
        $notified_users_ = $entry[4];
        
        mysqli_query($con, "INSERT INTO `notifications` (`device_ID`, `alert_ID`, `measurement_ID`, `notification`, `notified_users`) VALUES ($ID, $alert_, $measurement_, '$notification_', '$notified_users_');") or die ('Unable to execute query. '. mysqli_error($con));
    }
}

// Check when the last verification happened on all the sensors timekeeping
$query = "select MINUTE(TIMEDIFF(NOW(), timestamp)) as time FROM varia WHERE name='last_update'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
if($row["time"]>10){
    //If more then 10 minutes ago check if any sensors are behind on their updates
    $query = "SELECT * FROM sensoren";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($result)){
        $dev_id = $row["device_ID"];

        $query2 = "SELECT ((HOUR(TIMEDIFF(NOW(), timestamp))*60) + MINUTE(TIMEDIFF(NOW(), timestamp))) as time FROM data WHERE device_ID='".$dev_id."' ORDER by data_ID DESC limit 0,1";
        $result2 = mysqli_query($con, $query2);
        //print_r(mysqli_fetch_assoc($result));
        $time = mysqli_fetch_assoc($result2)["time"];
        $query2 = "SELECT * FROM alerts WHERE device_ID = $dev_id OR device_ID = '0' AND alert_type = '0' ORDER BY device_ID ASC";
        $result2 = mysqli_query($con, $query2);
        //print_r(mysqli_fetch_assoc($result));
        $alert = mysqli_fetch_assoc($result2);
        $alert["json"] = json_decode($alert["json"], true);
        if($alert["json"][1] < $time){
            //check if already alarmed
            //echo "Alarm";
            //if not, send alarm
        }
        
    }
}

