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

	if ($login->check_agent($username_s, $logged_in) == false && $login->check_master($username_s, $logged_in) == false) {
            //echo $username_s . $logged_in . '</br>';
            echo 'Permission denied!<br/>';
            printfooter();
    } else {
		$slip_id = $_GET['slipid'];
		if ($slip_id == '') {
			echo "เกิดข้อผิดพลาด<br/>";
			printfooter();
		} else {
			if ($login->qry("DELETE FROM slip_insert WHERE slip_id = '?'",$slip_id)) {
				echo "ลบข้อมูลเรียบร้อยแล้ว<br/>";
			} else {
				echo "เกิดข้อผิดพลาดการลบ กรุณาทดสอบใหม่อีกครัง<br/>";
			}
			printfooter();
		}
	}

	function printfooter() {
    echo '<a href="javascript:history.go(-1)">กลับ</a>';
    include 'footer.php';
    exit();
	}
?>
