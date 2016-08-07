<?php

$id = $_GET['id'];
$dbname = 'power'; // Enter DB Here
$username = 'poweruser'; // Enter Username Here
$password = 'M@t0rb1k3pi'; // Enter Password Here

$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
// $result = $conn->query("SELECT * FROM $dbname.power_10 WHERE timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND timestamp <= NOW() AND powerid = 1;");
// $result = $conn->query("SELECT * FROM $dbname.power_10 LEFT OUTER JOIN power_details ON (power_10.powerid = power_details.id) WHERE timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND timestamp <= NOW() AND powerid = $id;");

  if (empty($_GET['id'])) {
    $id = 1;
  }
  if ($id < 90)
  {
    $result = $conn->query("SELECT p0.*,(p0.cummKWH - p0.prev) as Diff FROM (SELECT p1.*,(SELECT p2.cummKWH FROM power_10 p2 WHERE p2.ID < p1.ID AND p2.powerid = p1.powerid ORDER BY p2.id DESC LIMIT 1) AS prev FROM power_10 p1 WHERE p1.timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND p1.timestamp <= NOW() AND p1.powerid = $id) p0;");
  }
  else if ($id == 91)
  {
    $result = $conn->query("SELECT p0.*,(p0.cummKWH1 - p0.prev1)-(p0.cummKWH3 - p0.prev3) as Diff FROM (SELECT p1.timestamp, p1.cummKWH AS cummKWH1, (SELECT p2.cummKWH FROM power_10 p2 WHERE p2.ID < p1.ID AND p2.powerid = p1.powerid ORDER BY p2.id DESC LIMIT 1) AS prev1, p3.cummKWH AS cummKWH3, (SELECT p4.cummKWH FROM power_10 p4 WHERE p4.ID < p3.ID AND p4.powerid = p3.powerid ORDER BY p4.id DESC LIMIT 1) AS prev3 FROM power_10 p1 INNER JOIN power_10 p3 ON (p1.powerid=1) ANd (p3.powerid=4) AND (p1.timestamp = p3.timestamp) WHERE p1.timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND p1.timestamp <= NOW()) p0;");
  }
  else if ($id == 92)
  {
    $result = $conn->query("SELECT p0.*,(p0.cummKWH1 - p0.prev1)-(p0.cummKWH3 - p0.prev3)-(p0.cummKWH5 - p0.prev5) as Diff FROM (SELECT p1.timestamp, p1.cummKWH AS cummKWH1,(SELECT p2.cummKWH FROM power_10 p2 WHERE p2.ID < p1.ID AND p2.powerid = p1.powerid ORDER BY p2.id DESC LIMIT 1) AS prev1,p3.cummKWH AS cummKWH3,(SELECT p4.cummKWH FROM power_10 p4 WHERE p4.ID < p3.ID AND p4.powerid = p3.powerid ORDER BY p4.id DESC LIMIT 1) AS prev3,p5.cummKWH AS cummKWH5,(SELECT p6.cummKWH FROM power_10 p6 WHERE p6.ID < p5.ID AND p6.powerid = p5.powerid ORDER BY p6.id DESC LIMIT 1) AS prev5 FROM power_10 p1 INNER JOIN power_10 p3 ON (p1.powerid=2) ANd (p3.powerid=5) AND (p1.timestamp = p3.timestamp) INNER JOIN power_10 p5 ON (p1.powerid=2) ANd (p5.powerid=6) AND (p1.timestamp = p5.timestamp) WHERE p1.timestamp > DATE_SUB(NOW(), INTERVAL 2 DAY) AND p1.timestamp <= NOW()) p0;");
  }

  $rows = array();
  $table = array();
  $table['cols'] = array(array('label' => 'Datetime', 'type' => 'string'),array('label' => $id, 'type' => 'number'));

  foreach($result as $r) {

  $data = array();
  $data[] = array('v' => (string) $r['timestamp']);
  $data[] = array('v' => (float) $r['Diff']);
  //$data[] = array('v' => (int) $r['value']);


  $rows[] = array('c' => $data);

  }

  $table['rows'] = $rows;

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

try {
//  $result2 = $conn->prepare("SELECT powerid, timestamp, rtKW, name, cummPulses, cummKWH FROM power_rt LEFT OUTER JOIN power_details ON (power_rt.powerid = power_details.id);");
  $result2 = $conn->prepare("SELECT IFNULL(powerid,id) as powerid, IFNULL(timestamp,(SELECT timestamp FROM power_rt WHERE powerid = pd.factor LIMIT 1)) as timestamp, IFNULL(rtKW,(SELECT rtKW FROM power_rt WHERE powerid = pd.factor)-(SELECT rtKW FROM power_rt WHERE powerid = pd.offset)) as rtKW, name, IFNULL(cummPulses,0) as cummPulses, IFNULL(cummKWH,0) as cummKWH FROM power_rt p0 RIGHT JOIN power_details pd ON (p0.powerid = pd.id);");

  $result2->execute();

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>

