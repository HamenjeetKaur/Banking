<<?php
session_start();

include('head.php');
include('foot.php');

$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "bank";
$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST["dd"];
  $amount = $_POST["inputField"];

  $sql = "INSERT INTO `tbl_account` (`cr`, `dr`, `bal`) VALUES (0, 0, 0)";
  
  if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    // Update the newly inserted row based on the selected action and amount
    if ($action == "option1") {
      $update_sql = "UPDATE `tbl_account` SET `cr` = `cr` + $amount, `bal` = `bal` + $amount WHERE `ac_id` = $last_id";
    } else {
      $update_sql = "UPDATE `tbl_account` SET `dr` = `dr` + $amount, `bal` = `bal` - $amount WHERE `ac_id` = $last_id";
    }

    if ($conn->query($update_sql) === TRUE) {
      $_SESSION['success_message'] = "Record added successfully";
    } else {
      $_SESSION['error_message'] = "Error updating record: " . $conn->error;
    }
  } else {
    $_SESSION['error_message'] = "Error inserting record: " . $conn->error;
  }
}

$conn->close();
?>

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


.form-group {
      margin-bottom: 15px;
    }

label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

select, input, button {
      width: 30%;
      padding: 10px;
      box-sizing: border-box;
      margin-top: 5px;
    }

button {
      background-color: #4caf50;
      color: #fff;
      cursor: pointer;
      border: none;
      border-radius: 4px;
    }

button:hover {
      background-color: #45a049;
    }
</style>
</head>
<body>

<h1>Account Details</h1>

<?php
// Display success or error messages if they exist in the session
if (isset($_SESSION['success_message'])) {
  echo '<div style="color: green;">' . $_SESSION['success_message'] . '</div>';
  unset($_SESSION['success_message']); // Clear the session variable
}

if (isset($_SESSION['error_message'])) {
  echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
  unset($_SESSION['error_message']); // Clear the session variable
}
?>

<form method="post">
  <div class="form-group">
    <label for="dd">Action:</label>
    <select id="dd" name="dd">
      <option value="option1">Deposit</option>
      <option value="option2">Withdrawal</option>
    </select>
    <label for="inputField">Enter Amount:</label>
    <input type="text" id="inputField" name="inputField" placeholder=""><br></br>
    <button type="submit">Submit</button>
  </div>
</form><br></br>

<table id="users">
  <tr>
    <th>Id</th>
    <th>Credit</th>
    <th>Debit</th>
    <th>Balance</th>
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

$sql = "SELECT `ac_id`, `cr`, `dr`, `bal`, `u_id` FROM `tbl_account` WHERE 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$row["ac_id"]."</td>";
    echo "<td>".$row["cr"]."</td>";
    echo "<td>".$row["dr"]."</td>";
    echo "<td>".$row["bal"]."</td>";
    echo "<td><a href='edit2.php?id=".$row['ac_id']."'><button>Edit</button></a></td>";
    echo "<td><a href='del2.php?id=".$row['ac_id']."'><button>Delete</button></a></td>";
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