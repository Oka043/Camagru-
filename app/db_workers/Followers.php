<?php 

namespace app\db_workers;

use app\additional\Database;

class Followers {
  protected static $pdo_db;

  const GET_ALL_FOLLOWING   = "SELECT 
                                u.user_id,
                                u.avatar_src,
                                u.login,
                                u.first_name,
                                u.last_name,
                                u.gender,
                                (SELECT count(*) FROM followers WHERE followers.user_id_follower=:user_id_session and followers.user_id_followed=u.user_id) AS followed
                              FROM followers 
                              INNER JOIN users u 
                                ON u.user_id = followers.user_id_followed
                              WHERE followers.user_id_follower=:user_id_info";
  // =====================================================================================================
  const GET_ALL_FOLLOWERS   = "SELECT 
                                u.user_id,
                                u.avatar_src,
                                u.login,
                                u.first_name,
                                u.last_name,
                                u.gender,
                                (SELECT count(*) FROM followers WHERE followers.user_id_follower=:user_id_session and followers.user_id_followed=u.user_id) AS followed
                              FROM followers 
                              INNER JOIN users u 
                                ON u.user_id = followers.user_id_follower
                              WHERE followers.user_id_followed=:user_id_info";

  // =====================================================================================================
  const FOLLOW              = "INSERT INTO followers (user_id_follower, user_id_followed) VALUES (:user_id_follower, :user_id_followed)";
  const UNFOLLOW            = "DELETE FROM followers WHERE user_id_follower=:user_id_follower and user_id_followed=:user_id_followed";
  const IS_FOLLOW           = "SELECT * FROM followers WHERE user_id_follower=:user_id_follower and user_id_followed=:user_id_followed";

  public static function    initPDO() {
    $settings = require ROOT_PATH."/config/db_config.php";
    self::$pdo_db = new Database($settings);
  }

  public static function    follow($user_id_follower, $user_id_followed) {
    try {
      $data = array (
        "user_id_follower" => $user_id_follower,
        "user_id_followed" => $user_id_followed);

      $result = self::$pdo_db->query(self::FOLLOW, $data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function    unfollow($user_id_follower, $user_id_followed) {
    try {
      $data = array (
        "user_id_follower" => $user_id_follower,
        "user_id_followed" => $user_id_followed);

      $result = self::$pdo_db->query(self::UNFOLLOW, $data);
      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public static function    getAllFollowing($user_id_info, $user_id_session=0) {
    try {

      $data = array (
        "user_id_info" => $user_id_info,
        "user_id_session" => $user_id_session
      );

      $result = self::$pdo_db->query(self::GET_ALL_FOLLOWING, $data);

      if (!empty($result))
        return $result;
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function    getAllFollowers($user_id_info, $user_id_session=0) {
    try {
      $data = array (
        "user_id_info" => $user_id_info,
        "user_id_session" => $user_id_session
      );

      $result = self::$pdo_db->query(self::GET_ALL_FOLLOWERS, $data);

      if (!empty($result))
        return $result;
      return null;
    } catch (PDOException $e) {
      return null;
    }
  }

  public static function    isUserFollow($user_id_follower, $user_id_followed) {
    try {
      $data = array (
        "user_id_follower" => $user_id_follower,
        "user_id_followed" => $user_id_followed);

      $result = self::$pdo_db->query(self::IS_FOLLOW, $data);

      if (!empty($result))
        return true;
      return false;
    } catch (PDOException $e) {
      return false;
    }
  }
}

Followers::initPDO();

?>