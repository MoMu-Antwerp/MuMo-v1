<?php settings_header("Edit user"); ?>

<div class="row row-cols-1 row-cols-md-2">
    <!-- information block -->
    <div class="col mb-4">
        <div class="card">
            <div class="card-header border-info strong">
                User information
            </div>
            <?php 
            $id=$_GET["user"];
            $query = "SELECT * FROM users WHERE user_ID = '$id'";
            $result = mysqli_query($con, $query);
            while($row = mysqli_fetch_assoc($result)){
                $email = $row["email"];
                $name = $row["user_name"];
                $login = $row["user_login"];
                $edit = $row["privileges"];
            }
            ?>

            <form action="parse_settings.php?edit_user=<?php echo $id; ?>" method="post">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="user_name" class="col-sm-4 col-form-label">
                            <h6> User name</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="user_name" name="user_name" class="form-control" type="text"
                               value="<?php echo $name; ?>" placeholder="User name" required></input>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label">
                            <h6> Email adress</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="email" name="email" class="form-control" type="email" placeholder="Email adress"
                                value="<?php echo $email; ?>" required></input>
                        </div>
                    </div>
                    
                    <div class="custom-control custom-checkbox text-center mb-3">
                        <input type="checkbox" class="custom-control-input" id="edit" name="edit" value="true" <?php if($edit>0){echo 'checked="checked"'; } ?>>
                        <label class="custom-control-label" for="edit">This user can make edits</label>
                    </div>
                    
                    <input class="btn btn-primary btn-block" type="submit" value="Update information">


                    <div class="form-group row mt-3">
                        <label for="password" class="col-sm-4 col-form-label">
                            <h6>Login & Password</h6>
                        </label>
                        <div class="col-sm-8">
                            <a href="parse_settings.php?reset_password=<?php echo $id; ?>" class="btn btn-outline-warning btn-block">Send reset link by email</a>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label for="password" class="col-sm-4 col-form-label">
                            <h6>Delete user</h6>
                        </label>
                        <div class="col-sm-8">
                            <a href="parse_settings.php?delete_user=<?php echo $id; ?>" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger btn-block">Delete this account</a>
                        </div>
                    </div>


                </div>
            </form>
        </div>
    </div>
</div>
<?php settings_footer(); ?>