<?php

include 'class.login.php';
$login = new logmein();

include 'class.log.php';
$log = new createlog();

include 'header.php';
echo '<div id="content">';

$timestamp = date('Y-m-d H:i:s', time());

//Validate input
if (isset($_REQUEST['action'])) {
    if (!is_string($_REQUEST['action'])) {
        $action = '';
        exit();
    }
    $action = (string) $_REQUEST['action'];
}

if (isset($_REQUEST['new_username'])) {
    if (!is_string($_REQUEST['new_username'])) {
        break;
    }
    $new_username = filter_input(INPUT_POST, 'new_username', FILTER_SANITIZE_STRING, FILTER_SANITIZE_SPECIAL_CHARS);
}
if (isset($_REQUEST['username'])) {
    if (!is_string($_REQUEST['username'])) {
        break;
    }
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING, FILTER_SANITIZE_SPECIAL_CHARS);
}
if (isset($_REQUEST['password'])) {
    if (!is_string($_REQUEST['password'])) {
        break;
    }
    $password = (string) $_REQUEST['password'];
}
if (isset($_REQUEST['confirm_password'])) {
    if (!is_string($_REQUEST['confirm_password'])) {
        break;
    }
    $confirm_password = (string) $_REQUEST['confirm_password'];
}
if (isset($_REQUEST['old_password'])) {
    if (!is_string($_REQUEST['old_password'])) {
        break;
    }
    $old_password = (string) $_REQUEST['old_password'];
}
if (isset($_REQUEST['new_password'])) {
    if (!is_string($_REQUEST['new_password'])) {
        break;
    }
    $new_password = (string) $_REQUEST['new_password'];
}
if (isset($_REQUEST['confirm_new_password'])) {
    if (!is_string($_REQUEST['confirm_new_password'])) {
        break;
    }
    $confirm_new_password = (string) $_REQUEST['confirm_new_password'];
} else {
    $confirm_new_password = false;
}

if (!isset($_REQUEST['slipno1'])) {
	/*
	if (!is_string($_REQUEST['slipno1'])) {
		break;
	}
	*/
} else {
	$slipno1 = (string) $_REQUEST['slipno1'];
}
if (!isset($_REQUEST['teamcode1'])) {
	/*
	if (!is_string($_REQUEST['teamcode1'])) {
		break;
	}
	*/
} else {
	$teamcode1 = (string) $_REQUEST['teamcode1'];
}
if (!isset($_REQUEST['price1'])) {
	/*
	if (!is_string($_REQUEST['price1'])) {
		break;
	}*/
} else {
	$price1 = (string) $_REQUEST['price1'];
}

if (!isset($_REQUEST['slipno2'])) {
	/*
	if (!is_string($_REQUEST['slipno2'])) {
		break;
	}
	*/
} else {
	$slipno2 = (string) $_REQUEST['slipno2'];
}
if (!isset($_REQUEST['teamcode2'])) {
	/*
	if (!is_string($_REQUEST['teamcode2'])) {
		break;
	}*/
} else {
	$teamcode2 = (string) $_REQUEST['teamcode2'];
}
if (!isset($_REQUEST['price2'])) {
	/*
	if (!is_string($_REQUEST['price2'])) {
		break;
	}
	*/
} else {
	$price2 = (string) $_REQUEST['price2'];
}

if (!isset($_REQUEST['slipno3'])) {
	/*
	if (!is_string($_REQUEST['slipno3'])) {
		break;
	}*/
} else {
	$slipno3 = (string) $_REQUEST['slipno3'];
}
if (!isset($_REQUEST['teamcode3'])) {
	/*
	if (!is_string($_REQUEST['teamcode3'])) {
		break;
	}*/
} else {
	$teamcode3 = (string) $_REQUEST['teamcode3'];
}
if (!isset($_REQUEST['price3'])) {
	/*
	if (!is_string($_REQUEST['price3'])) {
		break;
	}*/
} else {
	$price3 = (string) $_REQUEST['price3'];
}

