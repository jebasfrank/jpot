<?php 
  session_start();
  include "includes/functions.php";
  include "includes/login_check.php";
  $nextDrwatime = get_next_draw_time();
  $nextDrwatime = $nextDrwatime->nextDrawTimeStamp;
  $curTIime = time();
  //Get Results//
  $url = "https://www.pubgtime.com/api/ResultListAPI.php";
  $post = array();
  $uid = $_SESSION['uid'];
  $gameID = 12;
  $lifetimeToken = $_SESSION['lifetime_token'];
  $post = [
    'ResultListToken' => 'cmxwzd3e5wreecv8c7mnbcx0hsm5u',
    'UserID' => $uid,
    'DeviceID' => rand(999,9999),
    'gameID' => $gameID,
    'LoginToken' => $lifetimeToken
   ];
   $response = post_api_call($post,$url);
   $response = json_decode($response);
   $last_time_stamp = time();
   if(isset($response->ResultList[0]->TimeStamp)){
    $last_time_stamp = $response->ResultList[0]->TimeStamp;
   }

   //Getting Next Draw Time//
   $url = "https://www.pubgtime.com/api/NextDrawTimeAPI.php";
   $post = array();
   $post['TimeToken'] = "3reerntbk4cvxvdner22emul9kx7czv";
   $post['GameId'] = $gameID;
   $response2 = json_Decode(post_api_call($post,$url));
   $nex_time = "";
   if(isset($response2->nextDrawTimeStamp)){
    $nex_time = $response2->rawTime;
   }
   //Checking For Manual//
   $url = "https://www.pubgtime.com/api/ismanualApi.php";
   $post = array();
   $post['ValueToken'] = "3reerntbk4cvxvdner22emul9kx7czv";
   $post['GameID'] = $gameID;
   $response3 = json_Decode(post_api_call($post,$url));
   $is_manual = 0;
   if(isset($response3->is_manual)){
    $is_manual = $response3->is_manual;
   }
 ?>
<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <!--font-awesome CSS-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Card Game</title>
  <!--Check-in And Check-out Date Range Picker CSS-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body style="background-color:#00b2ff">
      <div style="background-color:hsla(120,100%,50%,0.3); position: fixed; width: 100%; height: 100vh; z-index: 999; display: none;" id="background_id">&nbsp;</div>

<div style="background:rgba(0, 0, 0, 0.5); z-index:999; width: 100%; height: 100vh; position: fixed; display:none;" id="result_animation">
  <div style="background: #fff; position: fixed; top:35%; left:35%; border-radius: 5px; padding: 5px; width:400px; text-align: center;"><strong>Declaring the result!</strong></div>
<div style="width:400px; height: 120px; overflow: hidden; text-align:center; background: #fff; position: fixed; top:40%; left:35%; border-radius: 5px;">
  <script>
    var numArr = new Array();
    <?php 
          for($i=1; $i<=120; $i++){
            ?>
            numArr[<?php echo $i; ?>] = "<?php echo get_result_name_img3($i);  ?>";
            <?php
          }
     ?>
  </script>
      <div class="animate" style="height: 100px; width: 100%; text-align:center; ">
        
          <div style="height:auto; width:100%; line-height: 60px; text-align: center;">
            <div style="font-size: 50px; font-weight: 800; text-align: center;" id="display_num"></div>
            <button type="button" onclick="location.reload()" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Reload</button>
          </div>
            
      </div>
    </div>
</div>

  <div id="container">
<div class="container-fluid top-bg" >
  <div class="container container-full-wrapper">
    <div class="row">
      <div class="top-banner-btn">
        <div class="btn1"><a href="#">120Card</a></div>
        <div class="btn2"><a href="#">2 Digit</a></div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid menu-wrapper">
  <div class="container container-full-wrapper">
    <div class="row">
      <div class="col-md-6">
        <div class="menu-left">
          <ul>
            <li><img src="images/whatsapp.png" class="img-fluid" alt="">70768-51401</li>
            <li>Jcash.top</li>
            <li>G.V : MH</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="menu-right">
          <ul>
            <li>ID : <?php echo $uid; ?></li>
            <li><a style="cursor:pointer;"  data-toggle="modal" data-target="#basicModal">BetDetails</a></li>
            <li><a style="cursor:pointer;"  data-toggle="modal" data-target="#ReportModal">Report</a></li>
            <li><a style="cursor:pointer;"  data-toggle="modal" data-target="#ResultModal">Result</a></li>
            <li><a href="logout.php">Sign out </a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>


<style>
  .slots {
    border-bottom: 1px solid #56C4FF;
    padding: 5px;
}
.slots label{
  margin-bottom: 0px !important;
  font-size: 13px;
}
.no {
  float: left;
}
</style>




<div class="wrapper-section-bg">
<div class="container-fluid wrapper-section2">
  <div class="container container-full-wrapper">
    <div class="row game-box-row-wrapper">
      <div class="col-md-4 co-4">
        <div class="wrapper-section-left">
          <h5 style="color:#fff;"><?php echo $_SESSION['uid']; ?></h5>
          <h5 style="color:#fff;">Balance : <span id="point_id"><?php echo user_balance($_SESSION['uid']); ?></span> <a style="cursor:pointer;" onclick="toggle_hide()"><span id="showid" style="font-size:11px; color:yellow;">Hide</span></a></h5>
          <h4 style="color:#fff;">Time : <span style="font-size: 20px;line-height: 25px;" id="time"></span></h4>
        </div>
      </div>
      <div class="col-md-4 co-4">
        <div class="wrapper-section-logo"><img src="images/jackpot_icon.png" class="img-fluid" alt=""> </div>
      </div>
      <div class="col-md-4 co-4">
        <div class="wrapper-section-right-r">
          <div class="wrapper-section-right">
            <h4>Time Left: <span class="countdown" style="font-size: 25px;color: #fff;font-weight: 400;line-height: 25px;"></span></h4>
          </div>
          <div class="wrapper-check-box-wrapper">
           <div class="slots">
                            <input id="current_id" class="draw_type" type="radio" value="1" name="draw_status" checked>
                            <label for="current_id">Current</label>
                          </div>
                          <div class="slots">
                            <input id="next5_id" class="draw_type" value="5" type="radio" name="draw_status">
                            <label for="next5_id">NEXT 5</label>
                          </div>
                          <div class="slots">
                            <input id="next10_id" class="draw_type" value="10" type="radio" name="draw_status">
                            <label for="next10_id">NEXT 10</label>
                          </div>
                          <div class="slots">
                            <input id="next20_id" class="draw_type" value="20" type="radio" name="draw_status">
                            <label for="next20_id">NEXT 20</label>
                          </div>
                          <div class="slots">
                            <input id="next30_id" class="draw_type" value="30" type="radio" name="draw_status">
                            <label for="next30_id">NEXT 30</label>
                          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .game-input-group-row ul li img{
    padding: 5px;
    height: 40px;
  }
  .select2-container .select2-selection--single{
    height: 40px;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 40px;
  }
  .game-row-box .game-row-1 .game-input-group-row ul li{
    margin-bottom: 40px;
  }
  .game-row-box .game-row-1 .game-input-group-row{
    margin-top: 87px;
  }
