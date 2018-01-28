<?php
// Routes

use Slim\Http\Request;
use Slim\Http\Response;

require_once 'Manager/DbManager.php';
require_once 'controllers/Route.php';
require_once 'controllers/UserRoutes.php';
require_once 'controllers/OffertaRoutes.php';

UserRoutes::register_routes($app);
OffertaRoutes::register_routes($app);
