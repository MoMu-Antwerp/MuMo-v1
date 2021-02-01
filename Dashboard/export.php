<?php include("settings.php");
if(isset($_GET["export"])){
    if(isset($_GET["sensor"])){
        $id = $_GET["sensor"];
        if(isset($_GET["start"]) && isset($_GET["start"])){ 
            $start = $_GET["start"];
            $end = $_GET["end"];
        }else{
            $start = date("Y/m/d", strtotime("-3 Month"));
            $end = date("Y/m/d", strtotime("+1 Day"));
        }
        $query = "SELECT timestamp, json, frame_counter FROM data WHERE device_ID = '$id' AND `timestamp` BETWEEN '$start' AND '$end' ORDER by data_ID ASC";
       
        $list;
        $result = mysqli_query($con, $query);
        $list[0] = array("Timestamp", "Battery", "Temperature", "Humidity", "Illumination", "Pressure", "VOC", "Dust", "Framecounter");
        while($row = mysqli_fetch_assoc($result)){
            $list[] = array_merge(array("timestamp"=>$row["timestamp"]), json_decode($row["json"], true), array("frame"=>$row["frame_counter"]));
        }
        if(isset($_GET["sensor_name"]) && $_GET["sensor_name"] != ""){
            array_to_csv_download($list, $_GET["sensor_name"].".csv");
        }else{
            array_to_csv_download($list);
        }
    }
}


function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    // open the "output" stream
    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
}   
?>