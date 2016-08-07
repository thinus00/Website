<html>
<body>

<?php
$con = mysql_connect("localhost","rainuser","M@t0rb1k3pi");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("rain", $con);

$sql="INSERT INTO rain_day (timestamp, value)
VALUES
('$_POST[date]','$_POST[mm]')";

if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
echo "1 record added";

mysql_close($con)
?>

</html>
</body>
