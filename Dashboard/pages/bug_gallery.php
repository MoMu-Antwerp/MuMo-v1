<div class="card">
    <div class="card-body">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3"><?php
            
            $query = "SELECT * FROM groepen ORDER by group_ID ASC";
            $result = mysqli_query($con, $query);
            while($row = mysqli_fetch_assoc($result)){
                $groepen[$row["group_ID"]] = $row;
            }

            $query = "SELECT * FROM sensoren WHERE type='1'";
            $result = mysqli_query($con, $query);
            while($row = mysqli_fetch_assoc($result)){            
                $gateways[$row["device_ID"]] = $row;
                $query = "SELECT * FROM data_images where device_ID = '".$row["device_ID"]."' ORDER BY timestamp DESC limit 0,1";
                $result2 = mysqli_query($con, $query);

                while($row2 = mysqli_fetch_assoc($result2)){
                    ?>
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-header">
                                <p class="card-text"><?php 
                                $parent = $gateways[$row["device_ID"]]["group_ID"];
                                $output = "";
                                if($parent == NULL){
                                    $output .= "> ";
                                }
                                while($parent != NULL){
                                    if(isset($groepen[$parent])){
                                        $output = $groepen[$parent]["group_name"] ." > ". $output;
                                        $parent = $groepen[$parent]["parent_group"];
                                    }else{ 
                                        $parent = NULL;
                                    }
                                }
                                echo $output;
                                ?></p>
                                <h5 class="card-title">
                                <?php if(!isset($gateways[$row["device_ID"]]["name"])){
                                    echo "Unregistered <small> <small> ".$gateways[$row["device_ID"]]["device_EUI"]."</small></small>";
                                    $output .= "Unregistered: ".$gateways[$row["device_ID"]]["device_EUI"];
                                }else{
                                    echo $gateways[$row["device_ID"]]["name"]. "&nbsp;";
                                    $output .= $gateways[$row["device_ID"]]["name"];
                                }
                                ?>
                                </h5>
                            </div>
                            <a href="#iframe_modal" class="" data-toggle="modal" data-target="#iframe_Modal" data-name="<?php echo $output; ?>" data-url="bug_gallery.php?id=<?php echo $row["device_ID"]; ?>">
                                <img class="card-img" src="data:image/png;base64, <?php echo $row2["image"]; ?>" style="filter: grayscale(0.75);" alt="">
                            </a>
                            <div class="card-footer">
                                <small class="text-muted">Last update: <?php echo time_elapsed_string($row2["timestamp"]); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } 
            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="iframe_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="height:775px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">device name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe src="bug_gallery.php" width="100%" height="625px" style="border: none;"></iframe> <!-- 97.5% -->
      </div>
    </div>
  </div>
</div>

<script>
$('#iframe_Modal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var url = button.data('url'); // Extract info from data-* attributes
  var name = button.data('name'); // Extract info from data-* attributes
  var modal = $(this);
  modal.find('.modal-title').text(name);
  modal.find('.modal-body iframe').attr("src", url);
})
</script>