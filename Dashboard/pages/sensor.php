<style>
.dygraph-legend {
    text-align: right;
}

.graph {
    width: 97.5%;
    min-height: max(15vh, 150px);
}

.dygraph-ylabel {
    font-size: 2.5em;
    margin-top: -1em;
    margin-left: 50px;
}
</style>

<?php // get the data for this sensor
	$id = $_GET["sensor"];
	$query = "SELECT * FROM sensoren WHERE device_ID='$id' OR device_EUI='$id' LIMIT 0,1";
    $result = mysqli_query($con, $query);
    if(!mysqli_num_rows($result)){
        echo "<h1 class='text-center'>Sensor not found</h1>";
        exit();
    }
	while($row = mysqli_fetch_assoc($result)){
		$sensor = $row;
      		$id = $row["device_ID"];
    }
    $offsets = json_decode($sensor["offsets"]);

	$query = "SELECT * FROM groepen ORDER by group_ID ASC";
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_assoc($result)){
		$groepen[$row["group_ID"]] = $row;
    }
    
    if(!isset($_GET["measurement"])){
        $show_items = ["title_link","last","graph","alerts", "download"];
    }else{
        $show_items = $_GET["measurement"];
        if(!is_array($show_items)){
            $show_items = array($show_items);
        }
    }
?>

<!-- Header (only if not in iframe) -->
<?php if(array_search("title", $show_items)!== false || array_search("title_link", $show_items)!== false){ ?>
    <div class="page-header mt-4 mb-3">
        <h3 class="page-title">
            <?php if(!isset($_SESSION["user_privileges"]) || $_SESSION["user_privileges"] != 2 || array_search("title", $show_items)!== false){ ?>
                <a class="btn btn-outline-primary btn-lg" href="#" role="button" data-toggle="tooltip" data-placement="left">
                    <?php echo icon_geo(); ?>
                </a>
            <?php }else{ ?>
                <a class="btn btn-outline-primary btn-lg" href="<?php echo (isset($_SESSION["user_privileges"]) && $_SESSION["user_privileges"] > 0)?"?settings=sensor&id=".$_GET["sensor"]:"#"; ?>"
                    role="button" data-toggle="tooltip" data-placement="left" title="Open this sensors settings">
                    <?php echo icon_gear(); ?>
                </a>
            <?php } ?>
            <span class="ml-2">
                <span style="font-size: 1.25rem;"><?php 
                    $parent = $sensor["group_ID"];
                    $output = "";
                    while($parent != NULL){
                        if(isset($groepen[$parent])){
                            $output = $groepen[$parent]["group_name"] ." > ". $output;
                            $parent = $groepen[$parent]["parent_group"];
                        }else{ 
                            $parent = NULL;
                        }
                    }
                    echo $output;
                ?></span>
                <?php if(isset($sensor["name"]) && $sensor["name"] != ""){
                    echo $sensor["name"]; }else{ echo "<small>".$sensor["device_EUI"]."</small>"; } ?>
                <?php if(isset($sensor["url"]) && $sensor["url"] != ""){ ?>
                    <a href="<?php echo $sensor["url"]; ?>" class="text-info" target="_blank" rel="noreferrer noopener">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-link-45deg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                            <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
                        </svg>
                    </a>
                <?php } ?>
            </span>
        </h3>
    </div>
<?php }else{
    echo "<div class='mt-3'> </div>";
} ?>

<!-- LAST -->
<?php if(array_search("last", $show_items)!== false){ ?>
    <h3 class="page-title">
        <span class="badge badge-primary">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clock-history" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                <path fill-rule="evenodd" d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                <path fill-rule="evenodd" d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
            </svg>
        </span>
        Most recent measurement:
    </h3>
    <?php include("pages/sensors.php"); ?>
<?php } ?>

