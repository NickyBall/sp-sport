<?php

require 'db.php';

class createlog {

    function log_login($timestamp, $username, $login_status) {
        $result = $this->qry("INSERT INTO log_login VALUES('?','?','?');", $timestamp, $username, $login_status);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function log_message($submit_timestamp, $finish_timestamp, $schema_result, $rule_result, $message_type_id, $original_filename, $username) {
        $result = $this->qry("INSERT INTO log_message VALUES(DEFAULT,'?','?','?','?','?','?','?');", $submit_timestamp, $finish_timestamp, $schema_result, $rule_result, $message_type_id, $original_filename, $username);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function log_error($rule_id, $message_id) {
        $result = $this->qry("INSERT INTO log_error VALUES(DEFAULT,'?','?');", $rule_id, $message_id);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function select_message_id($submit_timestamp, $username) {
        $result = $this->qry("SELECT message_id FROM log_message WHERE submit_timestamp = '?' AND username ='?';", $submit_timestamp, $username);
        $row = mysql_fetch_assoc($result);
        if ($row != 'Error') {
            return $row['message_id'];
        } else {
            return false;
        }
    }

    //Prevent sql injection
    function qry($query) {
        $args = func_get_args();
        $query = array_shift($args);
        $query = str_replace("?", "%s", $query);
        $args = array_map('mysql_real_escape_string', $args);
        array_unshift($args, $query);
        $query = call_user_func_array('sprintf', $args);
        $result = mysql_query($query) or die(mysql_error());
        if ($result) {
            return $result;
        } else {
            $error = 'Error';
            return $result;
        }
    }

}

?>
