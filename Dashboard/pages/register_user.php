<div class="container h-100 d-flex" style="width: 500px;">
		<div class="jumbotron my-auto text-center w-100">
		<h1 class="display-3">
			<img alt="Brand" src="assets\images\logo\mumoLogo.png" width="400px">
		</h1>


    <!-- information block -->
    <div class="col my-4">
        <div class="card">
            <div class="card-header border-info strong">
                Update user information
            </div>
            <?php 
            if(isset($_GET["stamp"])){
                $time = base64_decode($_GET["stamp"]);
                if(abs(time() - $time) > (60*60*24*7)){ //link is more then 7 days old
                    ?>
                    <div class="alert alert-danger mt-3 mx-3" role="alert">
                        The link as expired.<br/>Please request a new update link.
                    </div>
                    <?php
                    exit();
                }
                //al looks good so far, just some more small checks
                if(!isset($_GET["id"]) || $_GET["id"] == ""){
                    ?>
                    <div class="alert alert-danger mt-3 mx-3" role="alert">
                        The link you received seems broken.<br/>Please request a new link.
                    </div>
                    <?php
                    exit();
                }
            }else{
                header('Location: index.php?error=wrong_turn1');
                exit;
            }
            ?>
            <form action="parse_settings.php?register_user=<?php echo $_GET["id"]; ?>" method="post">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="user_name" class="col-sm-4 col-form-label">
                            <h6> User name</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="user_name" name="user_name" class="form-control" type="text" title="This is how others see you (decided by the admin)"
                               value="<?php echo $_GET["name"]; ?>" placeholder="User name" required readonly></input>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label">
                            <h6> Email adress</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="email" name="email" class="form-control" type="email" placeholder="Email adress" title="Only the admin can change this"
                                value="<?php echo $_GET["email"]; ?>" required readonly></input>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="login" class="col-sm-4 col-form-label">
                            <h6> Login name</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="login" name="login" class="form-control" type="text" placeholder="login name" title="What name you would like to use to login"
                                value="<?php echo $_GET["name"]; ?>" required></input>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">
                            <h6> Password</h6>
                        </label>
                        <div class="col-sm-8">
                            <input id="password" name="password" class="form-control" type="password" placeholder="password"
                            required ></input>
                        </div>
                    </div>


                    <input class="btn btn-primary btn-block" type="submit" value="Activate">
                </div>
            </form>
        </div>
    </div>
</div>
	</div>