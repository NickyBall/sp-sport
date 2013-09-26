<?php
	include 'class.login.php';
	$login = new logmein();

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
	$userp = $_GET['userp'];
	if (($login->check_agent($username_s, $logged_in) == true) || ($username_s == $userp) || $login->check_master($username_s, $logged_in) == true) {
		ob_start();
		include('detail.html');
		$content = ob_get_clean();

		require_once('html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->WriteHTML($content);
			$html2pdf->Output('detail.pdf');
		} catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
    } else {
		//echo $username_s . $logged_in . '</br>';
		echo '<center>Permission denied!</center><br/>';
		printfooter();
	}
	function printfooter() {
		echo '<center><a href="index.php">กลับ</a><center>';
		include 'footer.php';
		exit();
	}
?>
