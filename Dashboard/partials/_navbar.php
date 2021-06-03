<div class="container-xl">

    <nav class="navbar navbar-expand-sm navbar-dark bg-primary rounded-bottom">
        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <img alt="Brand" src="<?php echo $logo_w; ?>" height="25px">
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php //Notificaties opvragen
                $user_id = $_SESSION["user"];
                $notifications = null;
                $query = "SELECT * FROM notifications WHERE notified_users LIKE '%\"$user_id\"%' AND active = '1' ORDER BY timestamp DESC";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_assoc($result)){
                    $notifications[] = $row;
                }
                //print_r($notifications);
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                        aria-haspopup="true" aria-expanded="false">
                        <?php icon_bell(); ?> Notifications
                        <span class="badge badge-pill badge-<?php echo isset($notifications)?"danger":"light"; ?>"><?php echo isset($notifications)?count($notifications):"0"; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php if(isset($notifications) && count($notifications)){
                            foreach($notifications as $notification){
                                //print_r($notification);
                                //Array ( [notification_ID] => 3 [device_ID] => 11 [alert_ID] => 25 [measurement_ID] => 1 [timestamp] => 2021-01-05 03:08:28 [active] => 1 [notified_users] => ["1","2"] [cleared_timestamp] => 2021-01-05 12:17:42 [notification] => A Temperature value exceeded the globally set maximal value (50.68°C with a limit at 40°C) [note] => [note_by] => )
                                echo '<a class="dropdown-item" href="?sensor='.$notification["device_ID"].'#notifications">'.$notification["notification"].'</a>';
                            }
                        }else{
                            echo '<a class="dropdown-item" href="#">There are no open notifications</a>';
                        }
                        ?>
                        <!--
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a>
                        -->
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php icon_person(); ?> <?php echo $_SESSION["user_name"]; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="parse_settings.php?logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

</div>
