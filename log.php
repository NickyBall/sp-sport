<?php

//include 'class.log.php';
$log = new createlog();

if ($ws == false) {
    if (isset($_SESSION['username'])) {
        if (!is_string($_SESSION['username'])) {
            break;
        }
        $username = (string) $_SESSION['username'];
    }
} else {
    $submit_timestamp = $timestamp;
}

$finish_timestamp = date('Y-m-d H:i:s', time());

//Create log for input xml file
$result = $log->log_message($submit_timestamp, $finish_timestamp, $schema_result, $rule_result[0], $message_type_id, $original_filename, $username);

//If rule validation is false
if ($rule_result[0] == 0 && $check_rule != false) {
    for ($i = 1; $i <= $maxrules; $i++) {
        if ($rule_result[$i] == 0) {
            $message_id = $log->select_message_id($submit_timestamp, $username);
            $rule_id = "R" . $i;
            $log->log_error($rule_id, $message_id);
        }
    }
}
?>