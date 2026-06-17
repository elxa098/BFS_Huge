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

    public function playGame()
    {
        $userId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');

        $gameId = TicTacToeModel::getGameId($userId, $opponentId);
        $board = TicTacToeModel::getBoard($gameId);

        if(!empty($board)){
            $gameId = TicTacToeModel::createGame($userId, $opponentId);
            $board = TicTacToeModel::getBoard($gameId);
        }

        
    }


    /**
     * Reset game and delete game data from database
     * @return void
     */
    public function resetGame()
    {
        $currentUserId = Session::get('user_id');
        $currentOpponent = Session::get('current_opponent');

        $gameId = TicTacToeModel::getGameId($currentUserId, $currentOpponent);
        TicTacToeModel::deleteGame($gameId);
        Redirect::to('tictactoe');
    }

    /**
     * Set opponent ID as environmental variable
     * @return void
     */
    public function setOpponent()
    {
        Session::set('current_opponent', Request::post('opponentId'));
        Redirect::to('tictactoe');
    }

}
