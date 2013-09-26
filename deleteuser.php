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

	$userd = $_GET["userd"];

	if ($login->check_agent($username_s, $logged_in) == false && $login->check_master($username_s, $logged_in) == false) {
            //echo $username_s . $logged_in . '</br>';
            echo 'Permission denied!<br/>';
            printfooter();
    } else {
		$canDel = false;
		$del_qry1 = $login->qry("SELECT * FROM user_account WHERE account_id = '?'", $userd);
		$del_fetch1 = mysql_fetch_array($del_qry1);
		if ($del_fetch1['power_user'] != $username_s && $login->check_master($username_s, $logged_in) == false) {
			echo "You don't have permission to delete this user.<br/>";
			printfooter();
		} else {
			if ($del_fetch1['user_type'] == 'agent') {
				if ($login->qry("DELETE FROM user_account WHERE power_user = '?'", $userd)) {
					echo "Hello World<br/>";
				}
			}
			if ($login->qry("DELETE FROM user_account WHERE account_id = '?'", $userd)) {
				echo 'ลบผู้ใช้สำเร็จแล้ว<br/>';
				printfooter();
			} else {
				echo 'เกิดข้อผิดพลาดกรุณาทดสอบใหม่อีกครั้ง<br/>';
				printfooter();
			}
		}
	}

	function printfooter() {
    echo '<a href="javascript:history.go(-1)">กลับ</a>';
    include 'footer.php';
    exit();
	}
?>
