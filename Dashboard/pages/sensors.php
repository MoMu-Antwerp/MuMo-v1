<style>
.col-1 { padding: 0 !important; }
    </style>

<div class="card mb-5">
    <div class="card-body">
        <div class="list-group list-group-root well">
            <?php 
                //make lists of sensors and list of groups to show on the home page
                if(isset($id)){ // for one specific sensor
                    $query = "SELECT * FROM sensoren WHERE device_ID=".$id;
                }else{ // for all sensors
                    $query = "SELECT * FROM sensoren";
                }
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_assoc($result)){
                    $sensoren[$row["device_ID"]] = $row;
                }

                $query = "SELECT * FROM groepen ORDER by group_ID ASC";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_assoc($result)){
                    $groepen[$row["group_ID"]] = $row;
                }
                ?>
                <div class="row" style="padding: .75rem 1.25rem;">
                    <div class="col-2"><?php if(!isset($id)){ echo "Group/<wbr>Sensor"; } ?></div>
                    <div class="col-7 text-center">Measurements</div>
                    <div class="col-2 text-center">Updated</div>
                    <div class="col-1 text-center">Alerts</div>
                </div>
                <?php 

                if(!isset($id)){
                    if(isset($groepen)){
                        search_groups(0, $groepen, null);
                        if(isset($sensoren) && count($sensoren)){
                            print_group_head(0, array("group_ID" => NULL, "group_name" => "Unregistered devices:", "parent_group" => NULL));
                            search_sensors(NULL);
                            print_group_end();
                        }
                    }else{
                        echo "<h4>Start by creating a new group in the settings menu</h4>";
                    }
                }else{
                    search_sensors($sensoren[$id]["group_ID"]);
                }

                function search_groups($level, $list, $parent_id){
                    foreach($list as $id => $list_){
                        if($list_["parent_group"] == $parent_id && $list_["group_ID"] != $parent_id){
                            $sub_id = $list_["group_ID"];
                            print_group_head($level, $list_);
                            search_sensors($id);
                            //recursively search sub groups
                            search_groups($level+1, $list, $list_["group_ID"]);
                            print_group_end();
                        }
                    }
                }

                function search_sensors($parent_id){
                    global $sensoren;
                    if(isset($sensoren)){
                        foreach($sensoren as $i => $sensor){
                            //echo $sensor["sensor_ID"];
                            if(!isset($parent_id) || $sensor["group_ID"] == $parent_id){
                                $id = $sensor["device_ID"];
                                print_sensor($parent_id, array_merge($sensor, (array)get_sensor_data($id)), get_sensor_tresholds($id));
                                unset($sensoren[$i]);
                            }
                        }
                    }
                }

                function get_sensor_data($id){
                    global $con;
                    $query = "SELECT * FROM data WHERE device_ID='".$id."' ORDER by data_ID DESC limit 0,1";
                    $result = mysqli_query($con, $query);
                    //print_r(mysqli_fetch_assoc($result));
                    return mysqli_fetch_assoc($result);
                }

                /* Print functions */
                function print_group_head($level, $data){ ?>
            <a href="#group_<?php echo $data["group_ID"]; ?>" class="list-group-item border text-reset"
                data-toggle="collapse">
                <span style="margin-left:<?php //echo $level; ?>em" class="text-primary">
                    <?php icon_chevron(); ?>
                    <?php echo $data["group_name"]; ?>
                </span>
            </a>
            <div class="list-group collapse show" id="group_<?php echo $data["group_ID"]; ?>">
                <?php 
                }

                function print_group_end(){
                    echo "</div>";
                }

                function print_sensor($parent_id, $data, $thresholds){
                    global $measurements_array;
                    //print_r($thresholds);
                    $data_count = 0;
                    $data_output = "";
                    //print_r($data);
                    if(isset($data["json"])){
                        $json = json_decode($data["json"],true);
                    }
                    if(isset($data["connections"])){
                        if(strlen($data["connections"]) > 5){
                            $connection = json_decode($data["connections"], true);
                            $connection = max(array_column($connection, "rssi"));
                        }else{
                            $connection = $data["connections"];
                        }
                    }
                    ?>
                <div class="list-group-item list-group-item-light">
                    <div class="row">

                        <div class="col-2 text-primary">
                            <a href="?sensor=<?php echo $data["device_ID"]; ?>" class="text-reset" data-toggle="tooltip" data-placement="right" title="<?php echo $data["description_note"]; ?>">
                                <?php 
                                icon_geo(); 
                                if(isset($data["name"])){
                                    echo " ".$data["name"]; 
                                }else{
                                    echo " Unregistered: <br/><small>".$data["device_EUI"]."</small>";
                                }
                                ?>
                            </a>
                        </div>

                        <?php 
                        foreach($measurements_array as $measurement_id => $measurement){
                            if($measurement_id != 0){
                                if(isset($json[$measurement_id])){
                                    $data_count++;
                                    if((isset($thresholds[1]["json"][$measurement_id]) && $json[$measurement_id] > $thresholds[1]["json"][$measurement_id]) || (isset($thresholds[1]["json"][$measurement_id]) && $json[$measurement_id] < $thresholds[2]["json"][$measurement_id])){
                                        $data_output .= '<div class="col-1 text-center bg-danger text-white rounded"  data-toggle="tooltip" data-placement="bottom" title="'.$measurement["print_name"].'">';
                                    }else{
                                        $data_output .= '<div class="col-1 text-center"  data-toggle="tooltip" data-placement="bottom" title="'.$measurement["print_name"].'">';
                                    }
                                    $data_output .= round($json[$measurement_id], 2);
                                    $data_output .= '<br/><small>'.$measurement["unit"].'</small>';
                                    $data_output .= '</div>';
                                }
                            }
                        }

                        if(isset($json[0])){ 
                            $data_count++; 
                            $data_output .= '<div class="col-1 text-center">';
                                if($json[0] > 75){
                                    $data_output .= '<div data-toggle="tooltip" data-placement="bottom" title="Battery level: ~'.round($json[0], 1).'%">'.icon_battery(2).'</div>';
                                }elseif($json[0] > 20){
                                    $data_output .= '<div data-toggle="tooltip" data-placement="bottom" title="Battery level: ~'.round($json[0], 1).'%">'.icon_battery(1).'</div>';
                                }else{
                                    $data_output .= '<div class="bg-danger text-white rounded" data-toggle="tooltip" data-placement="bottom" title="Battery level: ~'.round($json[0], 1).'%">'.icon_battery(0).'</div>';
                                }
                                if(isset($connection)){
                                    if($connection < -100){
                                        $data_output .=  '<div class="bg-danger text-white rounded" style="margin-top: -5px;" data-toggle="tooltip" data-placement="bottom" title="RSSI: '.$connection.'">'.icon_connection(1).'</div>';
                                    }elseif($connection < -70){
                                        $data_output .=  '<div style="margin-top: -5px;" data-toggle="tooltip" data-placement="bottom" title="RSSI: '.$connection.'">'.icon_connection(2).'</div>';
                                    }elseif($connection < -50){
                                        $data_output .=  '<div style="margin-top: -5px;" data-toggle="tooltip" data-placement="bottom" title="RSSI: '.$connection.'">'.icon_connection(3).'</div>';
                                    }elseif($connection < -30){
                                        $data_output .=  '<div style="margin-top: -5px;" data-toggle="tooltip" data-placement="bottom" title="RSSI: '.$connection.'">'.icon_connection(4).'</div>';
                                    }else{
                                        $data_output .=  '<div style="margin-top: -5px;" data-toggle="tooltip" data-placement="bottom" title="RSSI: '.$connection.'">'.icon_connection(4).'</div>';
                                    }
                                }
                            $data_output .= "</div>";
                        } 
                        //12 spots to devide, 5 taken by id and notification area, 7 left. Make it somewhat centered
                        for($i=0; $i<((7-$data_count)/2); $i++){
                            echo '<div class="col-1"> </div>'; $data_count++;
                        }
                        echo $data_output;
                        
                        while($data_count<7){
                            echo '<div class="col-1"> </div>'; $data_count++;
                        }
                        if(isset($data["timestamp"])){
                            echo '<div class="col-2 text-center';
                            if(strtotime($data["timestamp"]) < strtotime("-".$thresholds[0]["json"][0]." minutes")){ echo ' bg-danger text-white rounded'; }
                            echo '">'.time_elapsed_string($data["timestamp"], "<br/>").'</div>';
                        }else{
                            echo '<div class="col-2 text-center">Never seen</div>';
                        }
                        ?>
                        <div class="col-1 text-center">
                            <?php 
                            //REQUEST ALL KNOWN NOTIFICATIONS
                            global $con;
                            $device_id = $data["device_ID"];
                            $query = "SELECT * FROM notifications WHERE device_ID = $device_id AND active = '1'";
                            $result = mysqli_query($con, $query);
                            if(mysqli_num_rows($result)){ ?>
                                <a href="?sensor=<?php echo $data["device_ID"]; ?>#notifications" class="badge badge-danger p-2 px-3"><h5><?php echo mysqli_num_rows($result); ?></h5></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
                }

            ?>
            </div>
        </div>
    </div>
</div>

<?php 
