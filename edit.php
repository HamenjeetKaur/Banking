<!DOCTYPE html>
<html>
<head>
<style>
  .error {
    color: #FF0000;
    font-size: 14px;
    text-align: center;
  }
</style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "bank";
$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `tbl_user` WHERE u_id=" .$_GET['id'];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  
  
  while($row = $result->fetch_assoc()) {
    $name=$row["name"];
    $email=$row["email"];
    $address=$row["address"];
  }
} else {
  echo "<tr><td colspan='4'>0 results</td></tr>";
}
$conn->close();
?>


<?php
$nameErr = $emailErr =$addressErr = "";
$name = $email = $address = "";

$valid=true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Please enter Username";
	$valid=false;
  } else {
    $name = test_input($_POST["name"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed.";
    }
  }

  if (empty($_POST["email"])) {
    $emailErr = "Please enter Email";
	$valid=false;
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email.";
    }
  }
  
    
  if (empty($_POST["address"])) {
    $addressErr = "Please enter Address.";
	$valid=false;
  } else {
    $address = test_input($_POST["address"]);
    if (!filter_var($address)) {
      $addressErr = "Invalid address.";
    }
  }

  if($valid){
    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "bank";
    
    
    $conn = mysqli_connect($servername, $username, $db_password, $dbname);
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
    
    $sql = "UPDATE tbl_user SET name='$name', address='$address', email='$email' WHERE u_id=" . $_GET['id'];


if ($conn->query($sql) === TRUE) {
  echo "Updated Successfully.";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

  

   }
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
  }
?>

<h2>Update Data</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="edit.php?id=<?php echo $_GET['id']; ?>">
 
  Name: <input type="text" name="name">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  
  Address: <textarea name="address" rows="5" cols="40"></textarea>
  <br><br>
  
  <input type="submit" name="submit" value="Update">  
</form>


</body>
</html>