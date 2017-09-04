<?php

namespace Model\Entities;

use Exceptions\APIMissingParametersException;
use Services\DBServices\DBConnection;
use Model\Repository\FeatureRepository;

/**
 * Class Feature
 * @package RawApi\Model
 */
class Feature implements iEntity
{
    /** @var FeatureRepository  */
    protected $repository;
    /** @var string */
    protected $tableName = 'feature';

    /* getters and setters done just in case: not used now

    /** @var  integer */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var  string */
    protected $featureValue;
    /** @var array  */
    protected $features = [];

    /**;
     * Feature constructor.
     */
    public function __construct()
    {
        $this->repository = new FeatureRepository(DBConnection::getConnection());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFeatureValue()
    {
        return $this->featureValue;
    }

    /**
     * @param string $featureValue
     */
    public function setFeatureValue($featureValue)
    {
        $this->featureValue = $featureValue;
    }

    /**
     * @return array
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * @param array $features
     */
    public function setPersons($features)
    {
        $this->persons = $features;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function get($params)
    {
        return $this->repository->select($this->tableName, ['*']);
    }

    /**
     * @param $params
     * @return bool|mixed
     * @throws APIMissingParametersException
     */
    public function post($params)
    {
        if (!$this->isPostValid($params))
        {
            throw new APIMissingParametersException ('Missing parameters in request');
        }

        $name  = isset($params['name'])? $params['name']:'';
        $keys = ['name'];
        $values = [$name];
        try
        {
            $feature = $this->repository->select($this->tableName, ['*'], ['name = ?'], $values);
            if (empty( $feature))
            {
                $this->repository->insert($this->tableName, $keys, [$values]);
                $feature = $this->repository->select( $this->tableName,  ['*'], ['email = ?'], $values);
            }
            return $feature;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $parameters
     * @return bool
     */
    public function isPostValid($params)
    {
        $name  = isset($params['name'])? $params['name']: null;

        return null !== $name;
    }

    /**
     * @param $params
     */
    public function put($params)
    {
        // TODO: Implement put() method.
    }

    /**
     * @param $paramas
     */
    public function delete($paramas)
    {
        // TODO: Implement put() method.
    }
}