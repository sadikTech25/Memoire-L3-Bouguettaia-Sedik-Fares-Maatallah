<?php session_start(); ob_start();
$_SESSION['account'] = NULL;
session_destroy();
session_regenerate_id();
header("location: ../../../index.html");
