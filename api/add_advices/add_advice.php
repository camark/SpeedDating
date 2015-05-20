<?php 
$name = $_REQUEST['name'];
$dorm = $_REQUEST['dorm'];
$phone = $_REQUEST['phone'];
$advice = $_REQUEST['advice'];

mysql_connect('localhost','christopher','wudbadmin') or die (mysql_error());
mysql_select_db('avshop');
$sql = "INSERT INTO `advices` values('$name','$dorm','$phone','$advice')";
mysql_query("SET NAMES 'UTF8'");
mysql_query($sql) or die (mysql_error());
?>