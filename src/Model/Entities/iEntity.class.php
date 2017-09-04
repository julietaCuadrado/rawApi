<?php

namespace Model\Entities;

/**
 * Interface iEntity
 * @package Model\Entities
 */
interface iEntity
{
    /**
     * @param $params
     * @return mixed
     */
    public function get($params);

    /**
     * @param $params
     * @return mixed
     */
    public function post($params);

    /**
     * @param $params
     * @return mixed
     */
    public function put($params);

    /**
     * @param $params
     * @return mixed
     */
    public function delete($params);
}