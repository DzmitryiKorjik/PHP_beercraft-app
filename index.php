<?php
session_start();
$_SESSION['test'] = 'Session OK';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/app/routes/router.php';

$router = new Router();
$router->handleRequest();
?>
