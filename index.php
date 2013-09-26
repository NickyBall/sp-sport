<?php
include 'class.login.php';
$login = new logmein();

include 'header.php';

//get class into the page
require('tc_calendar.php');

//Validate input
if (isset($_SESSION['logged_in'])) {
    if (!is_string($_SESSION['logged_in'])) {
        break;
    }
    $logged_in = (string) $_SESSION['logged_in'];
} else {
    $logged_in = false;
}
if (isset($_SESSION['username'])) {
    if (!is_string($_SESSION['username'])) {
        break;
    }
    $username_s = (string) $_SESSION['username'];
} else {
    $username_s = false;
}
if (isset($_REQUEST['registermore']) && strlen($_REQUEST['registermore']) == 1) {
    $registermore = (int) $_REQUEST['registermore'];
} else {
    $registermore = false;
}
if (isset($_REQUEST['backdetail']) && strlen($_REQUEST['backdetail']) == 1) {
    $backdetail = (int) $_REQUEST['backdetail'];
} else {
    $backdetail = false;
}
if (isset($_REQUEST['backfilelist']) && strlen($_REQUEST['backfilelist']) == 1) {
    $backfilelist = (int) $_REQUEST['backfilelist'];
} else {
    $backfilelist = false;
}
if (isset($_REQUEST['mdetail']) && strlen($_REQUEST['mdetail']) == 1) {
    $mdetail = (int) $_REQUEST['mdetail'];
} else {
    $mdetail = false;
}
?>
<div id="content">
    <ul class="tabs">
        <li><a href="#">ผู้ใช้</a></li>
        <?php
        if ($login->check_logged_in($logged_in, $username_s) == true) {
            echo '
                <li><a href="#">บันทึกรายการ</a></li>
				<li><a href="#">ดาวน์โหลดใบบอล</a></li>
				<li><a href="#">รายการที่ส่งในวันนี้</a></li>
                ';
        }

        if ($login->check_agent($username_s, $logged_in) || $login->check_master($username_s, $logged_in)) {
            echo '
                    <li><a href="#">เพิ่มผู้ใช้</a></li>
                    <li><a href="#">รายการของแต่ละผู้ใช้</a></li>
                    ';
        }
		if ($login->check_master($username_s, $logged_in)) {
            echo '
					<li><a href="#">อัพโหลดใบบอล</a></li>
                    ';
        }
        ?>
    </ul>

    <div class="panes">
        <div id="login">
            <?php
            if ($login->check_logged_in($logged_in, $username_s) == false) {
                echo '
                    <form name="login" method="post" enctype="application/x-www-form-urlencoded" action="authen.php">
                    <table>
                        <tr>
                            <td>ชื่อผู้ใช้ : </td><td><input name="username" type="text" maxlength="30" size="30" style="width: 250px;"/></td>
                        </tr>
                        <tr>
                            <td>รหัสผ่าน : </td><td><input name="password" type="password" maxlength="30" size="30" style="width: 250px;"/></td>
                        </tr>
                    </table>
                        <input name="action" value="login" type="hidden"/>
                        <input name="submit" value="เข้าสู่ระบบ" type="submit"/>
                    </form>
                    ';
            } else {
                echo '<input type="button" onClick="parent.location=';
                echo "'logout.php'" . '" value="ออกจากระบบ"/><br/><br/><hr/>';
                $result = $login->qry("SELECT * FROM user_account WHERE username = '?';", $username_s);
                $row = mysql_fetch_array($result);
                if ($row == 'Error') {
                    echo "Error!";
                } else {
                    echo '
                <form id="edit_account" name="edit_account" action="authen.php" method="post">
                    <table>
                        <tr>
                            <td>ชื่อผู้ใช้ : </td><td>' . $row['username'] . '</td>
                            <input name="username" value="' . $row['username'] . '" type="hidden"/><br/>
                        </tr>
                        <tr>
                            <td>รหัสผ่านเดิม : </td><td><input type="password" id="old_password" name="old_password" maxlength="30" style="width: 250px;" onkeyup="edit_profile();"/></td>
                        </tr>
                        <tr>
                            <td>รหัสผ่านใหม่ : </td><td><input type="password" id="new_password" name="new_password" maxlength="30" style="width: 250px;" disabled="true" onkeyup="edit_profile();"/></td>
                        </tr>
                        <tr>
                            <td></td><td><small><font color="Red">รหัสผ่านต้องมีความยาวไม่น้อยกว่า 4 ตัวอักษร</font></small></td>
                        </tr>
                        <tr>
                            <td>ยืนยันรหัสผ่านใหม่ :</td><td><input type="password" id="confirm_new_password" name="confirm_new_password" maxlength="30" style="width: 250px;" disabled="true" onkeyup="edit_profile();"/></td>
                        </tr>
                        <tr>
							<td>ขอบเขตวงเงินแทง :</td><td>'.$row['limit_price'].' (0 คือ ไม่จำกัด)</td>
                        </tr>
                        <tr>
							<td>การแทงสเตป 2 :</td><td>'.$row['step2'].'</td>
                        </tr>
                        <tr>
							<td>การแทงสเตป 1 :</td><td>'.$row['step1'].'</td>
                        </tr>
                    </table>
                <input name="action" value="edit_account" type="hidden"/><br/>
                <input id="btn_edit_profile" type="submit" value="แก้ไขข้อมูลผู้ใช้" disabled="true" />
                </form>
                    <script language="javascript">
                        function edit_profile()
                        {
                            if (document.getElementById("old_password").value.length == 0)
                            {
                                document.getElementById("btn_edit_profile").disabled = true;
                                document.getElementById("new_password").disabled = true;
                                document.getElementById("confirm_new_password").disabled = true;
                            } else {
                                document.getElementById("btn_edit_profile").disabled = false;
                                document.getElementById("new_password").disabled = false;
                            }

                            if (document.getElementById("new_password").value.length == 0)
                            {
                                document.getElementById("confirm_new_password").disabled = true;
                            } else {
                                document.getElementById("confirm_new_password").disabled = false;
                                if (document.getElementById("new_password").value != document.getElementById("confirm_new_password").value) {
                                    document.getElementById("btn_edit_profile").disabled = true;
                                } else {
                                    document.getElementById("btn_edit_profile").disabled = false;
                                }
                            }
                        }
                    </script>
                ';
                }
            }
            ?>
        </div>
		<?php
			if ($login->check_logged_in($logged_in, $username_s) == true) {
				echo '
						
					<div id = "insertdata" class="CSSTableGenerator">
						<form name = "insertdata" method = "post" enctype = "application/x-www-form-urlencoded" action="authen.php">
							<table>
								<tr>
									<td>สลิปเลขที่</td><td>รายการแทง</td><td>ราคา</td>
								</tr>
									<td style="text-align: center;"><input id = "slipno1" name = "slipno1" type="text" maxlength = "30" size = "30""></td>
									<td style="text-align: center;"><input id = "teamcode1" name = "teamcode1" type="text" maxlength = "50" size = "60""></td>
									<td style="text-align: center;"><input id = "price1" name = "price1" type="text" maxlength = "30" onkeyup = "chkPrice()" size = "30""></td>
								<tr>
									<td style="text-align: center;"><input id = "slipno2" name = "slipno2" type="text" maxlength = "30" size = "30""></td>
									<td style="text-align: center;"><input id = "teamcode2" name = "teamcode2" type="text" maxlength = "50" size = "60""></td>
									<td style="text-align: center;"><input id = "price2" name = "price2" type="text" maxlength = "30" onkeyup = "chkPrice()" size = "30""></td>
								</tr>
								<tr>
									<td style="text-align: center;"><input id = "slipno3" name = "slipno3" type="text" maxlength = "30" size = "30""></td>
									<td style="text-align: center;"><input id = "teamcode3" name = "teamcode3" type="text" maxlength = "50" size = "60""></td>
									<td style="text-align: center;"><input id = "price3" name = "price3" type="text" maxlength = "30" onkeyup = "chkPrice()" size = "30""></td>
								</tr>
								<tr>
									<td style="text-align: center;"><input id = "slipno4" name = "slipno4" type="text" maxlength = "30" size = "30""></td>
									<td style="text-align: center;"><input id = "teamcode4" name = "teamcode4" type="text" maxlength = "50" size = "60""></td>
									<td style="text-align: center;"><input id = "price4" name = "price4" type="text" maxlength = "30" onkeyup = "chkPrice()" size = "30""></td>
								</tr>
							</table>
							<center><font color="red">*หากในแถวใดกรอกข้อมูลไม่ครบ จะไม่ได้รับการบันทึก*</font></center>
						<input name="action" value="insertdata" type="hidden"/><br/>
                        <center><input id="btn_insertdata" name="submit" value="ส่งข้อมูล" type="submit" /></center>
                        
						</form>
						
						<script language="javascript">
                            function chkinsert()
                            {
                                if ((document.getElementById("slipno").value.length != 0) && (document.getElementById("teamcode").value.length != 0) && (document.getElementById("price").value.length != 0))
                                {
                                    document.getElementById("btn_insertdata").disabled = false;
                                } else {
                                    document.getElementById("btn_insertdata").disabled = true;
                                }
                            }
                            function chkPrice() {
								
							}
                        </script>
					</div>
				';
				echo '
						<div id = "downloadstep" class="CSSTableGenerator">
							<table>
								<tr>
									<td>ไฟล์ใบบอล</td>
							';
							if ($login->check_agent($username_s, $logged_in)) {
								$isAgent = true;
							}
							if ($login->check_master($username_s, $logged_in)) {
								$isMaster = true;
							}
							if ($isMaster) {
					echo '
									<td>#</td>
					';
							}
							echo '
								</tr>
							';
							$objScan = scandir("uploads", 1);
							foreach ($objScan as $value) {
								if ($value == ".." || $value == ".") continue;
								else {
					echo '
							<tr>
								<td><a href = "uploads/'.$value.'">'.$value.'</a></td>
					';
							if ($isMaster) {
					echo '
							<td style = "text-align: center">[<a href = "remove.php?filename='.$value.'">ลบ</a>]</td>
					';
							}
					echo '		
							</tr>
					';
								}
							}
					echo '
						</table></div>
					';
				echo '
					<div id = "showdetail" class="CSSTableGenerator">';

					$cYear = date('Y');
					$cMonth = date('m');
					$cDay = date('d');

					if (date('H') < 12) {
						if ($cDay == "01") {
							$cDay = $cLastDay;
							if ($cMonth == "01") $cMonth = "12";
							elseif ($cMonth == "02") $cMonth = "01";
							elseif ($cMonth == "03") $cMonth = "02";
							elseif ($cMonth == "04") $cMonth = "03";
							elseif ($cMonth == "05") $cMonth = "04";
							elseif ($cMonth == "06") $cMonth = "05";
							elseif ($cMonth == "07") $cMonth = "06";
							elseif ($cMonth == "08") $cMonth = "07";
							elseif ($cMonth == "09") $cMonth = "08";
							elseif ($cMonth == "10") $cMonth = "09";
							elseif ($cMonth == "11") $cMonth = "10";
							elseif ($cMonth == "12") $cMonth = "11";
						} else {
							(int) $cDay -= 1;
						}
					}

					if ($cMonth == "01" || $cMonth == "03" || $cMonth == "05" || $cMonth == "07" || $cMonth == "08" || $cMonth == "10" || $cMonth == "12") {
						$cLastDay = "31";
					} elseif ($cMonth == "04" || $cMonth == "06" || $cMonth == "09" || $cMonth == "11"){
						$cLastDay = "30";
					} elseif ($cMonth == "02") {
						if (((int)$cYear % 4) == 0) $cLastDay = 29;
						else $cLastDay = 28;
					}
					if ($cDay == $cLastDay) {
						$cNextDay = "01";
						if ($cMonth == "01") $cNextMonth = "02";
						elseif ($cMonth == "02") $cNextMonth = "03";
						elseif ($cMonth == "03") $cNextMonth = "04";
						elseif ($cMonth == "04") $cNextMonth = "05";
						elseif ($cMonth == "05") $cNextMonth = "06";
						elseif ($cMonth == "06") $cNextMonth = "07";
						elseif ($cMonth == "07") $cNextMonth = "08";
						elseif ($cMonth == "08") $cNextMonth = "09";
						elseif ($cMonth == "09") $cNextMonth = "10";
						elseif ($cMonth == "10") $cNextMonth = "11";
						elseif ($cMonth == "11") $cNextMonth = "12";
						elseif ($cMonth == "12") $cNextMonth = "01";
						if ($cMonth == "12") $cNextYear = (int) $cYear + 1;
						else $cNextYear = $cYear;
					} else {
						$cNextDay = (int) $cDay + 1;
					}

					//echo $cLastDay." ".$cDay." ".$cNextDay." ".$cMonth." ".$cNextMonth;
					$round_time1 = null; $round_time2 = null; $round_time3 = null;
					$round1_query = $login->qry("SELECT * FROM round_time WHERE round_date = '?' AND round_no = 'round1'", $cYear."-".$cMonth."-".$cDay);
					$round2_query = $login->qry("SELECT * FROM round_time WHERE round_date = '?' AND round_no = 'round2'", $cYear."-".$cMonth."-".$cDay);
					$round3_query = $login->qry("SELECT * FROM round_time WHERE round_date = '?' AND round_no = 'round3'", $cYear."-".$cMonth."-".$cDay);

					if (mysql_num_rows($round1_query) != 0) {
						$round1_result = mysql_fetch_array($round1_query);
						$round_time1 = $round1_result["round_time"];
					} else {
						$round_time1 = "12:00:01";
					}
					if (mysql_num_rows($round2_query) != 0) {
						$round2_result = mysql_fetch_array($round2_query);
						$round_time2 = $round2_result["round_time"];
					} else {
						//$round_time2 = "12:00:00";
					}
					if (mysql_num_rows($round3_query) != 0) {
						$round3_result = mysql_fetch_array($round3_query);
						$round_time3 = $round3_result["round_time"];
					} else {
						//$round_time3 = "12:00:00";
					}
					//echo "r1:".$round_time1.", r2: ".$round_time2.", r3: ".$round_time3;

					$query1 = ""; $query2 = ""; $query3 = "";
					if ($round_time2 == "") {
						if ($cDay == $cLastDay) {
							$query1 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
							"".$cYear."-".$cMonth."-".$cDay." ".$round_time1, "".$cNextYear."-".$cNextMonth."-".$cNextDay." 12:00:00");
						} else {
							$query1 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
							"".$cYear."-".$cMonth."-".$cDay." ".$round_time1, "".$cYear."-".$cMonth."-".$cNextDay." 12:00:00");
						}
					} else {
						$query1 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
							"".$cYear."-".$cMonth."-".$cDay." ".$round_time1, "".$cYear."-".$cMonth."-".$cDay." ".$round_time2);
						if ($round_time3 == "") {
							if ($cDay == $cLastDay) {
								$query2 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
								"".$cYear."-".$cMonth."-".$cDay." ".$round_time2, "".$cNextYear."-".$cNextMonth."-".$cNextDay." 12:00:00");
							} else {
								$query2 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
							"".$cYear."-".$cMonth."-".$cDay." ".$round_time2, "".$cYear."-".$cMonth."-".$cNextDay." 12:00:00");
							}
						} else {
							$query2 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
						"".$cYear."-".$cMonth."-".$cDay." ".$round_time2, "".$cYear."-".$cMonth."-".$cDay." ".$round_time3);
							if ($cDay == $cLastDay) {
								$query3 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
								"".$cYear."-".$cMonth."-".$cDay." ".$round_time3, "".$cNextYear."-".$cNextMonth."-".$cNextDay." 12:00:00");
							} else {
								$query3 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $username_s, 
							"".$cYear."-".$cMonth."-".$cDay." ".$round_time3, "".$cYear."-".$cMonth."-".$cNextDay." 12:00:00");
							}
						}
					}
						$number = 0;
						//echo $cDay." ".$cNextDay;
						if (mysql_num_rows($query1) != 0) {
				echo '
							<center>
								User: '.$username_s.'<br/>
								<br/>
								<center>รอบที่ 1 เริ่มเวลา '.$round_time1.'</center>
							<table>
							<tr>
								<td>
									สลิปเลขที่
								</td>
								<td>
									รหัสทีม
								</td>
								<td>
									ราคา
								</td>
								<td>
									เวลา
								</td>
							</tr>
						';
						
						while ($detail = mysql_fetch_array($query1)) {
							$number += (int) $detail['price'];
					echo '
								<tr>
									<td style="width: 10%;">'.$detail['slip_no'].'</td>
									<td style="width: 50%;">'.$detail['teamcode'].'</td>
									<td style="text-align: right; width: 10%;">'.$detail['price'].'</td>
									<td style="text-align: center; width: 25%;">'.$detail['timestamp'].'</td>
								</tr>
					'; }
					echo '
							</table></center><br/>';
						}

						if ($query2 != "" && mysql_num_rows($query2) != 0) {
				echo '
							<center>
								User: '.$username_s.'<br/>
								<br/>
								<center>รอบที่ 2 เริ่มเวลา '.$round_time2.'</center>
							<table>
							<tr>
								<td>
									สลิปเลขที่
								</td>
								<td>
									รหัสทีม
								</td>
								<td>
									ราคา
								</td>
								<td>
									เวลา
								</td>
							</tr>
						';
						while ($detail = mysql_fetch_array($query2)) {
							$number += (int) $detail['price'];
					echo '
								<tr>
									<td style="width: 10%;">'.$detail['slip_no'].'</td>
									<td style="width: 50%;">'.$detail['teamcode'].'</td>
									<td style="text-align: right; width: 10%;">'.$detail['price'].'</td>
									<td style="text-align: center; width: 25%;">'.$detail['timestamp'].'</td>
								</tr>
					'; }
					echo '
							</table></center><br/>';
						}
						if ($query3 != "" && mysql_num_rows($query3) != 0) {
				echo '
							<center>
								User: '.$username_s.'<br/>
								<br/>
								<center>รอบที่ 3 เริ่มเวลา '.$round_time3.'</center>
							<table>
							<tr>
								<td>
									สลิปเลขที่
								</td>
								<td>
									รหัสทีม
								</td>
								<td>
									ราคา
								</td>
								<td>
									เวลา
								</td>
							</tr>
						';
						while ($detail = mysql_fetch_array($query3)) {
							$number += (int) $detail['price'];
					echo '
								<tr>
									<td style="width: 10%;">'.$detail['slip_no'].'</td>
									<td style="width: 50%;">'.$detail['teamcode'].'</td>
									<td style="text-align: right; width: 10%;">'.$detail['price'].'</td>
									<td style="text-align: center; width: 25%;">'.$detail['timestamp'].'</td>
								</tr>
					'; }
					echo '
							</table></center><br/>';
						}


					echo '
							
							<center>ยอดรวม  '.$number.' บาท</center>
							<center><a href = "datadetail.php?user='.$username_s.'">ปริ้นหรือดูรายการย้อนหลัง</a></center>
							<!-- <br/><center><a href = "print.php?userp='.$username_s.'">ปริ้น</a></center> -->
				';
					echo '
					</div>
				';
			}
			
			
		?>
        <?php
        if ($login->check_agent($username_s, $logged_in) || $login->check_master($username_s, $logged_in)) {
            echo '
                        <div id="register">
                            <form name="register" method="post" enctype="application/x-www-form-urlencoded" action="authen.php">
                                <table>
                                        ';
            echo '              <tr>
                                    <td>ชื่อผู้ใช้ : </td><td><input name="new_username" type="text" maxlength="30" size="30"/></td>
                                </tr>
                                <tr>
                                    <td>รหัสผ่าน : </td><td><input id="password" name="password" type="password" maxlength="30" size="30" onkeyup="chkpwd();"/></td>
                                </tr>
                                <tr>
                                    <td>ยืนยันรหัสผ่าน : </td>
                                    <td><input id="confirm_password" name="confirm_password" type="password" maxlength="30" size="30" onkeyup="chkpwd();"/></td>
                                </tr>
                           </table>
                        <input name="action" value="register" type="hidden"/><br/>
                        <input id="btn_register" name="submit" value="ลงทะเบียน" type="submit" disabled="true"/>
                        <script language="javascript">
                            function chkpwd()
                            {
                                if (document.getElementById("password").value.length == 0)
                                {
                                    document.getElementById("btn_register").disabled = true;
                                } else if (document.getElementById("password").value == document.getElementById("confirm_password").value)
                                {
                                    if (document.getElementById("password").value.length >= 4) {
                                        document.getElementById("btn_register").disabled = false;
                                    }
                                } else {
                                    document.getElementById("btn_register").disabled = true;
                                }
                            }
                        </script>
                    </form>
                </div>
                ';
            echo '
                <div id="data_detail" class="CSSTableGenerator">';
                if ($login->check_master($username_s, $logged_in)) {
					$result2 = $login->qry("SELECT * FROM user_account;");
				} else {
					$result2 = $login->qry("SELECT * FROM user_account WHERE power_user = '?';", $username_s);
				}
			echo '
                        <table>
							<tr>
								<td>ชื่อผู้ใช้</td>
								<td>ผู้สร้าง</td>
								<td>จำกัดเงินแทง (บาท) (0 คือ ไม่จำกัด)</td>
								<td>Step 2?</td>
								<td>Step 1?</td>
								<td>#</td>
							</tr>
						';
						while ($detail = mysql_fetch_array($result2)) {
							if ($detail['username'] != "pre_master") {
			echo '
                            <tr>
                                <td><a href = "datadetail.php?user='.$detail['username'].'&id='.$detail['account_id'].'">'.$detail['username'].'</a></td>
                                <td style="text-align:center;">'.$detail['power_user'].'</td>
                                <td style="text-align:center;">'.$detail['limit_price'].'</td>
                                <td style="text-align:center;">'.$detail['step2'].'</td>
                                <td style="text-align:center;">'.$detail['step1'].'</td>
				';
						if ($detail['user_type'] != "master") {
			echo '
								<td style = "text-align: center;"><a href = "deleteuser.php?userd='.$detail['account_id'].'">ลบ</a></td>
                            ';
						} else {
							echo '<td></td>';
						}
						echo '</tr>';
						}
						}
			echo '
                        </table>
                </div>
                ';
				if ($login->check_master($username_s, $logged_in)) {
			echo '
				<div id = "uploadstep">
					<form id="sendfile" name="sendfile" method="post" enctype="multipart/form-data" action="upload.php" onsubmit="return clickupload();" target="uploadtarget">
                            <label>
                                ไฟล์ใบบอลที่ต้องการอัพโหลด : <input type="file" name="inputfile" id="inputfile"/>
                            </label>
							<select id="round" name="round">
							  <option value="round1">รอบที่ 1</option>
							  <option value="round2">รอบที่ 2</option>
							  <option value="round3">รอบที่ 3</option>
							</select>
                            <input name="uploadbutton" id="uploadbutton" type="submit" value="Upload"/>
                            <br/>
                        </form>
				</div>
				<script language="javascript">
                        function clickupload()
                        {
                            if (document.getElementById("inputfile").value.length == 0)
                            {
                                alert("กรุณาเลือกไฟล์ใบบอลที่ต้องการอัพโหลด");
                                return false;
                            }

                            document.getElementById("uploadbutton").value = "Uploading...";
                            document.getElementById("uploadbutton").disabled = true;
                            return true;
                        }

                        function uploaded(result)
                        {
                            document.getElementById("inputfile").value ="";
                            document.getElementById("uploadbutton").value = "Upload";
                            document.getElementById("uploadbutton").disabled = false;
                            document.getElementById("showupload").innerHTML = result;
                            document.getElementById("sendfile").reset();
                            return true;
                        }
                </script>

                    <iframe id="uploadtarget" name="uploadtarget" src="" style="width:0px;height:0px;border:0"></iframe>
			';
				}
        }
        ?>
    </div>

    <script type="text/javascript">
        // perform JavaScript after the document is scriptable.
        $(function() {
            // setup ul.tabs to work as tabs for each div directly under div.panes
            $("ul.tabs").tabs("div.panes > div");
        });

        $(":date").dateinput({ format: "yyyy-mm-dd" })
    </script>

    <?php
    if ($login->check_logged_in($logged_in, $username_s) == true) {
        echo '
                <script type="text/javascript">
                    $(function() {
                        // get handle to the api (must have been constructed before this call)
                        var api = $("ul.tabs").data("tabs");
                        ';
        
        if ($registermore == '1') {
            echo 'api.next();';
            echo 'api.next();';
			echo 'api.next();';
            echo 'api.next();';
		} elseif ($backdetail == '1') {
			echo 'api.next();';
            echo 'api.next();';
			echo 'api.next();';
            echo 'api.next();';
			echo 'api.next();';
		} elseif ($backfilelist == '1') {
			echo 'api.next();';
            echo 'api.next();';
		} elseif ($mdetail == '1') {
			echo 'api.next();';
            echo 'api.next();';
			echo 'api.next();';
		} else {
            echo 'api.next();';
        }

        echo '
                    });
                </script>
                ';
    }
    ?>
</div>
<?php
include 'footer.php';
?>
