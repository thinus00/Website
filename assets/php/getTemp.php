<?php

$id = $_GET['id'];
$dbname = 'temps'; // Enter DB Here
$username = 'tempuser'; // Enter Username Here
$password = 'M@t0rb1k3pi'; // Enter Password Here

$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
  if (empty($_GET['id'])) {
    $result = $conn->query("SELECT *,(value/16) as temp_value FROM $dbname.temp_10 WHERE timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND timestamp <= NOW() AND tempid = 1;");
  } else {
    $result = $conn->query("SELECT *,(value/16) as temp_value FROM $dbname.temp_10 WHERE timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND timestamp <= NOW() AND tempid = $id;");
  }

  $rows = array();
  $table = array();
  $table['cols'] = array(array('label' => 'Datetime', 'type' => 'string'),array('label' => $id, 'type' => 'number'));

  foreach($result as $r) {

    $data = array();
    $data[] = array('v' => (string) $r['timestamp']); 
    $data[] = array('v' => (float) $r['temp_value']); 
    //$data[] = array('v' => (int) $r['value']);

    $rows[] = array('c' => $data);
  }

$table['rows'] = $rows;

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

try {
  $result2 = $conn->prepare("SELECT tempid, timestamp, value, name, (value/16) as temp_value FROM temp_rt LEFT OUTER JOIN temp_details ON (temp_rt.tempid = temp_details.id);");

  $result2->execute();

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>
