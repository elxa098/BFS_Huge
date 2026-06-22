<?php

class TicTacToeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public static function index()
    {
        $currentUserId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');
        
        $status = "Gegner aussuchen um Spiel zu starten.";
        $board = [];
        $isUserTurn = false;
        $gameFinished = false;
        
        if($opponentId){
            $gameId = TicTacToeModel::getGameId($currentUserId, $opponentId);

            // create game if it doesn't exist
            if(!$gameId){
                $gameId = TicTacToeModel::createGame($currentUserId, $opponentId);
            }

            $winner = TicTacToeModel::getWinner($gameId);
            $turnUserId = TicTacToeModel::getCurrentTurn($gameId);
            $board = TicTacToeModel::getBoard($gameId);

            if(!$winner){
                if($turnUserId == $currentUserId){
                    $status = "Du bist dran!";
                    $isUserTurn = true;
                }
                else{
                    $status = "Gegner ist dran!";
                    $isUserTurn = false;
                }
            }
            else{
                $winnerProfile = UserModel::getPublicProfileOfUser($winner);
                $winnerName = $winnerProfile ? $winnerProfile->user_name : $winner;
                $status = "Spiel beendet! Gewinner: " . $winnerName;
                $gameFinished = true;
            }
        }

        $this->View->render('tictactoe/index', [
            'users' => UserModel::getAllUsersExcept($currentUserId),
            'status' => $status,
            'board' => $board,
            'isUserTurn' => $isUserTurn,
            'gameFinished' => $gameFinished,
        ]);
    }

    /**
     * Set opponent ID as environmental variable
     * @return void
     */
    public static function setOpponent()
    {
        Session::set('current_opponent', Request::post('opponentId'));
        Redirect::to('tictactoe');
    }

    /**
     * Set current turn in database
     * @return void
     */
    public static function setCurrentTurn()
    {
        $userId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');
        $turn = Request::post('turn');

        $gameId = TicTacToeModel::getGameId($userId, $opponentId);
        TicTacToeModel::setCurrentTurn($gameId, $turn);
        
        Redirect::to('tictactoe');
    }

    public static function playGame()
    {
        $userId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');
        $move = Request::post('move');

        if(!$opponentId){
            Redirect::to('tictactoe');
        }

        $gameId = TicTacToeModel::getGameId($userId, $opponentId);
        if(!$gameId){
            $gameId = TicTacToeModel::createGame($userId, $opponentId);
        }

        if($move){
            $winner = TicTacToeModel::getWinner($gameId);
            $currentTurnUserId = TicTacToeModel::getCurrentTurn($gameId);

            // only allow move if game not finished and it's user's turn and position is free
            if(!$winner && $currentTurnUserId == $userId && !TicTacToeModel::isPositionTaken($gameId, $move)){
                TicTacToeModel::makeMove($gameId, $userId, $move);

                // check for a winner and finish game if found
                TicTacToeModel::checkWinner($gameId);
            }
        }

        Redirect::to('tictactoe');
    }

    /**
     * Updated status - displays current turn and winner
     * @return void
     */
    public static function status()
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
    public static function resetGame()
    {
        $currentUserId = Session::get('user_id');
        $currentOpponent = Session::get('current_opponent');

        $gameId = TicTacToeModel::getGameId($currentUserId, $currentOpponent);
        TicTacToeModel::deleteGame($gameId);
        Redirect::to('tictactoe');
    }

    private static function checkForWinner()
    {

    }

}
