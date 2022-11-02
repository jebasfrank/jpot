<?php 
  session_start();
  date_default_timezone_set('Asia/Kolkata');
  include "includes/functions.php";
  if(isset($_GET['login_user'])){
    $user_id = $_GET['user_id'];
    $login_pass = $_GET['login_pass'];
    $login_token = "4xo8wae2rwv1erma8vcv3nesm65cssacx90xv";
    $rand = rand(9999,99999);
    $post = [
      'LoginToken' => $login_token,
      'UserID' => $user_id,
      'Password'   => $login_pass,
      'DeviceID' =>$rand
    ];
    $url = "https://www.pubgtime.com/api/LoginAPI.php";
    $response = post_api_call($post,$url);
    $response = json_decode($response);
    $return_arr = array();
    $is_logged_in = 0;
    if(isset($response->UserDetails[0])){
      $is_logged_in = 1;
      $_SESSION['uid'] = $response->UserDetails[0]->UserName;
      $_SESSION['Balance'] = $response->UserDetails[0]->Balance;
      $_SESSION['lifetime_token'] = $response->UserDetails[0]->lifetime_token;
      $_SESSION['retailerName'] = $response->UserDetails[0]->retailerName;
      $message = "Loggedin successfully"; 
    }else{
      $message = $response->error;
    }
    $return_arr['is_logged_in'] = $is_logged_in;
    $return_arr['message'] = $message;
    echo json_encode($return_arr);
  }
  if(isset($_POST['buy_ticket'])){
    $all_particulars = $_POST['all_particulars'];
    $userID = $_SESSION['uid'];
    $gameID = 12;
    $draw_type = $_POST['draw_type'];
    $SalesToken = "3reerntbk4cvxvdner22emul9kx7czv";
    $post = [
      'SalesToken' => $SalesToken,
      'UserID' => $userID,
      'Values'   => $all_particulars,
      'GameId' => $gameID
    ];
    if($draw_type>1){
      $post['advanceDrawFor'] = $draw_type;
    }
    $url = "https://www.pubgtime.com/api/SaleAPI.php";
    $response = post_api_call($post,$url);
    $response = json_decode($response);
    $is_error = 0;
    $return = array();
    if(isset($response->error)){
      $is_error = 1;
      $message = $response->error;
    }else{
      $message = $response;
    }
    $return['is_error'] = $is_error;
    $return['message'] = $message;
    echo json_encode($return);
  }
  if(isset($_GET['get_report'])){
    $datefrom = strtotime($_GET['datefrom']);
    $to_date  = strtotime($_GET['to_date']);
    $uid = $_SESSION['uid'];
    $GameId = 12;
    $ReportToken = "vmrwzd3e5wreecv8c7mnbcx0hsm3d";
    $url = "https://www.pubgtime.com/api/ReportAPI.php";
    $post = [
      'ReportToken' => $ReportToken,
      'UserID' => $uid,
      'FromTimeStamp'   => $datefrom,
      'toTimeStamp' => $to_date,
      'GameId' => $GameId
    ];
    $response = post_api_call($post,$url);
    $response = json_decode($response);
    ?>
    <table style="color:#fff; width: 100%;">
      
      <tr>
        <td>Date: <?php echo date("d-m-Y"); ?></td>
        <td>Time: <?php echo date("h:i a"); ?></td>
      </tr>
      <tr>
        <td>From: <?php echo $_GET['datefrom']; ?></td>
        <td>To: <?php echo $_GET['to_date']; ?></td>
      </tr>
      <tr>
        <td>Balance</td>
        <td><?php echo $response->SalesReport[0]->netplaypoints ?></td>
      </tr>
      <tr>
        <td>Cancel BL</td>
        <td><?php echo $response->SalesReport[0]->calcelpoints ?></td>
      </tr>
      <tr>
        <td>Net Balance</td>
        <td><?php echo $response->SalesReport[0]->netplaypoints-$response->SalesReport[0]->calcelpoints ?></td>
      </tr>
      <tr>
        <td>Claim BL</td>
        <td><?php echo $response->SalesReport[0]->claimpoints ?></td>
      </tr>
      <tr>
        <td>Operator BL</td>
        <td><?php echo $response->SalesReport[0]->OperatorBalance-($response->SalesReport[0]->claimpoints+$response->SalesReport[0]->calcelpoints); ?></td>
      </tr>
      <tr>
        <td>Retailer Discount</td>
        <td><?php echo $response->SalesReport[0]->Commission ?></td>
      </tr>
      <tr>
        <td>Net Pay BL</td>
        <td><?php echo $response->SalesReport[0]->NetToPay-$response->SalesReport[0]->calcelpoints ?></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center;">
          <button type="button" class="btn btn-success">Print</button>
        </td>
      </tr>
    </table>
    <?php
  }
  if(isset($_GET['get_sale_report'])){
    $report_type_id = $_GET['report_type'];
    $last_result_time_id = $_GET['last_result_time_id'];
    $UserID = $_SESSION['uid'];
    $GameId = 12;
    if($report_type_id=="C"){
      $FromTimeStamp = $last_result_time_id;
      $toTimeStamp   = time();
    }else{
      $FromTimeStamp = strtotime(date("Y-m-d"));
      $toTimeStamp   = time();
    }
    $is_key_required = 1;
    $url = "https://www.pubgtime.com/api/BarcodeAPI.php";
    $post = [
      'BarocdeToken' => 'klxwzd3e5wreecv6vr9mnbcx0hsm2m',
      'UserID' => $UserID,
      'DeviceID' => rand(999,9999),
      'GameId' => 12,
      'FromTimeStamp' => $FromTimeStamp,
      'toTimeStamp' => $toTimeStamp,
      'is_key_required' =>1
     ];
     $response = post_api_call($post,$url);
     $response = json_decode($response);
     ?>
     <div style="height: 250px; width: 100%; overflow: scroll; overflow-x: hidden;">
     <table style="width:100%;">
       <tr>
          <td>Time</td>
          <td>Barcode ID</td>
          <td>Point</td>
          <td>Status</td>
          <td>Option</td>
       </tr>
       <?php 
       if(isset($response->sales_details)){
        foreach ($response->sales_details as $key => $value) {
          //print_r($value);
          ?>
          <tr>
            <td><?php echo get_normal_time($value->drawTimeStamp);?>  </td>
            <td><?php echo $value->barcode;?></td>
            <td><?php echo $value->Amount;?></td>
            <td><?php if($value->Status=='Canceled' || $value->Status=='Claimed'){ echo $value->Status; }?></td>
            <td>
              <?php 
                $cur_time = time()+25;
                if($value->drawTimeStamp>$cur_time){
                  if($value->Status!='Canceled'){
              ?>
              <button id="cancel_id<?php echo $value->barcode;?>" onclick="cancel_ticket(<?php echo $value->barcode;?>)" class="btn btn-danger btn-sm" type="button"><i class="fa fa-remove"></i></button>
            <?php } } 
            if(time()>$value->drawTimeStamp){
            if($value->Status!='Claimed'){
            ?>
            <button id="cancel_id<?php echo $value->barcode;?>" onclick="claimTicket(<?php echo $value->barcode;?>)" class="btn btn-success btn-sm" type="button"><i class="fa fa-check-circle"></i></button>
          <?php } } 
if($value->Status!='Canceled'){
          ?>
            <button class="btn btn-success" type="button" onclick="print_ticket(<?php echo $value->barcode;?>)">Print</button>
          <?php } ?>
            </td>
          </tr>
          <?php
        }
        }
       ?>
     </table>
     </div>
     <?php
  }
  if(isset($_GET['get_cancel_ticket'])){
    $ticket_id = $_GET['ticket_id'];
    $url = "https://www.pubgtime.com/api/CancelApi.php";
    $post = [
      'CancelToken' => '3reerntbk4cvxvdner22emul9kx7czv',
      'Barcode' => $ticket_id
     ];
     $response = post_api_call($post,$url);
     $response = json_decode($response);
     $message = "";
     if(isset($response->error)){
      $message = $response->error;
     }
     if(isset($response->warning)){
      $message = $response->warning;
     }
     if(!isset($response->warning) && !isset($response->error)){
      $message = "Ticket has been cancelled!";
     }
     $balance = 0;
     if(isset($response->terminalBal)){
      $balance = $response->terminalBal;
     }
     $array = array();
     $array['message'] = $message;
     $array['balance'] = $balance;
     echo json_encode($array);
  }
  if(isset($_GET['get_claim_ticket'])){
    $ticket_id = $_GET['ticket_id'];
    $UserID = $_SESSION['uid'];
    $url = "https://www.pubgtime.com/api/ClaimApi.php";
    $post = [
      'claim_token' => 'cmxwzd3e5wreecv8c7mnbcx0hsp5u',
      'barcodeid' => $ticket_id,
      'UserID' =>$UserID
     ];
     $response = post_api_call($post,$url);
     $response = json_decode($response);
     echo json_encode($response);
  }
  if(isset($_GET['print_ticket'])){
    $barcode = $_GET['barcode'];
    $url = "https://www.pubgtime.com/api/GenerateBill.php";
    $post = [
      'ResultToken' => 'vmrwzd3e5wreecv1c7mnbcx0hsm3k',
      'BarcodeId' => $barcode,
      'GameId' => 12
     ];
     $response = post_api_call($post,$url);
     $response = json_decode($response);
     $barcode = $response->barcode;
     $booking_date = $response->date;
     $current_rate = $response->rate;
     $ticket_details = $response->ticket_details;
     $ticket_details_exp = explode(";",$ticket_details[0]);
     $total_tickets = count($ticket_details_exp);

     $total_booking_amount = $response->tot_amount;
     $drawtime = $response->drawTime;

     $data = array();
     $data['barcode'] = $barcode[0];
     $data['booking_date'] = $booking_date[0];
     $data['current_rate'] = $current_rate[0];
     $data['ticket_details'] = $ticket_details[0];
     $data['total_tickets'] = $total_tickets;
     $data['total_booking_amount'] = $total_booking_amount[0];
     $data['drawtime'] = get_normal_time($drawtime[0]);
     echo json_encode($data);
  }
  if(isset($_GET['update_token'])){
    $UpToken = "MM4MwzcbD8QrqnENOPIYT0ysOnnHBU";
    $LifeTimeToken = $_SESSION['lifetime_token'];
    $UserId = $_SESSION['uid'];
    $url = "https://www.pubgtime.com/api/UpdateTokenAPI.php";
    $post = [
      'UpToken' => $UpToken,
      'LifeTimeToken' => $LifeTimeToken,
      'UserId' => $UserId
     ];
     $response = post_api_call($post,$url);
     $response = json_decode($response);
     //print_r($response);
     $token = $response->TokenDetails[0]->LifeToken;
     $_SESSION['lifetime_token'] = $token;
  }
  if(isset($_GET['get_result'])){
    $result_date =$_GET['date'];
    $ResultListToken = "cmxwzd3e5wreecv8c7mnbcx0hsm5u";
    $gameID = 12;
    $url = "https://www.pubgtime.com/api/ResultListAPI.php";
    $post = [
      'result_date' => $result_date,
      'ResultListToken' => $ResultListToken,
      'gameID' => $gameID
     ];
     $response = post_api_call($post,$url);
     $response = json_decode($response);
     ?>
     <table class="table table-striped">
      <tr>
        <td>Game Time</td>
        <td>Value</td>
        <td></td>
      </tr>
     <?php
     foreach($response->ResultList AS $rows){
      $dislay = $rows->GameValue;
      if($rows->GameValue>60){
        $dislay =$dislay-60;
      }
      ?>
      <tr>
        <td><?php echo $rows->GameTime; ?></td>
        <td><?php get_result_name_img($value->GameValue); ?>
                            </td>
                            <td></td>
      </tr>
      <?php
     }
     ?>
     </table>
     <?php
  }
?>