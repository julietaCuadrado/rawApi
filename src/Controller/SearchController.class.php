<?php

namespace Controller;

use View\SearchPageView;

/**
 * Class SearchController
 * @package Controller
 */
class SearchController
{
    /**
     * @return string
     */
    public function searchAction()
    {
        return SearchPageView::RenderView('search.html');
    }
}