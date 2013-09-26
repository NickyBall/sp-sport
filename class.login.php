<?php

session_start();
date_default_timezone_set('Asia/Bangkok');

require 'db.php';

class logmein {
	/*
    //Register function
    function register($org_id, $username, $password, $email, $name_lastname, $timestamp) {
        $password = sha1($password);
        $result = $this->qry("INSERT INTO user_account VALUES(DEFAULT,'?','?','?','?','?',DEFAULT,DEFAULT,'?',DEFAULT,DEFAULT);", $org_id, $username, $password, $email, $name_lastname, $timestamp);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
	*/
	
	//Insert the Data
	function insertData($username, $slipno, $teamcode, $price, $timestamp) {
		$result = $this->qry("INSERT INTO slip_insert VALUES(DEFAULT,'?','?','?','?','?');", $username, $slipno, $teamcode, $price, $timestamp);
        if ($result) {
            return true;
        } else {
            return false;
        }
	}

	//Register function
    function register($username, $password, $user_type, $timestamp, $poweruser) {
        $password = sha1($password);
        $result = $this->qry("INSERT INTO user_account VALUES(DEFAULT,'?','?','?','?','?', DEFAULT, DEFAULT, DEFAULT);", $username, $password, $user_type, $timestamp, $poweruser);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    //Login function
    function login($username, $password) {
        $password = sha1($password);
        $result = $this->qry("SELECT * FROM user_account WHERE username = '?' AND password = '?';", $username, $password);
        $row = mysql_fetch_array($result);
        if ($row) {
            if ($row['username'] != '' && $row['password'] != '') {
                //$result = $this->login_success($username);
				$result = true;
                if ($result) {
                    //Open sessions
                    $_SESSION['logged_in'] = $row['password'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_type'] = $row['user_type'];
                    return true;
                }
            }
        } else {
            //Destroy Session
            session_destroy();
            //$this->login_fail($username);
            return false;
        }
    }

	/*
    function login_success($username) {
        //Reset number of login fail
        $result = $this->qry("UPDATE user_account SET login_fail = '0' WHERE username = '?';", $username);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
	*/
	/*
    function login_fail($username) {
        $result = $this->qry("SELECT * FROM user_account WHERE username = '?';", $username);
        $row = mysql_fetch_array($result);
        if ($row) {
            //Increase number of login fail by 1 if user is not blocked
            if ($row['user_status'] != 'block') {
                $result = $this->qry("UPDATE user_account SET login_fail = login_fail + 1 WHERE username = '?';", $username);
                if ($result) {
                    //Block user if login fail more than 3 times
                    if ($row['login_fail'] >= 3) {
                        $this->qry("UPDATE user_account SET user_status = 'block' WHERE username = '?';", $username);
                    }
                }
            }
        }
    }
	*/

    //Logout function
    function logout() {
        session_destroy();
        return;
    }

    //Prevent sql injection
    function qry($query) {
        $args = func_get_args();
        $query = array_shift($args);
        $query = str_replace("?", "%s", $query);
        $args = array_map('mysql_real_escape_string', $args);
        array_unshift($args, $query);
        $query = call_user_func_array('sprintf', $args);
//        echo $query;
        $result = mysql_query($query) or die(mysql_error());
        if ($result) {
            return $result;
        } else {
            //$error = 'Error';
			$result = 'Error';
            return $result;
        }
    }

    //Check if username already exist
    function check_username($username) {
        $result = $this->qry("SELECT username FROM user_account WHERE username = '?';", $username);
        $row = mysql_fetch_assoc($result);
        //Return true if username already exist and false if not
        if ($row != 'Error') {
            if ($row['username'] == $username) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Check if logged in
    function check_logged_in($logincode, $username) {
        $result = $this->qry("SELECT password FROM user_account WHERE password = '?';", $logincode);
        $rownum = mysql_num_rows($result);
        $row = mysql_fetch_assoc($result);
        //Return true if logged in and false if not
        if ($row != 'Error') {
            if ($rownum > 0) {
                $result = $this->qry("SELECT * FROM user_account WHERE username = '?';", $username);
                $row = mysql_fetch_assoc($result);
                if ($row != 'Error') {
                    if ($row['password'] == $logincode) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    //Check if this username is admin
    function check_agent($username, $password) {
        $result = $this->qry("SELECT * FROM user_account WHERE username = '?' AND password = '?';", $username, $password);
        $row = mysql_fetch_assoc($result);
        //Return true if this user is admin and false if not
        if ($row != 'Error') {
            if ($row['user_type'] == 'agent') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	function check_master($username, $password) {
        $result = $this->qry("SELECT * FROM user_account WHERE username = '?' AND password = '?';", $username, $password);
        $row = mysql_fetch_assoc($result);
        //Return true if this user is admin and false if not
        if ($row != 'Error') {
            if ($row['user_type'] == 'master') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	/*
    //Check if this user_type is BOT
    function check_BOT($username) {
        $result = $this->qry("SELECT * FROM user_account WHERE username = '?';", $username);
        $row = mysql_fetch_assoc($result);
        //Return true if this user_type is BOT and false if not
        if ($row != 'Error') {
            if ($row['user_type'] == 'BOT') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	*/

    function check_password($username, $password) {
        $password = sha1($password);
        $result = $this->qry("SELECT password FROM user_account WHERE username = '?';", $username);
        $row = mysql_fetch_assoc($result);
        if ($row != 'Error') {
            if ($row['password'] == $password) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function change_password($password, $username) {
        $password = sha1($password);
        $result = $this->qry("UPDATE user_account SET password = '?' WHERE username = '?';", $password, $username);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function select_password($username) {
        $result = $this->qry("SELECT password FROM user_account WHERE username = '?';", $username);
        $row = mysql_fetch_assoc($result);
        if ($row != 'Error') {
            return $row['password'];
        } else {
            return false;
        }
    }
	/*
    function check_email($username) {
        $result = $this->qry("SELECT email FROM user_account WHERE username = '?';", $username);
        $row = mysql_fetch_assoc($result);
        if ($row != 'Error') {
            return $row['email'];
        } else {
            return false;
        }
    }
	*/
	/*
    function change_email($email, $username) {
        $result = $this->qry("UPDATE user_account SET email = '?' WHERE username = '?';", $email, $username);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
	*/
	/*
    function check_blocked($username) {
        $result = $this->qry("SELECT * FROM user_account WHERE username = '?';", $username);
        $row = mysql_fetch_array($result);
        if ($row) {
            if ($row['user_status'] == 'block') {
                return true;
            } else {
                return false;
            }
        }
    }
	*/
	/*
    function unblock($username) {
        $result = $this->qry("UPDATE user_account SET user_status='activated',login_fail='0' WHERE username='?';", $username);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
	*/
    //Reset password
    /*
      function reset_password($username) {
      //Generate new password
      $newpassword = $this->create_password();
      $newpassword = sha1($newpassword);
      //Update database with new password
      $qry = "UPDATE user_account SET password = '" . $newpassword . "' WHERE username = '" . $username . "'";
      $result = mysql_query($qry) or die(mysql_error());
      //Get user's e-mail address
      $result = $this->qry("SELECT username FROM user_account WHERE username = '?';", $username);
      $row = mysql_fetch_assoc($result);
      $email = $row['email'];
      $to = stripslashes($email);
      //Some injection protection
      $illigals = array("%0A", "%0D", "%0a", "%0d", "bcc:", "Content-Type", "BCC:", "Bcc:", "Cc:", "CC:", "TO:", "To:", "cc:", "to:");
      $to = str_replace($illigals, "", $to);
      $getemail = explode("@", $to);
      //Send only if there is one email
      if (sizeof($getemail) > 2) {
      return false;
      } else {
      //Send email
      $from = $_SERVER['SERVER_NAME'];
      $subject = "Password Reset: " . $_SERVER['SERVER_NAME'];
      $msg = "<p>Your new password is: " . $newpassword . "</p>";
      //Set mail headers
      $headers = "MIME-Version: 1.0 rn";
      $headers .= "Content-Type: text/html; rn";
      $headers .= "From: $from  rn";
      //Send mail
      $sent = mail($to, $subject, $msg, $headers);
      if ($sent) {
      return true;
      } else {
      return false;
      }
      }
      }
     */

    //Create random password with 8 alphanumerical characters
    /*
      function create_password() {
      $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      srand((double) microtime() * 1000000);
      $i = 0;
      $pass = '';
      while ($i <= 7) {
      $num = rand() % 33;
      $tmp = substr($chars, $num, 1);
      $pass = $pass . $tmp;
      $i++;
      }
      return $pass;
      }
     */
}

?>
