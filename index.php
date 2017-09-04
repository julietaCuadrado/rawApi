<?php

include_once __DIR__ . '/src/Services/RawApiApp.class.php';

use Services\RawApiApp;
use Controller\IndexController;

    RawApiApp::CreateEnvironment();
    $requestController = new IndexController();
    echo $requestController->showHomeAction();

