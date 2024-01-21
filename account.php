<?php
session_start();

include('head.php');

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

    $userId = $_SESSION['u_id'];
  
    $getLatestBalance = mysqli_prepare($conn, "SELECT balance FROM tbl_acc WHERE u_id = ? ORDER BY ac_id DESC LIMIT 1");
    mysqli_stmt_bind_param($getLatestBalance, "d", $userId);
    mysqli_stmt_execute($getLatestBalance);
    mysqli_stmt_bind_result($getLatestBalance, $currentBalance);
    mysqli_stmt_fetch($getLatestBalance);
    mysqli_stmt_close($getLatestBalance);

    if ($action == "option1") {
        $newBalance = $currentBalance + $amount;
        $update_sql = "UPDATE tbl_acc SET balance = ? WHERE ac_id = ?";
        $transactionType = "Credit";
    } else {
        if ($currentBalance >= $amount) {
            $newBalance = $currentBalance - $amount;
            $update_sql = "UPDATE tbl_acc SET balance = ? WHERE ac_id = ?";
            $transactionType = "Debit";
        } else {
            $_SESSION['error_message'] = "Insufficient balance for withdrawal.";
            header('Location: account.php');
            exit;
        }
    }

    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "di", $newBalance, $row["ac_id"]);

    if (mysqli_stmt_execute($update_stmt)) {
        $insertTransaction = mysqli_prepare($conn, "INSERT INTO tbl_acc (u_id, action, amount, balance) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertTransaction, "issd", $userId, $transactionType, $amount, $newBalance);
        mysqli_stmt_execute($insertTransaction);
        mysqli_stmt_close($insertTransaction);

        $_SESSION['success_message'] = "Transaction successful.";
    } else {
        $_SESSION['error_message'] = "Error updating record: " . $conn->error;
    }

    header('Location: account.php');
    exit;
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

if (isset($_SESSION['success_message'])) {
  echo '<div style="color: green;">' . $_SESSION['success_message'] . '</div>';
  unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
  echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
  unset($_SESSION['error_message']); 
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
    <th>Action</th>
    <th>Amount</th>
    <th>Balance</th>

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

$sql = "SELECT `ac_id`, `action`, `amount`, `balance` FROM `tbl_acc` WHERE u_id = ? ORDER BY ac_id DESC";
$getTransactions = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($getTransactions, "i", $_SESSION['u_id']);
mysqli_stmt_execute($getTransactions);
$result = mysqli_stmt_get_result($getTransactions);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ac_id"] . "</td>";
        echo "<td>" . $row["action"] . "</td>";
        echo "<td>" . $row["amount"] . "</td>";
        echo "<td>" . $row["balance"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>0 results</td></tr>";
}

mysqli_stmt_close($getTransactions);
?>
</table>
<?php include ('foot.php');?>
</body>
</html>