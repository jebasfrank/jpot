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

  function get_api_call($url){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
  }

  function post_api_call2($data,$url){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Launcher');
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
  }

  function LaunchExternalScript($url)
  {
     $options = array(
        CURLOPT_TIMEOUT   => 1,
        CURLOPT_NOSIGNAL  => true,
        CURLOPT_USERAGENT => "Launcher"
     );
     $ch = curl_init($url);
     curl_setopt_array($ch,$options);
     curl_exec($ch);
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

  function get_result_name_img_report($DIGIT,$bonus){
    if($bonus>1){
      $bonus = "<span style='color:black'>x$bonus<span>";
    }else{
      $bonus = "";
    }
    if($DIGIT<=30){
      echo "<div class='no'>KS".$DIGIT."$bonus</div>";
      ?>
      <div style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important;">&#9824;<!-- <img style="width: 30px;" src="images/box1-icon1.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
      
    }else if($DIGIT>30 && $DIGIT<=60){
      $DIGIT = $DIGIT-30;
      echo "<div class='no'>KD".$DIGIT."$bonus</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important; color: red;">&#9830;<!-- <img style="width: 30px;" src="images/Dimond-icon/Dimond.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
      
    }else if($DIGIT>60 && $DIGIT<=90){
      $DIGIT = $DIGIT-60;
      echo "<div class='no'>KC".$DIGIT."$bonus</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important;">&#9827;<!-- <img style="width: 30px;" src="images/box-3-icon/Chiri.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
      
    }else if($DIGIT>90){
        $DIGIT = $DIGIT-90;

       echo "<div class='no'>KH".$DIGIT."$bonus</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important; color: red;">&#9829;<!-- <img style="width: 30px;" src="images/Heart-icon/Heart.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
     
    }

  }


  function get_result_normal_game($DIGIT,$bonus){
    if($bonus>1){
      $bonus = "<span style='color:black'>x$bonus<span>";
    }else{
      $bonus = "";
    }
    if(1){
      echo "<div class='no'>".$DIGIT."$bonus</div>";
      ?>
      
      <div style="clear: both;">&nbsp;</div>
      <?php
      
    }

  }

  function get_result_name_img($DIGIT,$bonus){
    if($bonus>1){
      $bonus = "<span style='color:yellow'>x$bonus<span>";
    }else{
      $bonus = "";
    }
    if($DIGIT<=30){
      echo "<div class='no'>KS".$DIGIT."$bonus</div>";
      ?>
      <div style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important;">&#9824;<!-- <img style="width: 30px;" src="images/box1-icon1.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
      
    }else if($DIGIT>30 && $DIGIT<=60){
      $DIGIT = $DIGIT-30;
      echo "<div class='no'>KD".$DIGIT."$bonus</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important; color: red;">&#9830;<!-- <img style="width: 30px;" src="images/Dimond-icon/Dimond.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
      
    }else if($DIGIT>60 && $DIGIT<=90){
      $DIGIT = $DIGIT-60;
      echo "<div class='no'>KC".$DIGIT."$bonus</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important;">&#9827;<!-- <img style="width: 30px;" src="images/box-3-icon/Chiri.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
      
    }else if($DIGIT>90){
        $DIGIT = $DIGIT-90;

       echo "<div class='no'>KH".$DIGIT."$bonus</div>";
      ?>
      <div class="Heart-iconss" style="text-align: right; float: right; font-size: 50px; margin-top: 10px; line-height: 0px !important; color: red;">&#9829;<!-- <img style="width: 30px;" src="images/Heart-icon/Heart.png" alt=""> --></div>
      <div style="clear: both;">&nbsp;</div>
      <?php
     
    }

  }

  function get_result_name_img2($DIGIT){
    $data = "";
    if($DIGIT<=30){
      $data = "<span style='font-size:20px;'>&#9824; </span>";
      if($DIGIT<10){
        $DIGIT = "0".$DIGIT;
      }
      $data .= "<span style='font-size:13px;'>KS".$DIGIT."</span>";
    }else if($DIGIT>30 && $DIGIT<=60){
      $DIGIT = $DIGIT-30;
      if($DIGIT<10){
        $DIGIT = "<span style='font-size:13px;'>0".$DIGIT."</span>";
      }
      $data = "<span style='font-size:20px;'>&#9830; </span>";    
      $data .= "<span style='font-size:13px;'>KD".$DIGIT."</span>";   
    }else if($DIGIT>60 && $DIGIT<=90){
      $DIGIT = $DIGIT-60;
      if($DIGIT<10){
        $DIGIT = "<span style='font-size:13px;'>0".$DIGIT."</span>";
      }
      $data .= "<span style='font-size:20px;'>&#9827; </span>";       
      $data .= "<span style='font-size:13px;'>KC".$DIGIT."</span>";
  }else if($DIGIT>90){
      $DIGIT = $DIGIT-90;
      if($DIGIT<10){
      $DIGIT = "<span style='font-size:13px;'>0".$DIGIT."</span>";
      }
      $data .= "<span style='font-size:20px;'>&#9829; </span>";    
      $data .= "<span style='font-size:13px;'>KH".$DIGIT."</span>";   
    }
    return $data;
  }

  function get_result_name_img3($DIGIT){
    $data = "";
    if($DIGIT<=30){
      $data = "<span style='font-size:50px;'>&#9824; </span>";
      if($DIGIT<10){
        $DIGIT = "0".$DIGIT;
      }
      $data .= "<span style='font-size:40px;'>KS".$DIGIT."</span>";
    }else if($DIGIT>30 && $DIGIT<=60){
      $DIGIT = $DIGIT-30;
      if($DIGIT<10){
        $DIGIT = "<span style='font-size:40px;'>0".$DIGIT."</span>";
      }
      $data = "<span style='font-size:50px;'>&#9830; </span>";    
      $data .= "<span style='font-size:40px;'>KD".$DIGIT."</span>";   
    }else if($DIGIT>60 && $DIGIT<=90){
      $DIGIT = $DIGIT-60;
      if($DIGIT<10){
        $DIGIT = "<span style='font-size:40px;'>0".$DIGIT."</span>";
      }
      $data .= "<span style='font-size:50px;'>&#9827; </span>";       
      $data .= "<span style='font-size:40px;'>KC".$DIGIT."</span>";
  }else if($DIGIT>90){
      $DIGIT = $DIGIT-90;
      if($DIGIT<10){
      $DIGIT = "<span style='font-size:40px;'>0".$DIGIT."</span>";
      }
      $data .= "<span style='font-size:50px;'>&#9829; </span>";    
      $data .= "<span style='font-size:40px;'>KH".$DIGIT."</span>";   
    }
    return $data;
  }
?>