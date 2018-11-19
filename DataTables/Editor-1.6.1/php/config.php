<?php if (!defined('DATATABLES')) exit(); // Ensure being used in DataTables env.

// Enable error reporting for debugging (remove for production)
error_reporting(E_ALL);
ini_set('display_errors', '1');


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Database user / pass
*/
require '../config.php';

$sql_details = array(
  "type" => "Mysql",  // Database type: "Mysql", "Postgres", "Sqlserver", "Sqlite" or "Oracle"
  "user" => $DB_USER,            // Database user name
  "pass" => $DB_PASS,            // Database password
  "host" => $DB_HOST,            // Database host
  "port" => $DB_PORT,    // Database connection port (can be left empty for default)
  "db"   => $DB_NAME,            // Database name
  "dsn"  => "charset=utf8"       // PHP DSN extra information. Set as `charset=utf8` if you are using MySQL
);
