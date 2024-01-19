<?php include ('head.php');?>
<?php include ('foot.php');?>

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
$emailErr = $passwordErr= $loginErr= "";
$email = $password  = "";

$valid=true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
      $passwordErr = "Invalid Password";
    }
  }

  if ($valid) {
    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "bank";

    $conn = mysqli_connect($servername, $username, $db_password, $dbname);
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }


    $sql = "SELECT * FROM `tbl_user` WHERE email = '$email' AND password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {

        header('Location:home.php');
      }
    } else {
      echo "Invalid Email or Password";
    }
    $conn->close();


 }
}


function test_input($data){
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;

 }

?>	

<h2>Log In</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Password: <input type="password" name="password" value="<?php echo $password;?>">
  <span class="error">* <?php echo $passwordErr;?></span>
  <br><br>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>


</body>
</html>