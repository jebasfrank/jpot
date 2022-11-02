<?php
require "class/connector.php";
include "include/function.php";
include "include/tradefunctions.php";
$conn = conn();
global $conn;
$game_id = 10;
$price = get_game_mrp(10);
date_default_timezone_set('Asia/Kolkata');
function time_min($date){
	$date = explode(":",$date);
	if ($date[0]<9) {
		$date[0] = '0'.(int)($date[0]);
	}
	if($date[1]<9){
		$date[1] = '0'.(int)($date[1]);
	}
	return $date[0].$date[1];
}
function date_min2($date){
	$date = explode("-",$date);
	if ($date[1]<9) {
		$date[1] = '0'.(int)($date[1]);
	}
	if($date[2]<9){
		$date[2] = '0'.(int)($date[2]);
	}
	return $date[2].$date[1].$date[0];
}

$data_time = date("d-m-Y h:i:a s");
$is_update=0;
$token='';
$cur_time = time_min(date("H:i"));
$original_time_master = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM time_master WHERE min_time = '".$cur_time."'"));
$original_time_master = $original_time_master['time_id'];
$manual_bonus = 1;
$min_date=date_min2(date('d-m-Y'));
$fetch_time_details=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM assign_game JOIN time_master ON time_master.time_id=assign_game.time_master_id WHERE min_time = '$cur_time' AND `game_id`='".$game_id."' AND `status`='1' ORDER BY min_time ASC LIMIT 1"));
$time_id = $fetch_time_details['time_id'];

$check_game_manual = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `manual_result_activation` WHERE `game_id` ='$game_id' AND `status` = 1"));
#count final_result_publised
$final_result_publised_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `final_result_publised` WHERE `time_id` = '$time_id' AND `game_id` ='$game_id' AND `min_date` = '$min_date'"));

if($check_game_manual == 1 && $final_result_publised_count == 0){
	$hold_result_declear = mysqli_query($conn,"INSERT INTO `hold_result`(`game_id`,`time_id`, `date_min`) VALUES ('$game_id','$time_id','$min_date')");
	die();
}

if($final_result_publised_count == 1){
	die();
}


$all_digit = get_result($min_date,$time_id,$game_id);
#final_result_publised
$final_result_publised = mysqli_query($conn,"INSERT INTO `final_result_publised`(`game_id`,`time_id`, `game_digit`,`min_date`) VALUES ('$game_id','$time_id','$all_digit','$min_date')");

#sales_details update
$sales_details = mysqli_query($conn,"SELECT * FROM `sales_details` WHERE game_id='$game_id' AND time_id='$time_id' AND min_date = '$min_date'");
$all_digit  = explode(",", $all_digit);
$prize_digits = prize_digits($all_digit);
//print_r($prize_digits);
while($row = mysqli_fetch_assoc($sales_details)){
	$winning_amount = 0;
	$winning_qty = 0;
	$get_ticket_particulars = explode(";",$row['ticket_details']);
	foreach ($get_ticket_particulars as $rows){	
		$rowdetails = explode("-", $rows);
		$digit = $rowdetails[0];
		$qty = $rowdetails[1];
		if(digitExist($prize_digits,$digit)){
			$times = $prize_digits[$digit];
			$winning_amount = (($qty*$price)*$times)+$winning_amount;
			$winning_qty = $winning_qty+$qty;
		}
	}
	update_winning_qty_amount($winning_amount,$winning_qty,$row['sales_id']);
	terminal_balance_update($row['uid'],$winning_amount);
}
