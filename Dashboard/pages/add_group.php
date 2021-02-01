<?php settings_header("Add group"); ?>

<div class="row row-cols-1 row-cols-md-2">
    <!-- information block -->
    <div class="col mb-4">
        <div class="card">
            <div class="card-header border-info strong">
                Group information
            </div>
            <?php 
            $parent_id=$_GET["parent"];
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

            <form action="parse_settings.php?add_group=<?php echo $parent_id; ?>" method="post">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="group_name" class="col-sm-4 col-form-label">
                            <h6> Group name</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="group_name" name="group_name" class="form-control" type="text" placeholder="Group name" required></input>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="parent_id" class="col-sm-4 col-form-label">
                            <h6> Parent group</h6>
                        </label>
                        <div class="col-sm-8">
                            <select id="parent_id" name="parent_id" class="form-control" required>
                                <option value="NULL">Main level</option>
                                <?php foreach($groups as $group){ ?>
                                    <option value="<?php echo $group["id"]; ?>" <?php if($parent_id == $group["id"]){ echo 'SELECTED="SELECTED"'; } ?>><?php echo $group["name"]; ?></option>
                                <?php } ?>
                            
                            </select>
                        </div>
                    </div>
                    
                    <input class="btn btn-primary btn-block" type="submit" value="Create new group">

                </div>
            </form>
        </div>
    </div>
</div>
<?php settings_footer(); ?>