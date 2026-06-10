<?php

/**
 * PictureModel
 * Handles all pictures/gallery operations
 */
class PicturesModel
{
    /**
     * Gets all images from the current user
     * @param mixed $userId
     * @return array
     */
    public static function getAllPicturesForUser($userId)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT * FROM user_pictures
            WHERE user_id = :user_id
            ORDER BY uploaded_at DESC;
        ";

        $query = $conn->prepare($sql);
        $query->execute([':user_id' => $userId]);

        return $query->fetchAll();
    }

    /**
     * Get picture by ID
     * @param mixed $id
     * @return bool|mixed|null
     */
    public static function getPictureById($id)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT * FROM user_pictures 
            WHERE id = :id 
            LIMIT 1;
        ";

        $query = $conn->prepare($sql);
        $query->execute([':id' => $id]);

        return $query->fetch();
    }

    public static function uploadPicture(int $user_id, string $name, int $size, string $link)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            INSERT INTO user_pictures (user_id, name, size, link)
            VALUES (:user_id, :name, :size, :link)
        ";

        $query = $conn->prepare($sql);
        
        return $query->execute([
            ':user_id' => $user_id,
            ':name' => $name,
            ':size' => $size,
            ':link' => $link
        ]);
    }

    public static function downloadPicture($pictureId)
    {
        // Handle file download logic here
        // Retrieve the file path from the database using the picture ID and serve the file for download
    }

    public static function deletePicture($pictureId)
    {
        // Handle file deletion logic here
        // Remove the file from the server and delete the corresponding entry from the database
    }

    public static function getSharingLink($pictureId)
    {
        // Generate a unique sharing link for the picture
        // Save the sharing link in the database and return it to the user
    }
}