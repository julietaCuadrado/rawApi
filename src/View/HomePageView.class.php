<?php

namespace View;

use Exceptions\RenderException;

/**
 * Class HomePageView
 * @package View
 */
class HomePageView extends RenderView
{
    /**
     * @param $homeDataArray
     * @return string
     * @throws RenderException
     */
    public static function RenderView ($homeDataArray)
    {
        if(!array_key_exists('persons', $homeDataArray))
        {
            throw new RenderException('Missing key person in ' . HomePageView::class);
        }
        if(!array_key_exists('features', $homeDataArray))
        {
            throw new RenderException('Missing key features in ' . HomePageView::class);
        }
        
        $contentPersons = parent::Render('_homePersonSnippet.html', $homeDataArray['persons']);
        $contentFeatures = parent::Render('_homeFeatureSnippet.html', $homeDataArray['features']);

        $header = parent::Render('_header.html');
        $homepage = parent::Render('homePage.html', [ ['persons' => $contentPersons, 'features' => $contentFeatures] ] );
        $footer = parent::Render('_footer.html');

        return $header . $homepage . $footer;
    }
}