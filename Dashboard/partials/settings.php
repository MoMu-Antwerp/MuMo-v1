<?php
function settings_header($title){
    ?>
    <div class="row mt-4 mb-2">
        <div class="col-sm-6">
            <div class="page-header">
                <h3 class="page-title">
                    <button class="btn btn-primary btn-lg" onclick="window.history.back();" role="button">
                        <?php echo icon_gear(); ?>
                    </button><span class="ml-2"><?php echo $title; ?></span>
                </h3>
            </div>
        </div>
    </div>

    <!-- For white background -->
    <div class="card mb-5">
        <div class="card-body">
            <?php
}

function settings_footer(){
    echo "</div></div>";
}