</style>
      <input type="hidden" name="last_result_time" value="<?php echo $last_time_stamp; ?>" id="last_result_time_id">

<div class="container-fluid wrapper-section3">
  <div class="container container-full-wrapper">
    <div class="row game-box-row">
      <div class="col-md-3">
        <div class="game-row-box">
          <div class="game-row-1">
            <div class="game-input-group-row">
              <?php $index= 1; ?>
              <ul>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text"  data-from="1" data-to="6" class="outer_input input-rr up_horizontal_class numeric">
                </li>
                <?php $index= $index+28; ?>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text" data-from="7" data-to="12" class="outer_input input-rr up_horizontal_class numeric input-rr">                
                </li>

                <?php $index= $index+28; ?>

                <li>
                  <input maxlength="2" type="text" data-from="13" tabindex="<?php echo $index; ?>" data-to="18" class="outer_input input-rr  up_horizontal_class numeric input-rr">
                </li>
                              <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="19" data-to="24" tabindex="<?php echo $index; ?>" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
 <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="25" tabindex="<?php echo $index; ?>" data-to="30" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
              </ul>
            </div>
          </div>
          <div class="game-row-2">
            <div class="game-row-box-wrapper">
              <div class="game-box-title">
                <div class="game-box-title-icon"> <img src="images/box1-icon1.png" class="img-fluid" alt=""> </div>
                <div class="inpou-box-top">
                  <input type="text" id="text" name="text" class="section_class numeric sc1" data-index="1" data-from="1" data-to="30">
                </div>
              </div>
              <div class="game-box-wrapper2">
                
                <div class="game-input-group-row">
                  <ul>
                    <?php 
                      $tab_index= 1;
                        for($i=1; $i<=30; $i++){
                          $tab_index++;
                      ?>
                    <li>
                      <img src="images/box-1-icon/<?php echo $i; ?>.png" class="img-fluid" alt="" >
                      <input maxlength="2" tabindex="<?php echo $tab_index; ?>" data-digit="<?php echo $i; ?>" type="text" id="num_id_<?php echo $i; ?>" name="num<?php echo $i; ?>" class="inner_input numeric qty_field input-rr">

                    </li>

                    <?php 
                        if($i%6==0){
                          $tab_index = $tab_index+22;
                          ?>
                        </div>
                          </ul>
                          <div class="game-input-group-row">
                        <ul>
                          <?php
                        }
                        
                       } ?>
                    
                </div>
               

               
                
              </div>
              <div class="game-input-group-row">
                <?php $index=142; ?>
                <ul>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="1" data-to="25" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="2" data-to="26" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="3" data-to="27" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="4" data-to="28" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="5" data-to="29" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="6" data-to="30" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="game-row-box">
          <div class="game-row-1">
            <div class="game-input-group-row">
              
                 <?php $index= 8; ?>
              <ul>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text"  data-from="31" data-to="36" class="outer_input input-rr up_horizontal_class numeric">
                </li>
                <?php $index= $index+28; ?>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text" data-from="37" data-to="42" class="outer_input input-rr up_horizontal_class numeric input-rr">                
                </li>

                <?php $index= $index+28; ?>

                <li>
                  <input maxlength="2" type="text" data-from="43" tabindex="<?php echo $index; ?>" data-to="48" class="outer_input input-rr  up_horizontal_class numeric input-rr">
                </li>
  <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="49" data-to="54" tabindex="<?php echo $index; ?>" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
 <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="55" tabindex="<?php echo $index; ?>" data-to="60" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
                </ul>
            </div>
          </div>
          <div class="game-row-2">
            <div class="game-row-box-wrapper">
              <div class="game-box-title">
                <div class="game-box-title-icon"> <img src="images/Dimond-icon/Dimond.png" class="img-fluid" alt=""> </div>
                <div class="inpou-box-top">
                  <input type="text" id="text" name="text" class="section_class numeric sc2" data-index="2" data-from="31" data-to="60">
                </div>
              </div>
              <div class="game-box-wrapper2">
              
                <div class="game-input-group-row">
                  <ul>
                    <?php 
                      $tab_index= 8;
                        for($i=1; $i<=30; $i++){
                          $tab_index++;
                      ?>
                      <li>
                        <img src="images/Dimond-icon/dimod-<?php echo $i; ?>.png" class="img-fluid" alt="">
                          <input maxlength="2" tabindex="<?php echo $tab_index; ?>" data-digit="<?php echo $i+30; ?>" type="text" id="num_id_<?php echo ($i+30); ?>" name="num<?php echo $i+30; ?>" class="inner_input numeric qty_field input-rr">
                      </li>
                    <?php 
                      if($i%6==0){
                        $tab_index = $tab_index+22;
                        ?>
                        </div>
                          </ul>
                          <div class="game-input-group-row">
                        <ul>
                        <?php
                        }
                     } 
                     ?>

                    
                </div>
               
                
               
              </div>
              <div class="game-input-group-row">
                <?php $index=149; ?>
                <ul>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="31" data-to="55" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="32" data-to="56" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="33" data-to="57" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="34" data-to="58" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="35" data-to="59" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="36" data-to="60" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="game-row-box">
          <div class="game-row-1">
            <div class="game-input-group-row">


              <?php $index= 15; ?>
              <ul>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text"  data-from="61" data-to="66" class="outer_input input-rr up_horizontal_class numeric">
                </li>
                <?php $index= $index+28; ?>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text" data-from="67" data-to="72" class="outer_input input-rr up_horizontal_class numeric input-rr">                
                </li>

                <?php $index= $index+28; ?>

                <li>
                  <input maxlength="2" type="text" data-from="73" tabindex="<?php echo $index; ?>" data-to="78" class="outer_input input-rr  up_horizontal_class numeric input-rr">
                </li>
  <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="79" data-to="84" tabindex="<?php echo $index; ?>" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
 <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="85" tabindex="<?php echo $index; ?>" data-to="90" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
                </ul>
            </div>
          </div>
          <div class="game-row-2">
            <div class="game-row-box-wrapper">
              <div class="game-box-title">
                <div class="game-box-title-icon"> <img src="images/box-3-icon/Chiri.png" class="img-fluid" alt=""> </div>
                <div class="inpou-box-top">
                  <input type="text" id="text" name="text" class="section_class numeric sc3" data-index="3" data-from="61" data-to="90">
                </div>
              </div>
              <div class="game-box-wrapper2">
                
                <div class="game-input-group-row">
                  <ul>

                    <?php 
                      $tab_index= 15;
                        for($i=1; $i<=30; $i++){
                          $tab_index++;
                      ?>
                      <li>
                        <img src="images/box-3-icon/Chiri-<?php echo $i; ?>.png" class="img-fluid" alt="">
                          <input maxlength="2" tabindex="<?php echo $tab_index; ?>" data-digit="<?php echo $i+60; ?>" type="text" id="num_id_<?php echo ($i+60); ?>" name="num<?php echo $i+60; ?>" class="inner_input numeric qty_field input-rr">
                      </li>
                    <?php 
                      if($i%6==0){
                        $tab_index = $tab_index+22;
                        ?>
                        </div>
                          </ul>
                          <div class="game-input-group-row">
                        <ul>
                        <?php
                        }
                     } 
                     ?>
             
                    
                  </ul>
                </div>
                
              </div>
              <div class="game-input-group-row">
                <?php $index=156; ?>
                <ul>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="61" data-to="85" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="62" data-to="86" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="63" data-to="87" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="64" data-to="88" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="65" data-to="89" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="66" data-to="90" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="game-row-box">
          <div class="game-row-1">
            <div class="game-input-group-row">
              <?php $index= 22; ?>
              <ul>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text"  data-from="91" data-to="96" class="outer_input input-rr up_horizontal_class numeric">
                </li>
                <?php $index= $index+28; ?>
                <li>
                  <input maxlength="2" tabindex="<?php echo $index; ?>" type="text" data-from="97" data-to="102" class="outer_input input-rr up_horizontal_class numeric input-rr">                
                </li>

                <?php $index= $index+28; ?>

                <li>
                  <input maxlength="2" type="text" data-from="103" tabindex="<?php echo $index; ?>" data-to="108" class="outer_input input-rr  up_horizontal_class numeric input-rr">
                </li>
  <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="109" data-to="114" tabindex="<?php echo $index; ?>" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
 <?php $index= $index+28; ?>
                <li>
