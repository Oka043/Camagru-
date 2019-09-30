<?php

namespace app\controllers;

use app\core\Controller;

use app\db_workers\Users;
use app\db_workers\Likes;
use app\db_workers\Gallery;
use app\db_workers\Comments;
use app\db_workers\Followers;

use app\additional\Database;
use app\additional\Masks;
use app\additional\MailManage;


class AccountController extends Controller {
	// =====================================================================================================
	public function loginAction() {
		if (USER_LOGGED)
			header('Location: /');

		if ($_SERVER['REQUEST_METHOD'] == 'POST'
				&& isset($_POST['login']) 
				&& isset($_POST['password'])) {
			$result = Users::getUserInfoByLogin(htmlspecialchars($_POST['login']));
      if ($result != null) {
        if ($result['active'] == 0) {
          $fmsg = "Please Activate Your Account. ";
          $fmsg .= "Before you can login, you must active your account";
          $fmsg .= " with the code sent to your email address.";
        } else if (password_verify($_POST['password'], $result["password"])) {
          $_SESSION["user_id"] = $result["user_id"];
          header('Location: /');
        } else {
          $fmsg = "Invalid Login or Password";
        }
      } else {
         $fmsg = "Such user does not exist.";
      }
		}
		$res = [
        "smsg" => (isset($smsg) ? $smsg : ''),
        "fmsg" => (isset($fmsg) ? $fmsg : ''),
      ];
		$this->view->reader("Логин", $res);
	}

	// =====================================================================================================
	public function logoutAction() {
		if (isset($_SERVER['HTTP_COOKIE'])) {
      //get all cookies
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        //kill it
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
      }
    }
    // Free Session and destroy it.
    session_unset();
    session_destroy();

    header("location:/");
	}

	// =====================================================================================================
	public function registerAction() {
		if (USER_LOGGED)
			header('Location: /');

		if (isset($_POST['login'])
        && isset($_POST['first_name'])
        && isset($_POST['last_name'])
        && isset($_POST['email']) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != FALSE)
        && isset($_POST['password'])
        && isset($_POST['gender']) && (filter_var($_POST['gender'], FILTER_VALIDATE_INT) != FALSE)) {
      $token = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM123456789";
      $token= str_shuffle($token);      

      $data = [
        'login' => htmlspecialchars($_POST['login']),
        'email' => htmlspecialchars($_POST['email']),
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        'gender' => $_POST['gender'],
        'first_name' => htmlspecialchars($_POST['first_name']),
        'last_name' => htmlspecialchars($_POST['last_name']),
        'signup_token' => substr($token, 0, 15),
        'active' => "0",
        'bio' => "",
      ];


      if (Users::loginExist($data["login"])) {
        $fmsg = "Login exist";
      } else if (Users::emailExist($data["email"])) {
        $fmsg = "Email exist";
      } else if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,25}$/", $_POST['password'])) {
        $fmsg  = "Password must have one lower case letter, ";
        $fmsg .= "one upper case letter, one digit, 8-25 length, and no spaces!";
      } else if (!preg_match("/^[A-Za-zА-Яа-яЁё_-]{3,50}$/", $_POST['login'])) {
        $fmsg = "Login can contain letters, '-' , '_' - symbols and 3-50 symbols length!";
      } else {
				$result = Users::addNewUser($data);
				if ($result) {
					MailManage::registerConfirmation($data['email'], $data['signup_token']);
        	$smsg = "Registration almost done, check email to activte profile.";
				} else {
					$fmsg = "Error";
				}
			}
		}
		$res = [
        "smsg" => (isset($smsg) ? $smsg : ''),
        "fmsg" => (isset($fmsg) ? $fmsg : ''),
      ];

		$this->view->reader("Registration", $res);
	}

	// =====================================================================================================
	public function activateProfileAction() {
    if (USER_LOGGED)
      header('Location: /');
		
		if (!isset($_GET['email']) || !isset($_GET['token'])) {
      header('Location: /404');
    } else if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    	$user_data = Users::getUserInfoByEmail($_POST['email']);
      if ($user_data != null && $user_data["active"] == 0) {
	    	$token = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM123456789";
	      $token = str_shuffle($token);
	      $token = substr($token, 0, 15);
        Users::updateSignInToken($user_data['user_id'], $token);
        MailManage::registerConfirmation($_POST['email'], $token);
        $smsg = "Email sent"; 
      } else {
        $fmsg = "Error, invalid mailbox";
      }
    } else if (preg_match("/^[A-Za-z0-9]{15}/", $_GET['token'])
          && (filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) != FALSE)) {
    	$user_data = Users::getUserInfoByEmail($_GET['email']);
    	if ($user_data != null 
    			&& $user_data["active"] == 0 
    			&& $user_data["signup_token"] == $_GET['token']) {
    		Users::activateUserAccount($user_data["user_id"]);
	  		$smsg = "Activation was successful, Registration completed.";
      } else {
        $fmsg = "Something went wrong, try sending the activation email again";
      }
		} else {
      $fmsg = "Something went wrong, try sending the activation email again";
    }

		$res = [
        "smsg" => (isset($smsg) ? $smsg : ''),
        "fmsg" => (isset($fmsg) ? $fmsg : ''),
      ];
		$this->view->reader("Account activation", $res);
	}

	// =====================================================================================================
	public function forgotPasswordAction() {
		if (USER_LOGGED)
			header('Location: /');

    if ($_SERVER['REQUEST_METHOD'] == 'POST'
	    && isset($_POST['email'])
	    && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != FALSE)) {

	  	$recoverToken = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM123456789";
	    $recoverToken = str_shuffle($recoverToken);
	    $recoverToken = substr($recoverToken, 0, 15);

	    if (Users::forgotPassword($recoverToken, $_POST['email'])) {
				MailManage::restorePasswordConfirmation($_POST['email'], $recoverToken);
	      $smsg = "A link to reset password will be sent to your e-mail";
	    } else {
	      $fmsg = "Error, Wrong email";
	    }
	  }
    
    $result = [
        "smsg" => (isset($smsg) ? $smsg : ''),
        "fmsg" => (isset($fmsg) ? $fmsg : ''),
      ];
		$this->view->reader("Forgot Password", $result);
	}

	// =====================================================================================================
	public function restorePasswordAction() {
		if (USER_LOGGED)
			header('Location: /');

    if (isset($_GET['email']) 
      && isset($_GET['token']) 
      && preg_match("/[^0-9\/]+/", $_GET['token'])
      && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {

		  $result = Users::getUserInfoByEmail($_GET['email']);
      if ($result && $result["password_token"] == $_GET['token']) {
        // Check for filled form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          if (!isset($_POST['password']) || !isset($_POST['passRepeat'])) {
            $fmsg = "Fill the passwords fields.";
          } else if ($_POST['password'] != $_POST['passRepeat']) {
            $fmsg = "Passwords do not match";
          } else if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,25}$/", $_POST['password'])) {
            $fmsg  = "Password must have one lower case letter, ";
            $fmsg .= "one upper case letter, one digit, 8-25 length, and no spaces!";
          } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $user_id = $result["user_id"];

            if (Users::updatePassword($result["user_id"], $password))
              $smsg = "Password succefully updated.";
            else
              $fmsg = "Something went wrong, try later.";
          }
        }
      } else {
        $fmsg = "The wrong information, try to resend the letter.";
      }
    }

    $vars = [
        "smsg" => (isset($smsg) ? $smsg : ''),
        "fmsg" => (isset($fmsg) ? $fmsg : ''),
      ];
		$this->view->reader("Restore Password", $vars);	
	}

}

?>