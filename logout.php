<?php 
  session_start();
  include "includes/functions.php";
  $user_id = $_SESSION['uid'];
  session_destroy();
  $LogoutToken = "3buvimtredf6bfkent7rnvicb8vcb";  
  $post = [
    'LogoutToken' => $LogoutToken,
    'UserID' => $user_id
  ];
  $url = "https://www.pubgtime.com/api/LogOutApi.php";
  $response = post_api_call($post,$url);
  header("Location: index.php");
  die();
?>