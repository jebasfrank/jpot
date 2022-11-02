<?php 
function prize_digits($result_declared){
	$result_arr = array();
	foreach($result_declared AS $rowdigit){
		if(isset($result_arr[$rowdigit])){
			$result_arr[$rowdigit] = $result_arr[$rowdigit]+1;
		}else{
			$result_arr[$rowdigit] = 1;
		}
	}
	$final_digit = array();
	foreach($result_arr AS $digit=>$times){
		if($times>1){
			$final_digit[$digit] = $times;
		}
	}
	return $final_digit;
}
function digitExist($prize_digits,$digit){
	$is_exist = 0;
	foreach($prize_digits AS $pdigit=>$values){
		if($pdigit==$digit){
			$is_exist = 1;
		}
	}
	return $is_exist;
}
function update_winning_qty_amount($winning_amount,$winning_qty,$sale_id){
	global $conn;
	$update_sale_details = "UPDATE `sales_details` SET `winning_amount`='$winning_amount',`winning_qty`='$winning_qty' WHERE sales_id  ='$sale_id'";
	mysqli_query($conn,$update_sale_details);	
}
function terminal_balance_update($terminal_id,$amount){
	$row = array();
	global $conn;
	$get_terminal_balance = "SELECT * FROM `terminal` WHERE tid = '$terminal_id'";
	$get_terminal_query = mysqli_query($conn,$get_terminal_balance);
	$row = mysqli_fetch_assoc($get_terminal_query);
	$current_balance = $row['cbal'];
	$update_balance = $amount+$current_balance;
	$update_ter_bal ="UPDATE `terminal` SET `cbal`='$update_balance' WHERE tid = '$terminal_id'";	
	mysqli_query($conn,$update_ter_bal);	
}
function get_ticket_particulars($saleid,$all_digit){
	global $conn;
	$ticket_particulars_array = array();
	$row = array();
	$ticket_particulars = mysqli_query($conn,"SELECT * FROM `ticket_particulars` WHERE sales_id='$saleid' AND is_canceled = 0 AND quantity > 0 AND digit IN (".$all_digit.")");	
	while ($row = mysqli_fetch_assoc($ticket_particulars)){
		$ticket_particulars_array[] = array('digit'=>$row['digit'],'quantity'=>$row['quantity']);
	}		
	return $ticket_particulars_array;
}
function count_digit($digit,$all_digit){
	$count = 0;
	$arr_digit = explode(',',$all_digit);		
	foreach ($arr_digit as $list){		
		if($list == $digit){			
			$count++;			
		}
	}
	return $count;	
}
function get_game_mrp($game_id){
	global $conn;
	$get_mrp = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM ticket_mrp WHERE game_id = '$game_id'"));
	if(empty($get_mrp['mrp_rate'])){
		$get_mrp['mrp_rate'] = 1;
	}
	return $get_mrp['mrp_rate'];
}
function getting_all_combination_for_single_result($masterData,$percentage_start,$percentage_end){

	$desiredData = array();
	foreach($masterData AS $key=> $rows){
		if(($rows['win_per']>=$percentage_start) && ($rows['win_per'] <= $percentage_end)){
			$desiredData[$key] = $rows;
		}
	}
	$finalresult = array();
	$i=0;
	$depth = 0;
	shuffle($desiredData);
	foreach($desiredData AS $key=>$rows){
		$i++;
		if($rows['Digit_depth']>$depth){
			$finalresult = array();
			$rows['digit'] = $key;
			$finalresult = $rows;
			$depth = $rows['Digit_depth'];
			if($rows['Digit_depth']==100){
				break;
			}
		}
	}
	return $finalresult;
}
function search_get_combos($array,$total_terminal,$percentage_start,$percentage_end,$total_sales){
	$primaryIndex = 0;
	$result_digit = array();;
	foreach($array AS $key=>$rows){
		$secondaryIndex = 0;
		foreach($array AS $key2=> $rows2){
			if($secondaryIndex >= $primaryIndex){
				$digit1 = $rows;
				$digit2 = $rows2;
				$allTerminals1  = $rows['terminals'];
				$allTerminals2  = $rows2['terminals'];
				$total_terminals = array_merge($allTerminals1,$allTerminals2);
				$total_terminals = array_unique($total_terminals);
				$combo_terminal = count($total_terminals);
				$depth = ($combo_terminal/$total_terminal)*100;
				$total_win_per = (($rows['win_amt']+$rows2['win_amt'])/$total_sales)*100;
				if(($total_win_per >=$percentage_start) && ($total_win_per  <= $percentage_end)){
					$rowArr['digit'] = $key.",".$key2;
					$rowArr['depth'] = $depth;
					$rowArr['win_amt'] = $rows['win_amt']+$rows2['win_amt'];
					$rowArr['win_per'] = $total_win_per;
					$rowArr['terminals'] = $total_terminals;
					$result_digit[] = $rowArr;
				}
			}
			$secondaryIndex++;
		}
		$primaryIndex++;
	}
	return $result_digit;
} 
function search_get_triples($array,$total_terminal,$percentage_start,$percentage_end,$total_sales){
	$primaryIndex = 0;
	$result_digit = array();;
	foreach($array AS $key=>$rows){
		$secondaryIndex = 0;
		foreach($array AS $key2=> $rows2){	
			if($secondaryIndex >= $primaryIndex){
				$tripleIndex = 0;
				foreach($array AS $key3=> $rows3){	
					if($tripleIndex >= $secondaryIndex){
						$digit1 = $rows;
						$digit2 = $rows2;
						$digit3 = $rows3;
						$allTerminals1  = $rows['terminals'];
						$allTerminals2  = $rows2['terminals'];
						$allTerminals3  = $rows3['terminals'];
						$total_terminals = array_merge($allTerminals1,$allTerminals2,$allTerminals3);
						$total_terminals = array_unique($total_terminals);
						$combo_terminal = count($total_terminals);
						$depth = ($combo_terminal/$total_terminal)*100;
						$total_win_per = (($rows['win_amt']+$rows2['win_amt']+$rows3['win_amt'])/$total_sales)*100;
						if(($total_win_per >=$percentage_start) && ($total_win_per  <= $percentage_end)){
							$rowArr['digit'] = $key.",".$key2.",".$key3;
							$rowArr['depth'] = $depth;
							$rowArr['win_amt'] = $rows['win_amt']+$rows2['win_amt']+$rows3['win_amt'];
							$rowArr['win_per'] = $total_win_per;
							$rowArr['terminals'] = $total_terminals;
							$result_digit[] = $rowArr;
						}
					}
					$tripleIndex++;
				}
			}
			$secondaryIndex++;
		}
		$primaryIndex++;
	}
	//print_r($result_digit); die();
	return $result_digit;
} 
function getting_all_combination_for_double_result($masterData,$percentage_start,$percentage_end,$total_terminal,$total_sales){
	$desiredData = array();
	$allDigits = array();
	$i=0;	
	sort($masterData);
	#Getting all the Combos#
	$combopossible = search_get_combos($masterData,$total_terminal,$percentage_start,$percentage_end,$total_sales);	
	$finalresult = array();
	if(!empty($combopossible)){
		shuffle($combopossible);
		#getting the final Result#
		$top_depth = 0;
		foreach($combopossible AS $row){
			if($row['depth']>$top_depth){
				$finalresult = $row;
				if($row['depth']==100){
					break;
				}
				$top_depth = $row['depth'];
			}
		}
	}
	return $finalresult;
}

