<?php
  include_once("./config.php");
  session_start();
  $user_role = $user_email = "";
  if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
     header("Location:../OJ");
  }
  $user_role = $_SESSION['user_role'];
  $user_email = $_SESSION['email'];
  if($user_role === 'manager' || $user_role === 'staff'){
     $order_num = $_POST['order_num'];
     echo $order_num;
     $statement = $conn->prepare("UPDATE orders SET status = 'Shipped' WHERE order_num = ?");
     $statement->bind_param("i", $order_num);
     $statement->execute();
     $statement->close();
     header("Location:./allorders.php");
  }
?>
