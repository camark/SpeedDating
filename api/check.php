<?php
$con = mysql_connect('localhost','christopher','wudbadmin')or die(mysql_error());
mysql_select_db('ours');
mysql_query("SET NAMES 'UTF8'");

$name = $_REQUEST['name'];
$name = '"'.$name.'"';
$sql = "SELECT * FROM `gdpuer_user` WHERE name = $name";
$result = mysql_query($sql);
$data = mysql_fetch_array($result);
if($data['number'] != '')
    echo "success";
?>
