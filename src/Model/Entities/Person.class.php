<?php

namespace Model\Entities;

use Services\DBServices\DBConnection;
use Model\Repository\PersonRepository;
use Exceptions\APIMissingParametersException;

/**
 * Class Person
 * @package RawApi\Model
 */
class Person implements iEntity
{
    /** @var PersonRepository */
    protected $repository;
    protected $tableName = 'person';
    
    /* getters and setters done just in case: not used now
    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var  string */
    protected $email;
    /** @var array */
    protected $features = [];

    /**
     * Person constructor.
     */
    public function __construct()
    {
        $this->repository = new PersonRepository(DBConnection::getConnection());
    }

    /**
     * @param $params
     * @return mixed
     */
    public function get($params)
    {
        if (array_key_exists('q', $params))
        {
            return $this->repository->getPersonByFeatureValue($params['q']);
        }
        try {
            $results = $this->repository->select($this->tableName, ['*']);
        } catch (  \Exception $e) {
            $results = "error found";
        }
        return $results;
    }

    /**
     * @param $params
     * @throws APIMissingParametersException
     */
    public function post($params)
    {
        if (!$this->isPostValid($params))
        {
            throw new APIMissingParametersException ('Missing parameters in request');
        }
        $name  = isset($params['name'])? $params['name']:'';
        $email = isset($params['email'])? $params['email']:'';

        $keys = ['name', 'email'];
        $values = [$name, $email];
        try
        {

            $person = $this->repository->select($this->tableName, ['*'], ['email = ?'], [ $email ]);
            if (0 === count( $person))
            {
                $this->repository->insert($this->tableName, $keys, $values);
                $person = $this->repository->select( $this->tableName,  ['*'], ['email = ?'], [ $email ]);
            }
            return $person;
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * @param $parameters
     * @return bool
     */
    private function isPostValid($parameters)
    {
        $name  = isset($params['name'])? $params['name']: '';
        $email = isset($params['email'])? $params['email']: '';

        return null !== $name && null !== $email;
    }

    /**
     * @param $params
     * @return bool
     */
    public function put($params)
    {
        // TODO: Implement put() method.
        return false;
    }

    /**
     * @param $params
     * @return bool
     */
    public function delete($params)
    {
        // TODO: Implement delete() method.
        return false;
    }
}