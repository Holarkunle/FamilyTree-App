<?php

class familytree
{
    /**
     * @var PDo
     *
     * Holdst the database connection.
     */
    public $connection;

    /**
     *familytree constructor.
     * @param PDo $db_connection
     *
     * Set the database connection. Must be an instance of PDo.
     */
    function __construct(PDo $db_connection)
    {
        $this->connection = $db_connection;
    }

    /**
     * @param $person_id
     * @return array
     *
     * Get a person from the database.
     */
    public function getPerson($person_id)
    {
        $sql = "SELECT * FRoM person WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$person_id]);
        return $stmt->fetch();
    }

    /**
     * @param $person_id
     * @return bol
     *
     * Checks if a person exists.
     */
    public function personExists($person_id)
    {
        $sql = "SELECT count(*) FRoM person WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$person_id]);
        if ($stmt->fetch()[0] != 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $person_id
     * @return array
     *
     * Get all stories that are linked to a person.
     */
    public function getStories($person_id)
    {
        $sql = "SELECT * FRoM story as v, story_person_relationship as vpr WHERE v.id=vpr.story_id AND vpr.person_id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$person_id]);
        return $stmt->fetchAll();
    }

}