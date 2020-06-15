<?php
session_start();
include("includes/connect_plan.php");
include("includes/premium.php");

$userid = 0;
$cov_order = 0;
$question = "";
$reply = "";
$chat_qs = array();
$chat_as = array();
$n_cov = 0;
$curr_planner_qa_id = 0;
$new_planner_qa_id = 0;
$curr_planner_qa_sec = 2;
$new_planner_qa_sec = 2;
$male_words = array('male', 'boy', 'he', 'man', 'gentleman');
$female_words = array('female', 'girl', 'she', 'woman', 'lady');
$negative_words = array('no', 'not', 'isnt','negative','incorrect', 'nope');
$positive_words = array('yes', 'right', 'aye','correct', 'yeah', 'yup');

$error_message = "";
$ioutput = "N";
$reminder_message = "";
$age = "";
$gender = "";
$smoker = "";
$married = "";
$sage = "";
$sgender = "";
$ssmoker = "";
$ndependant = "";
$d1age = "";
$d1gender = "";
$d2age = "";
$d2gender = "";
$d3age = "";
$d3gender = "";
$netincome = "";
$yourperc = "";
$spendperc = "";
$netasset = "";
$loanamount = "";
$retireincome = "";
$educost = "";
$protectperc = -1;
$investperc = -1;

$t1 = 0;
$t2 = 0;
$d1 = 0;
$d2 = 0;
$ci1 = 0;
$ci2 = 0;
$ci3 = 0;
$ci4 = 0;
$ci5 = 0;
$a1 = 0;
$a2 = 0;


if(isset($_SESSION['user_id'])){
	$userid = $_SESSION['user_id'];
	$result3 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ORDER by cov_order");
	$n_cov = mysqli_num_rows($result3);

	if ($n_cov == 0){ 
		$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id=0");
		$row = mysqli_fetch_assoc($result6);
		$question = $row['first'];
		$reply = $row['second'];
		$reply = mysqli_real_escape_string($connect, $reply);
		$question = mysqli_real_escape_string($connect, $question);
		$n_cov = $n_cov + 1;
		$result2 = mysqli_query($mysqli, "INSERT INTO chat_history (user_id, cov_order, question, answer, planner_qa_id, planner_qa_sec) VALUES ('$userid', '$n_cov', '$question', '$reply', 0, 2)");
	}

	$result9 = mysqli_query($mysqli, "SELECT * FROM users WHERE user_id='$userid' ") or die(mysql_error());
	if(mysqli_num_rows($result9)==1){
		while ($row = mysqli_fetch_assoc($result9))	{
			$age = $row['age'];
			$gender = $row['gender'];
			$smoker = $row['smoker'];
			$married = $row['married'];
			$sgender = $row['sgender'];
			$ssmoker = $row['ssmoker'];
			$ndependant = $row['ndependant'];
			$d1gender = $row['d1gender'];
			$d2gender = $row['d2gender'];
			$d3gender = $row['d3gender'];
			$sage= $row['sage'];
			$d1age= $row['d1age'];
			$d3age= $row['d3age'];
			$yourperc= $row['yourperc'];
			$spendperc= $row['spendperc'];
			$netincome= $row['netincome'];
			$netasset= $row['netasset'];
			$loanamount= $row['loanamount'];
			$retireincome= $row['retireincome'];
			$educost= $row['educost'];
		}
	}

	$result3 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ORDER by cov_order");

	$numResults = mysqli_num_rows($result3);
	$counter = 0;
	while ($row = mysqli_fetch_assoc($result3))	{
		$cov_order = $row['cov_order'];
		$chat_qs[$cov_order] = $row['question'];
		$chat_as[$cov_order] = $row['answer'];
		
		if (++$counter == $numResults) {
			
			$curr_planner_qa_id = $row['planner_qa_id'];
			$curr_planner_qa_sec = $row['planner_qa_sec'];

			if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 8) && ($ndependant == 0)) {
				$new_planner_qa_id = $curr_planner_qa_id + 7;
			} else if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 10) && ($ndependant == 1)) {
				$new_planner_qa_id = $curr_planner_qa_id + 5;
			} else if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 12) && ($ndependant == 2)) {
				$new_planner_qa_id = $curr_planner_qa_id + 3;
			} else if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 20) && ($ndependant == 0)) {
				$new_planner_qa_id = $curr_planner_qa_id + 2;
			} else if ($curr_planner_qa_sec == 2) {
				$new_planner_qa_id = $curr_planner_qa_id + 1;
			} else {
				$new_planner_qa_id = $curr_planner_qa_id;
			}
			
			if (($curr_planner_qa_sec == 2)) {
				$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id='$new_planner_qa_id'");
				$row = mysqli_fetch_assoc($result6);
				$question = $row['first'];
			} else {
				$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id='$new_planner_qa_id'");
				$row = mysqli_fetch_assoc($result6);
				$question = $row['second'];		
			}
		}
	}
			
} else {
	$userid = mt_rand(111111,987654);
	
	$result4 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ");
	while(mysqli_num_rows($result4)>0){
		$userid = "t".mt_rand(111111,987654);
		$result4 = mysqli_query($mysqli, "SELECT * FROM usersinsure WHERE user_id='$userid' ");
	}
	$_SESSION['user_id'] = $userid;
	
	$q = "INSERT INTO users SET user_id='$userid', age='$age', gender='$gender', smoker='$smoker', married='$married', ndependant='$ndependant', sgender='$sgender', ssmoker='$ssmoker', d1gender='$d1gender', d2gender='$d2gender', d3gender='$d3gender' ";
	// echo $q;
	$result9 = mysqli_query($mysqli, $q);

	// if($result9){
		// echo "success";
	// } else {
		// echo "fail";				
	// }
	
	$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id=0");
	$row = mysqli_fetch_assoc($result6);
	$question = $row['first'];
	$reply = $row['second'];
	$reply = mysqli_real_escape_string($connect, $reply);
	$question = mysqli_real_escape_string($connect, $question);
	$n_cov = $n_cov + 1;
	
	$result2 = mysqli_query($mysqli, "INSERT INTO chat_history (user_id, cov_order, question, answer, planner_qa_id, planner_qa_sec) VALUES ('$userid', '$n_cov', '$question', '$reply', 0, 2)");

	$result3 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ORDER by cov_order");

	$numResults = mysqli_num_rows($result3);
	$counter = 0;
	while ($row = mysqli_fetch_assoc($result3))	{
		$cov_order = $row['cov_order'];
		$chat_qs[$cov_order] = $row['question'];
		$chat_as[$cov_order] = $row['answer'];

		if (++$counter == $numResults) {
			$curr_planner_qa_id = $row['planner_qa_id'];
			$curr_planner_qa_sec = $row['planner_qa_sec'];
			
			if (($curr_planner_qa_sec == 2)) {
				$new_planner_qa_id = $curr_planner_qa_id + 1;
			} else {
				$new_planner_qa_id = $curr_planner_qa_id;
			}

			if (($curr_planner_qa_sec == 2)) {
				$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id='$new_planner_qa_id'");
				$row = mysqli_fetch_assoc($result6);
				$question = $row['first'];
			} else {
				$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id='$new_planner_qa_id'");
				$row = mysqli_fetch_assoc($result6);
				$question = $row['second'];		
			}
		}
	}

}