<input maxlength="2" type="text" data-from="115" tabindex="<?php echo $index; ?>" data-to="120" class="outer_input input-rr up_horizontal_class numeric input-rr">                </li>
                </ul>
            </div>
          </div>
          <div class="game-row-2">
            <div class="game-row-box-wrapper">
              <div class="game-box-title">
                <div class="game-box-title-icon"> <img src="images/Heart-icon/Heart.png" class="img-fluid" alt=""> </div>
                <div class="inpou-box-top">
                  <input type="text" id="text" name="text" class="section_class numeric sc4" data-index="4" data-from="91" data-to="120">
                </div>
              </div>
              <div class="game-box-wrapper2">
               
                <div class="game-input-group-row">
                  <ul>
                   <?php 
                      $tab_index= 22;
                        for($i=1; $i<=30; $i++){
                          $tab_index++;
                      ?>
                      <li>
                        <img src="images/Heart-icon/Heart-<?php echo $i; ?>.png" class="img-fluid" alt="">
                          <input maxlength="2" tabindex="<?php echo $tab_index; ?>" data-digit="<?php echo $i+90; ?>" type="text" id="num_id_<?php echo ($i+90); ?>" name="num<?php echo $i+90; ?>" class="inner_input numeric qty_field input-rr">
                      </li>
                    <?php 
                      if($i%6==0){
                        $tab_index = $tab_index+22;
                        ?>
                        </div>
                          </ul>
                          <div class="game-input-group-row">
                        <ul>
                        <?php
                        }
                     } 
                     ?>
                </div>
                

              </div>

              <div class="game-input-group-row">
              <?php $index=163; ?>
                <ul>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="91" data-to="115" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="92" data-to="116" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="93" data-to="117" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="94" data-to="118" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="95" data-to="119" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                  <?php $index++; ?>
                  <li>
                    <input maxlength="2" tabindex="<?php echo $index; ?>" data-from="96" data-to="120" autocomplete="off" class="input-rr inner_input numeric inner_input_vertical" type="text" id="text" name="text">
                  </li>
                </ul>
            </div>
            </div>
          </div>
        </div>
      </div>
      <style>
        .Jackpot-wrapper{
          border: none;
        }
      </style>
      <div class="col-md-3 last-3">
        <div class="Jackpot-wrapper">
          <div class="jackpot-title-box">120Card</div>
          <div class="jackpot-box-section-scroll">
            <div class="jackpot-roww" style="background-color: #fff;">
              <p><?php echo $nex_time; ?></p>
              </div>  
                      <?php 
                        foreach ($response->ResultList as $key => $value) {  
                        $bonus = 1;
                        if($value->bonus>1){
                          $bonus = $value->bonus;
                        }
                        //print_r($value);
                          ?>
                               <div class="jackpot-roww">
                                <?php get_result_name_img($value->GameValue,$bonus); ?>
                                <p><?php echo $value->GameTime; ?></p>            
                               </div>                             
                          <?php
                        }
                      ?>


           
            
          </div>
        </div>
      </div>
    </div>
    <style type="text/css">
      .left-box-btn .left-box-btn1, .left-box-btn .left-box-btn2, .left-box-btn .left-box-btn3{
        width: 72px !important;
      }
    </style>
 
            <input type="hidden" id="xvalue_id" value="1">
    <div class="row">
      <div class="col-md-3">
        <div class="left-box-btn">
          <div class="left-box-btn1">
            <div class="left-box-btn-wr">
           <button onclick="select_qtyx(10)" id="btn10" type="button" class="btn btn-danger bottom_btn" style="background-color: #FE0E00;">10</button></div>
                <p>1050</p>
          </div>
          <div class="left-box-btn2">
                        <div class="left-box-btn-wr">

             <button onclick="select_qtyx(5)" id="btn5" type="button" class="btn bottom_btn" style="background-color: #FE9A00;color: #fff;">5</button></div>
                <p>525</p>
          </div>
          <div class="left-box-btn3">
            <div class="left-box-btn-wr">
            <button onclick="select_qtyx(2)" id="btn2" type="button" class="btn  bottom_btn" style="background-color: #01D649;color: #fff;">2</button></div>
                <p>210</p>

          </div>

          <div class="left-box-btn1">
            <div class="left-box-btn-wr">
            <button onclick="select_qtyx(1)" id="btn1" type="button" class="btn  bottom_btn" style="background-color: #FE0E00;color: #fff;">1</button></div>
                <p>105</p>

          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="quantity-row">
          <div class="quantity">Quantity : <span id="qtyID" style="color:#000">0</span></div>
          <div class="quantity">Amount : <span id="amtID" style="color:#000">0</span></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="bottom-btn-wrapper">
          <div class="bottom-btn-wrapper-row1">
            <div class="bottom-btn" onclick="clear_field()"><a style="cursor: pointer;"><img src="images/refresh-buttons1.png" class="img-fluid" alt="">Clear</a></div>
            <div class="bottom-btn" style="background-color:#FF1493" onclick="buy_ticket()"><a style="cursor: pointer; color: #000;">BUY</a></div>
          </div>
          <div class="bottom-btn-wrapper-row2">
            <div class="bottom-btn-wrapper-display-fix">
              <div class="bottom-btn" onclick="claimTicket()" id="claimTicketID"><a style="cursor: pointer;"><img src="images/check-icon.png" class="img-fluid" alt=""></a></div>
              <div class="bottom-btn-wrapper-row-inpout2">
                <select name="barocde_name" id="searchid" style="width: 100%; height: 40px;">
                            <option value="">Search Barcode</option>
                          </select>
              </div>
              <div class="bottom-btn closeb" onclick="cancel_ticket2()"><a style="cursor: pointer;"><img src="images/close.png" class="img-fluid" alt=""></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<!--Check-in And Check-out Date Range Picker--> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
	var date_input = $('input[name="date"]'); //our date input has the name "date"
	var container = "body";
	var options = {
		format: 'dd/mm/yyyy',
		container: container,
		todayHighlight: true,
		autoclose: true,
		language: 'el' 
	};
	date_input.datepicker(options);
  select_qtyx(2);
})

