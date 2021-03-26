<?php settings_header("General settings"); ?>

<div class="card-columns">
    <!-- information block -->
    <div class="card">
        <div class="card-header border-info thick-border">
            Information
        </div>
        <div class="card-body">
            <!-- Sensor Naam -->
            <div class="form-group row">
                <label for="sensor_name" class="col-sm-4 col-form-label">
                    <h6> Website name</h6>
                </label>
                <div class="col-sm-8">
                    <input id="sensor_name" name="website_name" class="form-control disabled" type="text" disabled
                        value="<?php echo $clustername; ?>" placeholder="Sensor Name"></input>
                </div>
            </div>
            <div class="form-group row">
                <label for="endpoint" class="col-sm-4 col-form-label">
                    <h6> Endpoint url</h6>
                </label>
                <div class="col-sm-8">
                    <?php //validate endpoint url
                    if(substr($url, -1)=="/"){ ?>
                        <div class="alert alert-warning" role="alert">
                            There might be an issue with this url<br/>
                            <small>Please remove the trailing "/" from the url in settings.php</small>
                        </div>
                    <?php }else{
                        $url .= "/endpoint.php";
                        $headers = @get_headers($url); 
                        if($headers && strpos( $headers[0], '200')) { ?>
                            <input id="endpoint" onClick="this.setSelectionRange(0, this.value.length)" name="endpoint" class="form-control" type="text" readonly value="<?php echo $url; ?>" placeholder="endpoint url"></input>
                        <?php } else { ?>
                            <input id="endpoint" onClick="this.setSelectionRange(0, this.value.length)" name="endpoint" class="form-control border-danger" type="text" disabled value="<?php echo $url; ?>" placeholder="endpoint url"></input>
                            <div class="alert alert-warning" role="alert">
                                There might be an issue with this url<br/>
                                <small>Try it in your browser first and check the documentation for details on how to fix this.</small>
                            </div>
                        <?php } 
                    }
                    
                    ?>
                    
                </div>
            </div>
            <footer class="blockquote-footer mb-3">Note: Insert this link into a The things network integration as a http integration</footer>
           
        </div>
    </div>


    <!-- email block -->
    <div class="card">
        <div class="card-header border-info thick-border">
            User accounts
        </div>
        <div class="card-body">
            <?php 
            $query = "SELECT * FROM users WHERE activated != '-1' ORDER by activated DESC, user_ID ASC";
            $result = mysqli_query($con, $query);
            while($row = mysqli_fetch_assoc($result)){
            ?>
            <div class="form-group row">
                <label for="user_<?php echo $row["user_ID"]; ?>" class="col-sm-3 col-form-label">
                    <h6><?php if($row["activated"]==0){ echo "<span class='text-danger' title='User did not activate account yet'>".icon_exclamation()."</span> "; } ?><?php echo $row["user_name"]; ?></h6>
                </label>
                <div class="col-sm-7">
                    <input id="user_<?php echo $row["user_ID"]; ?>" name="" class="form-control" type="text"
                        value="<?php echo $row["email"]; ?>" readonly></input>
                </div>
                <div class="col-sm-2">
                    <a href="?settings=user&user=<?php echo $row["user_ID"]; ?>" class="btn btn-outline-primary"><?php echo icon_gear(); ?></a>
                </div>
            </div>
            <?php } ?>
            <a href="?settings=add_user" class="btn btn-outline-primary btn-block">Add user</a>
        </div>
    </div>

    <!-- groups block -->
    <div class="card">
        <div class="card-header border-info thick-border">
            Groups and devices
        </div>
        <div class="card-body">
        <div class="list-group list-group-root well">
        <?php 
            //make lists of sensors and list of groups to show on the home page
            $query = "SELECT * FROM sensoren";
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
                <div class="col-6">Group/sensor</div>
                <div class="col-6 text-right">
                    <a class="btn btn-secondary btn-sm" href="?settings=add_group&parent=NULL" role="button">add a main group</a>
                </div>
            </div>
            <?php
            if(isset($groepen)){
                search_groups(0, $groepen, null);
                if(isset($sensoren) && count($sensoren)){
                    print_group_head(0, array("group_ID" => NULL, "group_name" => "Unregistered:", "parent_group" => NULL));
                    search_sensors(NULL);
                    print_group_end();
                }
            }else{
                echo "<a href='?settings=new_group&parent=0'><h4>Create a first group</h4></a>";
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
                if(is_array($sensoren)){
                    foreach($sensoren as $i => $sensor){
                        //echo $sensor["device_ID"]."-".$sensor["group_ID"]."-".$parent_id."<br/>";
                        if(!isset($parent_id) || $sensor["group_ID"] == $parent_id){
                            $id = $sensor["device_ID"];
                            print_sensor($parent_id, $sensor);
                            unset($sensoren[$i]);
                        }
                    }
                }
            }

            /* Print functions */

            function print_group_head($level, $data){ ?>
                <div class="list-group-item border <?php if(!isset($data["group_ID"])){ echo "list-group-item-warning"; } ?>">
                    <div class="row">
                        <div class="col-6">
                            <a href="#group_<?php echo $data["group_ID"]; ?>" class=" text-reset" data-toggle="collapse">
                                <span style="margin-left:<?php //echo $level; ?>em" class="text-primary">
                                    <?php icon_chevron(); ?>
                                    <?php echo $data["group_name"]; ?>
                                </span>
                            </a>
                        </div>
                        <?php if(isset($data["group_ID"])){ ?>
                    <div class="col-6 text-right">
                        <div class="btn-group" role="group">
                            <button id="add" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo icon_plus(); ?> add
                            </button>
                            <div class="dropdown-menu" aria-labelledby="add">
                            <a class="dropdown-item" href="?settings=add_group&parent=<?php echo $data["group_ID"]; ?>">a new subgroup</a>
                            <a class="dropdown-item" href="?settings=new_device&parent=<?php echo $data["group_ID"]; ?>">a new sensor</a>
                            </div>
                        </div>
                        <a class="btn btn-secondary btn-sm" href="?settings=edit_group&group=<?php echo $data["group_ID"]; ?>" role="button"><?php echo icon_gear(); ?></a>
                        
                    </div>
                        <?php } else { ?>
                            <div class="col-6 text-right">
                                Register these:
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="list-group collapse show" id="group_<?php echo $data["group_ID"]; ?>">
            <?php 
            }

            function print_group_end(){
                echo "</div>";
            }

            function print_sensor($parent_id, $data){
                $data_count = 0;
                $data_output = "";
                ?>
            <div class="list-group-item <?php if(!isset($parent_id)){ echo "list-group-item-warning"; }else{ echo "list-group-item-light"; } ?>">
                <div class="row">

                    <!--<div class="col-2 text-right text-primary"></div>-->
                    <div class="col-9 text-left text-primary">
                        <a href="?sensor=<?php echo $data["device_ID"]; ?>" class="text-reset">
                            <?php 
                            icon_geo(); 
                            if(isset($data["name"])){
                                echo " ".$data["name"]; 
                            }else{
                                echo " ".$data["device_EUI"];
                            }
                            ?>
                        </a>
                    </div>
                    <div class="col-3 text-right">
                        <a class="btn btn-secondary btn-sm" href="?settings=sensor&id=<?php echo $data["device_ID"]; ?>" role="button"><?php echo icon_gear(); ?></a>
                    </div>
                </div>
            </div>
            <?php 
            }

        ?>
        </div>
        </div>
    </div>
                
    

    <?php
    $id = 0;
    $alerts = array(
        0=>array("email"=>array(), "json"=>array()),
        1=>array("email"=>array(), "json"=>array()),
        2=>array("email"=>array(), "json"=>array())
    );
    $query = "SELECT * FROM alerts WHERE device_ID = $id";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($result)){
        $alerts[$row["alert_type"]]["email"] = json_decode($row["action"], true);
        $alerts[$row["alert_type"]]["json"] = json_decode($row["json"], true);
    }
    //print_r($alerts);
    ?>


    <!-- Thresholds block -->
    <div class="card">
    <form method="post" action="parse_settings.php?hardware_alerts&id=<?php echo $id; ?>">
        <div class="card-header border-info thick-border">
            Global Hardware alerts
            <small> (If any devices values exceed:)</small>
        </div>
        <div class="card-body">
            <div id="thresholds">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">If any Battery is Below: </span>
                    </div>
                    <input type="number" name="battery" step="1" value="<?php echo isset($alerts[0]["json"][1])? $alerts[0]["json"][1] : 0; ?>" min="0" max="50" class="form-control">
                    <div class="input-group-append">
                        <span class="input-group-text unit">%</span>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">If no update was received within: </span>
                    </div>
                    <input type="number" step="1" value="<?php echo isset($alerts[0]["json"][0])? $alerts[0]["json"][0] : 0; ?>" min="1" max="4320" name="timeout" class="form-control">
                    <select class="form-control measurement" name="range">
                        <option value="hours">Hour</option>
                        <option value="mins" selected="selected">Minutes</option>
                    </select>
                </div>
                <hr/>
                <select class="multiselect" multiple="multiple" name="hardware_email_list[]">
                    <?php 
                    $query = "SELECT * FROM users WHERE activated = '1' ORDER by activated DESC, user_ID ASC";
                    $result = mysqli_query($con, $query);
                    while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <option value="<?php echo $row["user_ID"]; ?>" <?php if(in_array($row["user_ID"], $alerts[0]["email"])){ echo " selected "; } ?> ><?php echo $row["email"]; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

    <!-- Thresholds block -->
        <div class="card-header border-info thick-border">
            Global Sensor alerts 
            <small> (If any devices values exceed:)</small>
        </div>
        <div class="card-body">
            <?php foreach($measurements_array as $measurement_id => $measurement){ ?>
            <?php if($measurement_id != 0){ ?>
            <div>
                <div class="input-group input-group mb-1">
                    <div class="input-group-prepend"><div class="input-group-text">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input enabler" id="<?php echo $measurement["variable_name"]."1";?>" <?php echo isset($alerts[1]["json"][$measurement_id])?" checked='checked' ":""; ?> >
                            <label class="custom-control-label" for="<?php echo $measurement["variable_name"]."1";?>">&ZeroWidthSpace;</label>
                        </div>
                    </div></div>
                    <div class="input-group-prepend">
                        <span class="input-group-text">If <?php echo $measurement["print_name"]; ?> is above: </span>
                    </div>
                    <input type="number" 
                        value="<?php echo isset($alerts[1]["json"][$measurement_id])? $alerts[1]["json"][$measurement_id] : 0; ?>" 
                        name="above[<?php echo $measurement_id; ?>]" 
                        class="form-control enabler_input" <?php echo isset($alerts[1]["json"][$measurement_id])?"":"disabled='disabled' "; ?> 
                        <?php if($measurement["type"] == "float"){ echo 'step="0.01" '; }elseif($measurement["type"] == "big_float"){ echo 'step="0.1" '; }else{ echo /*Assume int*/ 'step="1" '; } ?> 
                        min="<?php echo $measurement["range"][0]; ?>" max="<?php echo $measurement["range"][1]; ?>" 
                    />
                    <div class="input-group-append">
                        <span class="input-group-text unit"><?php echo $measurement["unit"]; ?></span>
                    </div>
                </div>
            </div>
            <div>
                <div class="input-group input-group mb-3">
                    <div class="input-group-prepend"><div class="input-group-text">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input enabler" id="<?php echo $measurement["variable_name"]."2";?>" <?php echo isset($alerts[2]["json"][$measurement_id])?" checked='checked' ":""; ?> >
                            <label class="custom-control-label" for="<?php echo $measurement["variable_name"]."2";?>">&ZeroWidthSpace;</label>
                        </div>
                    </div></div>
                    <div class="input-group-prepend">
                        <span class="input-group-text">If <?php echo $measurement["print_name"]; ?> is below: </span>
                    </div>
                    <input type="number" 
                        value="<?php echo isset($alerts[2]["json"][$measurement_id])? $alerts[2]["json"][$measurement_id] : 0; ?>" 
                        name="below[<?php echo $measurement_id; ?>]" 
                        class="form-control enabler_input" <?php echo isset($alerts[2]["json"][$measurement_id])?"":"disabled='disabled' "; ?> 
                        <?php if($measurement["type"] == "float"){ echo 'step="0.01" '; }elseif($measurement["type"] == "big_float"){ echo 'step="0.1" '; }else{ echo /*Assume int*/ 'step="1" '; } ?> 
                        min="<?php echo $measurement["range"][0]; ?>" max="<?php echo $measurement["range"][1]; ?>" 
                    />
                    <div class="input-group-append">
                        <span class="input-group-text unit"><?php echo $measurement["unit"]; ?></span>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>
            <hr/>
            <select class="multiselect" multiple="multiple" name="email_list2[]">
                <?php 
                $query = "SELECT * FROM users WHERE activated = '1' ORDER by activated DESC, user_ID ASC";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_assoc($result)){
                ?>
                <option value="<?php echo $row["user_ID"]; ?>" <?php if(in_array($row["user_ID"], $alerts[1]["email"])){ echo " selected "; } ?>><?php echo $row["email"]; ?></option>
                <?php } ?>
            </select>
            <input class="btn btn-outline-primary btn-block mt-3" type="submit" value="Update threshold alerts">
        </div>
    </form>
    </div>

    <div class="card">
    <form method="post" action="parse_settings.php?visualization">
        <div class="card-header border-info thick-border">
            Data visualization
            <footer class="blockquote-footer">This defines if the graphs include the 0 point in view or zoom into the data range itself.</footer>
        </div>
        <?php 
        $query = "SELECT * FROM varia WHERE name = 'visualization'";
        $result = mysqli_query($con, $query);
        while($row = mysqli_fetch_assoc($result)){
            $visualization = json_decode($row["value"]);  
        }
        ?>
        <div class="card-body">
            <?php foreach($measurements_array as $measurement_id => $measurement){ ?>
                <div class="custom-control custom-switch mb-2 input-group-text text-left">
                    <span class="mx-1">&nbsp;</span>
                    <input class="custom-control-input" type="checkbox" name="zero[<?php echo $measurement_id; ?>]" value="1" id="<?php echo $measurement["variable_name"]."zero"; ?>" <?php echo isset($visualization[$measurement_id])?" checked='checked' ":""; ?> >
                    <label class="custom-control-label" for="<?php echo $measurement["variable_name"]."zero"; ?>">
                        <?php echo $measurement["print_name"]; ?>
                    </label>
                </div>
            <?php } ?>
        <footer class="blockquote-footer">Turn on to include the 0 point</footer>
        <input class="btn btn-outline-primary btn-block mt-3" type="submit" value="Update visualizations">
        </div>
    </form>
    </div>


    <script>
        $('.enabler').change(function() {
            $(this).parent().parent().parent().parent().find(".enabler_input").prop( "disabled", !this.checked );    
        });
        
    </script>


</div>
