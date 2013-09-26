<?php
	include 'class.login.php';
	$login = new logmein();

	include 'header.php';

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
	$user = $_GET['user'];
	$GLOBAL['usert'] = $user;
	$id = $_GET['id'];
	$font_size = 14;
    if (($login->check_agent($username_s, $logged_in) == true) || ($username_s == $user) || $login->check_master($username_s, $logged_in) == true) {
		
		if (!isset($_POST['date5'])) {
			//echo date('d')." ".date('m')." ".date('Y');
			$sYear = date('Y');
			$sMonth = date('m');
			$sDay = date('d');
			if ($sMonth == "01" || $sMonth == "03" || $sMonth == "05" || $sMonth == "07" || $sMonth == "08" || $sMonth == "10" || $sMonth == "12") {
				$sLastDay = "31";
			} elseif ($sMonth == "04" || $sMonth == "06" || $sMonth == "09" || $sMonth == "11"){
				$sLastDay = "30";
			} elseif ($sMonth == "02") {
				if (((int)$sYear % 4) == 0) $sLastDay = 29;
				else $sLastDay = 28;
			}
			if ($sDay == date('d')) {
				if (date('H') < 12) {
					if ($sDay == "01") {
						$sDay = $sLastDay;
						if ($sMonth == "01") $sMonth = "12";
						elseif ($sMonth == "02") $sMonth = "01";
						elseif ($sMonth == "03") $sMonth = "02";
						elseif ($sMonth == "04") $sMonth = "03";
						elseif ($sMonth == "05") $sMonth = "04";
						elseif ($sMonth == "06") $sMonth = "05";
						elseif ($sMonth == "07") $sMonth = "06";
						elseif ($sMonth == "08") $sMonth = "07";
						elseif ($sMonth == "09") $sMonth = "08";
						elseif ($sMonth == "10") $sMonth = "09";
						elseif ($sMonth == "11") $sMonth = "10";
						elseif ($sMonth == "12") $sMonth = "11";
						if ($sMonth == "01" || $sMonth == "03" || $sMonth == "05" || $sMonth == "07" || $sMonth == "08" || $sMonth == "10" || $sMonth == "12") {
							$sLastDay = "31";
						} elseif ($sMonth == "04" || $sMonth == "06" || $sMonth == "09" || $sMonth == "11"){
							$sLastDay = "30";
						} elseif ($sMonth == "02") {
							if (((int)$sYear % 4) == 0) $sLastDay = 29;
							else $sLastDay = 28;
						}
						$sDay = $sLastDay;
					} else {
						(int) $sDay -= 1;
					}
				}
			}
		} else {
			$sDate = split("-", $_POST['date5']);
			$sYear = $sDate[0];
			$sMonth = $sDate[1];
			$sDay = $sDate[2];
		}
		
		
		if ($sDay == $sLastDay) {
			$sNextDay = "01";
			if ($sMonth == "01") $sNextMonth = "02";
			elseif ($sMonth == "02") $sNextMonth = "03";
			elseif ($sMonth == "03") $sNextMonth = "04";
			elseif ($sMonth == "04") $sNextMonth = "05";
			elseif ($sMonth == "05") $sNextMonth = "06";
			elseif ($sMonth == "06") $sNextMonth = "07";
			elseif ($sMonth == "07") $sNextMonth = "08";
			elseif ($sMonth == "08") $sNextMonth = "09";
			elseif ($sMonth == "09") $sNextMonth = "10";
			elseif ($sMonth == "10") $sNextMonth = "11";
			elseif ($sMonth == "11") $sNextMonth = "12";
			elseif ($sMonth == "12") $sNextMonth = "01";
			if ($sMonth == "12") $sNextYear = (int) $sYear + 1;
			else $sNextYear = $sYear;
		} else {
			$sNextDay = (int) $sDay + 1;
		}

		//echo $sLastDay." ".$sDay." ".$sNextDay." ".$sMonth." ".$sNextMonth;

		$isEmpty = 0;
		
		?>
		<?php
			echo '
			<form action="datadetail.php?user='.$user.'" method="post">
		';
		
			//get class into the page
			require('tc_calendar.php');

			$myCalendar = new tc_calendar("date5", true, false);
			$myCalendar->setIcon("images/iconCalendar.gif");
			$myCalendar->setDate(date('d'), date('m'), date('Y'));
			$myCalendar->setPath("./");
			$myCalendar->setYearInterval(2000, 2015);
			$myCalendar->dateAllow('2008-05-13', '2015-03-01');
			$myCalendar->setDateFormat('j F Y');
			$myCalendar->setAlignment('left', 'bottom');
			$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
			$myCalendar->setSpecificDate(array("2011-04-10", "2011-04-14"), 0, 'month');
			$myCalendar->setSpecificDate(array("2011-06-01"), 0, '');
			$myCalendar->writeScript();

			echo '
				<input name="submit" value="submit" type="submit"/>
			';
			?>
			</form>

		<?php
			$round_time1 = null; $round_time2 = null; $round_time3 = null;
			$round1_query = $login->qry("SELECT * FROM round_time WHERE round_date = '?' AND round_no = 'round1'", $sYear."-".$sMonth."-".$sDay);
			$round2_query = $login->qry("SELECT * FROM round_time WHERE round_date = '?' AND round_no = 'round2'", $sYear."-".$sMonth."-".$sDay);
			$round3_query = $login->qry("SELECT * FROM round_time WHERE round_date = '?' AND round_no = 'round3'", $sYear."-".$sMonth."-".$sDay);
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
			if ($sDay == $sLastDay) {
				$query1 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
				"".$sYear."-".$sMonth."-".$sDay." ".$round_time1, "".$sNextYear."-".$sNextMonth."-".$sNextDay." 12:00:00");
			} else {
				$query1 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
				"".$sYear."-".$sMonth."-".$sDay." ".$round_time1, "".$sYear."-".$sMonth."-".$sNextDay." 12:00:00");
			}
		} else {
			$query1 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
				"".$sYear."-".$sMonth."-".$sDay." ".$round_time1, "".$sYear."-".$sMonth."-".$sDay." ".$round_time2);
			if ($round_time3 == "") {
				if ($sDay == $sLastDay) {
					$query2 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
					"".$sYear."-".$sMonth."-".$sDay." ".$round_time2, "".$sNextYear."-".$sNextMonth."-".$sNextDay." 12:00:00");
				} else {
					$query2 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
				"".$sYear."-".$sMonth."-".$sDay." ".$round_time2, "".$sYear."-".$sMonth."-".$sNextDay." 12:00:00");
				}
			} else {
				$query2 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
			"".$sYear."-".$sMonth."-".$sDay." ".$round_time2, "".$sYear."-".$sMonth."-".$sDay." ".$round_time3);
				if ($sDay == $sLastDay) {
					$query3 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
					"".$sYear."-".$sMonth."-".$sDay." ".$round_time3, "".$sNextYear."-".$sNextMonth."-".$sNextDay." 12:00:00");
				} else {
					$query3 = $login->qry("SELECT * FROM slip_insert WHERE username = '?' AND timestamp BETWEEN '?' AND '?';", $user, 
				"".$sYear."-".$sMonth."-".$sDay." ".$round_time3, "".$sYear."-".$sMonth."-".$sNextDay." 12:00:00");
				}
			}
		}
		//if (mysql_num_rows($query) == 0) $isEmpty = 1;
		$step_qry = $login->qry("SELECT step2, step1 FROM user_account WHERE username = '?'", $user);
		$step_fet = mysql_fetch_array($step_qry);
		$step_result2 = $step_fet['step2'];
		$step_result1 = $step_fet['step1'];
		$agt_qry = $login->qry("SELECT * FROM user_account WHERE username = '?';", $username_s);
		$agt_fet = mysql_fetch_array($agt_qry);
		$user_type = $agt_fet['user_type'];
		$agt_step2 = $agt_fet['step2'];
		$agt_step1 = $agt_fet['step1'];
		echo '
			<div class="CSSTableGenerator" >
			<center>
				User: '.$user.' [<a href="print.php?userp='.$user.'">ปริ้น</a>] <br/>';
		if ($user_type == 'agent') {
			if ($agt_step2 == 'yes') {
				echo '
					Step 2? : '.$step_result2.' [<a href="chgstep.php?userc='.$user.'">เปลี่ยน</a>] <br/>';
			}
			if ($agt_step1 == 'yes') {
				echo '
					Step 1? : '.$step_result1.' [<a href="chgstep1.php?user1='.$user.'">เปลี่ยน</a>] <br/>';
			}
		} else if ($user_type == 'master') {
			if ($login->check_agent($username_s, $logged_in) == true || $login->check_master($username_s, $logged_in) == true) {
				echo '
					Step 2? : '.$step_result2.' [<a href="chgstep.php?userc='.$user.'">เปลี่ยน</a>] <br/>';
			}
			if ($login->check_agent($username_s, $logged_in) == true || $login->check_master($username_s, $logged_in) == true) {
				echo '
					Step 1? : '.$step_result1.' [<a href="chgstep1.php?user1='.$user.'">เปลี่ยน</a>] <br/>';
			}
		}
		echo '
		';
		$number = 0;
		$strFileName = 'detail.html';
		$objFopen = fopen($strFileName, 'w');
		fwrite($objFopen, '<page style="font-family:freeserif;width:100%;"><font size = "'.$font_size.'">User: '.$user.'</font><br/><br/>');


		if (mysql_num_rows($query1) != 0) {
			echo '
				รอบที่ 1 เริ่มเวลา ';
				echo $round_time1;
			echo '
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
					<td>
						#
					</td>
				</tr>
			';

			
			fwrite($objFopen, '<font size = "'.$font_size.'">รอบที่ 1 เริ่มเวลา '.$round_time1.'</font><table cellspacing="0" style="width: 100%; border: solid 1px black;  text-align: left; font-size: '.$font_size.'pt;"><tr>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						สลิปเลขที่
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						รหัสทีม
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						ราคา
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						เวลา
					</th></tr>');

			
			while ($detail = mysql_fetch_array($query1)) {
				$number += (int) $detail['price'];
				echo '
					<tr>
						<td style="width: 10%;">'.$detail['slip_no'].'</td>
						<td style="width: 50%;">'.$detail['teamcode'].'</td>
						<td style="text-align: right; width: 10%;">'.$detail['price'].'</td>
						<td style="text-align: center;width: 20%;">'.$detail['timestamp'].'</td>
						<td style="text-align: center; width: 10%;">	<a href="deleterow.php?slipid='.$detail['slip_id'].'&username='.$user.'">ลบ</a></td>
					</tr>
				';
				$teamcode = str_replace(".",".  ",$detail['teamcode']);
				$str = '
					<tr>
						<td style="width: 10%; border: solid 1px #000000;">'.$detail['slip_no'].'</td>
						<td style="width: 50%; border: solid 1px #000000;">'.$teamcode.'</td>
						<td style="text-align: right; width: 10%; border: solid 1px #000000;">'.$detail['price'].'</td>
						<td style="text-align: center; width: 25%; border: solid 1px #000000;">'.$detail['timestamp'].'</td>
					</tr>
				';
				fwrite($objFopen, $str);
			}
			echo '
				</table></center><br/>
			';
			
			//fwrite($objFopen, '</table><br/><br/><font size="10">ยอดรวม : '.$number.' บาท</font></page>');
			fwrite($objFopen, '</table><br/><br/>');
		}

		if ($query2 != "" && mysql_num_rows($query2) != 0) {
			echo '
				<center>รอบที่ 2 เริ่มเวลา ';
				echo $round_time2;
			echo '
				</center>
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
					<td>
						#
					</td>
				</tr>
			';

			
			fwrite($objFopen, '<font size = "10">รอบที่ 2 เริ่มเวลา '.$round_time2.'</font><br/><br/><table cellspacing="0" style="width: 100%; border: solid 1px black;  text-align: left; font-size: 12pt;"><tr>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						สลิปเลขที่
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						รหัสทีม
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						ราคา
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						เวลา
					</th></tr>');

			
			while ($detail = mysql_fetch_array($query2)) {
				$number += (int) $detail['price'];
				echo '
					<tr>
						<td style="width: 10%;">'.$detail['slip_no'].'</td>
						<td style="width: 50%;">'.$detail['teamcode'].'</td>
						<td style="text-align: right; width: 10%;">'.$detail['price'].'</td>
						<td style="text-align: center;width: 20%;">'.$detail['timestamp'].'</td>
						<td style="text-align: center; width: 10%;">	<a href="deleterow.php?slipid='.$detail['slip_id'].'&username='.$user.'">ลบ</a></td>
					</tr>
				';
				$teamcode = str_replace(".",".  ",$detail['teamcode']);
				$str = '
					<tr>
						<td style="width: 10%; border: solid 1px #000000;">'.$detail['slip_no'].'</td>
						<td style="width: 50%; border: solid 1px #000000;">'.$teamcode.'</td>
						<td style="text-align: right; width: 10%; border: solid 1px #000000;">'.$detail['price'].'</td>
						<td style="text-align: center; width: 25%; border: solid 1px #000000;">'.$detail['timestamp'].'</td>
					</tr>
				';
				fwrite($objFopen, $str);
			}
			echo '
				</table></center><br/>
			';
			
			//fwrite($objFopen, '</table><br/><br/><font size="10">ยอดรวม : '.$number.' บาท</font></page>');
			fwrite($objFopen, '</table><br/><br/>');
		}

		if ($query3 != "" && mysql_num_rows($query3) != 0) {
			echo '
				<center>รอบที่ 3 เริ่มเวลา ';
				echo $round_time3;
			echo '
				</center>
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
					<td>
						#
					</td>
				</tr>
			';

			
			fwrite($objFopen, '<font size = "10">รอบที่ 3 เริ่มเวลา '.$round_time3.'</font><table cellspacing="0" style="width: 100%; border: solid 1px black;  text-align: left; font-size: 12pt;"><tr>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						สลิปเลขที่
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						รหัสทีม
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						ราคา
					</th>
					<th style="text-align: center; border: solid 1px #000000; background-color: #E7E7E7;">
						เวลา
					</th></tr>');

			
			while ($detail = mysql_fetch_array($query3)) {
				$number += (int) $detail['price'];
				echo '
					<tr>
						<td style="width: 10%;">'.$detail['slip_no'].'</td>
						<td style="width: 50%;">'.$detail['teamcode'].'</td>
						<td style="text-align: right; width: 10%;">'.$detail['price'].'</td>
						<td style="text-align: center;width: 20%;">'.$detail['timestamp'].'</td>
						<td style="text-align: center; width: 10%;">	<a href="deleterow.php?slipid='.$detail['slip_id'].'&username='.$user.'">ลบ</a></td>
					</tr>
				';
				$teamcode = str_replace(".",".  ",$detail['teamcode']);
				$str = '
					<tr>
						<td style="width: 10%; border: solid 1px #000000;">'.$detail['slip_no'].'</td>
						<td style="width: 50%; border: solid 1px #000000;">'.$teamcode.'</td>
						<td style="text-align: right; width: 10%; border: solid 1px #000000;">'.$detail['price'].'</td>
						<td style="text-align: center; width: 25%; border: solid 1px #000000;">'.$detail['timestamp'].'</td>
					</tr>
				';
				fwrite($objFopen, $str);
			}
			echo '
				</table></center><br/>
			';
			fwrite($objFopen, '</table><br/><br/>');
		}
			echo '<center>ยอดรวม  '.$number.' บาท</center>
			</div>
			';
			fwrite($objFopen, '<font size="10">ยอดรวม : '.$number.' บาท</font></page>');


		fclose($objFopen);
		echo '<center><table>
				<tr>
					<!-- <td><a href="index.php?backdetail=1">กลับ</a></td> -->
					<td><button onclick="javascript:history.go(-1)">กลับ</td>
					<!-- <td><a href="print.php?userp='.$user.'">ปริ้น</a></td> -->
					<!-- <td><button onclick="javascript:location.href="print.php?userp='.$user.'"">ปริ้น</td> -->
					<!-- <td><button onclick="goPrint()">ปริ้น</td> -->
					<!-- <td><button href="print.php?userp='.$user.'">ปริ้น</td> -->
					<td><button onclick="setPrice()">แก้ไขจำกัดเงินแทง</button></td>
				</tr>';
?>
		<script language = "JavaScript">
			function setPrice()
			{
				var number = <?php echo $number;?>;
				var id = <?php echo $id;?>;

				var limit=prompt("Please enter your name",number);

				if (limit < number) {
					alert("เงินจำกัดน้อยกว่ายอดแทงปัจจุบัน กรุณาระบุใหม่อีกครั้ง");
				} else {
					window.location.href="changelimit.php?newlimit="+limit+"&id="+id;
				}
			}
			function goPrint()
			{
				var userp = <?php echo $GLOBAL['usert'];?>;
				window.location.href="print.php?userp="+userp;
			}
		</script>
<?php
		include 'footer.php';
		exit();
	} else {
		//echo $username_s . $logged_in . '</br>';
		echo '<center>Permission denied!</center><br/>';
		printfooter();
	}

	function printfooter() {
		echo '<center><a href="index.php?backdetail=1">กลับ</a><center>';
		include 'footer.php';
		exit();
	}
?>
