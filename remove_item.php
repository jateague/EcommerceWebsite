<?php
      session_start();
      if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
            header("Location:../OJ/");
      }

      include_once("./config.php");
      $orderdetailsID = test_input($_POST['orderdetailsID']);
      $sql = "DELETE FROM order_details WHERE id={$orderdetailsID}";
      if ($conn->query($sql) === TRUE) {
         debug_to_console(["Success in deleting id", $orderdetailsID]);
      }
      else {
         debug_to_console(["Error", $conn->error()]);
      }
      header("Location:./cart.php");
?>
