<?php

namespace Controller;

use Services\APIServices\APIParse;
use Model\Entities\Person;

/**
 * Class APIController
 * @package Controller
 */
class APIController
{
    /**
     * @return mixed
     * @throws \Exceptions\APIResourceNotFoundException
     * @throws \Exceptions\APIVerbNotAllowedException
     */
    public function API()
    {
        $verb = APIParse::getMethod($_SERVER['REQUEST_METHOD']);
        $resource = APIParse::getResource( $_SERVER['REQUEST_URI']);
        $params = APIParse::getParams ($verb);

        $resourceObject = new $resource();

        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        try
        {
            $results = $resourceObject->$verb($params);
            $data = [
                'status' => '200',
                'results' => $results,
            ];
            $content = json_encode($data, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $data = [
                'status' => '500',
                'error' => $e->getMessage(),
            ];
            $content = json_encode($data,JSON_PRETTY_PRINT);
        }
        return $content;
    }
}