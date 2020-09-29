<?php
session_start();

require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/router.php';
require_once 'core/helpers/Path.php';

$path = Path::getInstance("application/config/path.ini");

$router = new Router($_SERVER['REQUEST_URI']);
$router->start();
