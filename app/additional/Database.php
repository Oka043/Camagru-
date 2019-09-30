<?php

// https://www.youtube.com/watch?v=fB7AJXBWL80
namespace app\additional;

use \PDO;

class Database {
  /**
   * The PDO object.
   * @var PDO
   */
  private $pdo;
  /**
   * Connected to database.
   * @var bool
   */
  private $isConnected;
  /**
   * PDO statement object.
   * @var PDO Statement.
   */
  private $statement;
  /**
   * The database settings.
   * @var array
   */
  protected $settings = [];
  /**
   * The parameters of SQL query
   * @var array
   */
  private $parameters = [];


  public function __construct($settings) {
    $this->settings = $settings;
    $this->connect();
  }

  /**
   *
   */
  private function connect(){
    $host = $this->settings['msql_host'];
    //    https://www.php.net/manual/ru/pdo.connections.php
    $port = $this->settings['msql_port'];
    $db_name = $this->settings['msql_db_name'];
    $charset = $this->settings['msql_charset'];

    $dsn = "mysql:host=$host;port=$port;dbname=$db_name";

    try {
      $this->pdo = new PDO($dsn, $this->settings['msql_username'], $this->settings['msql_password'], [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset"
      ]);

      # Disable emulations and we can now log
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      $this->isConnected = true;
    } catch (PDOException $e) {
      echo "<b>Connection failed</b> : " . $e->getMessage();
      exit(0);
    }

  }


  public function closeConnection() {
    $this->pdo = null;
    $this->isConnected = false;
  }

  /**
   * @param $query
   * @param $parameters
   */
  private function init($query, $parameters) {
    if (!$this->isConnected){
      $this->connect();
    }

    try {
      # Prepare query
      $this->statement = $this->pdo->prepare($query);
      # Bind parameters
      $this->bind($parameters);
      if (!empty($this->parameters)) {
        foreach ($this->parameters as $param => $value) {
          if (is_int($value[1])) {
            $type = PDO::PARAM_INT;
          } elseif (is_bool($value[1])) {
            $type = PDO::PARAM_BOOL;
          } elseif (is_null($value[1])) {
            $type = PDO::PARAM_NULL;
          } else {
            $type = PDO::PARAM_STR;
          }
          $this->statement->bindValue($value[0], $value[1], $type);
        }
      }
      $this->statement->execute();

    } catch (PDOException $e) {
      echo  "Error Database=>init(): ". $e->getMessage();
      exit(0);
    }
    $this->parameters = [];
  }

  /**
   * @return void
   * @param array $parameters
   */
  private function bind($parameters) {
    if (!empty($parameters) and is_array($parameters)) {
      $columns = array_keys($parameters);

      foreach ($columns as $i => &$column) {
        $this->parameters[sizeof($this->parameters)] = [
          ':' . $column,
          $parameters[$column]
        ];
      }
    }
  }

  /**
   * @param $query
   * @param $parameters
   * @param int $mode
   * @return |null
   */

  public function query($query, $parameters = [], $mode = PDO::FETCH_ASSOC)
  {
    // удаление переносов строки и удаление пробелов вначале и конце строки.
    $query = trim(str_replace('\r', '', $query));

    $this->init($query, $parameters);

    $rawStatement = explode(' ', preg_replace("/\s+|\t+|\n+/", " ", $query));
    // get command SELECT, DELETE ect.
    $statement = strtolower($rawStatement[0]);
    if ($statement === 'select' || $statement === 'show') {
      return $this->statement->fetchAll($mode);
    } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
      return $this->statement->rowCount();
    } else {
      return null;
    }
  }

  /**
   * @return string
   */
  public function lastIncertId() {
    return $this->pdo->lastInsertId();
  }

}

//$settings = require_once  "./params_db.php";
//$db = new Database($settings);


?>