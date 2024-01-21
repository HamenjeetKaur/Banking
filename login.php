<?php include ('head.php');?>


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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = mysqli_prepare($conn, "SELECT u_id FROM tbl_user WHERE email = ? AND password = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            session_start();
            $_SESSION['u_id'] = $row['u_id']; // Set the user ID from the result set
            header("Location: home.php");
            exit();
        } else {
            $errorMessage = "Invalid email or password. Please try again.";
        }

        mysqli_stmt_close($stmt);
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

<?php include ('foot.php');?>
</body>
</html>

