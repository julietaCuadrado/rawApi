<?php

include_once __DIR__ . '/src/Services/RawApiApp.class.php';

use Services\RawApiApp;
use Controller\APIController;

    RawApiApp::CreateEnvironment();
    $controller = new APIController();
    echo $controller->API();
