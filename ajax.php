<?php 
  session_start();
  date_default_timezone_set('Asia/Kolkata');
  include "includes/functions.php";
  $uid = 0;
  if(isset($_SESSION['uid'])){
    $uid = $_SESSION['uid'];
  }
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
    $gameID = $_POST['GAMEID'];
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
    $url = "https://www.pubgtime.com/api/SaleAPItest.php";
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
    $datefrom = strtotime($_GET['datefrom']." 00:00:00");
    $to_date  = strtotime($_GET['to_date']." 23:59:59");
    $uid = $_SESSION['uid'];
    $GameId = $_GET['GAMEID'];
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
    <style>
      @media print {
    #printbtn {
        display :  none;
    }
    .borderdot td{
        border-bottom: 1px dotted #000 !important; 
      }
}
      .print_report tr td{
          color: #000;
          border: none;
      }
      .borderdot td{
        border-bottom: 1px dotted #000; 
      }
    </style>
    <table style="color:#000; width: 100%;" class="print_report">
      <tr>
        <td colspan="2">JACKPOT</td>
      </tr>
      <tr>
        <td>Agent ID :</td>
        <td><?php echo $uid; ?></td>
      </tr>
      <tr>
        <td>Date: </td>
        <td><?php echo date("d-m-Y"); ?> <?php echo date("h:i a"); ?></td>
      </tr>
      <tr class="borderdot" style=" border-bottom: 1px dotted #000 !important; ">
        <td colspan="2">
          <strong>REPORT</strong> <br>
          From: <?php echo $_GET['datefrom']; ?> To: <?php echo $_GET['to_date']; ?>
        </td>        
      </tr>
    </table>
    <table style="color:#000; width: 100%;" class="print_report">
      <tr>
        <td style="width:40%">Gross Sales</td>
        <td >:</td>
        <td style="width:40%"><?php echo $response->SalesReport[0]->playpoints ?></td>
      </tr>
      <tr class="borderdot" style=" border-bottom: 1px dotted #000 !important; ">
        <td>Cancel</td>
        <td>:</td>
        <td><?php echo $response->SalesReport[0]->calcelpoints ?></td>
      </tr>
      <?php 
          $net_balance = $response->SalesReport[0]->playpoints-$response->SalesReport[0]->calcelpoints;
       ?> 
      <tr>
        <td>Net Sales</td>
        <td>:</td>
        <td><?php echo $net_balance; ?></td>
      </tr>
      <tr class="borderdot" style=" border-bottom: 1px dotted #000 !important; ">
        <td>Claim</td>
        <td>:</td>
        <td><?php echo $response->SalesReport[0]->claimpoints ?></td>
      </tr>
      <tr class="borderdot" style=" border-bottom: 1px dotted #000 !important; ">
        <td>Operator</td>
        <td>:</td>
        <td><?php echo $net_balance-($response->SalesReport[0]->claimpoints); ?></td>
      </tr>
      <?php 
          $net_balance = $net_balance-($response->SalesReport[0]->claimpoints);
       ?>
      <tr class="borderdot" style=" border-bottom: 1px dotted #000 !important; ">
        <td>Retailer Discount</td>
        <td>:</td>
        <td><?php echo $response->SalesReport[0]->Commission ?></td>
      </tr>
      <tr>
        <td>Net Pay</td>
        <td>:</td>
        <td><?php echo $net_balance-$response->SalesReport[0]->Commission ?></td>
      </tr>
      <tr>
        <td colspan="3" style="text-align: center;">
          <button onclick="printreport('report_html')" type="button" id="printbtn" class="btn btn-success">Print</button>
        </td>
      </tr>
    </table>
    <?php
  }
  if(isset($_GET['get_sale_report'])){
    $report_type_id = $_GET['report_type'];
    $last_result_time_id = $_GET['last_result_time_id'];
    $UserID = $_SESSION['uid'];
    $GameId = $_GET['GAMEID'];
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
      'GameId' => $GameId,
      'FromTimeStamp' => $FromTimeStamp,
      'toTimeStamp' => $toTimeStamp,
      'report_type_id' => $report_type_id,
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
            <button class="btn btn-success  btn-sm" type="button" onclick="print_ticket(<?php echo $value->barcode;?>)">Print</button>
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
      'GameId' => $_GET['GAMEID']
     ];
     $response = post_api_call($post,$url);
     $response = json_decode($response);
     $barcode = $response->barcode;
     $booking_date = $response->date;
     $current_rate = $response->rate;
     $ticket_details = $response->ticket_details;
     $ticket_details_exp = explode(";",$ticket_details[0]);
     $total_tickets = count($ticket_details_exp);
     $ticket_purchase_time = explode(" ",$response->ticket_purchased_date_time[0]);
     $ticket_purchase_time  = $ticket_purchase_time[1];

     $total_booking_amount = $response->tot_amount;
     $drawtime = $response->drawTime;
     $all_tickets = "<table><tr>";
     $i=0;
     $total_tickets = 0;
     foreach($ticket_details_exp AS $ticket){
      $i++;
      $tickets = explode("-",$ticket);
      if($_GET['GAMEID']==12){
        if($i%2==0){
          $all_tickets .= "<td>".get_result_name_img2($tickets[0])." - <span style='font-size:13px;'>".($tickets[1]/2)."</span></td></tr><tr>";
        }else{
          $all_tickets .= "<td>".get_result_name_img2($tickets[0])." - <span style='font-size:13px;'>".($tickets[1]/2)."</span></td>";
        }
      }else{
        if($i%3==0){
          $all_tickets .= "<td>".($tickets[0])." - <span>".($tickets[1]/2)."</span></td></tr><tr>";
        }else{
          if(isset($tickets[1])){
            $all_tickets .= "<td>".($tickets[0])." - <span>".($tickets[1]/2)."</span></td>";
          }
        }
      }
      if(isset($tickets[1])){
        $total_tickets = $total_tickets+($tickets[1]/2);
      }
     }
     $all_tickets .= "</table>";
     $all_tickets2 = $all_tickets;
     //$ticket_details[0]    = str_replace(";", "&nbsp;&nbsp;", $ticket_details[0]);

     $data = array();
     $data['barcode']         = $barcode[0];
     $data['booking_date']    = $booking_date[0];
     $data['booking_time']    = $ticket_purchase_time;
     $data['current_rate']    = $current_rate[0];
     $data['ticket_details']  = $all_tickets2;
     $data['total_tickets']   = $total_tickets;
     $data['total_booking_amount'] = $total_booking_amount[0];
     $data['drawtime']        = get_normal_time($drawtime[0]);
     $all_barcode_details     = $response->all_barcode_details[0];
     $display_barcode = "<table style='padding:0px; margin:0px'>";
     foreach($all_barcode_details  AS $time){
        $display_barcode .= "<tr><td style='font-size:12px;'>".$time->barcode."</td><td style='font-size:12px;'>".get_normal_time($time->time)."</td></tr>";
     }
     $display_barcode .= "</table>";
     $data['display_barcode'] = $display_barcode;
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
    $gameID = $_GET['GAMEID'];
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
     if(isset($response->ResultList)){
       foreach($response->ResultList AS $rows){
        $dislay = $rows->GameValue;
        // if($rows->GameValue>60){
        //   $dislay =$dislay-60;
        // }
        $bonus = 1;
        if($rows->bonus>1){
          $bonus = $rows->bonus;
        }
        ?>
        <tr>
          <td><?php echo $rows->GameTime; ?></td>
          <td><?php if($gameID==12){ get_result_name_img_report($rows->GameValue,$bonus); }else{ get_result_normal_game($rows->GameValue,$bonus); } ?></td>
          <td></td>
        </tr>
        <?php
       }
     }
     ?>
     </table>
     <?php
  }
  if(isset($_GET['GetBarcode'])){
    $data = array();
    $data['barcodeID'] = $_GET['barcodeID'];
    $data['ResultToken'] = $_GET['ResultToken'];
    $data['UserID'] = $_GET['UserID'];
    $data['GameId'] = $_GET['GameId'];
    $url = "https://www.pubgtime.com/api/BarcodeSearchApi.php?barcodeID=".$data['barcodeID']."&ResultToken=".$data['ResultToken']."&UserID=".$data['UserID']."&GameId=".$data['GameId'];
    $response = get_api_call($url);
    echo $response;
  }
  if(isset($_GET['result_declared'])){
    //$result_date =$_GET['date'];
    $ResultListToken = "cmxwzd3e5wreecv8c7mnbcx0hsm5u";
    $gameID = $_GET['GAMEID'];
    $url = "https://www.pubgtime.com/api/ResultAPI.php";
    $post = [
      'UserID' =>  $uid,
      'ResultToken' => $ResultListToken,
      'GameId' => $gameID,
      'DeviceID' => '9638522'
     ];
     $response = post_api_call($post,$url);
     //echo $response;
     $response = json_decode($response,true);
     //print_r($response);
     $result = "";
     if(isset($response['ResultDetails'])){
      $result= $response['ResultDetails'][0]['Value'];
     }
     echo $result;
  }
?>