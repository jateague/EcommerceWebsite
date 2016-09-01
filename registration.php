<!doctype html>

<html lang = "en">
<head>
	<meta charset="utf-8">
	<title> 405 Toys & Games - Registration </title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/stylesheet.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>

<body>
     <?php
     include_once("./config.php");
     $email = $first_name = $last_name = $password = $password2 = $address = $city = $zipcode = $state = "";
     $email_err = $first_name_err = $last_name_err = $password_err = $password2_err = $address_err = $city_err = $zipcode_err = $state_err = "";
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

         if(!empty($_POST["first_name"])){
            $first_name = test_input($_POST["first_name"]);
            if(!preg_match("/^[a-zA-Z]*$/",$first_name)){
               $first_name_err = "Letters allowed.";
            }
         }
         else{
            $first_name_err = "First name required.";
         }

         if(!empty($_POST["last_name"])){
            $last_name = test_input($_POST["last_name"]);
            if(!preg_match("/^[a-zA-Z]*$/",$last_name))
               $last_name_err = "Letters only.";
         }
         else
            $last_name_err = "Last name is required.";

         if(!empty($_POST["password"])){
            $password = test_input($_POST["password"]);
            if(!empty($_POST["password2"])){
               $password2 = test_input($_POST["password2"]);
               if($password != $password2)
                  $password_err = $password2_err = "Passwords do not match.";
            }
            else
               $password2_err = "Password validation required.";
         }
         else
            $password_err = "Password is required.";

         if(!empty($_POST["address"])){
            $address = test_input($_POST["address"]);
         }
         else
            $address_err = "Address required.";

         if(!empty($_POST["city"])){
            $city = test_input($_POST["city"]);
            if(!preg_match("/^[a-zA-Z]*$/",$city))
               $city_err = "Only letters.";
         }
         else
            $city_err = "City is required.";

         if(!empty($_POST["zipcode"])){
            $zipcode = test_input($_POST["zipcode"]);
            if(!preg_match("/^[0-9]{5}$/",$zipcode))
               $zipcode_err = "Only 5 numbers.";
         }
         else
            $zipcode_err = "Zipcode is required.";

         if(!empty($_POST["state"])){
            $state = test_input($_POST["state"]);
            if(!preg_match("/^[A-Z]*$/",$state))
               $state_err = "Invalid state initials.";
         }
         else {
            $state_err = "State required.";
         }

//		$first_name = $_POST["first_name"];
//		$last_name = $_POST["last_name"];
//		$password = $_POST["password"];
//		$address = $_POST["address"];
//		$city = $_POST["city"];
//		$zipcode = $_POST["zipcode"];
// 		$state = $_POST["state"];
         if($email_err === "" && $first_name_err === "" && $last_name_err === "" && $password_err === "" && $password2_err === "" && $address_err === "" && $city_err === "" && $zipcode_err === "" && $state_err === ""){
             $statement = $conn->prepare("SELECT email FROM users WHERE email=?");
             $statement->bind_param("s",$email);
             $statement->execute();
             $statement->bind_result($result);
             $statement->fetch();
             $statement->close();

             if(!$result){
                     $statement = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, address, city, zipcode, state) VALUES (?,?,?,?,?,?,?,?)");
                     $statement->bind_param("ssssssis", $first_name, $last_name, $email, $password, $address, $city, $zipcode, $state);
                     $statement->execute();
                     $statement->close();
                     $statement = $conn->prepare("INSERT INTO orders(user_email) VALUES(?)");
                     $statement->bind_param("s", $email);
                     $statement->execute();
                     $statement->close();
                     echo ("<script language='javascript'>window.alert('Registration Successful. You will now be directed back to the homepage.');
                     window.location='../OJ/';
                     </script>");
             }

             else{

                $email_err = "Email already in use.";
             }
         }
         else{
                debug_to_console(["email", "$email_err"]);
                debug_to_console($first_name_err);
                debug_to_console($last_name_err);
                debug_to_console($password_err);
                debug_to_console($password2_err);
                debug_to_console($address_err);
                debug_to_console($city_err);
                debug_to_console($zipcode_err);
                debug_to_console($state_err);
         }
      }

