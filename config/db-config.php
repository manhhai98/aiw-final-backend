<?php
$host = "ec2-174-129-231-25.compute-1.amazonaws.com";
$user = "eqjtgzyfcxoujp";
$password = "6d29b6f4e01cb6b0ff0ccc3f203284c00daae1970c468ff4bebdc2d5c9a58961";
$dbname = "d731vu481hvfj0";
$port = "5432";

if (!isset($conn)) {
  global $conn;

  try{
    //Set DSN data source name
    $dsn = "pgsql:host=" . $host . ";port=" . $port .";dbname=" . $dbname . ";user=" . $user . ";password=" . $password . ";";
    //create a pdo instance
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
}