/****** MENU jQuery ******/
jQuery(document).ready(function(){
jQuery(".toggle").click(function(){
jQuery("ul.menu").toggleClass('open');
});
jQuery('html').click(function() {
    if (jQuery('ul.menu').hasClass('open')){
        jQuery('ul.menu').removeClass('open');
    }
});
// replace mobile-nav with your entire nav container
jQuery('.nav_area').click(function(e){
    e.stopPropagation();
});
jQuery(window).load(function(){
  jQuery('li.menu-item-has-children  > span').click(function() {
      jQuery(this).next('.sub-menu').slideToggle('500');
      jQuery(this).closest('li').siblings().find('ul').hide();
      jQuery(this).toggleClass('close-icon');
      jQuery(this).closest('li').siblings().find('span').removeClass('close-icon');
     });
  });
});	

</script>


<!---Printable Area---->
<style>
  #printableArea table tr td{
    font-size: 12px !important;
  }
  #trans_id table tr td{
    font-size: 12px !important;
  }
</style>
<div id="printableArea" style="display:none; font-size: 12px;">
        <div style="width: 180px;">
            <div >
           
              <table style="width:100%">
                <tr>
                  <td style="width:50%; font-size: 12px !important;">Jackpot</td>
                  <td>:</td>
                  <td style="font-size:12px !important;"><?php echo $_SESSION['uid']; ?></td>
                </tr>
                 <!-- <tr>
                  <td style="width:50%; font-size:12px !important;">Barcode</td>
                  <td>:</td>
                  <td style="font-size:12px !important;"><span id="trans_id"></span></td>
                </tr> -->
                <tr>  
                    <td colspan="3" style="font-size:12px !important;">Barcode <br><span id="trans_id"></span></td>
                </tr> 
                <tr>
                  <td style="font-size:12px !important;" colspan="3">120Card Game</td>
                </tr>
                <!-- <tr>
                  <td style="width:50%">Ticket Time</td>
                  <td><span id="ticket_time_id"></span></td>
                </tr> -->
                <tr>
                  <td style="width:50%; font-size:12px !important;">Draw Time</td>
                  <td>:</td>
                  <td style="font-size:12px !important;"><span id="draw_id"></span></td>
                </tr>
               
              </table>
               
            </div>
            <div id="particulars" style="text-align: center;padding: 5px; font-size: 12px !important;">
            </div>
    <table style="width: 100%;text-align: center;margin-right:90px !important" class="table-bordered">
        <tr class="table-primary" style="float: left;margin-left: 5px;">
