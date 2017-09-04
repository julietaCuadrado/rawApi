<?php

namespace View;

use Exceptions\RenderException;

/**
 * Class SearchPageView
 * @package View
 */
class SearchPageView extends RenderView
{
    /**
     * @param $homeDataArray
     * @return string
     * @throws RenderException
     */
    public static function RenderView ($homeDataArray)
    {
        $header = parent::Render('_header.html');
        $homepage = parent::Render('searchPage.html');
        $footer = parent::Render('_footer.html');

        return $header . $homepage . $footer;
    }
}