if (!isset($_REQUEST['slipno4'])) {
	/*
	if (!is_string($_REQUEST['slipno4'])) {
		break;
	}*/
} else {
	$slipno4 = (string) $_REQUEST['slipno4'];
}
if (!isset($_REQUEST['teamcode4'])) {
	/*
	if (!is_string($_REQUEST['teamcode4'])) {
		break;
	}
	*/
} else {
	$teamcode4 = (string) $_REQUEST['teamcode4'];
}
if (!isset($_REQUEST['price4'])) {
	/*
	if (!is_string($_REQUEST['price4'])) {
		break;
	}*/
} else {
	$price4 = (string) $_REQUEST['price4'];
}

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
//Reject when user access this page directly
if ($action == '') {
    echo 'Permission denied!<br/>';
    printfooter();
} else {
	//echo $action;
    if ($action == 'register') {
        //Only agent can create new user
        if ($login->check_agent($username_s, $logged_in) == false && $login->check_master($username_s, $logged_in) == false) {
            //echo $username_s . $logged_in . '</br>';
            echo 'Permission denied!<br/>';
            printfooter();
        } else {
			if ($login->check_agent($username_s, $logged_in)) $user_type = 'member';
			else if ($login->check_master($username_s, $logged_in)) $user_type = 'agent';
			$poweruser = $username_s;
            if (check_input($new_username, $password) == false) {
                printfooter();
            } else {
                if ($login->check_username($new_username) == true) {
                    echo 'ชื่อผู้ใช้นี้มีอยู่แล้ว.<br/>';
                    printfooter();
                } elseif ($password != $confirm_password) {
                    echo 'รหัสผ่านไม่ตรงกัน<br/>';
                    printfooter();
                } else {
                    if ($login->register($new_username, $password, $user_type, $timestamp, $poweruser) == true) {
                        echo 'การลงทะเบียนเรียบร้อย<br/>';
                        //printfooter();
                        echo '<a href="index.php?registermore=1">ลงทะเบียนเพิ่มอีก</a>';
                        echo '</div>';
                        include 'footer.php';
                        exit();
                    } else {
                        echo 'การลงทะเบียนล้มเหลว!<br/>';
                        printfooter();
                    }
                }
            }
        }
    } elseif ($action == 'insertdata') {
		//echo $username_s." ".$slipno1." ".$teamcode1." ".$price1." ".$timestamp;
		$limit_qry = $login->qry("SELECT * FROM limit_time WHERE limit_id = 1");
		$limit_fet = mysql_fetch_array($limit_qry);
		
		$limit_from = (int) substr($limit_fet["limit_from"], 0, 2);
		$limit_to = (int) substr($limit_fet["limit_to"], 0, 2);
		$cur_hour = (int) date('H');
		$insertable = false;
		
		echo $limit_from." ".$limit_to." ".$cur_hour."<br/>";
		
		if ($limit_from < $limit_to) {
			if ($cur_hour < $limit_to && $cur_hour >= $limit_from) {
				$insertable = false;
			} else {
				$insertable = true;
			}
		} else if ($limit_from > $limit_to) {
			if ($cur_hour < $limit_to || $cur_hour >= $limit_from) {
				$insertable = false;
			} else {
				$insertable = true;
			}
		} else if ($limit_from == $limit_to) {
			$insertable = true;
		}
		if ($insertable) {
			$mindot = 2;
			$stepi_qry = $login->qry("SELECT step2, step1 FROM user_account WHERE username = '?'", $username_s);
			$stepi_fet = mysql_fetch_array($stepi_qry);
			$stepi_result = $stepi_fet['step2'];
			$stepi_result1 = $stepi_fet['step1'];
			if ($stepi_result == 'yes') {
				$mindot = 1;
			}
			if ($stepi_result1 == 'yes') {
				$mindot = 0;
			}
			if (date('H') < 12) {
				$today = date("Y")."-".date("m")."-".(date("d")-1);
				$tomorrow = date("Y")."-".date("m")."-".date("d");
			} else {
				$today = date("Y")."-".date("m")."-".date("d");
				$tomorrow = date("Y")."-".date("m")."-".(date("d")+1);
			}
			$sum_qry = $login->qry("SELECT SUM(price) FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, $today." 12:00:01", $tomorrow." 12:00:00");
			$sum_fet = mysql_fetch_array($sum_qry);
			$cur_price = $sum_fet[0];
			//echo $cur_price."<br/>";
			$sum_price = $price1 + $price2 + $price3 + $price4;
			$chk_price = $sum_price + $cur_price;
			//echo $chk_price;
			//echo $sum_price;
			$limit_qry = $login->qry("SELECT limit_price FROM user_account WHERE username = '?'", $username_s);
			$limit_fet = mysql_fetch_array($limit_qry);
			$limit_price = $limit_fet['limit_price'];
			//echo $limit_price;
			if ($limit_price == 0 || $chk_price <= $limit_price) {
				if ($slipno1 != "" && $teamcode1 != "" && $price1 != "") {
					if (substr_count($teamcode1,".") < $mindot || substr_count($teamcode1,".") > 9) {
										echo "&#3619;&#3634;&#3618;&#3585;&#3634;&#3619;&#3607;&#3637;&#3656; 1 &#3592;&#3635;&#3609;&#3623;&#3609;&#3607;&#3637;&#3617;&#3612;&#3636;&#3604;&#3614;&#3621;&#3634;&#3604;<br/>";
								} else {
					if ($login->insertData($username_s, $slipno1, $teamcode1, $price1, $timestamp)) {
						echo "รายการที่ 1 บันทึกเสร็จเรียบร้อย<br/>";
					} else {
						echo "รายการที่ 1 บันทึกล้มเหลว กรุณาลองใหม่อีกครั้ง<br/>";
						printfooter();
					}
								}
				}
				if ($slipno2 != "" && $teamcode2 != "" && $price2 != "") {
								if (substr_count($teamcode2,".") < $mindot || substr_count($teamcode2,".") > 9) {
										echo "&#3619;&#3634;&#3618;&#3585;&#3634;&#3619;&#3607;&#3637;&#3656; 2 &#3592;&#3635;&#3609;&#3623;&#3609;&#3607;&#3637;&#3617;&#3612;&#3636;&#3604;&#3614;&#3621;&#3634;&#3604;<br/>";
								} else {
					if ($login->insertData($username_s, $slipno2, $teamcode2, $price2, $timestamp)) {
						echo "รายการที่ 2 บันทึกเสร็จเรียบร้อย<br/>";
					} else {
						echo "รายการที่ 2 บันทึกล้มเหลว กรุณาลองใหม่อีกครั้ง<br/>";
						printfooter();
					}
								}
				}
				if ($slipno3 != "" && $teamcode3 != "" && $price3 != "") {
								if (substr_count($teamcode3,".") < $mindot || substr_count($teamcode3,".") > 9) {
										echo "&#3619;&#3634;&#3618;&#3585;&#3634;&#3619;&#3607;&#3637;&#3656; 1 &#3592;&#3635;&#3609;&#3623;&#3609;&#3607;&#3637;&#3617;&#3612;&#3636;&#3604;&#3614;&#3621;&#3634;&#3604;<br/>";
								} else {
					if ($login->insertData($username_s, $slipno3, $teamcode3, $price3, $timestamp)) {
						echo "รายการที่ 3 บันทึกเสร็จเรียบร้อย<br/>";
					} else {
						echo "รายการที่ 3 บันทึกล้มเหลว กรุณาลองใหม่อีกครั้ง<br/>";
						printfooter();
					}
								}
				}
				if ($slipno4 != "" && $teamcode4 != "" && $price4 != "") {
								if (substr_count($teamcode4,".") < $mindot || substr_count($teamcode4,".") > 9) {
										echo "&#3619;&#3634;&#3618;&#3585;&#3634;&#3619;&#3607;&#3637;&#3656; 4 &#3592;&#3635;&#3609;&#3623;&#3609;&#3607;&#3637;&#3617;&#3612;&#3636;&#3604;&#3614;&#3621;&#3634;&#3604;<br/>";
								} else {
					if ($login->insertData($username_s, $slipno4, $teamcode4, $price4, $timestamp)) {
						echo "รายการที่ 4 บันทึกเสร็จเรียบร้อย<br/>";
					} else {
						echo "รายการที่ 4 บันทึกล้มเหลว กรุณาลองใหม่อีกครั้ง<br/>";
						printfooter();
					}
								}
				}
			} else {
				echo "คุณแทงเกินวงเงินที่กำหนด<br/>";
				printfooter();
			}
			echo "บันทึกข้อมูลเรียบร้อย<br/>";
			echo '<a href="index.php">บันทึกข้อมูลเพิ่มอีก</a>';
			echo '</div>';
			include 'footer.php';
			exit();
		} else {
			echo "ไม่อยู่ในช่วงเวลาแทง<br/>";
			printfooter();
		}
	}	elseif ($action == 'login') {
        if (check_input($username, $password) == false) {
            //Create log
			echo "username: ".$username." Password: ".$password."\n";
            $log->log_login($timestamp, $username, 'fail');
            printfooter();
        } else {
            if ($login->login($username, $password) == true) {
                $login_status = 'success';
                echo '
                    <script language="javascript">
                        window.location = "index.php";
                    </script>
                    ';
            } else {
                $login_status = 'fail';
				/*
                if ($login->check_blocked($username) == true) {
                    echo 'ผู้ใช้นี้ถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ<br/>';
                } else {
                    echo 'ชื่อผู้ใช้หรือรหัสผ่านผิดพลาด<br/>';
                }
				*/
            }
            //Create log
            $log->log_login($timestamp, $username, $login_status);

            printfooter();
        }
    } elseif ($action == 'edit_account') {
        //Check old password
        if ($login->check_password($username_s, $old_password) == false) {
            echo "รหัสผ่านเดิมไม่ถูกต้อง<br/>";
            printfooter();
        } else {
            //Change password
            if ($new_password != $confirm_new_password) {
                echo 'รหัสผ่านใหม่ไม่ตรงกัน<br/>';
                printfooter();
            } elseif ($new_password != '') {
                if (strlen($new_password) < 4 OR strlen($new_password) > 30) {
                    echo 'รหัสผ่านต้องมีความยาวไม่น้อยกว่า 4 ตัวอักษร<br/>';
                    printfooter();
                } elseif ($login->change_password($new_password, $username) == true) {
                    echo "เปลี่ยนรหัสผ่านเรียบร้อยแล้ว<br/>";
                } else {
                    echo 'ไม่สามารถเปลี่ยนรหัสผ่านได้<br/>';
                }
            } else {
                echo 'รหัสผ่านไม่มีการเปลี่ยนแปลง<br/>';
            }
            printfooter();
        }
    } elseif ($action == "resetpassword") {
        //Only admin can reset user's password
        if ($login->check_agent($username_s, $logged_in) == false) {
            echo 'Permission denied!<br/>';
            printfooter();
        } elseif (check_input($username_s, $password) == false) {
            printfooter();
        } elseif (strlen($password) < 4) {
            echo 'รหัสผ่านต้องมีความยาวไม่น้อยกว่า 4 ตัวอักษร';
            printfooter();
        } elseif ($login->change_password($password, $username_s) == true) {
            echo 'รีเซ็ตรหัสผ่านของ ' . $username . " เรียบร้อยแล้ว<br/>";
        } else {
            echo 'รีเซ็ตรหัสผ่านไม่ได้<br/>';
        }
    } else {
        echo 'Invalid request.<br/>';
    }
    
    printfooter();
}

function printfooter() {
    echo '<a href="index.php">กลับ</a>';
    echo '</div>';
    include 'footer.php';
    exit();
}

function check_input($username, $password) {
    if ($username == '' && $password == '') {
        echo 'กรุณาใส่ชื่อผู้ใช้และรหัสผ่าน<br/>';
        return false;
    } elseif ($username == '') {
        echo 'กรุณาใส่ชื่อผู้ใช้<br/>';
        return false;
    } elseif ($password == '') {
        echo 'กรุณาใส่รหัสผ่าน<br/>';
        return false;
    } elseif (strlen($username) > 30 OR strlen($password) > 30) {
        echo 'ชื่อผู้ใช้หรือรหัสผ่านผิดพลาด<br/>';
        return false;
    } elseif (!preg_match("/^[a-zA-Z0-9.]+.+[a-zA-Z0-9]$/", $username)) {
        //Username allowed only alphanumeric and dot
        echo 'ชื่อผู้ใช้หรือรหัสผ่านผิดพลาด<br/>';
        return false;
    } else {
        return true;
    }
}

?>
