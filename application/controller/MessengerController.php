<?php

/**
 * MessengerController
 * Controlls everything messenger related.
 */
class MessengerController extends Controller{

    /**
     * Construct this object by extending the basic Controller class.
     */
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions.
        Auth::checkAuthentication();
    }

    /**
     * Shows messenger options
     * @return void
     */
    public function index(){
        $this->View->render('messenger/index');
    }


}