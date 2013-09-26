<?php
	include 'class.login.php';
	$login = new logmein();

	include 'class.log.php';
	$log = new createlog();

	include 'header.php';

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
	$user1 = $_GET["user1"];
	if ($login->check_agent($username_s, $logged_in) == false && $login->check_master($username_s, $logged_in) == false) {
            //echo $username_s . $logged_in . '</br>';
            echo 'Permission denied!<br/>';
            printfooter();
    } else {
		$stepc_qry = $login->qry("SELECT step1 FROM user_account WHERE username = '?'", $user1);
		$stepc_fet = mysql_fetch_array($stepc_qry);
		$stepc_result = $stepc_fet['step1'];
		//echo $stepc_result;
		if ($stepc_result == 'yes') {
			$login->qry("UPDATE user_account SET step1 = 'no' WHERE username = '?'", $user1);
		} else if ($stepc_result == 'no') {
			$login->qry("UPDATE user_account SET step1 = 'yes' WHERE username = '?'", $user1);
		}
		echo 'เปลี่ยนแปลงเสร็จเรียบร้อยแล้ว <br/>';
		printfooter();
	}
	
	function printfooter() {
		echo '<button onclick="javascript:history.go(-1)">กลับ</button>';
	}
?>
