<?php
header("Access-Control-Allow-Origin: *");
$information_complete = array_key_exists('checkin_code', $_POST);

if ($information_complete) {
  $is_localhost = $_SERVER['HTTP_HOST'] === "localhost";

  $hostname = $is_localhost ? "localhost" : "mysql";
  $username = $is_localhost ? "root" : "u825110536_stagon";
  $password = $is_localhost ? "" : ">26QPMeytC0s";
  $database =  $is_localhost ? "semanapreta" : "u825110536_stagon";

  $conn = new mysqli($hostname, $username, $password);

  if ($conn->query("CREATE DATABASE $database;") === TRUE) {
    $conn->select_db($database);
    $conn->query(
      "CREATE TABLE IF NOT EXISTS `present` (
      `checkin_code` VARCHAR(20) NOT NULL DEFAULT '0',
      `day_16` VARCHAR(1) NOT NULL DEFAULT '0',
      `day_17` VARCHAR(1) NOT NULL DEFAULT '0',
      primary key(`checkin_code`)
      );"
    );
  } else {
    $conn->select_db($database);
  }

  $sql = "INSERT INTO `present` (`checkin_code`) VALUES ('" . $_POST['checkin_code'] . "');";
  $result = $conn->query($sql);

  if (array_key_exists('day_16', $_POST)) {
    if ($_POST['day_16'] !== "") {
      $sql = "UPDATE `present` SET `day_16` = '" . $_POST['day_16'] . "' WHERE `present`.`checkin_code` = '" . $_POST['checkin_code'] . "'; ";
      $result = $conn->query($sql);
    }
  }

  if (array_key_exists('day_17', $_POST)) {
    if ($_POST['day_17'] !== "") {
      $sql = "UPDATE `present` SET `day_17` = '" . $_POST['day_17'] . "' WHERE `present`.`checkin_code` = '" . $_POST['checkin_code'] . "'; ";
      $result = $conn->query($sql);
    }
  }

  $result =  $conn->query("SELECT * FROM `present` WHERE `present`.`checkin_code` = '" . $_POST['checkin_code'] . "';")->fetch_all()[0];
  echo json_encode([
    "state" => $result[array_key_exists('day_17', $_POST) ? 2 : 1],
    'days' =>  [
      "day_16" => $result[1],
      "day_17" => $result[2],
    ]
  ]);

  die();
}

header("Location: https://stagon.in/semanapreta/credenciamento/", true, $status);
die();
