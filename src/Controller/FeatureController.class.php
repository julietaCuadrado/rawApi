<?php

namespace Controller;

use View\CreateFeaturePageView;

/**
 * Class SearchController
 * @package Controller
 */
class FeatureController
{
    /**
     * @return string
     */
    public function createAction()
    {
        return CreateFeaturePageView::RenderView('create_feature.html');
    }
}