<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title> 405 Toys & Games - Sign In </title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/stylesheet.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

</head>

<body>

        <?php
        include_once("./config.php");

         if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(!empty($_POST["email"])){
                  $email = test_input($_POST["email"]);
               if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                      $email_err = "Invalid email.";
               }
            }
            else{
               $email_err = "Email is required.";
             }
         if(!empty($_POST["password"]))
            $password = test_input($_POST["password"]);
         else{
            $password_err = "Password is required.";
         }
         $statement = $conn->prepare("SELECT email,role FROM users WHERE email=? AND password=?");
         $statement->bind_param("ss",$email, $password);
         $statement->execute();
         $statement->bind_result($user_email, $user_role);
         $statement->fetch();
         $statement->close();
         if($user_email && $user_role){
            session_start();
            $_SESSION['user_role'] = $user_role;
            $_SESSION['email'] = $user_email;
            header("Location:../OJ");
         }
         else{
            $error = "Email and/or password were incorrect. Try again.";
         }
         }
         ?>

	<div class = "container registration">
	<h1><b> Welcome to 405 Toys & Games!</b></h1>
        <?php
            echo "<nav>";
            echo "<ul class='nav nav-tabs'>";
            if($user_email === ""){
               echo "<li role='presentation'><a href='registration.php'>Customer Registration</a></li>";
               echo "<li role='presentation' class='active'><a href='signin.php'>Returning User Sign In</a></li>";
               echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
            }
            echo "</ul>";
            echo "</nav>";
         ?>

	<h2> Sign In </h2>
        <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <span class="error"><?php echo $error; ?></span>
          <div class="row">
          <div class="form-group col-sm-4">
            <label for="emailAddress">Email address</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="jane.doe@example.com">
          </div>
          </div>
          <div class="row">
          <div class="form-group col-sm-4">
            <label for="examplePassword">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          </div>
          </div>
	  <div class = "row">
	  <input type="submit" value="Submit" class="btn btn-default">
          <a class="btn btn-default" href="../OJ/" role="button">Cancel</a>
          </div>
	  </form>
	</div>
</body>
</html>