<!--             <th style="text-align:center">MRP: <strong id="current_rate"></strong></th>
 -->                <td style="text-align:center; font-size:12px !important;">Qty: <span id="total_tickets"></span></td>
                    <td style="text-align:center; font-size:12px !important;">Total: <span id="total_booking_amount"></span></td>
                    <td style="font-size:12px !important;"><span id="ticket_time_id"></span></td>
                    </tr>
                </table>
                <div style="width:100%; font-size: 12px; text-align: center;">Results : www.jcash.top</div>
            </div>
        </div>
        <div>
    </div>

<!---Printable Area---->


<!-- BetDetails  modal -->
<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">BetDetails</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body" style="margin: 0px; padding: 2px;">
        <table class="table table" style="border-top: 0px; margin: 0px;">
          <tr style="border-top: 0px;">
            <td  style="border-top: 0px;">
              <select name="report_type" class="form-control" id="report_type_id">
                <option value="C">Current</option>
                <option value="T">Barcode</option>
                <option value="CAN">Cancelled</option>
                <option value="CL">Claimed</option>
              </select>
            </td>
            
            <td style="border-top: 0px;"><button type="button" onclick="get_sale_report()" class="btn btn-primary btn-sm">Show</button> <button class="btn btn-danger btn-sm"  data-dismiss="modal"><i class="fa fa-times"></i></button></td>
            <td style="border-top: 0px;"></td>
          </tr>
        </table>
          <span id="report_html2" style="width:100%;"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Report  modal -->
<div class="modal fade" id="ReportModal" tabindex="-1" role="dialog" aria-labelledby="ReportModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Report</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body" style="font-size:13px;">
        <ul class="dropdown-menu-new">
          <div class="dropdown-menu-box  result-bg">
            <table class="table table">
          <tr>
            <td style="width:35%;"><input readonly value="<?php echo date("d-m-Y"); ?>" id="datefrom_id" placeholder="Date Select" data-date-format="dd-mm-yyyy" autocomplete="off" type="text" name="from_date" id="from_date_id" class="form-control datepicker2"></td>
            <td style="width:35%;"><input readonly value="<?php echo date("d-m-Y"); ?>" id="dateto_id" placeholder="Date Select" data-date-format="dd-mm-yyyy" autocomplete="off" type="text" name="from_date" id="to_date_id" class="form-control datepicker2"></td>
            <td><button type="button" onclick="get_report()" class="btn btn-primary btn-sm">Show</button>
<button class="btn btn-danger  btn-sm"  data-dismiss="modal"><i class="fa fa-times"></i></button>
            </td>
          </tr>
        </table>
            <div class="game-report-btn-wr"><a href="#">Game Report</a></div>
            <div class="table-wrapper-box">
              <span id="report_html" style="width:100%;"></span>            
            </div>
          </div>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Result  modal -->
<div class="modal fade" id="ResultModal" tabindex="-1" role="dialog" aria-labelledby="ResultModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Result</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">

        <ul class="dropdown-menu-new scroll-class">
          <div class="dropdown-menu-box report-bg">
            <div class="dropdown-menu-box-top-box t-wrapper-tow">
              <div class="select-box-rows datepicker-rowss">
                <div class="d-p">
<input value="<?php echo date("d-m-Y"); ?>" id="datefromresult_id" placeholder="Date Select" data-date-format="dd-mm-yyyy" autocomplete="off" type="text" name="from_date" id="from_date_id" class="form-control datepicker2">                </div>
                <div class="d-p">
                  <select name="cars" id="cars">
                    <option value="volvo">120Cards</option>
                    <option value="saab">2Digit</option>
                    <option value="mercedes">Roulette</option>
                    <option value="audi">Pick3</option>
                  </select>
                </div>
                <div class="Show_btn" onclick="get_result_report()"> <a style="cursor:pointer;">Show</a> </div>
              </div>
            </div>
            <div class="table-wrapper-box">
              <span id="result_report_html" style="width:100%;"></span>
            </div>
          </div>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Result  modal -->
<div class="modal fade" id="basicModal-1" tabindex="-1" role="dialog" aria-labelledby="basicModal-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Result</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <ul class="dropdown-menu-new scroll-class">
          <div class="dropdown-menu-box report-bg">
            <div class="dropdown-menu-box-top-box t-wrapper-tow">
              <div class="select-box-rows datepicker-rowss">
                <div class="d-p">
                  <input class="form-control" id="date" name="date" placeholder="DD/MM/YYY" type="text"/>
                </div>
                <div class="d-p">
                  <select name="cars" id="cars">
                    <option value="volvo">ZDIGIT</option>
                    <option value="saab">01</option>
                    <option value="mercedes">02</option>
                    <option value="audi">03</option>
                  </select>
                </div>
                <div class="Show_btn"> <a href="#">Show</a> </div>
              </div>
            </div>
            <div class="table-wrapper-box">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Result</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>10:15AM</td>
                    <td>10</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>09</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>10</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>09</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>10</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>09</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>10</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>09</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>10</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>09</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>10</td>
                  </tr>
                  <tr>
                    <td>10:15AM</td>
                    <td>09</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>
