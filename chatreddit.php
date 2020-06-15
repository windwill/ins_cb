<?php
session_start();
include("includes/connect_reddit.php");


$username = "Temp User";
$userid = 0;
$cov_order = 0;
$question = "";
$reply = "";
$chat_qs = array();
$chat_as = array();
$stop_words = array('i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', 'should', 'now', 'd', 'll', 'm', 'o', 're', 've', 'y', 'ain', 'aren', 'couldn', 'didn', 'doesn', 'hadn', 'hasn', 'haven', 'isn', 'ma', 'mightn', 'mustn', 'needn', 'shan', 'shouldn', 'wasn', 'weren', 'won', 'wouldn');
$clean_words = array();
$n_cov = 0;

if(isset($_SESSION['user_id'])){
	$userid = $_SESSION['user_id'];
	$result3 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ORDER by cov_order");
	$n_cov = mysqli_num_rows($result3);
	while ($row = mysqli_fetch_assoc($result3))
		{
			$cov_order = $row['cov_order'];
			$chat_qs[$cov_order] = $row['question'];
			$chat_as[$cov_order] = $row['answer'];
		}
} else {
	$userid = mt_rand(111111,987654);
	$result4 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ");
	while(mysqli_num_rows($result4)>0){
		$userid = "t".mt_rand(111111,987654);
		$result4 = mysqli_query($mysqli, "SELECT * FROM usersinsure WHERE user_id='$userid' ");
	}
	$_SESSION['user_id'] = $userid;
}

