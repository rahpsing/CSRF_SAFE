<?php 
    
    include("config.php");
    include("header.php");    
    
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['login_user']) && (isset($_GET["csrf"]) && $_GET["csrf"] == $_SESSION["token"])){
		 $myvar = $_SESSION["token"];
		 
		//echo "<script type='text/javascript'> alert('$_SESSION["token"]') </script>";
		$userLoggedIn = $_SESSION['login_user'];
		
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

	if($_SERVER["REQUEST_METHOD"] == "GET") {
		if (isset($_GET['account_num']) && isset($_GET['amount'])) {
			$var_account_num = $_GET['account_num'];
			$var_amount = $_GET['amount'];

			$sql1 = "UPDATE account SET balance= balance-'$var_amount' WHERE user_id='$userLoggedIn'";

			$sql2 = "UPDATE account SET balance= balance+'$var_amount' WHERE account_number='$var_account_num'";

			$sql3 = "UPDATE account SET balance= balance+'$var_amount' WHERE user_id='$userLoggedIn'";

			if ($conn->query($sql1) === TRUE) {
				if($conn->query($sql2) === TRUE){
				echo "<br><br><div class='form-control'><span> $var_amount  transferred to account number - $var_account_num</span></div>";
				}
				else{
					$conn->query($sql3);
					echo "Could not transfer money due to some error.";
				}  
			} else {
				echo "Could not transfer money due to some error.";
			}
		}
	}
	}
	else{
		echo "Token mismatch. Logging you out";
		session_unset();
        session_destroy();
		header("Location: login.php");
	}
	
?>
	<head>
	<title>Unsafe Bank</title>    
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link href="sn_styles.css" rel="stylesheet" type="text/css">
	</head>
	<br><br><br>

<div class="col-md-4 column col-md-offset-1">
	<form action = "" method = "get">
		<h4><label>Transfer Money</label></h4>
		<input class="form-control" type="text" name = "account_num" placeholder="Account Number" required><br>
		<input class="form-control" type="text" name = "amount" placeholder="Amount" required><br>
        <input type="hidden" name="csrf" value="<?php echo $_SESSION["token"]; ?>">
        <div class="action">
            <input type = "submit" class="btn btn-primary btn-block signup"  value = "Transfer"/>
        </div>
    </form>

    <br><br><br>
    <a href="homepage_POST.php">POST version</a>
</div>

</body>
</html>