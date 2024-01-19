<?php

echo $_GET['id'];

$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "bank";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM `tbl_user` WHERE id=".$_GET['id'];
$result = $conn->query($sql);

header('Location:view.php');

$conn->close();
?>