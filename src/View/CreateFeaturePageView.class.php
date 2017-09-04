<?php

namespace View;

use Exceptions\RenderException;

/**
 * Class FeaturePageView
 * @package View
 */
class CreateFeaturePageView extends RenderView
{
    /**
     * @param $homeDataArray
     * @return string
     * @throws RenderException
     */
    public static function RenderView ($homeDataArray)
    {
        $header = parent::Render('_header.html');
        $homepage = parent::Render('createFeaturePage.html');
        $footer = parent::Render('_footer.html');

        return $header . $homepage . $footer;
    }
}