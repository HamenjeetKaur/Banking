<html>
<head>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 30px;
  }

  .container {
    width: 50%;
    margin: auto;
    overflow: hidden;
  }

  h2 {
    text-align: center;
    color: #333;
  }

  form {
    width: 50%;
    margin: 0 auto;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    box-sizing: border-box;
  }

  input[type="submit"] {
    display: block;
    width: 50%; 
    margin: 0 auto;
    padding: 10px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  input[type="submit"]:hover {
    background-color: #45a049;
  }

  .error {
    color: #FF0000;
    font-size: 14px;
    text-align: center;
  }
</style>
</head>
<body>  

<?php
$nameErr = $emailErr = $passwordErr = $cpasswordErr =$addressErr = "";
$name = $email = $password = $cpassword = $address = "";

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

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required.";
	$valid=false;
  } else {
    $password = test_input($_POST["password"]);
    if (strlen($password)<6) {
      $passwordErr = "Password must contain atleast six characters.";
    }
  }

  if (empty($_POST["cpassword"])) {
    $cpasswordErr = "Please enter password.";
	$valid=false;
  } else {
    $cpassword = test_input($_POST["cpassword"]);
	    if ($_POST["cpassword"] === $_POST["password"]) {
		 }
		 else {
      $cpasswordErr = "Password don't match..";
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
  
  $sql = "INSERT INTO `tbl_user`(`name`, `password`, `address`, `email`) VALUES ('".$name."','".$password."','".$address."','".$email."')";
  
  if (mysqli_query($conn, $sql)) {
	echo "New record created successfully";
  } else {
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  
  mysqli_close($conn);
 }   
}

function test_input($data){
  $data= trim(data);
  $data= stripslashes($data);
  $data= htmlspecialchars($data);
  return $data;
}

?>	

<h2>Sign Up</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Password: <input type="password" name="password" value="<?php echo $password;?>">
  <span class="error">* <?php echo $passwordErr;?></span>
  <br><br>
  Confirm Password: <input type="password" name="cpassword" value="<?php echo $cpassword;?>">
  <span class="error">* <?php echo $cpasswordErr;?></span>
  <br><br>
  Address: <input type="text" name="address" value="<?php echo $address;?>">
  <span class="error">* <?php echo $addressErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>


</body>
</html>