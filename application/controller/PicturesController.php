<?php

class PicturesController extends Controller
{
    private $pathToPictures = ROOT . "/gallery_pictures";
    private $allowedFileTypes = ['jpg', 'jpeg', 'png', 'pdf'];

    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    } 
    
    /**
     * Show gallery option
     * @return void
     */
    public function index()
    {
        $this->View->render('pictures/index');
    }

    public function upload()
    {
        if (!isset($_FILES['datei']) || $_FILES['datei']['error'] !== UPLOAD_ERR_OK) {
            die('Error: No file uploaded.');
        }

        $file = $_FILES['datei'];

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $this->allowedFileTypes)) {
            die('File type not allowded.');
        }

        $currentUser = Session::get('user_id');

        $userFolder = $this->pathToPictures . "/" . $currentUser;
        if (!is_dir($userFolder)){
            mkdir($userFolder, 0777, true);
        }

        $newFilename = uniqid() . '.' . $fileExtension;
        $destination = $userFolder . "/" . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            header('Location: ' . URL . 'pictures');
            exit;
        } else {
            die('Upload failed.');
        }
    }

    public function download($pictureId)
    {
    }

    public function delete($pictureId)
    {
    }

    public function share($pictureId)
    {
    }
}