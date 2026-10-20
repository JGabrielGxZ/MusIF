<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php";

session_unset();
session_destroy();
header("Location: ../views/login.php");
?>