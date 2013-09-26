<?php
include 'class.login.php';
$login = new logmein();

include 'class.log.php';
$log = new createlog();

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
    header('Refresh: 2; url=index.php');
    echo 'คุณยังไม่ได้เข้าระบบ';
} else {
    echo '
            <script language="javascript">
                window.location = "index.php";
            </script>
            ';
    $timestamp = date('Y-m-d H:i:s', time());
    //Log out
    $login->logout();
    echo 'ออกจากระบบเรียบร้อยแล้ว';
    //Insert log into database
    $log->log_login($timestamp, $username_s, 'logout');
}
?>