function getting_all_combination_for_triple_result($masterData,$percentage_start,$percentage_end,$total_terminal,$total_sales){
	$desiredData = array();
	$allDigits = array();
	$i=0;	
	sort($masterData);
	#Getting all the Combos#
	$combopossible = search_get_triples($masterData,$total_terminal,$percentage_start,$percentage_end,$total_sales);	
	$finalresult = array();
	if(!empty($combopossible)){
		shuffle($combopossible);
		#getting the final Result#
		$top_depth = 0;
		foreach($combopossible AS $row){
			if($row['depth']>$top_depth){
				$finalresult = $row;
				if($row['depth']==100){
					break;
				}
				$top_depth = $row['depth'];
			}
		}
	}
	return $finalresult;
}
function threetimes($masterData,$percentage_start,$percentage_end,$total_terminal,$total_sales,$reward,$game_rate){
	$alleligible = array();
	foreach($masterData AS $key=>$rows){
		$total_qty = $rows['total_qty'];
		$winning_amt = ($total_qty*$game_rate)*3;
		$winning_per = ($winning_amt/$total_sales)*100;
		if(($winning_per >=$percentage_start) && ($winning_per  <= $percentage_end)){
			$digitTerminals = count(array_unique($rows['terminals']));
			$depth = ($digitTerminals/$total_terminal)*100;
			$rowArr = array();
			$rowArr['digit'] = $key;
			$rowArr['depth'] = $depth;
			$rowArr['win_amt'] = $winning_amt;
			$rowArr['win_per'] = $winning_per;
			$rowArr['terminals'] = $total_terminal;
			$rowArr['total_sale'] = $total_sales;
			$rowArr['total_qty'] = $total_qty;
			$alleligible[] = $rowArr;
		}
	}
	$higest_depth = 0;
	$finalresult = array();
	if(!empty($alleligible)){
		foreach ($alleligible as $key => $rows) {
			if($rows['depth']>$higest_depth){
				$finalresult = $rows;
				$higest_depth = $row['depth'];
			}
		}
	}
	return $finalresult;
}