</body>
<script src="js/script.js"></script>
<script type="text/javascript">
   var GAMEID = '<?php echo $gameID ?>';
    function show_report(){
      $("#myModal").modal('show');
    }
    function sale_reports(){
      $("#myModal2").modal('show');
    }
    
    $(".up_horizontal_class").keyup(function(e){
          //Updating the IDs//
          var from_input = Number($(this).data("from"));
          var to_input   = Number($(this).data("to"));
          var up_value   = Number($(this).val());
          console.log(up_value);
          if(up_value>99){
            up_value = 99;
            $(this).val(up_value);
          }
          for(z=from_input; z<=to_input; z++ ){
            var cur_input_val = up_value;
            $("#num_id_"+z).val(cur_input_val);
            var digit = Number($("#num_id_"+z).data("digit"));
            $('.inner_input_vertical').each(function(i, obj) {
                var from_data = Number($(obj).data("from"));
                var to_data   = Number($(obj).data("to"));
                for(zz=from_data; zz<=to_data; zz=zz+6)
                {
                  if(digit == zz){
                    var cur_val = cur_input_val+Number($(obj).val());
                    if(cur_val>=99){
                      cur_val = 99;
                    }
                    $("#num_id_"+z).val(cur_val);       
                  }
                }
            });
          }
          
      });

      function section_class(e,up_value){
        //console.log(up_value);
          //Updating the IDs//
          var from_input = Number($(e).data("from"));
          var to_input   = Number($(e).data("to"));
          var up_value   = Number(up_value);
          if(up_value>99){
            up_value = 99;
            $(e).val(up_value);
          }
          for(z=from_input; z<=to_input; z++ ){
            var cur_input_val = up_value;
            $("#num_id_"+z).val(cur_input_val);
            var digit = Number($("#num_id_"+z).data("digit"));
            $('.inner_input_vertical').each(function(i, obj) {
                var from_data = Number($(obj).data("from"));
                var to_data   = Number($(obj).data("to"));
                for(zz=from_data; zz<=to_data; zz=zz+6)
                {
                  if(digit == zz){
                    var cur_val = cur_input_val+Number($(obj).val());
                    if(cur_val>=99){
                      cur_val = 99;
                    }
                    $("#num_id_"+z).val(cur_val);       
                  }
                }
            });
          }
          
      };

      $(".inner_input_vertical").keyup(function(e){
          //Updating the IDs//
          var from_input = Number($(this).data("from"));
          var to_input   = Number($(this).data("to"));
          var up_value   = Number($(this).val());
          if(up_value>99){
            up_value = 99;
            $(this).val(up_value);
          }
          for(z=from_input; z<=to_input; z+=6 ){
            var cur_input_val = up_value;
            $("#num_id_"+z).val(cur_input_val);

            var digit = Number($("#num_id_"+z).data("digit"));
            $('.up_horizontal_class').each(function(i, obj) {
                var from_data = Number($(obj).data("from"));
                var to_data   = Number($(obj).data("to"));
                for(zz=from_data; zz<=to_data; zz++)
                {
                  if(digit == zz){
                    var cur_val = cur_input_val+Number($(obj).val());
                    if(cur_val>=99){
                      cur_val = 99;
                    }
                    $("#num_id_"+z).val(cur_val);             
                  }
                }
            });

          }
          //$(this).val("");
      });
      $(".numeric").keydown(function(event){
           if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57 ) {
              if(event.which>=96 && event.which<=105){
                return true;
              }else{
                return false;
              }
            }
            if(Number($(this).val())>99){
              return false;
              //$(this).val(99);
            }
      });
      function clear_field(){
        $(".numeric").val("");
        $("#qtyID").html(0);
        $("#amtID").html(0);
      }
      
      function realtime() {
        let time = moment().format('h:mm:ss a');
        document.getElementById('time').innerHTML = time;
        setInterval(() => {
          time = moment().format('h:mm:ss a');
          document.getElementById('time').innerHTML = time;
        }, 1000)
      }
      realtime();
      var eventTime= <?php echo $nextDrwatime; ?>; // Timestamp - Sun, 21 Apr 2013 13:00:00 GMT
      var currentTime = <?php echo time(); ?>; // Timestamp - Sun, 21 Apr 2013 12:30:00 GMT
      var diffTime = eventTime - currentTime;
      var duration = moment.duration(diffTime*1000, 'milliseconds');
      var interval = 1000;
      setInterval(function(){
        duration = moment.duration(duration - interval, 'milliseconds');
        //Condition for Reload//
        if(duration.minutes()==0 && duration.seconds()<10){
          myTimer();
          
         
          //$("#result_animation").show();
          <?php 
            if($is_manual==0){
          ?>
          $("#result_animation").show();
          <?php }
          ?>

        }
        if(duration.seconds()<-4){
          clearInterval(this);
          <?php 
            if($is_manual==0){
          ?>
          get_result_refresh();
         <?php }else{
          ?>
          location.reload();
          <?php
         } ?>
          //location.reload();
          
        }
        $('.countdown').text(duration.minutes() + ":" + duration.seconds())
      }, interval);

      function buy_ticket(){
        $("#submit_btn_id").hide();
        $("#wait_id").show();
        var all_particulars = new Array();
        var x=0;
        var xvalue = Number($("#xvalue_id").val());
        $('.qty_field').each(function(i, obj) {
            if($(this).val()!=""){
              all_particulars[x] = $(this).data("digit")+"-"+($(this).val()*xvalue);
              x++;
            }
            
        });
        if(x==0){
          swal("Please select Quantity", '', "warning");
          $("#submit_btn_id").show();
          $("#wait_id").hide();
          return false;
        }
        all_particulars = (all_particulars.join(';'));
        var draw_type = $('input[name="draw_status"]:checked').val();
        var data="buy_ticket&all_particulars="+all_particulars+"&draw_type="+draw_type+"&GAMEID="+GAMEID;
        $.ajax({
          type:"POST",
          url:"ajax.php",
          data:data,
          success: function(e){
            e = JSON.parse(e);
            if(e.is_error==0){
             //swal("Success!", "Purchase completed", "success");
             $("#point_id").html(e.message.SalesDetails[0]['Balance']);
            }else{
             swal(e.message, '', "warning");
            }
            print_ticket(e.message.SalesDetails[0]['Barcode']);
            //console.log(e.message.SalesDetails[0]['Barcode']);
            clear_field();
            $(".draw_type[value=1]").prop("checked","true");

            //$('input[name="draw_status"]:checked')
            //$(".draw_type").val(1);
            $("#submit_btn_id").show();
            $("#wait_id").hide();
          }
        })
      }
      $(".numeric, .draw_type").change(function(){
            var tot_qty = 0;
            var tot_amt = 0;
            console.log($(this).val());
            if($(this).val()>99){
              $(this).val(99);
            }
            var draw_type = Number($('input[name="draw_status"]:checked').val());
            $('.qty_field').each(function(i, obj) {
                if($(this).val()!=""){              
                  tot_qty = tot_qty+Number($(this).val());          
                }           
            });
            var xvalue = Number($("#xvalue_id").val());
            tot_amt = tot_qty*1*draw_type*xvalue;
            $("#qtyID").html(tot_qty);
            $("#amtID").html(tot_amt);
        
      });

      $('.datepicker2').datepicker({
          todayHighlight: true,
          autoclose: true
      });
      function get_report(){
        $("#report_html").html("<div style='text-align:center'><i class='fa fa-spin fa-spinner'></i> Please wait...</div>");
        var from_date = $("#datefrom_id").val();
        var to_date   = $("#dateto_id").val();
        if(from_date!="" && to_date!=""){
            var data = "get_report&datefrom="+from_date+"&to_date="+to_date+"&GAMEID="+GAMEID;
            $.ajax({
              type:"GET",
              url:"ajax.php",
              data:data,
              success: function(e){
                $("#report_html").html(e);
              }
            })
        }else{
          $("#report_html").html("Please select date!");
        }
      }
      function get_sale_report(){
        $("#report_html2").html("<div style='text-align:center'><i class='fa fa-spin fa-spinner'></i> Please wait...</div>");
        var report_type = $("#report_type_id").val();
        var last_result_time_id = $("#last_result_time_id").val();
        var data = "get_sale_report&report_type="+report_type+"&last_result_time_id="+last_result_time_id+"&GAMEID="+GAMEID;
        $.ajax({
          type:"GET",
          url:"ajax.php",
          data:data,
          success: function(e){
            $("#report_html2").html(e);
          }
        })
      }
      function cancel_ticket(TICKET_ID){
        var data = "get_cancel_ticket&ticket_id="+TICKET_ID;
        $.ajax({
          type:"GET",
          url:"ajax.php",
          data:data,
          success: function (e){
            e = JSON.parse(e);
            swal(e.message, "", "success");
            var balance = Number(e.balance);
            if(balance>0){
              $("#point_id").html(balance);
            }
            $("#cancel_id"+TICKET_ID).hide();
          }
        })
      }

      function cancel_ticket2(){

        var TICKET_ID = Number($("#searchid").val());
        if(TICKET_ID>0){
          $("#CancelTicketLoader").show();
          $("#cancelTicketBtnID").hide();
          var data = "get_cancel_ticket&ticket_id="+TICKET_ID;
          $.ajax({
            type:"GET",
            url:"ajax.php",
            data:data,
            success: function (e){
              $("#searchid").val("").trigger("change");
              $("#CancelTicketLoader").hide();
              $("#cancelTicketBtnID").show();
              e = JSON.parse(e);
              swal(e.message, "", "success");
              var balance = Number(e.balance);
              if(balance>0){
                $("#point_id").html(balance);
              }
              $("#cancel_id"+TICKET_ID).hide();
            }
          })
        }else{
          swal("Select Barcode", "", "warning");
        }
      }
       

   $(document).ready(function(eOuter) {
     $('input').on('keydown', function(eInner) {
         var keyValue = eInner.which; //enter key
         if (keyValue == 39 || keyValue == 37 || keyValue == 38 || keyValue == 40){
             var tabindex = Number($(this).attr('tabindex'));
             if(keyValue == 39){ 
                //Right Key
                 tabindex++;
             }else if(keyValue == 37){ 
                //Left Key
                 tabindex--;
             }else if(keyValue == 38){ 
                //Up Key
                if(tabindex<=28){
                  var upindex= Math.ceil(tabindex/7);
                    $('.section_class[data-index="'+upindex+'"]').focus();
                }
                tabindex = tabindex-28;
             }else if(keyValue == 40){
                //Down Key
                 tabindex = tabindex+28;
             }

             //CHecking if length available//
             if(!$('[tabindex=' + tabindex + ']').length){
               if(keyValue==39){
                  tabindex++;
                }
                if(keyValue==37){
                  tabindex--;
                }
              }
              $('[tabindex=' + tabindex + ']').focus();
         }
     });
     $(".section_class").on('keyup', function(eInner) {
        var index =$(this).data("index");
        var keyValue = eInner.which; //enter key
        
        if (keyValue == 39 || keyValue == 37 || keyValue == 38 || keyValue == 40){
          if(keyValue == 39){ 
                //Right Key
                 index++;
                 if($('.section_class[data-index="'+(index)+'"]').length){
                  $('.section_class[data-index="'+(index)+'"]').focus();
                 }else{
                  $('.section_class[data-index="1"]').focus();
                 }
             }else if(keyValue == 37){ 
                //Left Key
                 index--;
                if($('.section_class[data-index="'+(index)+'"]').length){
                 $('.section_class[data-index="'+(index)+'"]').focus();
                }else{
                  $('.section_class[data-index="4"]').focus();
                }
             }else if(keyValue == 38){
                //Up Key
                 //tabindex = tabindex-28;
             }else if(keyValue == 40){
                //Down Key
                if(index==1){
                  $('[tabindex=2]').focus();
                }else if(index==2){
                  $('[tabindex=9]').focus();
                }else if(index==3){
                  $('[tabindex=16]').focus();
                }else if(index==4){
                  $('[tabindex=23]').focus();
                }
             }
        }else{
          section_class(this,$(this).val());
        }
        //console.log(index);
     });
  });
   function print_ticket(BARCODE_ID){
      var data = "print_ticket&barcode="+BARCODE_ID+"&GAMEID="+GAMEID;
      $.ajax({
        type:"GET",
        data:data,
        url:"ajax.php",
        success: function(e){
          e = JSON.parse(e);          
          $("#trans_id").html(e.display_barcode);
          $("#booking_date").html(e.booking_date+", "+e.drawtime);
          $("#current_rate").html(e.current_rate);
          $("#total_tickets").html(e.total_tickets);
          $("#total_booking_amount").html(Number(e.total_booking_amount));
          $("#particulars").html(e.ticket_details);
          $("#draw_id").html(e.drawtime);
          $("#ticket_time_id").html(e.booking_time);
          PrintElem('printableArea');
        }
      })
   }
    function PrintElem(elem){
        var mywindow = window.open('', 'self', 'height=120,width=400');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
        mywindow.close();
        return true;
    }
    function requestFullScreen(element) {
      var element = document.querySelector("#container");

      // make the element go to full-screen mode
      element.requestFullscreen()
        .then(function() {
          // element has entered fullscreen mode successfully
        })
        .catch(function(error) {
          // element could not enter fullscreen mode
        });
        $("#FullScreenModal").modal("hide");
    }

    function exitFullScreen(){
      document.exitFullscreen()
        .then(function() {
          // element has exited fullscreen mode
        })
        .catch(function(error) {
          // element could not exit fullscreen mode
          // error message
          console.log(error.message);
        });
    }
