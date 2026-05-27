<?php

/**
 * Handles all messenger operations
 */
class MessengerModel
{
    /**
     * Gets the conversation id of the conversation. If the conversation doesn't alredy exists a new conversation gets created.
     * @param mixed $user1 id of first user
     * @param mixed $user2 id of second user
     * @return int conversation id
     */
    public function getOrCreateConversation($user1, $user2)
    {
        $result = self::getConversationId($user1, $user2);
        if ($result == NULL){
            $result = self::createNewConversation($user1, $user2);
        }

        return $result;
    }

    /**
     * Gets the conversation id when the converstion already exists.
     * @param mixed $user1 id of first user
     * @param mixed $user2 id of sencond user
     * @return mixed converation id or NULL
     */
    private function getConversationId($user1, $user2)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
           SELECT u1.conversation_id
           FROM conversation_participants u1
           INNER JOIN conversation_participants u1 ON u1.conversation_id = u2.conversation_id
           WHERE u1.user_id = :user1 
            AND u2.user_id = :user2
            AND (
                SELECT COUNT(*)
                FROM conversation_participants
                WHERE conversation_id = u1.converation_id
            ) = 2
           LIMIT 1
        ";

        $query = $database->prepare($sql);
        $query->execute([
            ':user1' => $user1,
            ':user2' => $user2
        ]);

        $conversationExists = $query->fetchAll();

        if($conversationExists){
            return $conversationExists['conversation_id'];
        }
        
        return null;
    }

    /**
     * Creates a new conversation
     * @param mixed $user1 id of first user
     * @param mixed $user2 id of second user
     * @return int id of new conversation
     */
    private function createNewConversation($user1, $user2)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // Create new conversation
        $sql = "
            INSERT INTO conversation()
            VALUES ();
        ";

        $query = $database->prepare($sql);
        $query->execute();
        $conversationId = $database->lastInsertId();

        // add participants
        $sql = "
            INSERT INTO conversation_participants(converation_id, user_id)
            VALUES 
                (:conversation_id, :user1),
                (:conversation_id, :user2)
        ";

        $query = $database->prepare($sql);
        $query->execute([
            ':conversation_id' => $conversationId,
            'user1' => $user1,
            'user2' => $user2
        ]);

        return $conversationId;
    }

    /**
     * Sends a message
     * @param mixed $conversationId
     * @param mixed $senderId
     * @param mixed $message
     * @return void
     */
    public static function sendMessage($conversationId, $senderId, $message)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            INSERT INTO messages(conversation_id, sender_id, message_text)
            VALUES (:conversation_id, :sender_id, :message)
        ";

        $query = $database->prepare($sql);
        $query->execute([
            ':conversation_id' => $conversationId,
            ':sender_id' => $senderId,
            ':message' => $message
        ]);
    }

    /**
     * Getting all messages of a conversation
     * @param mixed $conversationId
     * @return array
     */
    public static function getMessages($conversationId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT m.message_id, m.sender_id, u.user_name, m.message_text, m.create_at
            FROM messages m
            INNER JOIN user u
                ON m.sender_id = u.user_id
            WHERE m.converstaion_id = :conversationId
            ORDER BY m.created_at ASC
        ";

        $query = $database->prepare($sql);
        $query->execute([':conversationId' => $conversationId]);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Get all conversation of a user
     * @param mixed $userId
     * @return array
     */
    public static function getUserConversation($userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT c.conversation_id, u.user_id, u.user_name, m.message_text, m.created_at
            FROM conversation c
            INNER JOIN conversation_participants cp
                ON c.conversation_id = cp.conversation_id
            INNER JOIN conversation_participants cp2
                ON c.conversation_id = cp2.conversation_id
                AND cp2.user_id != :current_user
            INNER JOIN u
                ON cp2.user_id = u.userId
            LEFT JOIN messages m
                ON m.message_id = (
                    SELECT m2.message_id
                    FROM messages m2
                    WHERE m2.conversation_id = c.conversation_id
                    ORDER BY m2.created_at DESC
                    LIMIT 1
                )
            WHERE cp.user_id = :userId
            ORDER BY m.created_at DESC
        ";

        $query = $database->prepare($sql);
        $query->execute([':user_id' => $userId]);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Mark conversation as read
     * @param mixed $conversationId
     * @param mixed $userId
     * @return void
     */
    public static function markConversationAsRead($conversationId, $userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            UPDATE conversation_participants
            SET last_read_at = NOW()
            WHERE conversation_id = :conversation_id 
                AND user_id = :current_user
        ";

        $query = $database->prepare($sql);
        $query->execute([
            ':conversation_id' => $conversationId,
            ':current_user' => $userId
        ]);

        return;
    }

    /**
     * Counts unread messages from user
     * @param mixed $userId
     */
    public static function getUnreadMessageCount($userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        
        $sql = "
            SELECT COUNT(*) AS unread_count
            FROM messages m
            INNER JOIN conversation_participants cp
                ON m.conversation_id = cp.conversation_id
            WHERE cp.user_id = :current_user
                AND m.sender_id != :currentUser
                AND (
                    cp.last_read IS NULL
                    OR m.created_at > cp.last_read_at
                )
        ";

        $query = $database->prepare($sql);
        $query->execute(['current_user' => $userId]);
        $result = $query->fetch();

        return $result->unread_count;
    }

    /**
     * Checks if user has access to conversation
     * @param mixed $conversationId
     * @param mixed $userId
     * @return bool
     */
    public static function hasUserAccessToConversation($conversationId, $userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT *
            FROM conversation_participants
            WHERE conversation_id = :conversation_id
                AND user_id = :user_id
        ";

        $query = $database->prepare($sql);
        $query->execute([
            ':conversation_id' =>$conversationId,
            ':user_id' => $userId
        ]);

        return ($query -> rowCount() > 0);
    }

    /**
     * Delete entire conversation
     * @param mixed $conversationId
     * @return void
     */
    public static function deleteConversatioN($conversationId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            DELETE FROM conversations
            WHERE conversation_id = :conversation_id
        ";

        $query = $database->prepare($sql);
        $query->execute(['conversation_id' => $conversationId]);

        return;
    }
}