// echo 'qeustio is ';
// echo $question;

if(isset($_POST['submit'])){

	$result9 = mysqli_query($mysqli, "SELECT * FROM users WHERE user_id='$userid' ") or die(mysql_error());
	if(mysqli_num_rows($result9)==1){
		while ($row = mysqli_fetch_assoc($result9))	{
			$age = $row['age'];
			$gender = $row['gender'];
			$smoker = $row['smoker'];
			$married = $row['married'];
			$sgender = $row['sgender'];
			$ssmoker = $row['ssmoker'];
			$ndependant = $row['ndependant'];
			$d1gender = $row['d1gender'];
			$d2gender = $row['d2gender'];
			$d3gender = $row['d3gender'];
			$sage= $row['sage'];
			$d1age= $row['d1age'];
			$d3age= $row['d3age'];
			$yourperc= $row['yourperc'];
			$spendperc= $row['spendperc'];
			$netincome= $row['netincome'];
			$netasset= $row['netasset'];
			$loanamount= $row['loanamount'];
			$retireincome= $row['retireincome'];
			$educost= $row['educost'];
		}
	}

	$result = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ");
	$n_cov = mysqli_num_rows($result);
	$reply = mysqli_real_escape_string($connect, $_POST['inputmsg']);
	
	$reply = mysqli_real_escape_string($connect, $reply);
	
	if (($new_planner_qa_id == 1) && is_numeric($reply)) {
		$age = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 1) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$age = 30;
	}
	if (($new_planner_qa_id == 1) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 5) && is_numeric($reply)) {
		$sage = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 5) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$sage = 30;
	}
	if (($new_planner_qa_id == 5) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 8) && is_numeric($reply)) {
		$ndependant = max(0,min(3,$reply));
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 8) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$ndependant = 0;
	}
	if (($new_planner_qa_id == 8) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}
	
	if (($new_planner_qa_id == 9) && is_numeric($reply)) {
		$d1age = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 9) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$d1age = 5;
	}
	if (($new_planner_qa_id == 9) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}
	
	if (($new_planner_qa_id == 11) && is_numeric($reply)) {
		$d2age = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 11) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$d2age = 5;
	}
	if (($new_planner_qa_id == 11) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}
	
	
	if (($new_planner_qa_id == 13) && is_numeric($reply)) {
		$d2age = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 13) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$d2age = 5;
	}
	if (($new_planner_qa_id == 13) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 15) && is_numeric($reply)) {
		$netincome = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 15) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$netincome = 50000;
	}
	if (($new_planner_qa_id == 15) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 16) && is_numeric($reply)) {
		$yourperc = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 16) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$yourperc = 50;
	}
	if (($new_planner_qa_id == 16) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 17) && is_numeric($reply)) {
		$spendperc = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 17) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$spendperc = 70;
	}
	if (($new_planner_qa_id == 17) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 18) && is_numeric($reply)) {
		$netasset = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 18) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$netasset = 50000;
	}
	if (($new_planner_qa_id == 18) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 19) && is_numeric($reply)) {
		$loanamount = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 19) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$loanamount = 0;
	}
	if (($new_planner_qa_id == 19) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 20) && is_numeric($reply)) {
		$retireincome = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 20) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$retireincome = 0;
	}
	if (($new_planner_qa_id == 20) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id == 21) && is_numeric($reply)) {
		$educost = $reply;
		$new_planner_qa_sec = 2;
	}
	if (($new_planner_qa_id == 21) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 1)) {
		$new_planner_qa_sec = 2;
		$educost = 10000;
	}
	if (($new_planner_qa_id == 21) && !(is_numeric($reply)) && ($curr_planner_qa_sec == 2)) {
		$new_planner_qa_sec = 1;
	}

	if (($new_planner_qa_id >= 22)) {
		$new_planner_qa_sec = 2;
	}


	if ($new_planner_qa_id == 2) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$male_words)) {
				$value = "M";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$female_words)) {
				$value = "F";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				if ($value == "M"){
					$value = "F";
				}
				if ($value == "F") {
					$value = "M";
				}
				break;
			}
		}
		if ($value == "") {
			$value = "M";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$gender = $value;
	}

	if ($new_planner_qa_id == 6) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$male_words)) {
				$value = "M";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$female_words)) {
				$value = "F";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				if ($value == "M"){
					$value = "F";
				}
				if ($value == "F") {
					$value = "M";
				}
				break;
			}
		}
		if ($value == "") {
			$value = "M";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$sgender = $value;
	}

	if ($new_planner_qa_id == 10) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$male_words)) {
				$value = "M";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$female_words)) {
				$value = "F";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				if ($value == "M"){
					$value = "F";
				}
				if ($value == "F") {
					$value = "M";
				}
				break;
			}
		}
		if ($value == "") {
			$value = "M";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$d1gender = $value;
	}

	if ($new_planner_qa_id == 12) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$male_words)) {
				$value = "M";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$female_words)) {
				$value = "F";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				if ($value == "M"){
					$value = "F";
				}
				if ($value == "F") {
					$value = "M";
				}
				break;
			}
		}
		if ($value == "") {
			$value = "M";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$d2gender = $value;
	}

	if ($new_planner_qa_id == 14) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$male_words)) {
				$value = "M";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$female_words)) {
				$value = "F";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				if ($value == "M"){
					$value = "F";
				}
				if ($value == "F") {
					$value = "M";
				}
				break;
			}
		}
		if ($value == "") {
			$value = "M";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$d3gender = $value;
	}

	if ($new_planner_qa_id == 3) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				$value = "N";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$positive_words)) {
				$value = "Y";
				break;
			}
		}
		if ($value == "") {
			$value = "N";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$smoker = $value;
	}

	if ($new_planner_qa_id == 4) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				$value = "N";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$positive_words)) {
				$value = "Y";
				break;
			}
		}
		if ($value == "") {
			$value = "N";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$married = $value;
	}

	if ($new_planner_qa_id == 7) {
		$reply_lower = preg_replace("#[[:punct:]]#", "", $reply);
		$words = preg_split("/[\s,]+/", $reply_lower);
		
		$value = "";
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$negative_words)) {
				$value = "N";
				break;
			}
		}
		foreach ($words as $w) {
			$w = strtolower($w);
			if (in_array($w,$positive_words)) {
				$value = "Y";
				break;
			}
		}
		if ($value == "") {
			$value = "N";
			if ($curr_planner_qa_sec == 1) {
				$new_planner_qa_sec = 2;
			} else {
				$new_planner_qa_sec = 1;				
			}
		} else {
			$new_planner_qa_sec = 2;
		}
		$ssmoker = $value;
	}

	$n_cov = $n_cov+1;
	
	// if (($curr_planner_qa_sec == 2)) {
		// $new_planner_qa_sec = 1;
		// $new_planner_qa_id = $curr_planner_qa_id + 1;
	// } else {
		// $new_planner_qa_sec = 2;
		// $new_planner_qa_id = $curr_planner_qa_id;
	// }
	
	$question = mysqli_real_escape_string($connect, $question);
	// echo $n_cov;
	// echo $new_planner_qa_sec;
	// echo $new_planner_qa_id;
	// echo $userid;
	// echo $question;
	// echo $reply;
	$result2 = mysqli_query($mysqli, "INSERT INTO chat_history (user_id, cov_order, question, answer, planner_qa_id, planner_qa_sec) VALUES ('$userid', '$n_cov', '$question', '$reply', '$new_planner_qa_id', '$new_planner_qa_sec')");
	// if($result2){
		// echo "success";
	// } else {
		// echo "fail";				
	// }

	$result5 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ORDER by cov_order");
	$n_cov = mysqli_num_rows($result5);

	$numResults = mysqli_num_rows($result5);
	$counter = 0;
	while ($row = mysqli_fetch_assoc($result5)){
			$cov_order = $row['cov_order'];
			$chat_qs[$cov_order] = $row['question'];
			$chat_as[$cov_order] = $row['answer'];

		if (++$counter == $numResults) {
			$curr_planner_qa_id = $row['planner_qa_id'];
			$curr_planner_qa_sec = $row['planner_qa_sec'];
			
			if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 8) && ($ndependant == 0)) {
				$new_planner_qa_id = $curr_planner_qa_id + 7;
			} else if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 10) && ($ndependant == 1)) {
				$new_planner_qa_id = $curr_planner_qa_id + 5;
			} else if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 12) && ($ndependant == 2)) {
				$new_planner_qa_id = $curr_planner_qa_id + 3;
			} else if (($curr_planner_qa_sec == 2) && ($curr_planner_qa_id == 20) && ($ndependant == 0)) {
				$new_planner_qa_id = $curr_planner_qa_id + 2;
			} else if ($curr_planner_qa_sec == 2) {
				$new_planner_qa_id = min(23,$curr_planner_qa_id + 1);
			} else {
				$new_planner_qa_id = $curr_planner_qa_id;
			}

			if (($curr_planner_qa_sec == 2)) {
				$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id='$new_planner_qa_id'");
				$row = mysqli_fetch_assoc($result6);
				$question = $row['first'];
			} else {
				$result6 = mysqli_query($mysqli, "SELECT * FROM planner_qa WHERE id='$new_planner_qa_id'");
				$row = mysqli_fetch_assoc($result6);
				$question = $row['second'];		
			}
		}
	}


	$q = "UPDATE users SET age = '$age', gender =	'$gender', smoker = '$smoker', married = '$married', sgender = '$sgender', ssmoker = '$ssmoker', ndependant = '$ndependant', d1gender = '$d1gender', d2gender = '$d2gender', d3gender = '$d3gender' ";
	if(!($sage==="")) {$q.=", sage='$sage'";} else {$q.=", sage=NULL";}
	if(!($d1age==="")) {$q.=", d1age='$d1age'";} else {$q.=", d1age=NULL";}
	if(!($d2age==="")) {$q.=", d2age='$d2age'";} else {$q.=", d2age=NULL";}
	if(!($d3age==="")) {$q.=", d3age='$d3age'";} else {$q.=", d3age=NULL";}
	if(!($yourperc==="")) {$q.=", yourperc='$yourperc'";} else {$q.=", yourperc=NULL";}
	if(!($spendperc==="")) {$q.=", spendperc='$spendperc'";} else {$q.=", spendperc=NULL";}
	if(!($netincome==="")) {$q.=", netincome='$netincome'";} else {$q.=", netincome=NULL";}
	if(!($netasset==="")) {$q.=", netasset='$netasset'";} else {$q.=", netasset=NULL";}
	if(!($loanamount==="")) {$q.=", loanamount='$loanamount'";} else {$q.=", loanamount=NULL";}
	if(!($retireincome==="")) {$q.=", retireincome='$retireincome'";} else {$q.=", retireincome=NULL";}
	if(!($educost==="")) {$q.=", educost='$educost'";} else {$q.=", educost=NULL";}
	$q.=" WHERE user_id='$userid' ";
		
	$result2 = mysqli_query($mysqli, $q) or die(mysql_error());

	if(!$result2){
		die('Could not update database: '.mysql_error());
	}

	// $error = array();
	// $reminder = array();
 
	// //age
	// if($_POST['age']===""){
		// $age = 30;
	// }else if( is_numeric($_POST['age']) ){
		// $age = $_POST['age'];
	// }else{
		// $age = 30;
	// }
	
	// //gender
    // if(empty($_POST['gender'])){
		// $gender = "M";
    // }else{
		// $gender = mysqli_real_escape_string($connect, $_POST['gender']);
    // }
	
	// //smoker
    // if(empty($_POST['smoker'])){
        // $reminder[] = 'You have not input your smoking status. We will assume a non-smoker in the calculation.<br>';
		// $smoker = '';
    // }else{
		// $smoker = mysqli_real_escape_string($connect, $_POST['smoker']);
    // }

	// //married
    // if(empty($_POST['married'])){
		// $married = "N";
    // }else{
		// $married = mysqli_real_escape_string($connect, $_POST['married']);
    // }

	// //spouse age
	// if($_POST['sage']===""){
		// if($married == "Y"){
			// $reminder[] = 'You have not input your spouse\'s age. We will assume the same age as yours in the calculation.<br>';
		// }
		// $sage = '';
	// }else if( is_numeric($_POST['sage']) ){
		// $sage = $_POST['sage'];
	// }else{
		// $sage = $age;
	// }
	
	// //spouse gender
    // if(empty($_POST['sgender'])){
		// if($married == "Y"){
			// $reminder[] = 'You have not input your spouse\'s gender. We will assume the opposite gender of yours in the calculation.<br>';
		// }
		// $sgender = '';
    // }else{
		// $sgender = mysqli_real_escape_string($_POST['sgender']);
    // }
	
	// //spouse smoker
    // if(empty($_POST['ssmoker'])){
		// if($married == "Y"){
			// $reminder[] = 'You have not input your spouse\'s smoking status. We will assume a non-smoker in the calculation.<br>';
		// }
		// $ssmoker = '';
    // }else{
		// $ssmoker = mysqli_real_escape_string($_POST['ssmoker']);
    // }

	// //no of dependants
	// if($_POST['ndependant']===""){
		// $reminder[] = 'You have not input the number of dependants. We will assume zero dependant in the calculation.<br>';
		// $ndependant = '';
	// }else if( is_numeric($_POST['ndependant']) ){
		// $ndependant = $_POST['ndependant'];
	// }else{
		// $ndependant = '';
	// }

	// //1st dependant age
	// if($_POST['d1age']===""){
		// if($ndependant > 0){
			// $reminder[] = 'You have not input your first dependant\'s age. We will assume an age of 5 in the calculation.<br>';
		// }
		// $d1age = '';
	// }else if( is_numeric($_POST['d1age']) ){
		// $d1age = $_POST['d1age'];
	// }else{
		// $d1age = '';
	// }
	
	// //1st dependant gender
    // if(empty($_POST['d1gender'])){
		// if($ndependant > 0){
			// $reminder[] = 'You have not input your first dependant\'s gender. We will a male in the calculation.<br>';
		// }
		// $d1gender = '';
    // }else{
		// $d1gender = mysqli_real_escape_string($_POST['d1gender']);
    // }

	// //2nd dependant age
	// if($_POST['d2age']===""){
		// if($ndependant > 1){
			// $reminder[] = 'You have not input your second dependant\'s age. We will assume an age of 5 in the calculation.<br>';
		// }
		// $d2age = '';
	// }else if( is_numeric($_POST['d2age']) ){
		// $d2age = $_POST['d2age'];
	// }else{
		// $d2age = '';
	// }
	
	// //2nd dependant gender
    // if(empty($_POST['d2gender'])){
		// if($ndependant > 1){
			// $reminder[] = 'You have not input your second dependant\'s gender. We will a male in the calculation.<br>';
		// }
		// $d2gender = '';
    // }else{
		// $d2gender = mysqli_real_escape_string($_POST['d2gender']);
    // }

	// //3rd dependant age
	// if($_POST['d3age']===""){
		// if($ndependant > 2){
			// $reminder[] = 'You have not input your third dependant\'s age. We will assume an age of 5 in the calculation.<br>';
		// }
		// $d3age = '';
	// }else if( is_numeric($_POST['d3age']) ){
		// $d3age = $_POST['d3age'];
	// }else{
		// $d3age = '';
	// }
	
	// //3rd dependant gender
    // if(empty($_POST['d3gender'])){
		// if($ndependant > 2){
			// $reminder[] = 'You have not input your third dependant\'s gender. We will a male in the calculation.<br>';
		// }
		// $d3gender = '';
    // }else{
		// $d3gender = mysqli_real_escape_string($_POST['d3gender']);
    // }

	// //net income
	// if($_POST['netincome']===""){
		// $reminder[] = 'You have not input your annual after-tax net income. We will assume $50,000 in the calculation.<br>';
		// $netincome = '';
	// }else if( is_numeric(str_replace(',', '', $_POST['netincome'])) ){
		// $netincome = (float) str_replace(',', '', $_POST['netincome']);
	// }else{
		// $netincome = '';		
	// }
	
	// //your share of family income
	// if($_POST['yourperc']===""){
		// $reminder[] = 'You have not input your income as % of the family income. We will assume you contribute 50% in the calculation.<br>';
		// $yourperc = '';
	// }else if( is_numeric($_POST['yourperc']) ){
		// $yourperc = $_POST['yourperc'];
		// if ($yourperc <0) {
			// $yourperc = 0;
		// } else if ($yourperc >100) {
			// $yourperc = 100	;	
		// }
	// }else{
		// $yourperc = '';
	// }

	// //spending percentage
	// if($_POST['spendperc']===""){
		// $reminder[] = 'You have not input your family\'s monthly spending as % of the family income. We will assume you spend 70% in the calculation.<br>';
		// $spendperc = '';
	// }else if( is_numeric($_POST['spendperc']) ){
		// $spendperc = $_POST['spendperc'];
		// if ($spendperc <0) {
			// $spendperc = 0;
		// }
	// }else{
		// $spendperc = '';
	// }

	// //net asset
	// if($_POST['netasset']===""){
		// $reminder[] = 'You have not input your net asset. We will assume $50,000 in the calculation.<br>';
		// $netasset = '';
	// }else if( is_numeric(str_replace(',', '', $_POST['netasset'])) ){
		// $netasset = (float) str_replace(',', '', $_POST['netasset']);
	// }else{
		// $netasset = '';		
	// }

	// //loan amount
	// if($_POST['loanamount']===""){
		// $reminder[] = 'You have not input your loan amount. We will assume zero in the calculation.<br>';
		// $loanamount = '';
	// }else if( is_numeric(str_replace(',', '', $_POST['loanamount'])) ){
		// $loanamount = (float) str_replace(',', '', $_POST['loanamount']);
	// }else{
		// $loanamount = '';		
	// }

	// //retirement income
	// if($_POST['retireincome']===""){
		// $reminder[] = 'You have not input your desired annual retirement income in addition to social/employer\'s pension. We will assume zero in the calculation.<br>';
		// $retireincome = '';
	// }else if( is_numeric(str_replace(',', '', $_POST['retireincome'])) ){
		// $retireincome = (float) str_replace(',', '', $_POST['retireincome']);
	// }else{
		// $retireincome = '';		
	// }

	// //annual education cost
	// if($_POST['educost']===""){
		// $reminder[] = 'You have not input annual education cost for your kid(s). We will assume $10000 in the calculation.<br>';
		// $educost = '';
	// }else if( is_numeric(str_replace(',', '', $_POST['educost'])) ){
		// $educost = (float) str_replace(',', '', $_POST['educost']);
	// }else{
		// $educost = '';		
	// }

	$t1 = getrate($mysqli, $age, $gender, $smoker, 1);
	$d1 = getrate($mysqli, $age, $gender, $smoker, 3);
	$a1 = getrate($mysqli, $age, $gender, $smoker, 2);
	$ci1 = getrate($mysqli, $age, $gender, $smoker, 4);

	if($married == "Y") {
		if ($sage == ""){
			$ssage = $age;
		} else {
			$ssage = $sage;
		}

		if ($sgender == ""){
			if ($gender == "M") {
				$ssgender = "F";
			} else {
				$ssgender = "M";
			}
		} else {
			$ssgender = $sgender;
		}

		if ($ssmoker == ""){
			$sssmoker = "N";
		} else {
			$sssmoker = $ssmoker;
		}

		
		$t2 = getrate($mysqli, $ssage, $ssgender, $sssmoker, 1);
		$d2 = getrate($mysqli, $ssage, $ssgender, $sssmoker, 3);
		$ci2 = getrate($mysqli, $ssage, $ssgender, $sssmoker, 4);
		$a2 = getrate($mysqli, $ssage, $ssgender, $sssmoker, 2);
	}

	if($ndependant >=1) {

		if ($d1age == ""){
			$dd1age = 5;
		} else {
			$dd1age = $d1age;
		}

		if ($d1gender == ""){
			$dd1gender = "M";
		} else {
			$dd1gender = $d1gender;
		}

		$ci3 = getrate($mysqli, $dd1age, $dd1gender, 0, 4);
	}

	if($ndependant >=2) {

		if ($d2age == ""){
			$dd2age = 5;
		} else {
			$dd2age = $d2age;
		}

		if ($d2gender == ""){
			$dd2gender = "M";
		} else {
			$dd2gender = $d2gender;
		}

		$ci4 = getrate($mysqli, $dd2age, $dd2gender, 0, 4);
	}

	if($ndependant >=3) {

		if ($d3age == ""){
			$dd3age = 5;
		} else {
			$dd3age = $d3age;
		}

		if ($d3gender == ""){
			$dd3gender = "M";
		} else {
			$dd3gender = $d3gender;
		}

		$ci5 = getrate($mysqli, $dd3age, $dd3gender, 0, 4);
	}
	// echo $ci5;
} 

