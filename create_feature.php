<?php

include_once __DIR__ . '/src/Services/RawApiApp.class.php';

use Services\RawApiApp;
use Controller\FeatureController;

    RawApiApp::CreateEnvironment();
    $requestController = new FeatureController();
    echo $requestController->createAction();

