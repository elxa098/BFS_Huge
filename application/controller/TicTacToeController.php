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
            $isDraw = TicTacToeModel::isGameDraw($gameId);
            $turnUserId = TicTacToeModel::getCurrentTurn($gameId);
            $board = TicTacToeModel::getBoard($gameId);

            if($winner){
                $winnerProfile = UserModel::getPublicProfileOfUser($winner);
                $winnerName = $winnerProfile ? $winnerProfile->user_name : $winner;
                $status = "Spiel beendet! Gewinner: " . $winnerName;
                $gameFinished = true;
            }
            elseif ($isDraw) {
                $status = "Unentschieden!";
                $gameFinished = true;
            }
            else {
                if($turnUserId == $currentUserId){
                    $status = "Du bist dran!";
                    $isUserTurn = true;
                }
                else{
                    $status = "Gegner ist dran!";
                    $isUserTurn = false;
                }
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
            $activePlayerId = TicTacToeModel::getCurrentTurn($gameId);

            // only allow move if game not finished, the active player exists and position is free
            if(!$winner && $activePlayerId && !TicTacToeModel::isPositionTaken($gameId, $move)){
                TicTacToeModel::makeMove($gameId, $activePlayerId, $move);

                // check for a winner and finish game if found
                self::checkForWinner($gameId);
            }
        }

        Redirect::to('tictactoe');
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
        $isDraw = TicTacToeModel::isGameDraw($gameId);
        $turnUserId = TicTacToeModel::getCurrentTurn($gameId);

        if($winner){
            $winnerProfile = UserModel::getPublicProfileOfUser($winner);
            $winnerName = $winnerProfile ? $winnerProfile->user_name : $winner;
            $status = "Spiel beendet! Gewinner: " . $winnerName;
        }
        elseif ($isDraw) {
            $status = "Unentschieden!";
        }
        else{
            if($turnUserId == $userId){
                $status = "Du bist dran!";
            }
            else{
                $status = "Gegner ist dran!";
            }
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

    private static function checkForWinner($gameId)
    {
        $board = TicTacToeModel::getBoard($gameId);

        $possibleWinningLines = [
            // horizontal
            ['A1', 'A2', 'A3'],
            ['B1', 'B2', 'B3'],
            ['C1', 'C2', 'C3'],
            // vertical
            ['A1', 'B1', 'C1'],
            ['A2', 'B2', 'C2'],
            ['A3', 'B3', 'C3'],
            // diagnonal
            ['A1', 'B2', 'C3'],
            ['A3', 'B2', 'C1'],
        ];

        foreach ($possibleWinningLines as $line) {

            if (isset($board[$line[0]])) {
                $a = $board[$line[0]];
            } 
            else {
                $a = null;
            }

            if (isset($board[$line[1]])) {
                $b = $board[$line[1]];
            } 
            else {
                $b = null;
            }

            if (isset($board[$line[2]])) {
                $c = $board[$line[2]];
            } 
            else {
                $c = null;
            }

            if ($a && $a === $b && $a === $c) {
                $game = TicTacToeModel::getGameData($gameId);
                $winnerUserId = ($a === 'X') ? $game->player_x_id : $game->player_o_id;
                TicTacToeModel::finishGame($gameId, $winnerUserId);
                return $winnerUserId;
            }
        }

        if (count($board) === 9) {
            TicTacToeModel::finishGame($gameId, null);
            return null;
        }

        return false;
    }

}
