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

  <form>
    <div class="form-group">
      <label for="dd">Action:</label>
      <select id="dd" name="dd">
        <option value="option1">Deposit</option>
        <option value="option2">Withdrawl</option>
      </select>
      <label for="inputField">Enter Amount:</label>
      <input type="text" id="inputField" name="inputField" placeholder=""><br></br>
    <button type="sub">Submit</button>
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
  </div>
  
<?php
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

  $sql = "INSERT INTO `tbl_account`(`cr`, `dr`, `bal`) VALUES ('".$cr."','".$dr."','".$bal."')";
  
  if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
  
    if ($action == "option1") {
      $update_sql = "UPDATE `tbl_account` SET `cr` = $amount, `bal` = `bal` + $amount WHERE `ac_id` = $last_id";
    } else {
      $update_sql = "UPDATE `tbl_account` SET `dr` = $amount, `bal` = `bal` - $amount WHERE `ac_id` = $last_id";
    }

    if ($conn->query($update_sql) === TRUE) {
      echo "Record added successfully";
    } else {
      echo "Error updating record: " . $conn->error;
    }
  } else {
    echo "Error inserting record: " . $conn->error;
  }

if ($result->num_rows > 0) {
  
  
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$row["ac_id"]."</td>";
    echo "<td>".$row["cr"]."</td>";
    echo "<td>".$row["dr"]."</td>";
    echo "<td>".$row["bal"]."</td>";
    echo "<td><a href='edit.php?id=".$row['ac_id']."'><button>Edit</button></a></td>";
    echo "<td><a href='del.php?id=".$row['ac_id']."'><button>Delete</button></a></td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='4'>0 results</td></tr>";
}
}
$conn->close();
?>

</table>
</body>
</html>