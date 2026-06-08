<?php

/**
 * PictureController
 * Controlls everything gallery related.
 */
class PicturesController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class.
     */
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
}