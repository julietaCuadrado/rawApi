<?php

include_once __DIR__ . '/src/Services/RawApiApp.class.php';

use Services\RawApiApp;
use Controller\SearchController;

    RawApiApp::CreateEnvironment();
    $requestController = new SearchController();
    echo $requestController->searchAction();

