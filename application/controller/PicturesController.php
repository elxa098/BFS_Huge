<?php

class PicturesController extends Controller
{
    private $pathToPictures;
    private $allowedFileTypes = ['jpg', 'jpeg', 'png', 'pdf'];

    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();

        $this->pathToPictures = dirname(__DIR__, 2) . "/gallery_pictures";
    } 
    
    /**
     * Show gallery option
     * @return void
     */
    public function index()
    {
        $userId = Session::get('user_id');

        $this->View->render('pictures/index',
            ['pictures' => PicturesModel::getAllPicturesForUser($userId)]
        );
    }

    public function image($pictureId)
    {
        $userId = Session::get('user_id');
        $picture = PicturesModel::getPictureById($pictureId);

        if (!$picture) {
            http_response_code(404);
            exit('Not found');
        }

        if ($picture->user_id != $userId) {
            http_response_code(403);
            exit('Forbidden');
        }

        $path = $this->pathToPictures . '/'
            . $picture->user_id . '/'
            . $picture->name;

        if (!file_exists($path)) {
            http_response_code(404);
            exit('File missing');
        }

        header('Content-Type: ' . mime_content_type($path));
        header('Content-Length: ' . filesize($path));

        readfile($path);
        exit;
    }

    // TODO - no upload when pictures could be saved, forced upload when picture could be saved, try catch
    public function upload()
    {
        if (!isset($_FILES['datei']) || $_FILES['datei']['error'] !== UPLOAD_ERR_OK) {
            die('Error: No file uploaded.');
        }

        $file = $_FILES['datei'];
        $currentUser = Session::get('user_id');

        // Check file extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $this->allowedFileTypes)) {
            die('File type not allowded.');
        }

        // Create new unique file name
        $newFilename = uniqid() . '.' . $fileExtension;

        // Get new picture saving path
        $userFolder = $this->pathToPictures . "/" . $currentUser;
        if (!is_dir($userFolder)){
            mkdir($userFolder, 0755, true);
        }
        $destination = $userFolder . "/" . $newFilename;

        // Move picture to saving path
        if (move_uploaded_file($file['tmp_name'], $destination)) {

            // Generate unique hash code for sharing
            $shareLink = $this->generateUniqueShareLink();

            // Upload picture details into database
            $success = PicturesModel::uploadPicture($currentUser, $newFilename, $file['size'], $shareLink);
            if ($success){
                Redirect::to('pictures');
            } 
            else {
                die('Database insertion failed');
            }
        } 
        else {
            die('Upload failed.');
        }

    }

    /**
     * Generate a unique share link hash code
     * @return string
     */
    private function generateUniqueShareLink()
    {
        do {
            $shareLink = bin2hex(random_bytes(16));
        } while (PicturesModel::checkIfLinkExists($shareLink));

        return $shareLink;
    }

    public function download($pictureId)
    {
    }

    public function delete($pictureId)
    {
        $userId = Session::get('user_id');
        $picture = PicturesModel::getPictureById($pictureId);

        if (!$picture) {
            http_response_code(404);
            exit('Picture not found');
        }

        if ($picture->user_id != $userId) {
            http_response_code(403);
            exit('Forbidden: You can only delete your own pictures');
        }

        $path = $this->pathToPictures . '/'
            . $picture->user_id . '/'
            . $picture->name;

        // Delete file from server
        if (file_exists($path)) {
            unlink($path);
        }

        // Delete record from database
        $success = PicturesModel::deletePicture($pictureId);
        
        if ($success) {
            Redirect::to('pictures');
        } else {
            die('Database deletion failed');
        }
    }

    public function share($pictureId)
    {
    }
}