<?php
class GroupModel
{
    /**
     * Retrieves all user groups from the database
     * @return array List of all user groups ordered alphabetically by name
     */
    public static function getAllGroups()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM user_groups ORDER BY name ASC";

        $query = $database->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
}