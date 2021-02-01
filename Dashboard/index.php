<?php include "settings.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
	<?php if(isset($_SESSION["user"])){ ?>
		<meta name="viewport" content="width=768">
	<?php }else{ ?>
		<meta name="viewport" content="width=600">
		<!--<meta name="viewport" content="width=device-width, initial-scale=0.5, shrink-to-fit=no" />-->
    <?php } ?>
	
		

	<title>Mumo Dash</title>
	<!-- STYLESHEETS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css" integrity="sha512-3lMc9rpZbcRPiC3OeFM3Xey51i0p5ty5V8jkdlNGZLttjj6tleviLJfHli6p8EpXZkCklkqNt8ddSroB3bvhrQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- JAVASCRIPTS -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <!-- Plugins js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dygraph/2.1.0/dygraph.min.js" integrity="sha512-opAQpVko4oSCRtt9X4IgpmRkINW9JFIV3An2bZWeFwbsVvDxEkl4TEDiQ2vyhO2TDWfk/lC+0L1dzC5FxKFeJw==" crossorigin="anonymous"></script>
    <script src="assets/js/dygraphs_sync.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.7/jquery.timeago.min.js" integrity="sha512-RlGrSmkje9EE/FXpJKWf0fvOlg4UULy/blvNsviBX9LFwMj/uewXVoanRbxTIRDXy/0A3fBQppTmJ/qOboJzmA==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" integrity="sha512-Y2IiVZeaBwXG1wSV7f13plqlmFOx8MdjuHyYFVoYzhyRr3nH/NMDjTBSswijzADdNzMyWNetbLMfOpIPl6Cv9g==" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js"></script>
	<?php include("partials/icons.php"); ?>


    <!-- CUSTOM STYLING -->
    <style>
		html, body { height: 100%; }
		.collapsed .bi-chevron-down { transform: rotate(-90deg); }
		.list-group { margin-left: 0.75em; }
		.list-group-item { padding: 0.5rem 1.25rem }
		.card-columns { column-count: 1; }
		@media only screen and (min-width: 992px) {
			.card-columns { column-count: 2; }
		}
		.container-xl { min-width: 720px; }
		.bootstrap { color: #563d7c; font-weight: 500; font-family: "-apple-system","BlinkMacSystemFont","Segoe UI","Roboto","Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"; }
		.thick-border { border-width: 2px; }

		.icon_align { margin-top: 0.5em; }

		.ms-container .ms-list { height: 75px; }
		.ms-container { width: 100%; }
		.ms-container .ms-selectable li.ms-elem-selectable, .ms-container .ms-selection li.ms-elem-selection { font-size: 0.8em; }
		
		.modal-dialog, .modal-content { height: 95%; }
		.modal-body { max-height: calc(100% - 60px); overflow-y: scroll; }
	</style>



</head>

<body>

<?php 

if(isset($_GET["settings"]) && ($_GET["settings"] == "register_user")){
	include('pages/register_user.php');
	exit;
}

if(isset($_SESSION["user"])){ ?>
        <!-- Topbar -->
        <?php //include("partials/_navbar.html"); ?>
        <?php include("partials/_navbar.php"); ?>
        <!-- Main -->
        <div class="container-xl page-body-wrapper">
            <div class="content-wrapper">
				<?php 
					if(isset($_GET["succes"])){
						echo '<div class="alert alert-success mt-3" role="alert">';
						switch($_GET["succes"]){
							case "user_updated": echo 'The user data was updated!'; break;
							case "email_send": echo 'The email has been sent!'; break;
							case "group_deleted": echo 'The group has been deleted. Sensors within this group are now shown as unregistered.'; break;
							case "group_updated": echo 'The group has been updated!'; break;
							case "sensor_updated": echo 'The sensor settings have been updated!'; break;
							case "sensor_deleted": echo 'The sensor has been deleted. If the sensor sends new data, it will be added again as an unregistered sensor'; break;
							case "threshold_updated": echo 'Thresholds have been updated!'; break;
							case "notification_resolved": echo 'The notification has been marked as resolved!'; break;
							case "user_deleted": echo 'This user has been deleted from the platform'; break;
							case "changes_updated": echo 'Changes have been saved'; break;
						}
						echo '</div>';
					}
					if(isset($_GET["error"])){
						echo '<div class="alert alert-danger mt-3" role="alert">';
						switch($_GET["error"]){
							case "wrong_turn1": echo 'The link you received is broken. Please contact support (err:1)!'; break;
							case "privileges": echo 'You do not have the required privileges to adjust these settings'; break;
						}
						echo '</div>';
					}

				?>

                <!-- insert Mumo page depending on the get queries-->
                <?php 
				if(isset($_GET["sensor"])){
					include("pages/sensor.php");
				}elseif(isset($_GET["settings"])){
					if($_SESSION["user_privileges"] != 2){
						header('Location: index.php?error=privileges');
						exit();
					}
					include("partials/settings.php"); //include functions for the settings pages

					if($_GET["settings"] == "general"){
						include('pages/general_settings.php');
					}elseif($_GET["settings"] == "sensor"){
						include('pages/sensor_settings.php');
					}elseif($_GET["settings"] == "add_user"){
						include('pages/add_user.php');
					}elseif($_GET["settings"] == "user"){
						include('pages/edit_user.php');
					}elseif($_GET["settings"] == "edit_group"){
						include('pages/edit_group.php');
					}elseif($_GET["settings"] == "add_group"){
						include('pages/add_group.php');
					}else{
						echo "TODO: ";
						print_r($_GET);
						//include('pages/sensor_settings.php');
					}

				}else{
					include("pages/home.php");
				}
				?>

			</div>
			<!-- content-wrapper ends -->
		</div>
		<!-- Footer -->
		<?php include("partials/_footer.php"); ?>
		<!-- main-panel ends -->
		<!-- page-body-wrapper ends -->
	</div>
<?php }else{
	include("pages/login.php");
	exit;
} ?>


<script>
$(function() {
	$('[data-toggle="tooltip"]').tooltip()
	$('.multiselect').multiSelect({
  selectableHeader: "<div class='custom-header'>All users</div>",
  selectionHeader: "<div class='custom-header'>Selected users</div>"})
})

$(document).on('click', '[data-toggle="lightbox"]', function(event) {
	event.preventDefault();
	$(this).ekkoLightbox();
});
</script>
</body>

</html>