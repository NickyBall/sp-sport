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

	$filename = $_GET["filename"];

	if ($login->check_master($username_s, $logged_in) == false) {
            //echo $username_s . $logged_in . '</br>';
            echo 'Permission denied!<br/>';
            printfooter();
    } else {
		if (unlink("uploads/".$filename)) {
			echo "ลบไฟล์สำเร็จแล้ว<br/>";
		} else {
			echo "เกิดข้อผิดพลาดในการลบไฟล์<br/>";
		}
		printfooter();
	}

	function printfooter() {
    echo '<a href="index.php?backfilelist=1">กลับ</a>';
    include 'footer.php';
    exit();
	}
?>