<!-- GRAPHS -->
<?php if(array_search("graph", $show_items)!== false){ ?>
    <div class="row mt-5 mb-2">
        <div class="col-sm">
            <h3 class="page-title">
                <span class="badge badge-primary">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-graph-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5z"/>
                    </svg> 
                </span>
                Measurements history:
            </h3>
        </div>
        <?php if(!isset($_GET["measurement"]) || isset($_GET["time"])){ ?>
            <div class="col-sm">
            <form action="index.php" method="GET">
                <input type="hidden" name="sensor" value="<?php echo $id; ?>"/>
                &nbsp;
                <div class="input-daterange input-group input-group-sm" id="datepicker">
                    <span class="input-group-prepend"><span class="input-group-text"><?php echo icon_calendar(); ?></span></span>
                    <input type="text" class="input-sm form-control" name="start" style="min-width: 90px;" />
                    <span class="input-group-prepend"><span class="input-group-text">to</span></span>
                    <input type="text" class="input-sm form-control" name="end" style="min-width: 90px;" />
                    <span class="input-group-append"><button class="btn btn-primary" type="submit">Set</button></span>
                </div>
            </form>
            </div>
        <?php } ?>
    </div>

    <div class="card">
        <div class="card-body" id="error-show" style="display: none">
            <div class="alert alert-warning mt-3 text-center" role="alert">
            <h4>No data found within the requested time frame!</h4>
            </div>
        </div>
        <div class="card-body" id="error-hide">

            <?php 
            foreach($measurements_array as $measure_ID => $measure){
                if(isset($offsets[$measure_ID])){
                    ?>
                    <div class="card my-3">
                        <div class="row no-gutters">
                            <div class="card-header border-primary border rounded-left text-primary pt-5 text-center" style="min-width: 125px;" >
                                <?php echo $measure["print_name"]; ?>
                                <div class="big_unit" style="font-size: 3em;">
                                <?php echo $measure["unit"]; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<h1 class="display-4 mt-5">Temperature</h1>-->
                                <div id="<?php echo $measure["print_name"]; ?>" class="graph"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="card my-3">
                <div class="row no-gutters">
                    <div class="card-header border-primary border rounded-left text-primary pt-5 text-center" style="min-width: 125px;" >
                        Timing
                        <div class="big_unit" style="font-size: 3em;">
                            min
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="Time" class="graph"></div>
                    </div>
                </div>
            </div>
            <?php
            $params           = array_merge( $_GET, array( 'export' => 'true' ) );
            if(isset($sensor["name"]) && $sensor["name"] != ""){ $params = array_merge( $params, array( 'sensor_name' => $sensor["name"] ) ); }
            $new_query_string = http_build_query( $params );
            ?>
            <?php if(array_search("download", $show_items)!== false){ ?>
                <a download href="export.php?<?php echo $new_query_string; ?>" class="btn btn-primary btn-block"><span class="mr-1"><?php echo icon_save(); ?></span> Export this data</a>
            <?php } ?>
        </div>
    </div>

    <script type="text/javascript">
    <?php
        $start = date("Y/m/d", strtotime("-3 Month"));
        $end = date("Y/m/d", strtotime("+1 Day"));
        if(isset($_GET["start"])){ $start = $_GET["start"]; }
        if(isset($_GET["end"])){ $end = $_GET["end"]; }
        $query = "SELECT timestamp, json, frame_counter FROM data WHERE device_ID = '$id' AND `timestamp` BETWEEN '$start' AND '$end' ORDER by data_ID ASC";
    
        $list;
        $result = mysqli_query($con, $query);
        while($row = mysqli_fetch_assoc($result)){
            $list[] = array_merge(array("timestamp"=>$row["timestamp"]), json_decode($row["json"], true), array("frame"=>$row["frame_counter"]));
        }
        if(isset($list)){
            foreach($measurements_array as $measure_ID => $measure){
                if(isset($offsets[$measure_ID])){
                    print_csv($list, $measure["variable_name"], $measure["print_name"], $measure_ID);
                }
            }
            print_timing($list);
        }else{ ?>
            $("#error-hide").hide();
            $("#error-show").show();
        <?php }

        $query = "SELECT * FROM varia WHERE name = 'visualization'";
        $result = mysqli_query($con, $query);
        while($row = mysqli_fetch_assoc($result)){
            $visualization = json_decode($row["value"]);  
        }
    ?>

    var gs = [];
    <?php 
        foreach($measurements_array as $measure_ID => $measure){
            if(isset($offsets[$measure_ID])){
                ?>
                gs.push(
                    new Dygraph(
                        document.getElementById("<?php echo $measure["print_name"]; ?>"),
                        <?php echo $measure["variable_name"]; ?>, {
                            rollPeriod: 1,
                            customBars: true,
                            strokeWidth: '3',
                            highlightCircleSize: 7.5,
                            colors: ["<?php echo $measure["color"]; ?>"],
                            fillAlpha: 0.25,
                            animatedZooms: true<?php if(isset($visualization[$measure_ID])){ ?>,
                            includeZero: true <?php } ?>
                        }
                    )
                );
                <?php
            }
        }
    ?>
    gs.push(
        new Dygraph(
            document.getElementById("Time"),
            timing, {
                rollPeriod: 1,
                strokeWidth: '3',
                highlightCircleSize: 7.5,
                colors: ["rgba(52, 73, 94,0.75)"],
                animatedZooms: true,
                includeZero: true,
                valueRange: [null, 75]
            }
        )
    );
    var sync = Dygraph.synchronize(gs, {
        zoom: true,
        selection: true,
        range: false
    });
    </script>

    <script>
    //let now = new Date();
    //let startdate = new Date();
    //startdate.setDate(startdate.getDate() - 3);
    $("#datepicker [name='start']").val("<?php echo $start; ?>");
    $("#datepicker [name='end']").val("<?php echo $end; ?>");
    $('#datepicker').datepicker({
        format: 'yyyy/mm/dd',
        endDate: "+1d",
        startView: 1,
        maxViewMode: 3,
        todayBtn: "linked",
        clearBtn: true,
        orientation: "bottom auto",
        autoclose: true,
        todayHighlight: true
    });
    </script>
<?php } ?>

<!-- NOTIFICATIONS -->
<?php if(array_search("alerts", $show_items)!== false){ ?>
    <h3 class="page-title mt-4">
        <span class="badge badge-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
            </svg>
        </span>
        Notifications:
    </h3>
    <div class="card" id="notifications">
        <div class="card-body">
            <div class="card">
                <div class="card-header border-primary border-danger thick-border">Active alerts:</div>
                    <?php
                    //REQUEST ALL KNOWN NOTIFICATIONS
                    $query = "SELECT * FROM notifications WHERE device_ID = $id AND active = '1' ORDER BY 'timestamp' DESC limit 0,50";
                    $result = mysqli_query($con, $query);
                    if(mysqli_num_rows($result)){
                        echo '<div class="card-body"><ul class="list-group list-group-flush">';
                        while($row = mysqli_fetch_assoc($result)){
                            $note = explode("</strong>",$row["notification"]);
                            ?>
                                <li class="list-group-item">
                                    <?php echo '<p class="card-text mb-1">'.end($note).'</p>'; ?>
                                    <button class="btn btn-outline-primary float-right" onclick="myFunction('parse_settings.php?resolved_notification=<?php echo $row['notification_ID']; ?>')">Mark notification as resolved</button>
                                    <p class="card-text"><small class="text-muted" title="<?php echo $row["timestamp"]; ?>"><?php echo time_elapsed_string($row["timestamp"]); ?></small></p>
                                </li>
                            <?php
                        }
                        echo '</ul></div>';
                    }else{ ?>
                        <div class="card-body"><p class="card-text">No active alerts to report</p></div>
                    <?php }
                    ?>

                    <div class="card-footer card-header border-secondary thick-border">Resolved alerts:</div>
                    <?php
                    //REQUEST ALL KNOWN NOTIFICATIONS
                    $query = "SELECT * FROM notifications WHERE device_ID = $id AND active = '0' ORDER BY 'notification_ID' DESC limit 0,50";
                    $result = mysqli_query($con, $query);
                    if(mysqli_num_rows($result)){
                        echo '<div class="card-body"><ul class="list-group list-group-flush">';
                        while($row = mysqli_fetch_assoc($result)){
                            $note = explode("</strong>",$row["notification"]);
                            ?>
                                <li class="list-group-item">
                                    <p class="card-text float-right"><small class="text-muted" title="<?php echo $row["timestamp"]; ?>"><?php echo time_elapsed_string($row["timestamp"]); ?></small></p>
                                    <?php echo '<p class="card-text mb-1">'.end($note).'</p>'; 
                                    ?>
                                    <p class="card-text text-muted">Resolved 
                                        <span title="<?php echo $row["timestamp"]; ?>"><?php echo time_elapsed_string($row["timestamp"]); ?></span> 
                                        by: <?php echo $row["note_by"]; ?>
                                        <span class="ml-5"> <?php echo ($row["note"] == "")?"Without a note": "Note: ".$row["note"]; ?></span>
                                    </p>
                                    
                                </li>
                            <?php
                        }
                        echo '</ul></div>';
                    }else{ ?>
                        <div class="card-body"><p class="card-text">No active alerts to report</p></div>
                    <?php }
                    ?>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function myFunction(link) {
            var person = prompt("Would you like to add a short note? (or leave blank)", "");
            if (person != null) { // if not cancel
                window.location.href = link+"&note="+person;
            }
        }
    </script>
<?php } ?>

<script>
//$(document).ready(function() {
    //$("time.timeago").timeago(); //Mag wellicht weg, gebruik geen realtime meer in de site
//});
</script>

<?php 
function print_csv($list, $variable_name, $visible_name, $json_id){
    //print_r($list);
    $data=array("measurement"=>'"Date,'.$visible_name.'\n"', "time"=>'"Date,time\n"');
    $rolling_data = array(  
        "date"=>0,"min" => 999,"max" => -999,"data"=>array()
    );
    $last_date = 0;
    $last_frame = 0;

    foreach($list as $row){
        $new_rolling_date = substr($row["timestamp"], 0, -8); //remove the microseconds from the timestamp
        if($rolling_data["date"]==""){ $rolling_data["date"] = $new_rolling_date.":30"; $last_frame = $row["frame"]; } //on the first element. Pick that date.
        if($new_rolling_date != $rolling_data["date"]){ //If we hit a new date, transfer the buffer and refresh
            //add the buffer data to the big list;
            foreach($rolling_data["data"] as $temp){
                $data["measurement"] .= '+ "'.substr($temp["timestamp"],0 , -3).','.$rolling_data["min"].';'.$temp["measurement"].';'.$rolling_data["max"].'\n"';
            }
            //Refresh variables
            $rolling_data = array(  
                "date"=>0,"min" => 999,"max" => -999,"data"=>array()
            );
            $rolling_data["date"] = $new_rolling_date;
        }else{//not a new day, so just add to the buffer
            //parse new data and add to buffer
            if($row["frame"] > $last_frame+5){ //first check if we missed a bunch of frames
                $rolling_data["data"][] = array("timestamp"=>$row["timestamp"], "measurement"=>null);
            }
            //then add the nex data to the rolling data
            $rolling_data["min"] = min($rolling_data["min"], $row[$json_id]);
            $rolling_data["max"] = max($rolling_data["max"], $row[$json_id]);
            $rolling_data["data"][] = array("timestamp"=>$row["timestamp"], "measurement"=>$row[$json_id]);
            $last_frame = $row["frame"];
        }
    }
    if($new_rolling_date != 0){
        foreach($rolling_data["data"] as $temp){
            $data["measurement"] .= '+ "'.substr($temp["timestamp"],0 , -3).','.$rolling_data["min"].';'.$temp["measurement"].';'.$rolling_data["max"].'\n"';
        }
    }

    echo "var ".$variable_name." = " .$data["measurement"].";";
}

function print_timing($list){
    $data=array("measurement"=>'"Date,Timing\n"', "time"=>'"Date,time\n"');
    
    $last_date = 0;

    foreach($list as $row){
        if($last_date == 0){ $last_date = $row["timestamp"]; }
        $date_a = strtotime($last_date);
        $date_b = strtotime($row["timestamp"]);
        $interval = ($date_b-$date_a) / 60;
        if($interval < 0) $interval = 0;
        if($interval > 333){ 
            $data["time"] .= '+ "'.substr($last_date,0 , -3).',NaN\n"';
        }
        $last_date = $row["timestamp"];
        $data["time"] .= '+ "'.substr($row["timestamp"],0 , -3).':01,'.$interval.'\n"';
    }

    echo "var timing = " .$data["time"].";";
}
?>
