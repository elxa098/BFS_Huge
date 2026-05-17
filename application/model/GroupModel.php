<?php
class GroupModel
{
    public static function getAllGroups()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT * FROM user_groups ORDER BY name ASC";

        $query = $database->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
}