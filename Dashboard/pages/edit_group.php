<?php settings_header("Edit group"); ?>

<div class="row row-cols-1 row-cols-md-2">
    <!-- information block -->
    <div class="col mb-4">
        <div class="card">
            <div class="card-header border-info strong">
                Group information
            </div>
            <?php 
            $id=$_GET["group"];
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

            <form action="parse_settings.php?edit_group=<?php echo $id; ?>" method="post">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="group_name" class="col-sm-4 col-form-label">
                            <h6> Group name</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="group_name" name="group_name" class="form-control" type="text"
                               value="<?php echo $groups[$id]["name"]; ?>" placeholder="Group name" required></input>
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
                                    <option value="<?php echo $group["id"]; ?>" <?php if($groups[$id]["parent"] == $group["id"]){ echo 'SELECTED="SELECTED"'; } ?>><?php echo $group["name"]; ?></option>
                                <?php } ?>
                            
                            </select>
                        </div>
                    </div>
                    
                    <input class="btn btn-primary btn-block" type="submit" value="Update information">

                    <div class="form-group row mt-3">
                        <label for="password" class="col-sm-4 col-form-label">
                            <h6>Delete group</h6>
                        </label>
                        <div class="col-sm-8">
                            <a href="parse_settings.php?delete_group=<?php echo $id; ?>" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger btn-block">Delete this group</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php settings_footer(); ?>