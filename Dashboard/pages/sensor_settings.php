<?php settings_header("Sensor settings"); ?>
<?php
$id = $_GET["id"];
$query = "SELECT * FROM sensoren WHERE device_ID = $id";
$result = mysqli_query($con, $query);
while($row = mysqli_fetch_assoc($result)){
    $sensor = $row;
}
?>
<div class="card-columns">
    <div class="card">
        <form method="post" action="parse_settings.php?edit_sensor&id=<?php echo $id; ?>">
            <div class="card-header border-info thick-border">
                Information
            </div>
            <div class="card-body">
                    <!-- Sensor Naam -->
                    <div class="form-group row">
                    <label for="sensor_name" class="col-sm-4 col-form-label"><h6> Sensor name</h6></label>
                    <div class="col-sm-8">
                        <input  id="sensor_name" name="sensor_name" class="form-control" type="text" value="<?php echo $sensor["name"]; ?>" placeholder="Sensor Name"></input>
                    </div>
                </div>

                <!-- Sensor groep -->
                <?php 
                $query = "SELECT * FROM groepen";
                $result = mysqli_query($con, $query);
                $groups = array();
                while($row = mysqli_fetch_assoc($result)){
                    $group_id = $row["group_ID"];
                    $groups[$group_id]["name"] = $row["group_name"];
                    $groups[$group_id]["id"] = $row["group_ID"];
                    $groups[$group_id]["parent"] = $row["parent_group"];
                }
                ?>
                <div class="form-group row">
                    <label for="parent_group" class="col-sm-4 col-form-label" ><h6>  Select group </h6></label>
                    <div class="col-sm-8">
                        <select id="parent_id" name="parent_group" class="form-control" required>
                                    <option value="NULL">Main level</option>
                                    <?php foreach($groups as $group){ ?>
                                        <option value="<?php echo $group["id"]; ?>" <?php if($sensor["group_ID"] == $group["id"]){ echo 'SELECTED="SELECTED"'; } ?>><?php echo $group["name"]; ?></option>
                                    <?php } ?>
                                </select>
                        </select>
                    </div>
                </div>
                
                <!-- Sensor url-->
                <div class="form-group row">
                    <label for="sensor_pid" class="col-sm-4 col-form-label"><h6> External url</h6></label>
                    <div class="col-sm-8">
                        <input id="sensor_url" name="sensor_url" class="form-control" type="url" value="<?php echo $sensor["url"]; ?>" placeholder="url"></input>
                    </div>
                </div>
            
                <!-- Sensor TTN info-->
                <div class="form-group row">
                    <label for="sensor_ttn" class="col-sm-4 col-form-label"><h6> Sensor EUI </h6></label>
                    <div class="col-sm-8">
                        <input disabled="disabled" id="sensor_ttn" name="sensor_ttn" class="form-control" type="text" value="<?php echo $sensor["device_EUI"]; ?>" placeholder="Sensor EUI"></input>
                    </div>
                </div>

                <!-- Sensor discription -->
                <div class="form-group row">
                    <label for="sensor_discription" class="col-sm-4 col-form-label" ><h6>  Note/Discription </h6></label>
                    <span class="col-sm-8">
                        <textarea class="form-control" name="note" id="sensor_discription" rows="2" col="12"><?php echo $sensor["description_note"]; ?></textarea>
                    </span>
                </div>
            
                <input class="btn btn-outline-primary btn-block" type="submit" value="Update information">

                <div class="form-group row mt-3">
                    <label for="password" class="col-sm-4 col-form-label">
                        <h6>Delete sensor</h6>
                    </label>
                    <div class="col-sm-8">
                        <a href="parse_settings.php?delete_sensor=<?php echo $id; ?>" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger btn-block">Delete this sensor</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Iframe integratie block -->
    <div class="card">
        <div class="card-header border-info thick-border">
            Inline frame links
        </div>
        <div class="card-body">
        <form id="iframe">
            <label for="what">What to show</label>
            <div class="custom-control custom-switch mb-2 input-group-text text-left">
                <span class="mx-1">&nbsp;</span>
                <input class="custom-control-input" type="checkbox" name="what[]" value="title" id="what1">
                <label class="custom-control-label" for="what1">Location & name</label>
            </div>
            <div class="custom-control custom-switch mb-2 input-group-text text-left">
                <span class="mx-1">&nbsp;</span>
                <input class="custom-control-input" type="checkbox" name="what[]" value="last" id="what2" checked>
                <label class="custom-control-label" for="what2">Last recording</label>
            </div>
            <div class="custom-control custom-switch mb-2 input-group-text text-left">
                <span class="mx-1">&nbsp;</span>
                <input class="custom-control-input" type="checkbox" name="what[]" value="graph" id="what3">
                <label class="custom-control-label" for="what3">Graphs</label>
            </div>
            <div class="custom-control custom-switch mb-2 input-group-text text-left">
                <span class="mx-1">&nbsp;</span>
                <input class="custom-control-input" type="checkbox" name="what[]" value="alerts" id="what4">
                <label class="custom-control-label" for="what4">Alarms</label>
            </div>
         
            <label for="when">Date range to show</label>
            <div class="input-daterange input-group input-group-sm" id="datepicker">
                <span class="input-group-prepend"><span class="input-group-text"><?php echo icon_calendar(); ?></span></span>
                <input type="text" class="input-sm form-control" name="start" style="min-width: 90px;" />
                <span class="input-group-prepend"><span class="input-group-text">to</span></span>
                <input type="text" class="input-sm form-control" name="end" style="min-width: 90px;" />
            </div>
            <footer class="blockquote-footer mb-3">Leave blank to show most recent 3 months values</footer>
            
            <div class="input-group-prepend"><div class="input-group-text w-100">
                <div class="custom-control custom-switch w-100">
                    <input type="checkbox" class="custom-control-input enabler" id="allow_check" name="allow_time">
                    <label class="custom-control-label w-100" for="allow_check">Allow timeframe selection options</label>
                </div>
            </div></div>
            


            <input type="hidden" name="sensor_id" value="<?php echo $id; ?>"/>
            <input class="btn btn-outline-primary btn-block mt-3" type="submit" value="Generate Iframe code">
            
            <div class="input-group mt-3">
                <input type="text" id="iframe_text" class="form-control iframe_block" readonly onClick="this.setSelectionRange(0, this.value.length)" value="<iframe src='<?php echo $url; ?>/iframe.php?sensor=<?php echo $id; ?>&measurement=last' height='210px' width='100%'></iframe>" placeholder="Some path" id="copy-input">
            </div>     
        </form>    
        </div>
        <div class="card-header border-info thick-border">
            Preview
        </div>
        <div class="card-body" id="iframe_preview" style="padding: 0.25em; border: 3px solid #3498db; border-top-width: 1px; transform: scale(0.66666666); width: 150%; transform-origin: 0 0;">
            <footer class="blockquote-footer text-center" style="font-size:1.5em">No preview yet</footer>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#iframe").on("submit", function(event){
                event.preventDefault();
        
                var formValues= $(this).serialize();
        
                $.post("parse_settings.php?iframe", formValues, function(data){
                    // Display the returned data in browser
                    $("#iframe_text").val(data);
                    $("#iframe_preview").html(data);
                });
            });
        });
    </script>
    
    <!-- Calibration block -->
    <div class="card">
        <div class="card-header border-info thick-border">
            Calibrations
        </div>
        <div class="card-body">
        <form method="post" action="parse_settings.php?edit_sensor_offsets&id=<?php echo $id; ?>">
            <?php 
            $active_list = false; 
            $offset_list = json_decode($sensor["offsets"], true);
            ?>
            <?php foreach($measurements_array as $measurement_id => $measurement){ ?>
                <?php if(!is_null($offset_list[$measurement_id]) && $measurement_id !=0){ ?>
                    <?php $active_list = true; ?>
                    <div class="form-group row">
                        <label for="<?php echo $measurement["print_name"]; ?>" class="col-sm-4 col-form-label"><h6> <?php echo $measurement["print_name"]; ?> Offset </h6></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input id="<?php echo $measurement["print_name"]; ?>" name="offset[<?php echo $measurement_id; ?>]" class="form-control" type="number" min="<?php echo $measurement["calibration"][0]; ?>" max="<?php echo $measurement["calibration"][1]; ?>" value="<?php echo $offset_list[$measurement_id]; ?>" <?php if($measurement["type"] == "float"){ echo 'step="0.01" '; }elseif($measurement["type"] == "big_float"){ echo 'step="0.1" '; }else{ echo 'step="1" '; } ?> ></input>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2" style="min-width: 55px;"><?php echo $measurement["unit"]; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if(!$active_list){ ?>
                <div class="alert alert-info" role="alert">
                    Sensor calibrations will become available as soon as the node sends it's initial data sample!
                </div>
            <?php } ?>
            <footer class="blockquote-footer mb-3">Note: A change in the offsets does not update historical data, only future recordings</footer>
            <input class="btn btn-outline-primary btn-block" type="submit" value="Update offsets">
        </form>
        </div>
    </div>


    <?php
    $alerts = get_sensor_tresholds($id);
    
    //print_r(count($alerts[0]["json"]));
    ?>

    <!-- Thresholds block -->
    <div class="card">
    <form method="post" action="parse_settings.php?hardware_alerts&id=<?php echo $id; ?>">
        <?php if($alerts[0]["scope"] == 0){ ?>
        <div class="alert alert-info" role="alert">
            <h4>General settings</h4>
            At the moment these are the values from the global settings.<br/>
            You can make edits to make local settings for just this device or leave them and make edits to the <a href="?settings=general">global settings</a> page.
        </div>
        <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            <h4>Local settings</h4>
            These are custom local values just for this sensor and might deviate from the global settings.
            <div class="clearfix">
                <a href="parse_settings.php?hardware_alerts=clear&id=<?php echo $id; ?>" class="btn btn-primary float-right">Revert to Global settings</a>
            </div>
        </div>
        <?php } ?>
        <div class="card-header border-info thick-border">
            Hardware alerts
        </div>
        <div class="card-body">
            <div id="thresholds">
                <?php if(!is_null($offset_list[0])){ ?>
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">If the Battery is Below: </span>
                    </div>
                    <input type="number" name="battery" step="1" value="<?php echo isset($alerts[0]["json"][1])? $alerts[0]["json"][1] : 0; ?>" min="0" max="50" class="form-control">
                    <div class="input-group-append">
                        <span class="input-group-text unit">%</span>
                    </div>
                </div>
                <?php } ?>

                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">If no update was received in the last: </span>
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
                    <option value="<?php echo $row["user_ID"]; ?>" <?php if(isset($alerts[0]["email"]) && in_array($row["user_ID"], $alerts[0]["email"])){ echo " selected "; } ?> ><?php echo $row["email"]; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

    <!-- Thresholds block -->
        <div class="card-header border-info thick-border">
            Threshold alerts
        </div>
        <div class="card-body">
            <?php foreach($measurements_array as $measurement_id => $measurement){ ?>
            <?php if(!is_null($offset_list[$measurement_id]) && $measurement_id !=0){ ?>
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
                <option value="<?php echo $row["user_ID"]; ?>" <?php if(isset($alerts[1]["email"]) && in_array($row["user_ID"], $alerts[1]["email"])){ echo " selected "; } ?>><?php echo $row["email"]; ?></option>
                <?php } ?>
            </select>
            <input class="btn btn-outline-primary btn-block mt-3" type="submit" value="Update threshold alerts">
        </div>
    </form>
    </div>
    <?php
    /*
    Save alerts in 3 lists:
    0: hardware alerts
    1: maximal alerts
    2: minimal alerts
    */
    ?>
    <script>
        $('.enabler').change(function() {
            $(this).parent().parent().parent().parent().find(".enabler_input").prop( "disabled", !this.checked );    
        });
    </script>
    <script>
    //let now = new Date();
    //let startdate = new Date();
    //startdate.setDate(startdate.getDate() - 3);
    $("#datepicker [name='start']").val("<?php echo date("Y/m/d", strtotime("-1 Month")); ?>");
    $("#datepicker [name='end']").val("<?php echo date("Y/m/d", strtotime("+1 Day")); ?>");
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
</div>
