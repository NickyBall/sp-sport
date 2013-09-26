<?php
	// Author : Mr. Jakkrit Junrat

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
	
	$new_limit = $_GET["newlimit"];
	$userid = $_GET["id"];
	
	if ($login->check_agent($username_s, $logged_in) == false && $login->check_master($username_s, $logged_in) == false) {
            //echo $username_s . $logged_in . '</br>';
            echo 'Permission denied!<br/>';
            printfooter();
    } else {
		if ($login->qry("UPDATE user_account SET limit_price = '?' WHERE account_id = '?'",$new_limit, $userid)) {
			echo "เปลี่ยนแปลงการจำกัดเงินสำเร็จ<br/>";
			printfooter();
		} else {
			echo "มีข้อผิดพลาดเกิดขึ้น กรุณาลองใหม่อีกครั้ง<br/>";
			printfooter();
		}
	}
	
	function printfooter() {
    echo '<a href="javascript:history.go(-1)">กลับ</a>';
    include 'footer.php';
    exit();
	}
?>
