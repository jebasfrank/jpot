<?php 
  function post_api_call($data,$url){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
  }
  function get_next_draw_time(){
    $timeToken = "3reerntbk4cvxvdner22emul9kx7czv";
    $post = [
      'TimeToken' => $timeToken,
      'GameId' => 11
    ];
    $url = "https://www.pubgtime.com/api/NextDrawTimeAPI.php";
    $response = post_api_call($post,$url);
    return json_decode($response);
  }
  function user_balance($UID){
    $BalanceToken = "esdatbk4cvxvdner12egulfkd7caz";
    $UserID = $UID;
    $post = [
      'BalanceToken' => $BalanceToken,
      'UserID' => $UserID
    ];
    $url = "https://www.pubgtime.com/api/UserBalanceApi.php";
    $response = post_api_call($post,$url);
    $balance = 0;
    $response = json_decode($response);
    if(!isset($response->error)){
      $balance = $response->Balance;
    }
    return $balance;
  }
  function get_normal_time($timestamp){
    date_default_timezone_set('Asia/Kolkata');
    $epoch = $timestamp;
    //$dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
    return date('h:i:s a', $epoch); // output = 2017-01-01 00:00:00
  }

  function get_result_name_img($DIGIT){
    if($DIGIT<=30){
      echo "<div class='no'>KS".$DIGIT."</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right;"><img style="width: 30px;" src="images/box1-icon1.png" alt=""></div>
      <div style="clear: both;"></div>
      <?php
      
    }else if($DIGIT>30 && $DIGIT<=60){
      echo "<div class='no'>KD".$DIGIT."</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right;"><img style="width: 30px;" src="images/Dimond-icon/Dimond.png" alt=""></div>
      <div style="clear: both;"></div>
      <?php
      
    }else if($DIGIT>60 && $DIGIT<=90){
      echo "<div class='no'>KC".$DIGIT."</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right;"><img style="width: 30px;" src="images/box-3-icon/Chiri.png" alt=""></div>
      <div style="clear: both;"></div>
      <?php
      
    }else if($DIGIT>90){
       echo "<div class='no'>KH".$DIGIT."</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right;"><img style="width: 30px;" src="images/Heart-icon/Heart.png" alt=""></div>
      <div style="clear: both;"></div>
      <?php
     
    }

  }
?>