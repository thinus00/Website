<?php

$id = $_GET['id'];
$dbname = 'temps'; // Enter DB Here
$username = 'tempuser'; // Enter Username Here
$password = 'M@t0rb1k3pi'; // Enter Password Here

$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
  $result = $conn->query("call fill_numbers5(NOW(),2,-2,3);");
  if (empty($_GET['id'])) {
    $id = "0;1;21;22;31;32";
  }
  $rows = array();
  $table = array();

  $table['cols'] = array(array('label' => 'Datetime', 'type' => 'string'),array('label' => '0', 'type' => 'number'),array('label' => '1', 'type' => 'number'),array('label' => '21', 'type' => 'number'),array('label' => '22', 'type' => 'number'),array('label' => '31', 'type' => 'number'),array('label' => '32', 'type' => 'number'));

  $table['cols'] = array(array('label' => 'Datetime', 'type' => 'string'));
  $ids = explode(";", $id);
  foreach($ids as $i) {
      array_push($table['cols'], array('label' => $i, 'type' => 'number'));
  }

  foreach($result as $r) {

    $data = array();
    $data[] = array('v' => (string) $r['dateStart']); 
    if (in_array("0", $ids)) {
      if ($r['t0'] != 0) {
        $data[] = array('v' => (float) $r['t0']);
      } else {
        $data[] = array('v' => null);
      }
    }
    if (in_array("1", $ids)) {
      if (($r['t1'] != 0) && (in_array("1", $ids))) {
        $data[] = array('v' => (float) $r['t1']);
      } else {
        $data[] = array('v' => null);
      }
    }
    if (in_array("21", $ids)) {
     if (($r['t21'] != 0)  && (in_array("21", $ids))) {
        $data[] = array('v' => (float) $r['t21']);
      } else {
        $data[] = array('v' => null);
      }
    }
    if (in_array("22", $ids)) {
      if (($r['t22'] != 0) && (in_array("22", $ids))) {
        $data[] = array('v' => (float) $r['t22']);
      } else {
        $data[] = array('v' => null);
      }
    }
    if (in_array("31", $ids)) {
      if (($r['t31'] != 0) && (in_array("31", $ids))) {
        $data[] = array('v' => (float) $r['t31']);
      } else {
        $data[] = array('v' => null);
      }
    }
    if (in_array("32", $ids)) {
      if (($r['t32'] != 0) && (in_array("32", $ids))) {
        $data[] = array('v' => (float) $r['t32']);
      } else {
        $data[] = array('v' => null);
      }
    }

    $rows[] = array('c' => $data);

  }
  $result->closeCursor();

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
