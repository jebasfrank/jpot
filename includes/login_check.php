<?php 
if(!isset($_SESSION['uid'])){
  header("Location: logout.php");
  die();
}
?>