?>

	<div class = "container registration">
        <h1><b>Welcome to 405 Toys & Games!</b></h1>
       <?php
           echo "<nav>";
           echo "<ul class='nav nav-tabs'>";
           echo "<li role='presentation' class='active'><a href='registration.php'>Customer Registration</a></li>";
           echo "<li role='presentation'><a href='signin.php'>Returning User Sign In</a></li>";
           echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
           echo "</ul>";
           echo "</nav>";
         ?>

        <h2>Customer Registration</h2>

	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <div class="row">
	  <div class="form-group col-sm-4">
	    <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" name="email" value="<?php echo $email;?>" id="exampleInputEmail1" placeholder="Email">
            <span class="error">* <?php echo $email_err;?></span>
            <br>
	  </div>
	  </div>
	  <div class="row">
	  <div class="form-group col-sm-4">
	    <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" name="password"value="<?php echo $password;?>"  id="exampleInputPassword1" placeholder="Password">
            <span class="error">* <?php echo $password_err;?></span>
            <br>
          </div>
          </div>
         <div class="row">
          <div class="form-group col-sm-4">
            <label for="exampleInputPassword1">Re-Enter Password</label>
            <input type="password" class="form-control" name="password2"value="<?php echo $password2;?>"  id="exampleInputPassword1" placeholder="Repeat Password">
            <span class="error">* <?php echo $password2_err;?></span>
            <br>
          </div>
          </div>
	  <div class="row">
 	  <div class="form-group col-sm-4">
            <label for="exampleInputPassword1">First Name</label>
            <input type="First Name" class="form-control" name="first_name" value="<?php echo $first_name;?>" id="exampleInputPassword1" placeholder="First Name">
            <span class="error">* <?php echo $first_name_err;?></span>
            <br>
          </div>
	  </div>
	  <div class="row">
	  <div class="form-group col-sm-4">
            <label for="exampleInputPassword1">Last Name</label>
            <input type="Last Name" class="form-control" name="last_name"value="<?php echo $last_name;?>"  id="exampleInputPassword1" placeholder="Last Name">
            <span class="error">* <?php echo $last_name_err;?></span>
            <br>
          </div>
	  </div>
	  <div class="row">
	  <div class="form-group col-sm-4">
            <label for="exampleInputPassword1">Street Address</label>
            <input type="Street Address" class="form-control" name="address" value="<?php echo $address;?>" id="exampleInputPassword1" placeholder="Street Address">
            <span class="error">* <?php echo $address_err;?></span>
            <br>
          </div>
	  </div>
	  <div class= "row">
	  <div class="form-group col-sm-4">
            <label for="exampleInputPassword1">City</label>
            <input type="City" class="form-control" name="city" value="<?php echo $city;?>" id="exampleInputPassword1" placeholder="City">
            <span class="error">* <?php echo $city_err;?></span>
            <br>
          </div>
	  </div>
	 <div class="row">
  	 <div class="form-group col-sm-4">
         <label for="state" class="control-label">State</label>
		<select class="form-control" id="state" name="state">
			<option value="">N/A</option>
			<option value="AK">Alaska</option>
			<option value="AL">Alabama</option>
			<option value="AR">Arkansas</option>
			<option value="AZ">Arizona</option>
			<option value="CA">California</option>
			<option value="CO">Colorado</option>
			<option value="CT">Connecticut</option>
			<option value="DC">District of Columbia</option>
			<option value="DE">Delaware</option>
			<option value="FL">Florida</option>
			<option value="GA">Georgia</option>
			<option value="HI">Hawaii</option>
			<option value="IA">Iowa</option>
			<option value="ID">Idaho</option>
			<option value="IL">Illinois</option>
			<option value="IN">Indiana</option>
			<option value="KS">Kansas</option>
			<option value="KY">Kentucky</option>
			<option value="LA">Louisiana</option>
			<option value="MA">Massachusetts</option>
			<option value="MD">Maryland</option>
			<option value="ME">Maine</option>
			<option value="MI">Michigan</option>
			<option value="MN">Minnesota</option>
			<option value="MO">Missouri</option>
			<option value="MS">Mississippi</option>
			<option value="MT">Montana</option>
			<option value="NC">North Carolina</option>
			<option value="ND">North Dakota</option>
			<option value="NE">Nebraska</option>
			<option value="NH">New Hampshire</option>
			<option value="NJ">New Jersey</option>
			<option value="NM">New Mexico</option>
			<option value="NV">Nevada</option>
			<option value="NY">New York</option>
			<option value="OH">Ohio</option>
			<option value="OK">Oklahoma</option>
			<option value="OR">Oregon</option>
			<option value="PA">Pennsylvania</option>
			<option value="PR">Puerto Rico</option>
			<option value="RI">Rhode Island</option>
			<option value="SC">South Carolina</option>
			<option value="SD">South Dakota</option>
			<option value="TN">Tennessee</option>
			<option value="TX">Texas</option>
			<option value="UT">Utah</option>
			<option value="VA">Virginia</option>
			<option value="VT">Vermont</option>
			<option value="WA">Washington</option>
			<option value="WI">Wisconsin</option>
			<option value="WV">West Virginia</option>
			<option value="WY">Wyoming</option>
                </select>
                <span class="error">* <?php echo $city_err;?></span>
                <br>
                </div>
		</div>
          <div class = "row">
	  <div class="form-group col-xs-4">
            <label for="exampleInputPassword1">Zip Code</label>
            <input type="Zip Code" class="form-control" name="zipcode" value="<?php echo $zipcode;?>" id="exampleInputPassword1" placeholder="Zip Code">
            <span class="error">* <?php echo $zipcode_err;?></span>
            <br>
          </div>
	  </div>
	  <div class = "row">
	  <input type="submit" class="btn btn-default" value = "Submit">
	  <a class="btn btn-default" href="../OJ/" role="button">Cancel</a>
	  </div>
	</form>
	</div>
</body>
</html>
