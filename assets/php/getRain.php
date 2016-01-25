<?php

$dbname = 'rain'; // Enter DB Here
$username = 'rainuser'; // Enter Username Here
$password = 'M@t0rb1k3pi'; // Enter Password Here

$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
//  $result = $conn->query("SELECT rain_month.month_no, rain_month.month_name, IFNULL(tbl_2013.valueSum,0) as valueSum_2013, IFNULL(tbl_2014.valueSum,0) as valueSum_2014, IFNULL(tbl_2015.valueSum,0) as valueSum_2015, IFNULL(tbl_2016.valueSum,0) as valueSum_2016 FROM rain_month                          LEFT OUTER JOIN (SELECT YEAR(timestamp) as yearValue,MONTH(timestamp) as monthValue, sum(value) as valueSum FROM rain_day WHERE YEAR(timestamp) = 2013 GROUP BY YEAR(timestamp), MONTH(timestamp) ORDER BY monthValue) tbl_2013 ON (tbl_2013.monthValue = rain_month.month_no)                          LEFT OUTER JOIN (SELECT YEAR(timestamp) as yearValue,MONTH(timestamp) as monthValue, sum(value) as valueSum FROM rain_day WHERE YEAR(timestamp) = 2014 GROUP BY YEAR(timestamp), MONTH(timestamp) ORDER BY monthValue) tbl_2014 ON (tbl_2014.monthValue = rain_month.month_no)                          LEFT OUTER JOIN (SELECT YEAR(timestamp) as yearValue,MONTH(timestamp) as monthValue, sum(value) as valueSum FROM rain_day WHERE YEAR(timestamp) = 2015 GROUP BY YEAR(timestamp), MONTH(timestamp) ORDER BY monthValue) tbl_2015 ON (tbl_2015.monthValue = rain_month.month_no)                          ;");
  $result = $conn->query("SELECT rain_month.month_no, rain_month.month_name, IFNULL(tbl_2013.valueSum,0) as valueSum_2013, IFNULL(tbl_2014.valueSum,0) as valueSum_2014, IFNULL(tbl_2015.valueSum,0) as valueSum_2015, IFNULL(tbl_2016.valueSum,0) as valueSum_2016 FROM rain_month LEFT OUTER JOIN (SELECT YEAR(timestamp) as yearValue,MONTH(timestamp) as monthValue, sum(value) as valueSum FROM rain_day WHERE YEAR(timestamp) = 2013 GROUP BY YEAR(timestamp), MONTH(timestamp) ORDER BY monthValue) tbl_2013 ON (tbl_2013.monthValue = rain_month.month_no) LEFT OUTER JOIN (SELECT YEAR(timestamp) as yearValue,MONTH(timestamp) as monthValue, sum(value) as valueSum FROM rain_day WHERE YEAR(timestamp) = 2014 GROUP BY YEAR(timestamp), MONTH(timestamp) ORDER BY monthValue) tbl_2014 ON (tbl_2014.monthValue = rain_month.month_no) LEFT OUTER JOIN (SELECT YEAR(timestamp) as yearValue,MONTH(timestamp) as monthValue, sum(value) as valueSum FROM rain_day WHERE YEAR(timestamp) = 2015 GROUP BY YEAR(timestamp), MONTH(timestamp) ORDER BY monthValue) tbl_2015 ON (tbl_2015.monthValue = rain_month.month_no) LEFT OUTER JOIN (SELECT YEAR(timestamp) as yearValue,MONTH(timestamp) as monthValue, sum(value) as valueSum FROM rain_day WHERE YEAR(timestamp) = 2016 GROUP BY YEAR(timestamp), MONTH(timestamp) ORDER BY monthValue) tbl_2016 ON (tbl_2016.monthValue = rain_month.month_no);");


  $rows = array();
  $table = array();
  $table['cols'] = array(array('label' => 'Datetime', 'type' => 'string'),array('label' => '2013', 'type' => 'number'),array('label' => '2014', 'type' => 'number'),array('label' => '2015', 'type' => 'number'),array('label' => '2016', 'type' => 'number'));

  foreach($result as $r) {
    $data = array();
    $data[] = array('v' => (string)  $r['month_name']);
    if ($r['month_no'] > 9) {
      $data[] = array('v' => (int) $r['valueSum_2013']);
    } else {
      $data[] = array('v' => null);

    }
    $data[] = array('v' => (int) $r['valueSum_2014']);
    $data[] = array('v' => (int) $r['valueSum_2015']);
    if ($r['month_no'] < 2) {
      $data[] = array('v' => (int) $r['valueSum_2016']);
    }

    $rows[] = array('c' => $data);
  }

$table['rows'] = $rows;

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

try {
  $result2 = $conn->prepare("SELECT timestamp, value  FROM rain_day  WHERE timestamp > DATE_SUB(NOW(), INTERVAL 30 day) AND timestamp <= NOW();");

  $result2->execute();

} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>
