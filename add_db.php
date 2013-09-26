<?php

include 'class.login.php';
$login = new logmein();

include 'header.php';
echo '<div id="content">';

//Validate input
if (isset($_REQUEST['action'])) {
    if (!is_string($_REQUEST['action'])) {
        exit();
    }
    $action = (string) $_REQUEST['action'];
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
if (isset($_REQUEST['org_name'])) {
    if (!is_string($_REQUEST['org_name'])) {
        break;
    }
    $org_name = filter_input(INPUT_POST, 'org_name', FILTER_SANITIZE_STRING, FILTER_SANITIZE_SPECIAL_CHARS);
}
if (isset($_REQUEST['org_name_en'])) {
    if (!is_string($_REQUEST['org_name_en'])) {
        break;
    }
    $org_name_en = filter_input(INPUT_POST, 'org_name_en', FILTER_SANITIZE_STRING, FILTER_SANITIZE_SPECIAL_CHARS);
}
if (isset($_REQUEST['shortcode'])) {
    if (!is_string($_REQUEST['shortcode'])) {
        break;
    }
    $shortcode = filter_input(INPUT_POST, 'shortcode', FILTER_SANITIZE_STRING, FILTER_SANITIZE_SPECIAL_CHARS);
}
if (isset($_REQUEST['contact_email'])) {
    if (!is_string($_REQUEST['contact_email'])) {
        break;
    }
    $contact_email = filter_input(INPUT_POST, 'contact_email', FILTER_VALIDATE_EMAIL);
}
if (isset($_REQUEST['org_type'])) {
    if (!is_string($_REQUEST['org_type'])) {
        break;
    }
    $org_type = filter_input(INPUT_POST, 'org_type', FILTER_SANITIZE_NUMBER_INT);
}

//Reject when user is not logged in or not admin or user access to this page directly
if ($login->check_logged_in($logged_in, $username_s) == false OR $login->check_admin($username_s, $logged_in) == false OR $action == '') {
    echo 'Permission denied!';
    return false;
} else {
    //Add organization
    if ($action == 'organization') {
        //Validate input
        if ($org_name == '' OR $org_name_en == '' OR $org_type == '') {
            echo 'ข้อมูลไม่ครบ!';
            return false;
        } else {
            $result = $login->qry("INSERT INTO organization VALUES(DEFAULT,'?','?','?','?','?');", $org_name, $org_name_en, $shortcode, $contact_email, $org_type);
            if ($result) {
                echo 'เพิ่มหน่วยงาน ' . $org_name . ' เรียบร้อยแล้ว<br/>';
            } else {
                echo 'ไม่สามารถเพิ่มหน่วยงานได้ กรุณาตรวจสอบข้อมูลอีกครั้ง';
            }
        }
    }
}

echo '<a href="index.php">กลับ</a>';
echo '</div>';
include 'footer.php';
?>