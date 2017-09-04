<?php

namespace Services\APIServices;

use Exceptions\APIResourceNotFoundException;
use Exceptions\APIVerbNotAllowedException;

/**
 * Class APIParse
 * @package Services\APIServices
 */
class APIParse
{
    /**
     * @param $verb
     * @return mixed
     * @throws APIVerbNotAllowedException
     */
    public static function getMethod($verb)
    {
        switch ($verb) {
            case 'GET':
            case 'POST':
            case 'PUT':
            case 'DELETE':
                return strtolower($verb);
            default:
                throw new APIVerbNotAllowedException('Verb not allowed');
        }
    }

    /**
     * @param $uri
     * @return mixed
     * @throws APIResourceNotFoundException
     */
    public static function getResource($uri)
    {
        $uriResource = $uri;

        //clean all until api.php or api
        if (!is_bool (strpos($uri, 'api.php'))) {
            $uriResource = substr($uri, strpos($uri,'api.php')+strlen('api.php'));
        }
        if (!is_bool (strpos($uri, '/api/'))) {
            $uriResource = substr($uri, strpos($uri,'/api/')+strlen('/api/'));
        }
        //clean ?q
        $uriResourceExploded = explode('?', $uriResource);
        $uriResourceClean = $uriResourceExploded[0];
        //seek structure
        $uriResourceArray = explode('/', $uriResourceClean);
        if (array_key_exists(0, $uriResourceArray) > 0 && !empty($uriResourceArray[0]))
        {
            $resource = array_shift($uriResourceArray);
            if (in_array ($resource, ENDPOINTS))
            {
                return sprintf('Model\Entities\%s' , ucfirst(strtolower($resource)));
            }
        }

        throw new APIResourceNotFoundException(sprintf('API resource is missing'));
    }

    /**
     * @param $verb
     * @return array
     * @throws APIVerbNotAllowedException
     */
    public static function getParams ($verb)
    {
        switch ($verb)
        {
            case 'get':

                return isset($_GET['q'])?['q' => $_GET['q']]:[];
            case 'post':
            case 'delete':
                return $_POST;
            case 'put':
                return file_get_contents("php://input");
            default:
                throw new APIVerbNotAllowedException('Verb not allowed');
        }
    }
}