if(isset($_POST['submit'])){
	$result = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ");
	$n_cov = mysqli_num_rows($result);
	$question_ori = mysqli_real_escape_string($connect, $_POST['inputmsg']);
	
	$result6 = mysqli_query($mysqli,  "CREATE TEMPORARY TABLE replies (sentence_id INT(6) UNSIGNED PRIMARY KEY, sentence VARCHAR(600) NOT NULL, weight DECIMAL(15,8) NOT NULL)");
	
	$question = preg_replace("#[[:punct:]]#", "", $question_ori);
	$words = preg_split("/[\s,]+/", $question);
	
	$total_length = 0;
	
	foreach ($words as $w) {
		$w = strtolower($w);
		if (ctype_alpha($w) and !in_array($w,$stop_words)) {
			array_push($clean_words, $w);
			// echo $w;
			$total_length = $total_length + strlen($w);
		}
		
		$clean_words_association = array_count_values($clean_words);
	}

	// foreach ($clean_words_association as $key => $value){
		// echo $key;
		// echo $value;
	// }

	foreach ($clean_words_association as $key => $value){
		$weight = sqrt($value/$total_length);
		// echo $weight;
		// echo $key;
		$result7 = mysqli_query($mysqli, "INSERT INTO replies SELECT associations.sentence_id, sentences.sentence, '$weight'*associations.weight*(sentences.sentiment) FROM words INNER JOIN associations ON words.row_id = associations.word_id INNER JOIN sentences ON sentences.row_id=associations.sentence_id WHERE words.word='$key'");

		// if($result7){
			// echo "success";
		// } else {
			// echo "fail";				
		// }

	}//   $result7 = mysqli_query($mysqli, "INSERT INTO replies SELECT associations.sentence_id, sentences.sentence, ?*associations.weight*(sentences.sentiment) FROM words INNER JOIN associations ON associations.word_id=words.rowid INNER JOIN sentences ON sentences.rowid=associations.sentence_id WHERE words.word=?, ('$weight', '$key',)");
	
	$result8 = mysqli_query($mysqli,"SELECT sentence_id, sentence, SUM(weight) AS sum_weight FROM replies GROUP BY sentence_id ORDER BY sum_weight DESC LIMIT 2");

	// if($result8){
		// echo "success";
	// } else {
		// echo "fail";				
	// }
	
//	cursor.execute('SELECT sentence_id, sentence, SUM(weight) AS sum_weight FROM results GROUP BY sentence_id ORDER BY sum_weight DESC LIMIT 5')
//#     row = cursor.fetchone()
//    rows = cursor.fetchall()
	$reply = mysqli_fetch_assoc($result8)['sentence'];
	if ($reply == "") {
		$reply = "I do not know how to answer it";
	}
	
	$reply = str_replace("newlinechar", "", $reply);
	$reply = str_replace("  ", " ", $reply);
	$reply = mysqli_real_escape_string($connect, $reply);
	$question_ori = mysqli_real_escape_string($connect, $question_ori);
	// echo $reply;
	// $reply = str_replace("???", "'", $reply);
	// $reply = str_replace("?", "", $reply);
	// echo $reply;
	// echo $userid;
	// echo $n_cov;
	// echo $question;
	$n_cov = $n_cov+1;
	// echo "INSERT INTO chat_history (user_id, cov_order, question, answer) VALUES ('$userid', '$n_cov', '$question', '$reply')";
	$result2 = mysqli_query($mysqli, "INSERT INTO chat_history (user_id, cov_order, question, answer) VALUES ('$userid', '$n_cov', '$question_ori', '$reply')");

	// if($result2){
		// echo "success";
	// } else {
		// echo "fail";				
	// }

	$result5 = mysqli_query($mysqli, "SELECT * FROM chat_history WHERE user_id='$userid' ORDER by cov_order");
	$n_cov = mysqli_num_rows($result5);
	while ($row = mysqli_fetch_assoc($result5))
		{
			$cov_order = $row['cov_order'];
			$chat_qs[$cov_order] = $row['question'];
			$chat_as[$cov_order] = $row['answer'];
		}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insurance Reddit Chatbot</title>
    <style type="text/css">
        .header_one{
            width:600px;
            height:15px;
            margin:10px auto 0;
            background:#f9f9f9;
			font-family:tahoma
        }
        .intro{
            width:600px;
            height:10px;
            margin:30px auto 0;
            background:#f9f9f9;
			font-family:tahoma
        }
        .talk_con{
            width:600px;
            height:480px;
            border:1px solid #666;
            margin:50px auto 0;
            background:#f9f9f9;
        }
        .talk_show{
            width:580px;
            height:420px;
            border:1px solid #666;
            background:#fff;
            margin:10px auto 0;
            overflow:auto;
        }
        .talk_input{
            width:580px;
            margin:10px auto 0;
        }
        .whotalk{
            width:80px;
            height:30px;
            float:left;
            outline:none;
        }
        .talk_word{
            width:520px;
            height:20px;
            padding:0px;
            float:left;
            margin-left:0px;
            outline:none;
            text-indent:10px;
        }        
        .talk_sub{
            width:56px;
            height:30px;
            float:left;
            margin-left:10px;
        }
        .atalk{
           margin:10px; 
            text-align:left;
        }
        .atalk span{
            display:inline-block;
            background:#0181cc;
            border-radius:10px;
            color:#fff;
            padding:5px 10px;
        }
        .btalk{
            margin:10px;
            text-align:right;
        }
        .btalk span{
            display:inline-block;
            background:#ef8201;
            border-radius:10px;
            color:#fff;
            padding:5px 10px;
        }
    </style>

	<script type="text/javascript">
	function FocusOnBottom() { 
		var objDiv = document.getElementById("words");
		objDiv.scrollTop = objDiv.scrollHeight;
	}
	</script>

 </head>

<body onload="FocusOnBottom()">

	<div>
		<p style="text-align:center"> 
			<a href="chatplan.php">Insurance Planning Prototype</a> <a href="chatqaa.php">Q&A Prototype</a> <a href="chatreddit.php">Reddit Content Prototype</a>
		</p> 
	</div>

	<h1 class="header_one">Chatbot Prototype: Reddit Content</h1>
	<p class="intro"><i>This is a prototype chatbot built in the research project sponsored by Fundacion MAPFRE.
	The chatbot is based on reddit data relevant to insurance.</i></p>

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
		<form method="post" id="chatbox" align = "right">

			<div class="talk_show" id="words">
				<?php
					if ($n_cov >0) {
						for($id=1;$id<=$n_cov;$id++) {
							$element = "<div ";
							echo $element." class=\"atalk\"><span id=\"asay\">".$chat_qs[$id]."</span></div>";
							$temp_reply = str_replace("???", "'", $chat_as[$id]);
							$temp_reply = str_replace("?", "", $temp_reply);
							echo $element." class=\"btalk\"><span id=\"bsay\">".$temp_reply."</span></div>";
						}
					}
				?>
			</div>
			<div class="talk_input">
					<input type="text" value="" name="inputmsg" class="talk_word"  id="talkwords"  title="Input Message"/>
					<input type="submit" name="submit" id="submit" class="button" value="Send"/>
			</div>
		</form>
    </div>

	<footer>
		<p style="text-align:center">Copyright &copy 2020 All rights reserved.</p>
	</footer>

</body>

</html>