<?php

/**
 * TicTacToeController
 * Handle Tic Tac Toe game
 */
class TicTacToeController extends Controller
{
    private const WINNING_LINES = [
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

    /**
     * Construct this objet extending the basic controller
     */
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    /**
     * Show TicTacToe game
     * @return void
     */
    public function index()
    {
        $currentUserId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');
        
        $status = "Gegner aussuchen um Spiel zu starten.";
        $board = [];
        $isUserTurn = false;
        $gameFinished = false;
        
        if($opponentId){
            $gameId = self::getOrCreateGame($currentUserId, $opponentId);

            $winner = TicTacToeModel::getWinnerId($gameId);
            $isDraw = TicTacToeModel::isGameDraw($gameId);
            $turnUserId = TicTacToeModel::getCurrentTurn($gameId);
            $board = TicTacToeModel::getBoard($gameId);

            if($winner){
                $status = "Spiel beendet! Gewinner: " . self::getWinnerName($winner);
                $gameFinished = true;
            }
            else if ($isDraw) {
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

    /**
     * Handle game play
     * @return void
     */
    public function playGame()
    {
        $userId = Session::get('user_id');
        $opponentId = Session::get('current_opponent');
        $move = Request::post('move');

        if(!$opponentId || !$move){
            Redirect::to('tictactoe');
        }

        $gameId = self::getOrCreateGame($userId, $opponentId);

        $winnerId = TicTacToeModel::getWinnerId($gameId);
        $activePlayerId = TicTacToeModel::getCurrentTurn($gameId);
        $isPossitionTaken = TicTacToeModel::isPositionTaken($gameId, $move);

        if(!$winnerId && $activePlayerId && !$isPossitionTaken){
            TicTacToeModel::makeMove($gameId, $activePlayerId, $move);
            self::checkForWinner($gameId);
        }

        Redirect::to('tictactoe');
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
     * Checking for winner
     * @param mixed $gameId
     */
    private static function checkForWinner($gameId)
    {
        $board = TicTacToeModel::getBoard($gameId);

        foreach (self::WINNING_LINES as $line) {

            if (isset($board[$line[0]])) { $firstCell = $board[$line[0]]; } else { $firstCell = null; }
            if (isset($board[$line[1]])) { $secondCell = $board[$line[1]]; } else { $secondCell = null; }
            if (isset($board[$line[2]])) { $thirdCell = $board[$line[2]]; } else { $thirdCell = null; }

            if ($firstCell && $firstCell === $secondCell && $firstCell === $thirdCell) {
                $game = TicTacToeModel::getGameData($gameId);
                if($firstCell === 'X'){
                    $winnerUserId = $game->player_x_id;
                }
                else{
                    $winnerUserId = $game->player_o_id;
                }
                TicTacToeModel::finishGame($gameId, $winnerUserId);
                return $winnerUserId;
            }
        }

        if (count($board) === 9) {
            TicTacToeModel::finishGame($gameId, -1);
            return null;
        }

        return false;
    }

    /**
     * Gets the winner name from the user ID
     * @param mixed $winnerId
     */
    private static function getWinnerName($winnerId)
    {
        $winnerProfile = UserModeL::getPublicProfileOfUser($winnerId);

        if($winnerProfile){
            return $winnerProfile->user_name;
        }

        return $winnerId;
    }

    /**
     * Get the game ID (creates a game when there isn't already one)
     * @param mixed $userId
     * @param mixed $opponentId
     */
    private static function getOrCreateGame($userId, $opponentId)
    {
        $gameId = TicTacToeModel::getGameId($userId, $opponentId);

        if(!$gameId){
            $gameId = TicTacToeModel::createGame($userId, $opponentId);
        }

        return $gameId;
    }

}
