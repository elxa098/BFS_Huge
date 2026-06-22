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
        $opponentId = Session::get('current_opponent');
        
        $status = "Gegner aussuchen um Spiel zu starten.";
        
        if($opponentId){
            $gameId = TicTacToeModel::getGameId($currentUserId, $opponentId);
            $winner = TicTacToeModel::getWinner($gameId);
            $turn = TicTacToeModel::getCurrentTurn($gameId);
            
            if(!$winner){
                if($turn == "X"){
                    $status = "Du bist dran!";
                }
                else{ // turn == O
                    $status = "Gegner ist dran!";
                }
            }
            else{
                $status = "Spiel beendet! Gewinner: " . $winner;
            }
        }

        $this->View->render('tictactoe/index', [
            'users' => UserModel::getAllUsersExcept($currentUserId),
            'status' => $status,
        ]);
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

    /**
     * Set current turn in database
     * @return void
     */
    public function setCurrentTurn()
    {
        $userId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');
        $turn = Request::post('turn');

        $gameId = TicTacToeModel::getGameId($userId, $opponentId);
        TicTacToeModel::setCurrentTurn($gameId, $turn);
        
        Redirect::to('tictactoe');
    }

    public function playGame()
    {
        $userId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');

        $gameId = TicTacToeModel::getGameId($userId, $opponentId);
        $boardId = TicTacToeModel::getBoard($gameId);

        if(!empty($boardId)){
            $gameId = TicTacToeModel::createGame($userId, $opponentId);
            $boardId = TicTacToeModel::getBoard($gameId);
        }


    }

    /**
     * Updated status - displays current turn and winner
     * @return void
     */
    public function status()
    {
        $userId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');

        $gameId = TicTacToeModel::getGameId($userId, $opponentId);
        $winner = TicTacToeModel::getWinner($gameId);
        $turn = TicTacToeModel::getCurrentTurn($gameId);

        if(!$winner){
            if($turn == "X"){
                $status = "Du bist dran!";
            }
            else{ // turn == O
                $status = "Gegner ist dran!";
            }
        }
        else{
            $status = "Spiel beendet! Gewinner: " . $winner;
        }

        echo json_encode(['status' => $status]);
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

    

}
