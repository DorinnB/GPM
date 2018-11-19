<?php
class db {
  private $conn;
  private $host;
  private $user;
  private $password;
  private $baseName;
  private $port;
  private $Debug;

  function __construct($params=array()) {

    if (file_exists('config.php')) {
      require 'config.php';
    }
    elseif (file_exists('../config.php')) {
      require '../config.php';
    }
    
    $this->conn = false;
    $this->host = $DB_HOST; //hostname
    $this->user = $DB_USER; //username
    $this->password = $DB_PASS; //password
    $this->baseName = $DB_NAME; //name of your database
    //attention changer aussi dans le fichier de config d editor !!!!
    $this->port = $DB_PORT;
    $this->debug = true;
    $this->connect();
  }

  function __destruct() {
		$this->disconnect();
	}

	function connect() {
		if (!$this->conn) {
			try {
				$this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->baseName.'', $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			}
			catch (Exception $e) {
				die('Erreur : ' . $e->getMessage());
			}

			if (!$this->conn) {
				$this->status_fatal = true;
				echo 'Connection BDD failed';
				die();
			}
			else {
				$this->status_fatal = false;
			}
		}

		return $this->conn;
	}

	function disconnect() {
		if ($this->conn) {
			$this->conn = null;
		}
	}

	function getOne($query) {
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
		   echo 'PDO::errorInfo():';
		   echo '<br />';
		   echo 'error SQL: '.$query;
		   die();
		}
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$reponse = $result->fetch();

		return $reponse;
	}

	function getAll($query) {
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
		   echo 'PDO::errorInfo():';
		   echo '<br />';
		   echo 'error SQL: '.$query;
		   die();
		}
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$reponse = $result->fetchAll();

		return $reponse;
	}

	function execute($query) {
		if (!$response = $this->conn->exec($query)) {
			echo 'PDO::errorInfo():';
		   echo '<br />';
		   echo 'error SQL: '.$query;
                    print_r($this->conn->errorInfo());
		   die();
		}
		return $response;
	}

  function query($query) {
		$response = $this->conn->exec($query);
		return $response;
	}

  function isOne($query) {
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
		   return false;
		}
    else {
      $result->setFetchMode(PDO::FETCH_ASSOC);
      $reponse = $result->fetch();
      return $reponse;
    }
	}


  function quote($val) {
		return $this->conn->quote($val);
	}

  function lastId() {
		return $this->conn->lastInsertId();
	}
}
