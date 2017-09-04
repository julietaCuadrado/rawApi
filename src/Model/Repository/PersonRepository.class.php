<?php

namespace Model\Repository;

class PersonRepository extends AbstractRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getPersonById ($id)
    {
        return  $this->select('person', '*', ['id' => $id]);
    }

    /**
     * @return mixed
     */
    public function getAllPersons()
    {
        return  $this->select('person', ['*']);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getPersonByFeatureValue ($value)
    {
        $sql = 'SELECT p.name as person_name, fv.feature_value, f.name as feature_name' .
            ' FROM person p ' .
            ' INNER JOIN feature_value fv ON p.id = fv.person_id ' .
            ' INNER JOIN feature f ON f.id = fv.feature_id ' .
            ' WHERE fv.feature_value like ?';
        $stmt = $this->dbConnection->getHandler()->prepare($sql);
        $likeValue = '%' . $value . '%';
        $stmt->bind_param('s', $likeValue);
        $stmt->execute();
        $result = $stmt->get_result();
        $fetchedResult = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $fetchedResult ;
    }
}