<?php
class TicTacToeModel
{
    /**
     * Creates a new game
     * @param mixed $creatorId
     * @param mixed $opponentId
     * @return bool|string
     */
    public static function createGame($creatorId, $opponentId)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            INSERT INTO tictactoe_games (creator_id, player_x_id, player_o_id)
            VALUES (:creator_id, :player_x_id, :player_o_id);
        ";

        $query = $conn->prepare($sql);
        $success = $query->execute([
            ':creator_id' => $creatorId,
            ':player_x_id' => $creatorId,
            ':player_o_id' => $opponentId
        ]);

        if(!$success){
            return false;
        }

        return $conn->lastInsertId();
    }

    /**
     * User makes move
     * @param mixed $gameId
     * @param mixed $userId
     * @param mixed $position
     * @return bool|string
     */
    public static function makeMove($gameId, $userId, $position)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            INSERT INTO tictactoe_moves (game_id, user_id, position)
            VALUES (:game_id, :user_id, :position);
        ";

        $query = $conn->prepare($sql);
        $success = $query->execute([
            ':game_id' => $gameId,
            ':user_id' => $userId,
            ':position' => $position
        ]);

        if(!$success){
            return false;
        }

        return $conn->lastInsertId();
    }

    /**
     * Set game as finished
     * @param mixed $gameId
     * @param mixed $winnerId
     * @return bool
     */
    public static function finishGame($gameId, $winnerId)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            UPDATE tictactoe_games
            SET
                status = 'finished',
                winner_id = :winnerId,
                finished_at = NOW()
            WHERE id = :game_id;
        ";

        $query = $conn->prepare($sql);
        $success = $query->execute([
            ':winnerId' => $winnerId,
            ':game_id' => $gameId
        ]);

        if(!$success){
            return false;
        }

        return true;
    }

    /**
     * Gets board
     * @param mixed $game_id
     * @return array
     */
    public static function getBoard($game_id)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT
                m.position,
                CASE
                    WHEN m.user_id = g.player_x_id THEN 'X'
                    ELSE 'O'
                END AS symbol
            FROM tictactoe_moves m
            JOIN tictactoe_games g
                ON g.id = m.game_id
            WHERE m.game_id = :game_id
        ";

        $query = $conn->prepare($sql);
        $query->execute([
            ':game_id' => $game_id
        ]);

        return $query->fetchAll();
    }

    /**
     * Delets a game
     * @param mixed $game_id
     * @return bool
     */
    public static function deleteGame($game_id)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            DELETE g, m
            FROM tictactoe_games g
            LEFT JOIN tictactoe_moves m
                ON m.game_id = g.id
            WHERE g.id = :game_id
        ";

        $query = $conn->prepare($sql);

        return $query->execute([
            ':game_id' => $game_id,
            ]);
    }

    /**
     * Gets game id
     * @param mixed $player1
     * @param mixed $player2
     */
    public static function getGameId($player1, $player2)
    {
        $conn = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT id
            FROM tictactoe_games
            WHERE (player_x_id = :p1 AND player_o_id = :p2)
            OR (player_x_id = :p2 AND player_o_id = :p1)
            LIMIT 1
        ";

        $query = $conn->prepare($sql);
        $query->execute([
            ':p1' => $player1,
            ':p2' => $player2
        ]);

        $result = $query->fetch();

        if(!$result){
            return false;
        }

        return $result->id;
    }
}
