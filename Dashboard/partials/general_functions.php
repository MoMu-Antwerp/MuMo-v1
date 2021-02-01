<?php
//universal email takes an array or an individual email adress to email to. A subject and the message (may contain html).
function universal_email($email_to, $subject, $message){
  global $domain;
  // Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'X-Mailer: PHP/' . phpversion().'\r\n';

  // More headers
  $headers .= 'From: Mumo platform<do_not_reply@'.$domain.'>' . "\r\n";
  $headers .= 'Reply-To: do_not_reply@'.$domain.'\r\n';

  if($_SERVER['HTTP_HOST'] != "localhost"){
    if(is_array($email_to)){
      foreach($email_to as $email_to2){
        mail($email_to2,$subject,$message,$headers); 
      }
    }else{
      mail($email_to,$subject,$message,$headers); 
    }
  }else{ // Do not attempt to mail from a localhost server, just reply that a mail is send.
    echo "***emailed***";
  }
}

function time_elapsed_string($datetime, $split = " ") {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  date_add($ago, date_interval_create_from_date_string('0 hour'));
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
  );
  foreach ($string as $k => &$v) {
      if ($diff->$k) {
          $v = $diff->$k . $split . $v . ($diff->$k > 1 ? 's' : '');
      } else {
          unset($string[$k]);
      }
  }

  $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}

if( !function_exists('array_key_last') ) {
  function array_key_last(array $array) {
      if( !empty($array) ) return key(array_slice($array, -1, 1, true));
  }
}
if (!function_exists('array_key_first')) {
  function array_key_first(array $arr) {
      foreach($arr as $key => $unused) return $key;
  }
}

function get_sensor_tresholds($id){
  global $con;
  $alerts = array(
    0=>array("email"=>array(), "json"=>array()),
    1=>array("email"=>array(), "json"=>array()),
    2=>array("email"=>array(), "json"=>array())
  );
  $query = "SELECT * FROM alerts WHERE device_ID = $id OR device_ID = '0' ORDER BY device_ID ASC";
  $result = mysqli_query($con, $query);
  while($row = mysqli_fetch_assoc($result)){
    $settings_scope = $row["device_ID"];
    $alerts[$row["alert_type"]]["alert_ID"] = $row["alert_ID"];
    $alerts[$row["alert_type"]]["email"] = json_decode($row["action"], true);
    $alerts[$row["alert_type"]]["json"] = json_decode($row["json"], true);
    $alerts[$row["alert_type"]]["scope"] = ($row["device_ID"])?1:0;
  }
  return $alerts;
}
?>