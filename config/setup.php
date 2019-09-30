<?php

  use app\additional\Database;
  // Display all Errors
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  
  try {
    require_once "./../app/additional/Database.php";
    $settings = require_once  "./db_config.php";

    $pdo_db = new Database($settings);

    $pdo_db->query('DROP TABLE IF EXISTS `followers`');
    $pdo_db->query('DROP TABLE IF EXISTS `comments`');
    $pdo_db->query('DROP TABLE IF EXISTS `gallery`');
    $pdo_db->query('DROP TABLE IF EXISTS `users`');
    $pdo_db->query('DROP TABLE IF EXISTS `likes`');

    //  Создаем необходимые таблицы
    //  https://www.youtube.com/watch?v=KBnBEop_zEs
    echo "<h4>Create DataBases</h4>";
    $query = "CREATE TABLE IF NOT EXISTS `users`
      (
        `user_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `avatar_src` VARCHAR(255) NULL,
        `login` VARCHAR(255) NOT NULL UNIQUE,
        `last_name` VARCHAR(255) NOT NULL,
        `first_name` VARCHAR(255) NOT NULL,
        `bio` text NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `gender` VARCHAR(255) NOT NULL,
        `signup_token` VARCHAR(255) NULL,
        `password_token` VARCHAR(255) NULL,
        `active` TINYINT(1) NOT NULL,
        `recieve_mails` TINYINT(1) NOT NULL DEFAULT 1
  
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $result = $pdo_db->query($query);
    echo "Table <b>users</b> created<br>";


    $query = "CREATE TABLE IF NOT EXISTS `followers`
      (
        `follow_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `user_id_followed` INT(11) NOT NULL,
        `user_id_follower` INT(11) NOT NULL 
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $result = $pdo_db->query($query);
    echo "Table <b>followers</b> created<br>";


    $query = "CREATE TABLE IF NOT EXISTS `gallery`
      (
        `image_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `user_id` INT(11) NOT NULL,
        `image_src` VARCHAR(255) NOT NULL,
        `desc` VARCHAR(255) NOT NULL,
        `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $result = $pdo_db->query($query);
    echo "Table <b>gallery</b> created<br>";


    $query = "CREATE TABLE IF NOT EXISTS `comments`
      (
        `comment_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `user_id` INT(11) NOT NULL,
        `image_id` INT(11) NOT NULL,
        `comment` VARCHAR(255) NOT NULL,
        `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $result = $pdo_db->query($query);
    echo "Table <b>comments</b> created<br>";


    $query = "CREATE TABLE IF NOT EXISTS `likes`
      (
        `like_id` INT(64) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `user_id` INT(11) NOT NULL,
        `image_id` INT(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $result = $pdo_db->query($query);
    echo "Table <b>likes</b> created<br>";














    // Add test Data to Users
    $query = "INSERT INTO `users` (`login`,  `password`,  `email`,  `active`,  `gender`,  `first_name`,  `last_name`,  `bio`,  `avatar_src`) 
                VALUES  ('Nozzio', '123431jlskdjf', 'null', '1', '1', 'Vlad', 'Noz', 'Photographer', 'images/avatars/1.jpg'),
                        ('Nooqle', '1234312312', 'null', '1', '1', 'Igor', 'Glushko', 'some Dj', 'images/avatars/2.jpg'),
                        ('Sunlike', '123431awdkl12', 'null', '1', '2', 'Sunshine', 'Woman', 'some Dj', 'images/avatars/3.jpg'),
                        ('Tucha', '1231Dadsaw', 'null', '1', '1', 'Yurii', 'Levashov', 'some Dj', 'images/avatars/4.jpg')
                        ";
    $result = $pdo_db->query($query);


    // Add test Data to images
    $query = "INSERT INTO `gallery` (`user_id`, `image_src`, `desc`) 
                VALUES  ('1', 'images/user_1/1.jpg', 'Beautiful shit 1'),
                        ('1', 'images/user_1/2.jpg', 'Beautiful shit 2'),
                        ('1', 'images/user_1/3.jpg', 'Beautiful shit 3'),
                        ('1', 'images/user_1/4.jpg', 'Beautiful shit 4'),
                        
                        ('2', 'images/user_2/1.jpg', 'Beautiful house 1'),
                        ('2', 'images/user_2/2.jpg', 'Beautiful house 2'),
                        ('2', 'images/user_2/3.jpg', 'Beautiful house 3'),
                        ('2', 'images/user_2/4.jpg', 'Beautiful house 4'),
                        ('2', 'images/user_2/5.jpg', 'Beautiful house 4'),
                        
                        ('3', 'images/user_3/1.jpg', 'Beautiful book 1'),
                        ('3', 'images/user_3/2.jpg', 'Beautiful book 2'),
                        ('3', 'images/user_3/3.jpg', 'Beautiful book 3'),
                        ('3', 'images/user_3/4.jpg', 'Beautiful book 4'),
                        ('3', 'images/user_3/5.jpg', 'Beautiful book 5'),
                        
                        ('4', 'images/user_4/1.jpg', 'Beautiful photo 1'),
                        ('4', 'images/user_4/2.jpg', 'Beautiful photo 2'),
                        ('4', 'images/user_4/3.jpg', 'Beautiful photo 3'),
                        ('4', 'images/user_4/4.jpg', 'Beautiful photo 4'),
                        ('4', 'images/user_4/5.jpg', 'Beautiful photo 5')
                        ";
    $result = $pdo_db->query($query);


    $query = "INSERT INTO `followers` (`user_id_followed`, `user_id_follower`)
                VALUES  ('1', '2'),
                        ('1', '3'),
                        ('1', '4'),


                        ('2', '1'),
                        ('2', '3'),
                        ('2', '4'),

                        ('3', '1'),

                        ('4', '1'),
                        ('4', '3')
                        ";
    $result = $pdo_db->query($query);


    $query = "INSERT INTO `comments` (`user_id`, `image_id`, `comment`) 
                VALUES  ('1', '2', 'Beautiful shit 1'),
                        ('1', '8', 'Beautiful shit 2'),
                        ('1', '4', 'Beautiful shit 3'),
                        ('1', '3', 'Beautiful shit 4'),
                        
                        ('2', '1', 'Beautiful house 1'),
                        ('2', '2', 'Beautiful house 2'),
                        ('2', '3', 'Beautiful house 3'),
                        ('2', '4', 'Beautiful house 4'),
                        ('2', '7', 'Beautiful house 4'),
                        
                        ('3', '8', 'Beautiful book 1'),
                        ('3', '9', 'Beautiful book 2'),
                        ('3', '6', 'Beautiful book 3'),
                        ('3', '7', 'Beautiful book 4'),
                        ('3', '5', 'Beautiful book 5'),
                        
                        ('4', '1', 'Beautiful photo 1'),
                        ('4', '2', 'Beautiful photo 2'),
                        ('4', '3', 'Beautiful photo 3'),
                        ('4', '6', 'Beautiful photo 4'),
                        ('4', '7', 'Beautiful photo 5')
                        ";
    $result = $pdo_db->query($query);


    $query = "INSERT INTO `likes` (`user_id`, `image_id`)
                VALUES  ('1', '2'),
                        ('1', '8'),
                        ('1', '4'),
                        ('1', '3'),
                        
                        ('2', '2'),
                        ('2', '8'),
                        ('2', '4'),
                        ('2', '3'),
                        
                        ('3', '2'),
                        ('3', '8'),
                        ('3', '4'),
                        ('3', '3'),
                        
                        ('4', '2'),
                        ('4', '8'),
                        ('4', '4'),
                        ('4', '3')
                        ";
    $result = $pdo_db->query($query);


  } catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
  }

  echo "<h1>Please delete this file</h1>";
?>
