<div class="container h-100 d-flex" style="width: 500px;">
		<div class="jumbotron my-auto text-center w-100">
		<h1 class="display-3">
			<img alt="Brand" src="assets\images\logo\mumoLogo.png" width="400px">
		</h1>
		<?php 
		if(isset($_GET["error"])){
			if($_GET["error"] == "wrong_password"){
				echo '<div class="alert alert-danger mt-5" role="alert">Wrong password!</div>';
			}elseif($_GET["error"] == "user_not_found"){
				echo '<div class="alert alert-warning mt-5" role="alert">Your username is not found!</div>';
			}
		}
		?>
		<form action="parse_settings.php?login" method="post">
			<div class="row mt-5">
				<div class="col">
					<input type="text" id="login" class="form-control form-control-lg" name="login" placeholder="login" required>
				</div>
				<div class="col">
					<input type="password" id="password" class="form-control form-control-lg" name="password" placeholder="password" required>
				</div>
			</div>
			<input type="submit" class="mt-3 btn btn-outline-primary form-control btn-lg" value="Log In">
		</form>
		</div>
	</div>