// function 4times($masterData,$percentage_start,$percentage_end,$total_terminal,$total_sales,$reward,$game_rate){
	
// }

// function 5times($masterData,$percentage_start,$percentage_end,$total_terminal,$total_sales,$reward,$game_rate){
	
// }
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
function get_result($date_min,$time_id,$game_id){
	global $conn;
	#Getting all Played Digits# 
	$time_start = microtime_float();

	#Getting Game Details#
	$get_time = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM game_master INNER JOIN prize_reward ON prize_reward.game_id=game_master.game_id WHERE game_master.game_id='$game_id'"));
	$game_rate  = $get_time['game_rate'];
	$reward     = $get_time['reward'];

	#Getting Total Sale Details Of Given drawtime#
	$get_sum = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) AS tot_amount, SUM(quantity) AS total_qty FROM sales_details WHERE time_id ='".$time_id."' AND game_id = '".$game_id."' AND min_date='".$date_min."' AND is_canceled=0"));

	$total_sales  = $get_sum['tot_amount'];
	
	#Counting All Terminal Participated in Game with Given Time#
	$count_terminal_played = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM sales_details WHERE time_id ='".$time_id."' AND game_id = '".$game_id."' AND min_date='".$date_min."' GROUP BY uid"));
	
	#select All Result Declaration#
	$masterData = array();
	$get_tickets_row =  (mysqli_query($conn,"SELECT * FROM result_declaration WHERE date_min = '$date_min' AND game_id = '$game_id' AND time_id = '$time_id'"));
	
	$all_digit_played = array();
	while($fetch_data = mysqli_fetch_assoc($get_tickets_row)){
		$digit_played_terminals = unserialize($fetch_data['all_terminals']);
		$digit_played_terminals = array_unique($digit_played_terminals);
		#Calculating the percentage of digit played by no of terminals#
		$digit_depth = (count($digit_played_terminals)/$count_terminal_played)*100;
		$total_digit_sale = $fetch_data['digit_total_sale'];
		$total_sold_qty   = $fetch_data['total_qty'];

		#Particular Digit winning Percentage#
		$win_amt =  $total_sold_qty*$reward*$game_rate;
		$win_per =  ($win_amt/$total_sales)*100;
		$rowarray = array();
		$rowarray['Digit_depth'] = $digit_depth;
		$rowarray['win_per'] 	 = $win_per;
		$rowarray['win_amt'] 	 = $win_amt;
		$rowarray['total_qty'] 	 = $total_sold_qty;
		$rowarray['terminals']   = $digit_played_terminals;
		$masterData[$fetch_data['digit']] = ($rowarray);
		$all_digit_played[] = $fetch_data['digit'];
	}
	if(!empty($masterData)){	
		#If We have Tickets under this Draw time#
		$percentage_start = 70;
		$percentage_end   = 90;
		$desiredData	  = array();
		$dataTypeFlag     = 0; //0: No Purchase,1: In Range,2: Below Range, 3: Not in any range Blank Digit shooted, 4: All digits booked and all are above 100%//
		$single_combination = getting_all_combination_for_single_result($masterData,$percentage_start,$percentage_end);
		$double_combination = getting_all_combination_for_double_result($masterData,$percentage_start,$percentage_end,$count_terminal_played,$total_sales);
		$triple_combination = getting_all_combination_for_triple_result($masterData,$percentage_start,$percentage_end,$count_terminal_played,$total_sales);
		//$threetimes = threetimes($masterData,$percentage_start,$percentage_end,$count_terminal_played,$total_sales,$reward,$game_rate);
		
		// $4times = 4times($masterData,$percentage_start,$percentage_end,$count_terminal_played,$total_sales,$reward,$game_rate);
		// $5times = 5times($masterData,$percentage_start,$percentage_end,$count_terminal_played,$total_sales,$reward,$game_rate);
		#Finalizing the data#
		$is_not_in_range = 1;
		$finalDigit = array();
		if(!empty($single_combination) || !empty($double_combination) || !empty($triple_combination)){
			$is_not_in_range = 0;
			$final_depth = 0;
			
			if(!empty($single_combination)){
				$finalDigit[] = $single_combination;
			}

			if(!empty($double_combination)){
				$finalDigit[] = $double_combination;
			}

			if(!empty($triple_combination)){
				$finalDigit[] = $triple_combination;
			}
			shuffle($finalDigit);
			$finalDigit = $finalDigit[0];
		}
		if($is_not_in_range==1){
			#If We Found Not Digit in given Range#
			#Checking For any digit available below the given range#
			$finalDigit = array();
			$higest_per = 0;
			// print_r($masterData);
			// shuffle($masterData);
			// echo "<br>";echo "<br>";echo "<br>";
			// print_r($masterData);
			foreach($masterData AS $key=> $rows){
				if(($rows['win_per']<$percentage_start)){
					$desiredData[$key] 		 = $rows;
					$rowarray['depth'] 		 = $rows['Digit_depth'];
					$rowarray['win_per'] 	 = $rows['win_per'];
					$rowarray['win_amt'] 	 = $rows['win_amt'];;
					$rowarray['digit'] 	 	 = $key;
					if($rows['win_per'] > $higest_per){
						$finalDigit[0] = $rowarray;
						$higest_per = $rows['win_per'];
					}else if($rows['win_per'] == $higest_per){
						$finalDigit[] = $rowarray;
					}
					$dataTypeFlag = 2;

				}
			}
			shuffle($finalDigit);
			$finalDigit = $finalDigit[0];
			//print_r($finalDigit);
			//die();
			if(empty($desiredData)){
				#Checking For Any Digit no tickets Purchased#
				$all_digit_played = implode(",", $all_digit_played);
				$get_game_digit = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM game_digit WHERE game_master_id='$game_id' AND digit NOT IN ($all_digit_played) ORDER BY rand() LIMIT 1"));
				if(!empty($get_game_digit)){
					$rowarray = array();
					$rowarray['depth'] 		 = 0;
					$rowarray['win_per'] 	 = 0;
					$rowarray['win_amt'] 	 = 0;
					$rowarray['digit'] 	 	 = $get_game_digit['digit'];
					$desiredData 			 = $rowarray;
					$finalDigit 			 = $rowarray;
					$dataTypeFlag 			 = 3;
				}
				if(empty($desiredData)){
					#If Condition coming here means every digit is above the Given Range#
					#Now getting the lowest percentage#
						$lowest = 0;
						foreach($masterData AS $key=> $rows){
							if(($rows['win_per'] < $percentage_end)){
								$desiredData[$key] 		 = $rows;
								$rowarray['depth'] 		 = $rows['Digit_depth'];
								$rowarray['win_per'] 	 = $rows['win_per'];
								$rowarray['win_amt'] 	 = $rows['win_amt'];;
								$rowarray['digit'] 	 	 = $key;
								if($rows['win_per'] < $lowest){
									$finalDigit = $rowarray;
									$lowest = $rows['win_per'];
								}
								$dataTypeFlag = 2;
							}
						}
				}
			}
		}
	}else{
		#If We dont have any tickets purchased under the draw time#
		#getting the games digit#
		$get_game_digit = (mysqli_query($conn,"SELECT * FROM game_digit WHERE game_master_id='$game_id' ORDER BY rand() LIMIT 1"));
		$rowarray = array();
		$rowarray['Digit_depth'] = 0;
		$rowarray['win_per'] 	 = 0;
		$rowarray['win_amt'] 	 = 0;
		while($fetch_game_digit = mysqli_fetch_assoc($get_game_digit)){
			$desiredData[$fetch_game_digit['digit']] = $rowarray;
			$rowarray['depth'] 		 = 0;
			$rowarray['win_per'] 	 = 0;
			$rowarray['win_amt'] 	 = 0;
			$rowarray['digit'] 	 	 = $fetch_game_digit['digit'];
			$finalDigit 			 = $rowarray;
		}
	}
	#getting All Digits#
	$resultDigit = $finalDigit['digit'];

	$resultDigit = explode(",", $resultDigit);
	$left_digit = 6-(count($resultDigit))*2;

	$left_digit_arr = array();
	foreach($resultDigit AS $rows){
		$left_digit_arr[] = $rows;
		$left_digit_arr[] = $rows;
	}

	if($left_digit > 0){		
		$not_in_digit = implode(",", $resultDigit);
		$get_game_digit = (mysqli_query($conn,"SELECT * FROM game_digit WHERE game_master_id='$game_id' AND digit NOT IN($not_in_digit) ORDER BY rand() LIMIT $left_digit"));
		while($fetch_data = mysqli_fetch_assoc($get_game_digit)){
			$left_digit_arr[] = $fetch_data['digit'];
		}
		//$left_digit_arr = implode(",", $left_digit_arr);
	}
	
	$left_digit_arr = implode(",",$left_digit_arr);
	return $left_digit_arr;
}

?>