<?php

namespace app\db_workers;

use app\additional\Database;

class Users {
  protected static $pdo_db;


  const USER_INFO_BY_LOGIN  = "SELECT * FROM users WHERE login=:login";
  const USER_INFO_BY_ID     = "SELECT * FROM users WHERE user_id=:user_id";
  const EMAIL_EXIST         = "SELECT * FROM users WHERE email=:email";
  const USER_ID_EXIST       = "SELECT * FROM users WHERE user_id=:user_id";
  const USER_INFO_BY_EMAIL  = "SELECT * FROM users WHERE email=:email";
  
  
  const UPDATE_RECOVER_TOKEN = "UPDATE users SET password_token=:token WHERE user_id=:user_id";
  const UPDATE_SIGN_UP_TOKEN = "UPDATE users SET signup_token=:token WHERE user_id=:user_id";
  const UPDATE_PASSWORD      = "UPDATE users SET password_token='NULL', password=:password WHERE user_id=:user_id";
  const ACTIVATE_USER        = "UPDATE users SET signup_token='NULL', active=1 WHERE user_id=:user_id";
  
  // =====================================================================================================
  const ADD_NEW_USER        = "INSERT INTO users (login,  password,  email,  active,  gender,  first_name,  last_name,  bio,  signup_token) 
                            VALUES (:login, :password, :email, :active, :gender, :first_name, :last_name, :bio, :signup_token)";
  // =====================================================================================================
  const ALL_USER_INFO_BY_ID  = "SELECT
                              users.user_id,
                              users.login, 
                              users.last_name, 
                              users.first_name, 
                              users.gender, 
                              users.bio, 
                              users.avatar_src,
                              (SELECT count(*) from followers where followers.user_id_follower=users.user_id) following,
                              (SELECT count(*) from followers where followers.user_id_followed=users.user_id) followers
                            FROM
                              users
                            WHERE users.user_id=:user_id";
// =====================================================================================================
  const MUTABLE_USER_INFO_BY_ID  = "SELECT
                              users.user_id,
                              users.login,
                              users.first_name,
                              users.last_name,
                              users.bio,
                              users.email,
                              users.password,
                              users.avatar_src,
                              users.gender,
                              users.active,
                              users.recieve_mails,
                              users.signup_token
                            FROM
                              users
                            WHERE users.user_id=:user_id";

  // =====================================================================================================
  const UPDATE_USER_INFO_BY_ID = "UPDATE users 
                                  SET 
                                    users.login = :login,
                                    users.first_name = :first_name,
                                    users.last_name = :last_name,
                                    users.bio = :bio,
                                    users.email = :email,
                                    users.password = :password,
                                    users.avatar_src = :avatar_src,
                                    users.gender = :gender,
                                    users.active = :active,
                                    users.signup_token = :signup_token,
                                    users.recieve_mails = :recieve_mails
                                  WHERE users.user_id = :user_id";

  
  public static function  initPDO() {
    $settings = require ROOT_PATH."/config/db_config.php";
    self::$pdo_db = new Database($settings);
  }

  public static function  loginExist($login) {
    try {
      $data = array(
        "login" => $login);

      $result = self::$pdo_db->query(self::USER_INFO_BY_LOGIN, $data);
      
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  emailExist($email) {
    try {
      $data = array(
        "email" => $email);

      $result = self::$pdo_db->query(self::EMAIL_EXIST, $data);
      
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  userIdExist($user_id) {
    try {
    $data = array (
      "user_id" => $user_id);

      $result = self::$pdo_db->query(self::USER_ID_EXIST, $data);  
      
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  getUserInfo($user_id) {
    try {
      $data = array (
        "user_id" => $user_id);

      $result = self::$pdo_db->query(self::ALL_USER_INFO_BY_ID, $data);
      
      if (!empty($result))
        return $result[0];
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function  getUserInfoByUserId($user_id) {
   try {
      $data = array (
        "user_id" => $user_id);

      $result = self::$pdo_db->query(self::USER_INFO_BY_ID, $data);
      
      if (!empty($result))
        return $result[0];

      return null;
    } catch (PDOException $e) {
      return null;
    } 
  }

  public static function  getUserInfoByEmail($email) {
   try {
      $data = array (
        "email" => $email);

      $result = self::$pdo_db->query(self::USER_INFO_BY_EMAIL, $data);
      
      if (!empty($result))
        return $result[0];

      return null;
    } catch (PDOException $e) {
      return null;
    } 
  }

  public static function  getUserInfoByLogin($login) {
    try {
      $data = array(
        "login" => $login);

      $result = self::$pdo_db->query(self::USER_INFO_BY_LOGIN, $data);

      if (!empty($result))
        return $result[0];
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function  getMutableUserInfoById($user_id) {
    try {
      $data = array(
        "user_id" => $user_id);

      $result = self::$pdo_db->query(self::MUTABLE_USER_INFO_BY_ID, $data);

      if (!empty($result))
        return $result[0];
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function  addNewUser($user_data) {
    try {
      
      $result = self::$pdo_db->query(self::ADD_NEW_USER, $user_data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  updateUserInfo($user_data) {
    try {
      $result = self::$pdo_db->query(self::UPDATE_USER_INFO_BY_ID, $user_data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  forgotPassword($recover_token, $email) {
    try {
      $data = array(
        "email" => $email);
      $result = self::$pdo_db->query(self::USER_INFO_BY_EMAIL, $data);

      if (!empty($result)) {
        $data = array(
          'user_id' => $result[0]['user_id'],
          'token' => $recover_token);
        $result = self::$pdo_db->query(self::UPDATE_RECOVER_TOKEN, $data);
        if (!empty($result)) 
          return true;
      }
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  updateSignInToken($user_id, $token) {
    try {

      $data = array(
        "user_id" => $user_id,
        "token" => $token);
      $result = self::$pdo_db->query(self::UPDATE_SIGN_UP_TOKEN, $data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  activateUserAccount($user_id) {
    try {
      $data = array(
        "user_id" => $user_id);
      
      $result = self::$pdo_db->query(self::ACTIVATE_USER, $data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function  updatePassword($user_id, $password) {
    try {
      $data = array(
        "user_id" => $user_id,
        "password" => $password,
        );
      $result = self::$pdo_db->query(self::UPDATE_PASSWORD, $data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }
}


Users::initPDO();


?>