if(!empty($error)){
	$error_message = '<span class="error">';
	foreach ($error as $key => $values){
		$error_message.= "$values"."<br>";
	}
	$error_message.= "There are some optional fields you have not provided inputs. We will use default values for calculation. It may affect the appropriateness of our suggestions.</span><br/>";
} 

if ($new_planner_qa_id >= 22){
	$ioutput = "Y";
} else {
	$ioutput = "N";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Insurance Plan Chatbot</title>
	<link rel="stylesheet" href="css/insplan.css">
	<script type='text/javascript' src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.0.min.js'></script>
	<script type="text/javascript" src="js/plan.js"></script>
	<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.min.css" />
	<script src="jqplot/jquery.min.js" ></script>
	<script src="jqplot/jquery.jqplot.min.js" ></script>    
	<script src="jqplot/plugins/jqplot.pieRenderer.min.js"></script>
	
	<script type="text/javascript">
	function FocusOnBottom() { 
		var objDiv = document.getElementById("words");
		objDiv.scrollTop = objDiv.scrollHeight;
	}
	</script>
	
</head>

<body onload="addinfo(); insurealloc(); FocusOnBottom()">

	<div>
		<p style="text-align:center"> 
			<a href="chatplan.php">Insurance Planning Prototype</a> <a href="chatqaa.php">Q&A Prototype</a> <a href="chatreddit.php">Reddit Content Prototype</a>
		</p> 
	</div>

	<h1 class="header_one">Chatbot Prototype: Insurance Planning</h1>
	<p class="intro"><i>This is a prototype chatbot built in the research project sponsored by Fundacion MAPFRE.
	The chatbot is capable of providing customized insurance planning advice.</i></p>

	<?php
	if(isset($_SESSION['user_id'])){
	}
	else{
	?>
		</form>
	<?php
		}
	?>
	
    <div class="talk_con">
		<form method="post" onsubmit="insurealloc();" action="chatplan.php#outputd" id="chatbox" align = "right">

			<div class="talk_show" id="words">
				<?php
					if ($n_cov >0) {
						for($id=1;$id<=$n_cov;$id++) {
							$element = "<div ";
							echo $element." class=\"atalk\"><span id=\"asay\">".$chat_qs[$id]."</span></div>";
							$temp_reply = str_replace("???", "'", $chat_as[$id]);
							$temp_reply = str_replace("?", "", $temp_reply);
							if ($id > 1) {
								echo $element." class=\"btalk\"><span id=\"bsay\">".$temp_reply."</span></div>";
							}
						}
					echo $element." class=\"atalk\"><span id=\"asay\">".$question."</span></div>";
					}
				?>
			</div>

			<div class="talk_input">
					<input type="text" value="" name="inputmsg" class="talk_word"  id="talkwords"  title="Input Message"/>
					<input type="submit" name="submit" id="submit" class="button1" value="Send"/>
			</div>

			<div style="display:none;">
				<div class="field">
					<p style = "font-size: 17px; font-weight: bold; color:#505050; display:block; padding-left:30px;">Personal Information<span style = "font-size:15px; font-weight:normal; "> (Mandatory)</span></p>
				</div>

				<div class="field">
					<label class = "label0" for="age">Age:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="age" name="age" maxlength="3" value="<?php echo $age; ?>"/>&nbsp years
					<p class="hint">Your current legal age.</p>
				</div>
				
				<div class="field">
					<label class = "label0">Gender:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio1" name="gender" value="M" <?php echo ($gender=="M")?'checked':'' ?>>
					<label class = "label0" for="radio1">Male</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio2" name="gender" value="F" <?php echo ($gender=="F")?'checked':'' ?>>
					<label class = "label0" for="radio2">Female</label>
					<p class="hint">Your legal gender.</p>
				</div>
				
				<div class="field">
					<label class = "label0">Smoker:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio3" name="smoker" value="Y" <?php echo ($smoker=="Y")?'checked':'' ?>>
					<label class = "label0" for="radio3">Yes</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio4" name="smoker" value="N" <?php echo ($smoker=="N")?'checked':'' ?>>
					<label class = "label0" for="radio4">No</label>
					<p class="hint">Do you smoke? If you do not input your information, we will assume you are a non-smoker in the calculation.</p>
				</div>

				<div class="field">
					<label class = "label0">Married:</label>
					<input type="radio" id="radio5" onchange="addinfo(); insurealloc();" name="married" value="Y" <?php echo ($married=="Y")?'checked':'' ?>>
					<label class = "label0" for="radio5">Yes</label>
					<input type="radio" id="radio6" onchange="addinfo(); insurealloc();" name="married" value="N" <?php echo ($married=="N")?'checked':'' ?>>
					<label class = "label0" for="radio6">No</label>
					<p class="hint">Are you married?</p>
				</div>

				<div class="field">
					<label class = "label0" for="ndependant">No. of Dependants:</label>
					<input onchange="addinfo(); insurealloc();" onkeyup="addinfo(); insurealloc();" type="text" class="input" id="ndependant" name="ndependant" maxlength="3" value="<?php echo $ndependant; ?>"/>
					<p class="hint">How many dependants do you have? If you do not input the information, we will assume zero dependant in the calculation.</p>
				</div>

				<div class="field">
					<p id = "spousediv" style = "font-size: 17px; font-weight: bold; color:#505050; display:none; padding-left: 30px;">Spouse Information<span style = "font-size:15px; font-weight:normal; "> (Optional)</span></p>
				</div>

				<div class="field" id="sagediv" style="display:none;">
					<label class = "label0" for="sage">Spouse Age:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="sage" name="sage" maxlength="3" value="<?php echo $sage; ?>"/>&nbsp years
					<p class="hint">Your spouse's current legal age. If you do not input the information, we will assume the same age as yours in the calculation.</p>
				</div>
				
				<div class="field" id="sgenderdiv" style="display:none;">
					<label class = "label0">Spouse Gender:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio7" name="sgender" value="M" <?php echo ($sgender=="M")?'checked':'' ?>>
					<label class = "label0" for="radio7">Male</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio8" name="sgender" value="F" <?php echo ($sgender=="F")?'checked':'' ?>>
					<label class = "label0" for="radio8">Female</label>
					<p class="hint">Your spouse's legal gender. If you do not input the information, we will assume opposite gender of yours in the calculation.</p>
				</div>
				
				<div class="field" id="ssmokerdiv" style="display:none;">
					<label class = "label0">Smoker:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio9" name="ssmoker" value="Y" <?php echo ($ssmoker=="Y")?'checked':'' ?>>
					<label class = "label0" for="radio9">Yes</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio10" name="ssmoker" value="N" <?php echo ($ssmoker=="N")?'checked':'' ?>>
					<label class = "label0" for="radio10">No</label>
					<p class="hint">Does your spouse smoke? If you do not input the information, we will assume your spouse is a non-smoker in the calculation.</p>
				</div>

				<div class="field">
					<p id = "kiddiv" style = "font-size: 17px; font-weight: bold; color:#505050; display:none; padding-left: 30px;">Dependant Information<span style = "font-size:15px; font-weight:normal; "> (Optional)</span></p>
				</div>

				<div class="field" id="d1agediv" style="display:none;">
					<label class = "label0" for="d1age">1st Dependant Age:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="d1age" name="d1age" maxlength="3" value="<?php echo $d1age; ?>"/>&nbsp years
					<p class="hint">Your 1st dependant's age. If you do not input the information, we will assume age 5.</p>
				</div>
				
				<div class="field" id="d1genderdiv" style="display:none;">
					<label class = "label0">1st Dependant Gender:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio11" name="d1gender" value="M" <?php echo ($d1gender=="M")?'checked':'' ?>>
					<label class = "label0" for="radio11">Male</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio12" name="d1gender" value="F" <?php echo ($d1gender=="F")?'checked':'' ?>>
					<label class = "label0" for="radio12">Female</label>
					<p class="hint">Your 1st dependant's legal gender. If you do not input the information, we will assume a male.</p>
				</div>

				<div class="field" id="d2agediv" style="display:none;">
					<label class = "label0" for="d2age">2nd Dependant Age:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="d2age" name="d2age" maxlength="3" value="<?php echo $d2age; ?>"/>&nbsp years
					<p class="hint">Your 2nd dependant's age. If you do not input the information, we will assume age 5.</p>
				</div>
				
				<div class="field" id="d2genderdiv" style="display:none;">
					<label class = "label0">2nd Dependant Gender:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio13" name="d2gender" value="M" <?php echo ($d2gender=="M")?'checked':'' ?>>
					<label class = "label0" for="radio13">Male</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio14" name="d2gender" value="F" <?php echo ($d2gender=="F")?'checked':'' ?>>
					<label class = "label0" for="radio14">Female</label>
					<p class="hint">Your 2nd dependant's legal gender. If you do not input the information, we will assume a male.</p>
				</div>

				<div class="field" id="d3agediv" style="display:none;">
					<label class = "label0" for="d2age">3rd Dependant Age:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="d3age" name="d3age" maxlength="3" value="<?php echo $d3age; ?>"/>&nbsp years
					<p class="hint">Your 3rd dependant's age. If you do not input the information, we will assume age 5.</p>
				</div>
				
				<div class="field" id="d3genderdiv" style="display:none;">
					<label class = "label0">3rd Dependant Gender:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio15" name="d3gender" value="M" <?php echo ($d3gender=="M")?'checked':'' ?>>
					<label class = "label0" for="radio15">Male</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="radio" id="radio16" name="d3gender" value="F" <?php echo ($d3gender=="F")?'checked':'' ?>>
					<label class = "label0" for="radio16">Female</label>
					<p class="hint">Your 3rd dependant's legal gender. If you do not input the information, we will assume a male.</p>
				</div>

				<div class="field" id="fininfo">
					<p id = "findiv" style = "font-size: 17px; font-weight: bold; color:#505050; padding-left: 30px;">Financial Information<span style = "font-size:15px; font-weight:normal; "> (Optional)</span></p>
				</div>

				<div class="field">
					<label class = "label0" for="netincome">Annual Net Income:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="netincome" name="netincome" maxlength="20" value="<?php if(!($netincome==="")) {echo  number_format((float) $netincome);}?>"/>&nbsp $
					<p class="hint">Your family's annual after-tax net income. It does not need to be precise. If you do not input the number, we will assume $50,000 in the calculation.</p>
				</div>

				<div class="field">
					<label class = "label0" for="yourperc">Your Income Share:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="yourperc" name="yourperc" maxlength="20" value="<?php echo $yourperc; ?>"/>&nbsp %
					<p class="hint">Your income as % of total family income. If you do not input the number, we will assume 50% in the calculation.</p>
				</div>
				
				<div class="field">
					<label class = "label0" for="spendperc">Spending/Income:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="spendperc" name="spendperc" maxlength="20" value="<?php echo $spendperc; ?>"/>&nbsp %
					<p class="hint">Family spending as % of family income. If you do not input the number, we will assume 70% in the calculation.</p>
				</div>

				<div class="field">
					<label class = "label0" for="netasset">Net Asset (Excluding Residence):</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="netasset" name="netasset" maxlength="20" value="<?php if(!($netasset==="")) {echo  number_format((float) $netasset);} ?>"/>&nbsp $
					<p class="hint">Your family's net asset, which is your asset excluding your primary residence deducted by all the loans you have. It does not need to be precise. If you do not input the number, we will assume $50,000 in the calculation.</p>
				</div>

				<div class="field">
					<label class = "label0" for="loanamount">Loan Amount:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="loanamount" name="loanamount" maxlength="20" value="<?php if(!($loanamount==="")) {echo  number_format((float) $loanamount);} ?>"/>&nbsp $
					<p class="hint">Your family's current loan amount. It does not need to be precise. If you do not input the number, we will assume zero in the calculation.</p>
				</div>

				<div class="field">
					<label class = "label0" for="retireincome">Additional Retirement Income:</label>
					<input onchange="insurealloc();" onkeyup="insurealloc();" type="text" class="input" id="retireincome" name="retireincome" maxlength="20" value="<?php if(!($retireincome==="")) {echo  number_format((float) $retireincome);} ?>"/>&nbsp $
					<p class="hint">The amount of annual retirement income in addition to the social and employer's pension, that is desired. It does not need to be precise. If you do not input the number, we will assume zero in the calculation.</p>
				</div>

				<div class="field">
					<label class = "label0" for="educost">Annual Education Cost:</label>
					<input   type="text" class="input" id="educost" name="educost" maxlength="20" value="<?php if(!($educost==="")) {echo  number_format((float) $educost);} ?>"/>&nbsp $
					<p class="hint">The amount of annual undergraduate education cost per kid. It is used to evaluate whether the product can cover your children's education cost. It does not need to be precise and we will assume a 3% inflation rate. If you do not input the number, we will assume $10,000 in the calculation.</p>
				</div>
				
				<?php echo $error_message;?>

		
			</div>
			
			<div class="field" id="outputd">
			</div>

		



		</form>
    </div>

	<div id="section">
		
		<div class="box">

			<div class="field" style="display:none;">
				<input type="text" id="t1" name="t1" value="<?php echo $t1; ?>"/>
				<input type="text" id="t2" name="t2" value="<?php echo $t2; ?>"/>
				<input type="text" id="d1" name="d1" value="<?php echo $d1; ?>"/>
				<input type="text" id="d2" name="d2" value="<?php echo $d2; ?>"/>
				<input type="text" id="ci1" name="ci1" value="<?php echo $ci1; ?>"/>
				<input type="text" id="ci2" name="ci2" value="<?php echo $ci2; ?>"/>
				<input type="text" id="ci3" name="ci3" value="<?php echo $ci3; ?>"/>
				<input type="text" id="ci4" name="t1" value="<?php echo $ci4; ?>"/>
				<input type="text" id="ci5" name="t1" value="<?php echo $ci5; ?>"/>
				<input type="text" id="a1" name="a1" value="<?php echo $a1; ?>"/>
				<input type="text" id="a2" name="a2" value="<?php echo $a2; ?>"/>
			</div>

			<div class="field">
				<label class="label2" style="display:none"><?php echo $protectperc ?></label>
				<label class="label2" style="display:none"><?php echo $investperc ?></label>
			</div>

			<div id="output" <?php if ((!isset($_POST['submit'])) || ($ioutput == "N")){ echo 'style="display:none;"'; } ?>>
				<hr style="height:3px">
				<p style = "font-size: 17px; font-weight: bold; color:#505050;">Your Insurance Allocation</p>
				<p class="barsub3" style="padding-left:30px;">The insurance allocation plan is designed to protect your family's income and life style from unexpected events and refined to assure its affordability.</p>	
					
				<div style="padding-left:30px;">
				<table>
				  <tr>
					<th>Insured</th>
					<th>Life Insurance</th>
					<th>Disability Insurance</th>
					<th>Critical Illness Insurance</th>
					<th>Annuity</th>
					<th>Investment</th>
				  </tr>
				  <tr>
					<td>You</td>
					<td id="at1"></td>
					<td id="ad1"></td>
					<td id="aci1"></td>
					<td id="aa1"></td>
					<td>0</td>
				  </tr>
				  <tr <?php if ($married == "N" || $married == ""){ echo 'style="display:none;"'; } ?>>
					<td>Your Spouse</td>
					<td id="at2"></td>
					<td id="ad2"></td>
					<td id="aci2"></td>
					<td id="aa2"></td>
					<td>0</td>
				  </tr>
				  <tr <?php if ($ndependant < 1){ echo 'style="display:none;"'; } ?>>
					<td>1st Dependant</td>
					<td>0</td>
					<td>0</td>
					<td id="aci3"></td>
					<td>0</td>
					<td id="ae1"></td>
				  </tr>
				  <tr <?php if ($ndependant < 2){ echo 'style="display:none;"'; } ?>>
					<td>2nd Dependant</td>
					<td>0</td>
					<td>0</td>
					<td id="aci4"></td>
					<td>0</td>
					<td id="ae2"></td>
				  </tr>
				  <tr <?php if ($ndependant < 3){ echo 'style="display:none;"'; } ?>>
					<td>3rd Dependant</td>
					<td>0</td>
					<td>0</td>
					<td id="aci5"></td>
					<td>0</td>
					<td id="ae3"></td>
				  </tr>
				</table>
				</div>
				
				<p class="barsub3" style="padding-left:30px; padding-top:20px; font-size: 13px;" ><b style="text-decoration: underline;">Life Insurance:</b> We suggest you buy some term life insurance that provide death benefits. The amount is set as a multiple of your net income depending on age and wealth.</p>	
				<p class="barsub3" style="padding-left:30px; padding-top:5px; font-size: 13px;" ><b style="text-decoration: underline;">Disability Insurance:</b> Disability income insurance usually pays one or two years' income if the insured becomes disabled. It helps your family to get relief and adjust life style in a smooth manner.The amount depends on your net income.</p>	
				<p class="barsub3" style="padding-left:30px; padding-top:5px; font-size: 13px;" ><b style="text-decoration: underline;">Critical Illness Insurance:</b> Critical illness insurance helps cover the large amount of medical cost and the loss of income due to severe disease such as cancer, heart attack and Parkinson's disease. The amount depends on the medical cost in your area and the family income.</p>	
				<p class="barsub3" style="padding-left:30px; padding-top:5px; font-size: 13px;" ><b style="text-decoration: underline;">Annuity:</b> An annuity product usually has monthly payment for the rest of life. It can be used to meet your retirement income needs in addition to social pension and employer's pension. We recommend buying this product after age 50 and before retirement. The amount depends on the additional income needs and life expectancy.</p>	
				<p class="barsub3" style="padding-left:30px; padding-top:5px; font-size: 13px;" ><b style="text-decoration: underline;">Investment:</b> You may consider buying investment type of products to support your dependants' education. The amount is the expected cost of undergraduate education.</p>
				<hr style="height:1px">
				<p class="barsub3" style="padding-left:30px; padding-top:20px; font-size: 15px; font-weight:bold;" >Insurance Allocation (Amount: $)</p>
				<div id="chart1" style="margin-left: 30px; height: 300px; width: 600px;"></div>

				<hr id="dzl" style="height:1px">
				
				<p id="dzp" class="barsub3" style="padding-left:30px; padding-top:15px;" >Your expected <b style="text-decoration: underline;">total cost of protection</b> (Life insurance, Disability Insurance and Critical Illness Insurance) is about <label class="label3" id="protectperc" name="protectperc"></label>% of your family after-tax income.</p>
				<p id="dzp2" class="barsub3" style="padding-left:30px; padding-top:5px;" >Your <b style="text-decoration: underline;">total cost of investment</b> (annuity and education investment) is about <label class="label3" id="investperc" name="investperc"></label>% of your net asset.</p>
			
				<hr style="height:1px">
				
				<p style = "font-size: 11px; color:#505050; padding-left: 30px; text-align:justify;">This is my suggested insurance plan based on the information provided. Inaccurate and Incomplete information could lead to unreasonable advices. The user needs to make his/her own judgement of the soundness of the advice. We assume no responsibility for errors or omissions in the advice. In no event shall we be liable to you or any third parties for any damages of any kind arising out of or in connection with the use of this advice.</p>
			</div>
		</div>
		<footer>
			<p style="text-align:center">Copyright &copy 2020 All rights reserved.</p>
		</footer>
	</div>


	
</body>
</html>