$('#searchid').select2({        
      ajax: {
          url: "https://jpot.top/ajax.php",
          dataType: 'json',
          delay: 100,
          data: function (params) {
              return {
                  barcodeID: params.term, // search term
                  ResultToken: 'cmxwzd3e5wreecv8c7mnbcx0hsm5u',
                  UserID: '<?php echo $uid; ?>',
                  GameId:GAMEID,
                  GetBarcode:'1',
              };
          },
          processResults: function (data) {
              return {
                  results: data
              };
          },
          cache: true
      },
      minimumInputLength: 0
  });

  function claimTicket(TICKET_ID=""){
    if(TICKET_ID==""){
      var TICKET_ID = Number($("#searchid").val());
    }
    if(TICKET_ID>0){
      $("#claimTicketID").hide();
      $("#claimTicketLoader").show();
      var data = "get_claim_ticket&ticket_id="+TICKET_ID;
        $.ajax({
          type:"GET",
          url:"ajax.php",
          data:data,
          success: function (e){
            $("#claimTicketID").show();
            $("#claimTicketLoader").hide();
            e = JSON.parse(e);
            $("#searchid").val("").trigger("change");
            if(e.error){
              if(e.error=="No Winning amount for this ticket!"){
                e.error = "No Prize";
              }
              swal(e.error, "", "error");
            }else{            
              var balance = Number(e.terminal_balance);
              if(balance>0){
                $("#point_id").html(balance);
              }
              swal("You have won "+e.Prize, "", "success");
            }
          }
        })
    }else{
          swal("Select Barcode", "", "warning");
    }
  }
  function updateLifetimeToken(){
    var data = "update_token";
    $.ajax({
      type:"GET",
      data:data,
      url:"ajax.php",
      success:function(e){

      }
    })
  }
  function openValue(){
    $("#myModal5").modal("show");
  }
  function get_result_report(){
    var dateFrom = $("#datefromresult_id").val();
    var data = "get_result&date="+dateFrom+"&GAMEID="+GAMEID;
    $.ajax({
      type:"GET",
      data:data,
      url:"ajax.php",
      success: function(e){
        $("#result_report_html").html(e);
      }
    })
  }
  setInterval(function() { updateLifetimeToken(); }, 10000);
  // $("body").keypress(function(e){
  //     requestFullScreen('document.body');
  // });
  function select_qtyx(VAL){
    $(".checked_qty").remove();
    $("#btn"+VAL).html("<i class='fa fa-check-circle checked_qty'></i> "+VAL);
    $("#xvalue_id").val(VAL);
  }

  function printreport(elem) {


var mywindow = window.open('', 'self', 'height=120,width=400');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
        //mywindow.close();
        return true;


     // var printContents = document.getElementById(divName).innerHTML;
     // var originalContents = document.body.innerHTML;

     // document.body.innerHTML = printContents;

     // window.print();

     // document.body.innerHTML = originalContents;
}

  function generateRandomInteger(min, max) {
    return Math.floor(min + Math.random()*(max - min + 1))
  }
    var ccc=0;   
   // var myInterval = "";
   //  function start_result_animation(){
   //     myInterval = setInterval(myTimer, 100);
   //  }
    // $(document).ready(function(){

    // });
    var is_result_declared = 0;
    var result_value = "";
    var myIntervalStart = 0;
    var myIntervalStart2 = 0;
    function myTimer(){
      if(myIntervalStart==0){
        var myInterval = setInterval(myTimer, 100);
        myIntervalStart = 1;
      }
      ccc = generateRandomInteger(1, 120);
      //console.log(ccc);
      //var total_sum = 100*120;
      if(is_result_declared==1){
       // console.log(ccc);
        //operator = operator*-1;
        clearInterval(myInterval);
        $("#display_num").html(numArr[result_value]);
       // $(".animate").transition({y: -ccc+'px'});
      }else{
        $("#display_num").html(numArr[ccc]);
      }
      
    }
    var is_loaded = true;
    function get_result_refresh(){
      if(myIntervalStart2==0){
        var myInterval2 = setInterval(get_result_refresh, 2000);
        myIntervalStart2 = 1;
      }
      if(is_loaded==true){
        is_loaded = false;
        var data = "result_declared"+"&GAMEID="+GAMEID;
        $.ajax({
          type:"GET",
          data:data,
          url:"ajax.php",
          success: function(e){
            if(e!=0){
              is_result_declared = 1;
              result_value = e;
              clearInterval(myInterval2);
              is_loaded = true;
              setTimeout(() => {
                location.reload();
              }, "5000")
            }
          }
        })
      }
    }
    //get_result_refresh();
    
    //start_result_animation();
    function toggle_hide(){
      var status = $("#showid").text();
      if(status=='Hide'){
        $("#point_id").hide();
        $("#showid").html('show');
      }else{
        $("#point_id").show();
        $("#showid").html('Hide');
      }
    }
    //$("#result_animation").show();

    //myTimer();
          
         
          //$("#result_animation").show();
  </script>
</html>