<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css" integrity="sha512-3lMc9rpZbcRPiC3OeFM3Xey51i0p5ty5V8jkdlNGZLttjj6tleviLJfHli6p8EpXZkCklkqNt8ddSroB3bvhrQ==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<!-- JAVASCRIPTS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
</script>
<!-- Plugin js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dygraph/2.1.0/dygraph.min.js"
    integrity="sha512-opAQpVko4oSCRtt9X4IgpmRkINW9JFIV3An2bZWeFwbsVvDxEkl4TEDiQ2vyhO2TDWfk/lC+0L1dzC5FxKFeJw=="
    crossorigin="anonymous"></script>
<script src="assets/js/dygraphs_sync.js"></script>
<script src="assets/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.7/jquery.timeago.min.js" integrity="sha512-RlGrSmkje9EE/FXpJKWf0fvOlg4UULy/blvNsviBX9LFwMj/uewXVoanRbxTIRDXy/0A3fBQppTmJ/qOboJzmA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" integrity="sha512-Y2IiVZeaBwXG1wSV7f13plqlmFOx8MdjuHyYFVoYzhyRr3nH/NMDjTBSswijzADdNzMyWNetbLMfOpIPl6Cv9g==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js"></script>
	

<?php 
if(!isset($_GET["id"])){  exit(); }
$id = $_GET["id"];
include "settings.php"; 
if(isset($_GET["date"])){
  $date = $_GET["date"];
  $end = date("Y/m/d", strtotime($date . "+1 Day"));
  
  $query = "SELECT * FROM data_images WHERE device_ID='$id' AND timestamp BETWEEN '$date' AND '$end' ORDER BY timestamp DESC limit 0,1";
  $result = mysqli_query($con, $query);
  while($row2 = mysqli_fetch_assoc($result)){
    $image_array = $row2;
  }
  if(!isset($image_array)){
    echo "No results found for ".$_GET["date"];
  }
}
if(!isset($image_array)){
  $query = "SELECT * FROM data_images WHERE device_ID='$id' ORDER BY timestamp DESC limit 0,1";
  $result = mysqli_query($con, $query);
  while($row2 = mysqli_fetch_assoc($result)){
    $image_array = $row2;
    $date = substr($image_array["timestamp"], 0, 10);
  }
  if(!isset($image_array)){
    echo "No results found";
  }
}

$days = array();
$query = "SELECT data_ID, timestamp FROM data_images WHERE device_ID='$id'";
$result = mysqli_query($con, $query);
while($row = mysqli_fetch_assoc($result)){
  $day = substr($row["timestamp"], 0, 10);
  $days[$day] = $day;
}
$index = array_search($date, array_keys($days));
$keys = array_keys($days);
?>

<div class="container mt-2" style="max-width: 720px;">
  <!--<h4>Bug history</h4>-->
  <form method="GET">
    <div class="input-group mb-2">
      <input type="hidden" name="id" value="<?php echo $id; ?>"/>
      <input type="text" id="datepick" class="form-control" name="date" value="<?php echo $date; ?>" style="width: 50%;">
      <input type="submit" value="Fetch" class="btn btn-outline-secondary form-control"/>
    </div>
  </form>
  <div class="input-group mb-2">
    <?php if(isset($keys[$index-1]) && isset($days[$keys[$index-1]])){ ?>
      <a href="?id=<?php echo $id; ?>&date=<?php echo $days[$keys[$index-1]]; ?>" class="btn btn-outline-secondary form-control" type="button">Previous picture</a>
    <?php } else{ ?>
      <a href="#prev" class="btn btn-outline-secondary form-control disabled" type="button" aria-disabled="true">
        Previous picture
      </a>
    <?php } ?>
    <?php if(isset($keys[$index+1]) && isset($days[$keys[$index+1]])){ ?>
      <a href="?id=<?php echo $id; ?>&date=<?php echo $days[$keys[$index+1]]; ?>" class="btn btn-outline-secondary form-control" type="button">
        Next picture
      </a>
    <?php } else{ ?>
      <a href="#next" class="btn btn-outline-secondary form-control disabled" type="button" aria-disabled="true">Next picture</a>
    <?php } ?>
  </div>

  <div id="carouselExampleCaptions<?php echo $id; ?>" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
          <img src="data:image/png;base64, <?php echo $image_array["image"]; ?>" class="d-block w-100" style="filter: grayscale(0.75);" alt="...">
          <div class="carousel-caption d-none d-md-block">
              <h3><?php echo $image_array["timestamp"]; ?></h3>
          </div>
      </div>
    </div>
    <?php
    if(isset($keys[$index-1]) && isset($days[$keys[$index-1]])){?>
      <a class="carousel-control-prev" href="?id=<?php echo $id; ?>&date=<?php echo $days[$keys[$index-1]]; ?>" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </a>
    <?php }

    if(isset($keys[$index+1]) && isset($days[$keys[$index+1]])){?>
      <a class="carousel-control-next" href="?id=<?php echo $id; ?>&date=<?php echo $days[$keys[$index+1]]; ?>" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </a>
    <?php }
    ?>
  </div>
</div>

<script>
let enabledDates = [<?php echo "'".implode("','", $days)."'"; ?>];
$('#datepick').val("<?php  if(isset($_GET["date"])){ echo $date; }else{ echo date("Y/m/d", strtotime("-1 minute")); } ?>").datepicker({
	format: 'yyyy/mm/dd',
	endDate: "<?php echo array_key_last($days); ?>",
  startDate: "<?php echo array_key_first($days); ?>",
	startView: 0,
	maxViewMode: 3,
	todayBtn: "linked",
	orientation: "bottom auto",
	autoclose: true,
	todayHighlight: true,
  beforeShowDay: function (date) {
    let fullDate = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + ('0' + date.getDate()).slice(-2);
    console.log(fullDate);
    console.log(enabledDates.indexOf(fullDate) != -1);
    return enabledDates.indexOf(fullDate) != -1
  }
});
</script>