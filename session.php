<?php

   include('domainConfig.php');
   session_start();
   ob_start();
   $user_check = $_SESSION['UserID'];
   
   $ses_sql = mysqli_query($conn,"select UserID from Student where userID = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['UserID'];
   
   if(!isset($_SESSION['UserID'])){
      header("location:index.php");
      die();
   }
?>