<?php

namespace Controller;

use Model\Repository\FeatureRepository;
use Services\DBServices\DBConnection;
use View\HomePageView;
use View\PersonView;
use Model\Repository\PersonRepository;

/**
 * Class IndexController
 * @package Controller
 */
class IndexController
{
    /**
     * @return string
     * @throws \Exceptions\RenderException
     */
    public function showHomeAction()
    {
        $personRepository = new PersonRepository(DBConnection::getConnection());
        $featureRepository = new FeatureRepository(DBConnection::getConnection());
        $personArray = $personRepository->getAllPersons();
        $featuresArray = $featureRepository->getAllFeatures();

        return HomePageView::RenderView(['persons' => $personArray, 'features' => $featuresArray]);
    }

}