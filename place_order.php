<?php
   include_once("./config.php");
   session_start();
   if(!isset ($_SESSION['email']) || empty($_SESSION['email'])){
      header("Location:../OJ");
   }
   $email = $_SESSION['email'];
   $max_ID = test_input($_POST['maxID']);
   $date = date("Y-m-d");
   $statement = $conn->prepare("UPDATE items I, order_details D SET I.quantity = I.quantity-D.quantity WHERE I.prod_id = D.prod_id AND D.order_num = ?");
   $statement->bind_param("i", $max_ID);
   $statement->execute();
   $statement->fetch();
   $statement->close();
   $buying_statement = $conn->prepare("UPDATE order_details D, items I SET D.buying_price = ROUND(I.price - (I.price * (I.promo_discount / 100)),2) WHERE I.prod_id = D.prod_id AND D.order_num = ?");
   $buying_statement->bind_param("i", $max_ID);
   $buying_statement->execute();
   $buying_statement->fetch();
   $buying_statement->close();
   $total_statement = $conn->prepare("SELECT SUM(D.buying_price * D.quantity) FROM order_details D WHERE D.order_num = ?");
   $total_statement->bind_param("i", $max_ID);
   $total_statement->execute();
   $total_statement->bind_result($total);
   $total_statement->fetch();
   $total_statement->close();
   debug_to_console([$max_ID, $total, $date]);
   $statement = $conn->prepare("UPDATE orders SET total=?, date_time=? WHERE order_num=?");
   $statement->bind_param("dsi", $total, $date, $max_ID);
   $statement->execute();
   $statement->close();
   $order_statement = $conn->prepare("INSERT INTO orders(user_email) VALUES (?)");
   $order_statement->bind_param("s", $email);
   $order_statement->execute();
   $order_statement->close();
   header("Location:./custorders.php");

?>
