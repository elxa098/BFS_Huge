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


    public function resetGame()
    {
        $currentUserId = Session::get('user_id');
        $currentOpponent = Session::get('current_opponent');

        $gameId = TicTacToeModel::getGameId($currentUserId, $currentOpponent);
        TicTacToeModel::deleteGame($gameId);
    }

    public function setOpponent()
    {
        Session::set('current_opponent', Request::post('opponentId'));
        Redirect::to('tictactoe/index');
    }

}
