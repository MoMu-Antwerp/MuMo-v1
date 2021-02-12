<?php
include("settings.php");
if(isset($_GET["logout"])){
    session_destroy();
    header('Location: index.php');
    exit;
}elseif(isset($_GET["login"])){
    $login = $_POST["login"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE user_login = '$login' OR email = '$login'";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($result)){
        if(password_verify($password, $row["user_password"])){
            $_SESSION["user"] = $row["user_ID"];
            $_SESSION["user_name"] = $row["user_name"];
            $_SESSION["user_privileges"] = $row["privileges"];
            header('Location: index.php');
            exit;
        }else{
            session_destroy();
            header('Location: index.php?error=wrong_password');
            exit;
        }
    }
    session_destroy();
    header('Location: index.php?error=user_not_found');
    exit;


}elseif(isset($_GET["new_user"])){
    $name = $_POST["user_name"];
    $email = $_POST["email"];
    if(isset($_POST["edit"]) && $_POST["edit"] == "true"){
        $edit = 1;
    }else{
        $edit = 0;
    }
    mysqli_query($con, "INSERT INTO users (user_name, email, privileges) VALUES ('".$name."','".$email."',".$edit.")") or die ('Unable to execute query. '. mysqli_error($con));
    $id = mysqli_insert_id($con);

    $subject = "Mumo registration";
    $safety = base64_encode(time());
    $combined_url = $url."/?settings=register_user&id=".$id."&email=".$email."&name=".$name."&stamp=".$safety;

    $message = "
    <html>
        <head>
            <title>You are invited to register to the Mumo dashboard</title>
        </head>
        <body>
            <h3>You are invited to register to the Mumo dashboard</h3>
            <p>Follow this link to activate the account and choose your password:</p>
            <a href='".$combined_url."'>".$combined_url."</a>
        </body>
    </html>
    ";

    universal_email($email, $subject, $message);
    if(isset($show_link) && $_SESSION["user_privileges"] == 2){
        header('Location: index.php?settings=general&succes=user_created_url&url='.base64_encode($combined_url));
    }else{
        header('Location: index.php?settings=general&succes=user_created');
    }
    exit;

}elseif(isset($_GET["reset_password"])){
    //TODO: force password reset of option to reset? misschien huidig wachtwoord deleten uit de database
    $id=$_GET["reset_password"];
    $query = "SELECT * FROM users WHERE user_ID = '$id'";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($result)){
        $email = $row["email"];
        $name = $row["user_name"];
        $login = $row["user_login"];
        $safety = base64_encode(time());
        $combined_url = $url."/?settings=register_user&id=".$id."&email=".$email."&name=".$name."&stamp=".$safety;

        $subject = "Mumo password reset";
        $message = "
        <html><head>
        <title>A password reset to the Mumo dashboard was requested</title>
        </head><body>
        <h3>A password reset to the Mumo dashboard was requested</h3>
        <p>Follow this link to update the account and choose your new password:</p>
        <a href='".$combined_url."'>".$combined_url."</a>
        <p>If this was not requested by you, ignore this email.</p>
        </body></html>
        ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: Mumo platform<do_not_reply@'.$domain.'>' . "\r\n";

        mail($email,$subject,$message,$headers); 
        
        header('Location: index.php?settings=general&succes=email_send');
        exit;
    }
    header('Location: index.php?error=something');
    exit;
    

}elseif(isset($_GET["edit_user"])){
    $id = $_GET["edit_user"];
    $name = $_POST["user_name"];
    $email = $_POST["email"];
    ($_POST["edit"]=="true")?$edit=2:$edit=0;

    if (mysqli_query($con, "UPDATE users SET user_name='$name', email='$email', privileges='$edit' WHERE user_ID='$id'")) {
        header('Location: index.php?succes=user_updated');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}elseif(isset($_GET["delete_user"])){
    $id = $_GET["delete_user"];
    if (mysqli_query($con, "DELETE FROM users WHERE user_ID='$id'")) {
        header('Location: index.php?succes=user_deleted');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}elseif(isset($_GET["register_user"])){
    $id = $_GET["register_user"];
    $login = $_POST["login"];
    $password = $_POST["password"];
    $password = password_hash($password, PASSWORD_BCRYPT);

    if (mysqli_query($con, "UPDATE users SET activated='1', user_login='$login', user_password='$password' WHERE user_id='$id'")) {
        header('Location: index.php');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}elseif(isset($_GET["edit_group"])){
    $id = $_GET["edit_group"];
    $group_name = $_POST["group_name"];
    $parent_id = $_POST["parent_id"];
    echo $parent_id;
    if($_POST["parent_id"] == "NULL"){
        $parent_id = "null";
    }else{
        $parent_id = "'".$_POST["parent_id"]."'";
    }
    if (mysqli_query($con, "UPDATE groepen SET group_name='$group_name', parent_group=$parent_id WHERE group_ID='$id'")) {
        header('Location: index.php?succes=group_updated');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}elseif(isset($_GET["delete_group"])){
    $id = $_GET["delete_group"];
    if (mysqli_query($con, "UPDATE groepen SET parent_group=NULL WHERE parent_group='$id'")) {
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }   
    if (mysqli_query($con, "DELETE FROM groepen WHERE group_ID ='$id'")) {
        header('Location: index.php?succes=group_deleted');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

}elseif(isset($_GET["add_group"])){
    $parent = $_POST["parent_id"];
    if($parent == "NULL"){
        $parent = "NULL";
    }else{
        $parent = "'".$parent."'";
    }
    $name = $_POST["group_name"];
    mysqli_query($con, "INSERT INTO groepen (group_name, parent_group) VALUES ('".$name."',".$parent.")") or die ('Unable to execute query. '. mysqli_error($con));
    $ID = mysqli_insert_id($con);

    header('Location: index.php?settings=general');
    exit;
}elseif(isset($_GET["edit_sensor"])){
    $id = $_GET["id"];
    $name = $_POST["sensor_name"];
    $parent_group = $_POST["parent_group"];
    $url = $_POST["sensor_url"];
    $note = $_POST["note"];
    if (mysqli_query($con, "UPDATE sensoren SET name='$name', group_ID='$parent_group', description_note='$note', url='$url' WHERE device_ID='$id'")) {
        header('Location: index.php?succes=sensor_updated&sensor='.$id);
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }   
}elseif(isset($_GET["edit_sensor_offsets"])){
    $id = $_GET["id"];
    $offsets = $_POST["offset"] + array(null,null,null,null,null,null,null);
    ksort($offsets); //Order is distorted after a combine like above
    $offsets = json_encode($offsets);
    echo $offsets;
    if (mysqli_query($con, "UPDATE sensoren SET offsets='$offsets' WHERE device_ID='$id'")) {
        header('Location: index.php?succes=sensor_updated&sensor='.$id);
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}elseif(isset($_GET["delete_sensor"])){
    $id = $_GET["delete_sensor"];
    if (mysqli_query($con, "DELETE FROM sensoren WHERE device_ID ='$id'")) {
        header('Location: index.php?succes=sensor_deleted');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}elseif(isset($_GET["hardware_alerts"])){
    //print_r($_POST);
    $ID = $_GET["id"];
    mysqli_query($con, "DELETE FROM `alerts` WHERE device_ID = '".$ID."'") or die ('Unable to execute query. '. mysqli_error($con));

    if($_GET["hardware_alerts"] != "clear"){
        // hardware
        $action = json_encode($_POST["hardware_email_list"]);
        $json = array(NULL, NULL);
        if(isset($_POST["timeout"])){
            $json[0] = $_POST["timeout"];
            if($_POST["range"] == "hours"){
                $json[0] *= 60;
            }
        }
        if(isset($_POST["battery"])){
            $json[1] = $_POST["battery"];
        }
        $json = json_encode($json);
        mysqli_query($con, "INSERT INTO alerts (device_ID, alert_type, action, json) VALUES ('".$ID."','0','".$action."', '".$json."')") or die ('Unable to execute query. '. mysqli_error($con));
        
        // Minima
        if(isset($_POST["above"])){
            $json = $_POST["above"] + array(null,null,null,null,null,null,null);
            ksort($json);
        }else{
            $json = array(null,null,null,null,null,null,null);
        }
        $json = json_encode($json);
        $action = json_encode($_POST["email_list2"]);
        mysqli_query($con, "INSERT INTO alerts (device_ID, alert_type, action, json) VALUES ('".$ID."','1','".$action."', '".$json."')") or die ('Unable to execute query. '. mysqli_error($con));
        //maxima
        if(isset($_POST["below"])){
            $json = $_POST["below"] + array(null,null,null,null,null,null,null);
            ksort($json);
        }else{
            $json = array(null,null,null,null,null,null,null);
        }
        $json = json_encode($json);
        mysqli_query($con, "INSERT INTO alerts (device_ID, alert_type, action, json) VALUES ('".$ID."','2','".$action."', '".$json."')") or die ('Unable to execute query. '. mysqli_error($con));
        if($ID != 0){
            header('Location: index.php?settings=sensor&id='.$ID.'&succes=threshold_updated');
        }else{
            header('Location: index.php?settings=general&succes=threshold_updated');
        }
    }else{
        header('Location: index.php?settings=sensor&id='.$ID);
    }

}elseif(isset($_GET["resolved_notification"])){
    $id = $_GET["resolved_notification"];
    $note = $_GET["note"];
    $user_id = $_SESSION["user_name"];
    if (mysqli_query($con, "UPDATE notifications SET note = '$note', note_by = '$user_id', active = '0' WHERE notification_ID = '$id';")) {
        header('Location: index.php?succes=notification_resolved');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }   
}elseif(isset($_GET["visualization"])){
    if(isset($_POST["zero"])){
        $json = $_POST["zero"] + array(null,null,null,null,null,null,null);
        ksort($json);
    }else{
        $json = array(null,null,null,null,null,null,null);
    }
    $json = json_encode($json);
    mysqli_query($con, "DELETE FROM `varia` WHERE name = 'visualization'") or die ('Unable to execute query. '. mysqli_error($con));
    if (mysqli_query($con, "INSERT INTO varia (name, value) VALUES ('visualization', '".$json."');")) {
        header('Location: index.php?succes=changes_updated');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }   
}elseif(isset($_GET["iframe"])){
    $params = array("sensor"=>$_POST["sensor_id"]);
    if(isset($_POST["what"])){
        $measurement = $_POST["what"];
        $params = array_merge(array("measurement"=>$measurement), $params);
    }
    if(isset($_POST["start"])){
        $start = $_POST["start"];
        $params = array_merge(array("start"=>$start), $params);
    }
    if(isset($_POST["end"])){
        $end = $_POST["end"];
        $params = array_merge(array("end"=>$end), $params);
    }
    if(isset($_POST["allow_time"])){
        $params = array_merge(array("time"=>true), $params);
    }
    $height = count($measurement)*225;
    
    $new_query_string = http_build_query( $params );
    echo "<iframe src='".$url."/iframe.php?".$new_query_string."' height='".$height."px' width='100%' style='border:none'></iframe>";
    
}else{
    echo "<h4>Todo:</h4>";
    print_r($_GET);
    echo "<hr/>";
    print_r($_POST);
}