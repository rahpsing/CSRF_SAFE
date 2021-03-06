<!DOCTYPE html>
<?php
   include("config.php");

   $error = ".";
   
  if (isset($_GET['error'])) {
  	echo "CSRF attack detected. Terminating your session";
  }

   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      $error = ".";

      $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
       
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT email_id, Password, salt FROM login WHERE email_id='$myusername' ";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      
      $count = mysqli_num_rows($result);
    
      // If email ID exists in the login table
		
      if($count == 1) {
        $salt = $row["salt"];
        $password_hash = $row["Password"];
        $myhash = hash('sha512', $mypassword . $salt);

        //If the password is correct
        if($password_hash == $myhash){
			$_SESSION['login_user'] = $myusername;
			//$_SESSION['loggedin'] = true;

			//Send OTP to '$myusername' and also store it in the login table
			$random =  rand(100000,999999);
			$sql2 = "UPDATE login SET otp=". $random ." WHERE User_id='". $myusername. "'";
			$querry = mysqli_query($db,$sql2);

			$to = $myusername;
			$subject = 'Dual Authentication for Unsafe Bank';
			$body = 'Greetings. Your one time password is: '.$random;
			$headers = 'From: Gamify <gamify101@gmail.com>' . "\r\n" .
				'Reply-To: Gamify <gamify101@gmail.com>' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			
			$result = mail($to, $subject, $body, $headers);			
			header('Location: duo_auth.php'); 
        }
        else{
          $error = "Your Login Name or Password is invalid";
        }
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>


<html>
  <head>
    <title>Safe Bank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="styles.css" rel="stylesheet">
  </head>

  <body class="login-bg">
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-12">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1>Unsafe Bank</h1>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

	<div class="page-content container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-wrapper">
			        <div class="box">
			            <div class="content-wrap">
			                <h3>Sign In</h3>
			                
                            <form action = "" method = "post">
			                <input class="form-control" type="text" name = "username" placeholder="User Name" required>
			                <input class="form-control" type="password" name = "password" placeholder="Password" required>

		                    <div class="already">
		                    	<span><?php if (isset($error)) echo $error ?></span>
		                    </div>

			                <div class="action">
                                <input type = "submit" class="btn btn-primary btn-block signup"  value = " Login "/>
			                </div>
                            </form>
                            <br><br>
                           	<div class="already">
					            <p>Don't have an account yet?</p>
					            <a href="user.php">Register</a>
				        	</div>
                  			
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>