<?php

namespace View;

use Exceptions\RenderException;

/**
 * Class RenderView
 * @package View
 */
class RenderView
{
    const VIEW_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'templates';

    /**
     * @param $template
     * @param null $dataArray
     * @return string
     * @throws RenderException
     */
    protected static function Render($template, $dataArray = null)
    {
        try {
            $templateFullPath = self::VIEW_DIR . DIRECTORY_SEPARATOR . $template;
            $fileHandler = fopen($templateFullPath, 'rb');
            $templateContent = fread($fileHandler, filesize($templateFullPath));
            $renderedView = (null !== $dataArray)?self::ReplaceVars($templateContent, $dataArray):$templateContent;

            return $renderedView;
        } catch (\Exception $e) {
            throw new RenderException ($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $templateContent
     * @param array $dataArray
     *
     * @return string
     * @throws RenderException
     */
    private static function ReplaceVars($templateContent, $dataArray)
    {
        try {
            $content = '';
            foreach ($dataArray as $dataElement)
            {
                $keys = array_map (
                    function ($key)  {
                        return sprintf('/{{%s}}/', $key);
                    },
                    array_keys($dataElement)
                );
                $values = array_values($dataElement);
                $content .= preg_replace( $keys, $values, $templateContent);
            }
            return $content;
        } catch (\Exception $e) {
            throw new RenderException ($e->getMessage(), $e->getCode());
        }
    }
}