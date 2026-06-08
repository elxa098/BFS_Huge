<?php

/**
 * PictureModel
 * Handles all pictures/gallery operations
 */
class PicturesModel
{
    /**
     * Gets all images from the current user
     * @return void
     */
    public static function getAllPicturesForUser()
    {
        Session::get('user_id');
    }

    public static function uploadPicture($file)
    {
        // Handle file upload logic here
        // Validate the file, move it to the desired location, and save the file path in the database
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