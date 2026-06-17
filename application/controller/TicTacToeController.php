<?php

class TicTacToeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public function index()
    {
        $currentUserId = Session::get('user_id');

        $this->View->render('tictactoe/index', [
            'users' => UserModel::getAllUsersExcept($currentUserId),
        ]);
    }


}
