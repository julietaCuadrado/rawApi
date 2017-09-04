<?php

namespace Model\Repository;

class FeatureRepository extends AbstractRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getFeatureById ($id)
    {
        return  $this->select('Feature', ['*'], ['id' => $id]);
    }

    /**
     * @return mixed
     */
    public function getAllFeatures()
    {
        return  $this->select('Feature', ['*']);
    }
}