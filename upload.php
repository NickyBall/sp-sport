<?php
	// Author : Mr. Jakkrit Junrat

	include 'class.login.php';
	$login = new logmein();

	include 'class.log.php';
	$log = new createlog();

	echo "<body><hr/>";

	$submit_timestamp = date('Y-m-d H:i:s', time());
	//$filename = date('Y-m-d H-i-s', time());
	$foldername = date('Y-m-d', time());
	$uDate = date('Y-m-d', time());
	$uTime = date('H:i:s', time());
	$round = $_POST["round"];

	if (isset($_SESSION['logged_in'])) {
		if (!is_string($_SESSION['logged_in'])) {
			break;
		}
		$logged_in = (string) $_SESSION['logged_in'];
	}
	if (isset($_SESSION['username'])) {
		if (!is_string($_SESSION['username'])) {
			break;
		}
		$username_s = (string) $_SESSION['username'];
	}

	if ($login->check_logged_in($logged_in, $username_s) == false) {
		echo 'กรุณาเข้าสู่ระบบ';
		return false;
	}
	set_time_limit(1200);   //20 mins
	//Limit max file size
	$maxfilesize = 31457280;    //30 MB

	//Сheck input file
	if ((!empty($_FILES['inputfile'])) && ($_FILES['inputfile']['error'] == 0)) {

		if (($_FILES["inputfile"]["size"] < $maxfilesize)) {
			//Move uploaded file from temp directory to target directory.

			//$target_path = "uploads/".$foldername."/";
			$target_path = "uploads/";

			//Check whether folder exists or not.
			/*
			if (!file_exists($target_path)) {
				mkdir($target_path, 0777);
			}
			*/
			//Check directory to store file.
			$original_filename = mysql_escape_string(basename($_FILES['inputfile']['name']));
			
			$filename = $round;
			$target_path = $target_path . $foldername ." ". $filename . ".doc";

			$date_time_query = $login->qry("SELECT * FROM round_time WHERE round_date = '?' AND round_no = '?';", $uDate, $round);
			if (mysql_num_rows($date_time_query) == 0) {
				$date_time_insert = $login->qry("INSERT INTO round_time (round_date, round_no, round_time) VALUES ('?', '?', '?');", $uDate, $round, $uTime);
			}
			//        echo $target_path . "<br/>";
			if (move_uploaded_file($_FILES['inputfile']['tmp_name'], $target_path) == false) {
				echo '<br/>เกิดข้อผิดพลาดในระหว่างการอัพโหลด กรุณาลองใหม่อีกครั้ง';
				printfooter();
				return false;
			} else {
				echo '<br/><b>ชื่อไฟล์ : </b>' . strip_tags($original_filename) . '<br/>';
			}
		} else {
			echo "<br/>ข้อผิดพลาด: รองรับเฉพาะไฟล์ที่มีขนาดน้อยกว่า 30 MB เท่านั้น";
			printfooter();
			return FALSE;
		}
	} else {
		echo "ข้อผิดพลาด: ไม่มีไฟล์ที่จะอัพโหลด";
		return FALSE;
	}
	
	//unlink($target_path); // remove file from server.

	printfooter();

	function printfooter() {
		echo "</body>";
		echo "
		<script language='JavaScript'>
			window.parent.uploaded(document.body.innerHTML);
		</script>
		";
		exit();
	}
?>
