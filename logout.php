<?php
require_once 'classes/session.php';
$session = new session;
$session->start();

unset($_SESSION['message']);
$session->clearall();
$session->destroy();
header('Location: login.php'); 
exit();
