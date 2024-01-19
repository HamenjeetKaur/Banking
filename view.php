<?php include ('head.php');?>
<?php include ('foot.php');?>

<!DOCTYPE html>
<html>
<head>
<style>
#users {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#users td, #users th {
  border: 1px solid #ddd;
  padding: 8px;
}

#users tr:nth-child(even){background-color: #f2f2f2;}

#users tr:hover {background-color: #ddd;}

#users th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4caf50;
  color: white;
}
</style>
</head>
<body>

<h1>Users Details</h1>

<table id="users">
  <tr>
    <th>Id</th>
    <th>Name</th>
    <th>Email</th>
    <th>Address</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>

  
<?php
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "bank";
$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `u_id`, `name`, `address`, `email` FROM `tbl_user` WHERE 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  
  
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$row["u_id"]."</td>";
    echo "<td>".$row["name"]."</td>";
    echo "<td>".$row["email"]."</td>";
    echo "<td>".$row["address"]."</td>";
    echo "<td><a href='edit.php?id=".$row['u_id']."'><button>Edit</button></a></td>";
    echo "<td><a href='del.php?id=".$row['u_id']."'><button>Delete</button></a></td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='4'>0 results</td></tr>";
}
$conn->close();
?>
</table>
</body>
</html>