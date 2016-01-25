<?php

$id = $_GET['id'];
$dbname = 'tanklevels'; // Enter DB Here
$username = 'espuser'; // Enter Username Here
$password = 'M@t0rb1k3pi'; // Enter Password Here

$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
  if (empty($_GET['id'])) {
    $result = $conn->query("SELECT *,FLOOR(((11000-value)/11000) * 100) as percentage FROM $dbname.tanklevel_10 WHERE timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND timestamp <= NOW() AND tankid = 1;");
  } else { 
    $result = $conn->query("SELECT *,FLOOR(((offset-value)/offset) * 100) as percentage FROM $dbname.tanklevel_10 LEFT OUTER JOIN tank_details ON (tanklevel_10.tankid = tank_details.id) WHERE timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND timestamp <= NOW() AND tankid = $id;");
  }

  $rows = array();
  $table = array();
  $table['cols'] = array(array('label' => 'Datetime', 'type' => 'string'),array('label' => '%', 'type' => 'number'));

  foreach($result as $r) {

  $data = array();
  $data[] = array('v' => (string) $r['timestamp']); 
  $data[] = array('v' => (int) $r['percentage']); 
  //$data[] = array('v' => (int) $r['value']);


  $rows[] = array('c' => $data);

  }

$table['rows'] = $rows;

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

try {
  $result2 = $conn->prepare("SELECT tankid, timestamp, value, name, FLOOR((value-offset)/factor) as level, FLOOR(((offset-value)/offset) * 100) as percentage, FLOOR(PI()*radius_sq*(((value-offset)/factor)/100000)) as volume  FROM tanklevel_rt LEFT OUTER JOIN tank_details ON (tanklevel_rt.tankid = tank_details.id);");

  $result2->execute();

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>
