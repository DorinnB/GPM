<?php if (!defined('DATATABLES')) exit(); // Ensure being used in DataTables env.

// Enable error reporting for debugging (remove for production)
error_reporting(E_ALL);
ini_set('display_errors', '1');


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Database user / pass
*/
$ini = parse_ini_file('../var/config.ini');

$sql_details = array(
  "type" => "Mysql",  // Database type: "Mysql", "Postgres", "Sqlserver", "Sqlite" or "Oracle"
  "user" => $ini['DB_USER'],            // Database user name
  "pass" => $ini['DB_PASS'],            // Database password
  "host" => $ini['DB_HOST'],            // Database host
  "port" => $ini['DB_PORT'],    // Database connection port (can be left empty for default)
  "db"   => $ini['DB_NAME'],            // Database name
  "dsn"  => "charset=utf8"       // PHP DSN extra information. Set as `charset=utf8` if you are using MySQL
);
