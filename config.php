<?php
$ini_array = parse_ini_file("./mysql.ini");
$servername = $ini_array[servername];
$username = $ini_array[username];
$password = $ini_array[password];
$backup = $ini_array[backup];
$conn = new mysqli($servername, $username, $password, $username);
if ($conn->connect_error){
	$conn = new msqli($backup, $username, $password, $username);
	if ($conn->connect_error){
		die("Connection to backup failed." . $conn->connect_error);
	}
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data - htmlspecialchars($data);
   return $data;
}

function debug_to_console( $data ) {

   if ( is_array( $data ) )
      $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
   else
      $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

   echo $output;
}

?>
