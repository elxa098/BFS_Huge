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
    public static function getOrCreateConversation($user1, $user2)
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
    private static function getConversationId($user1, $user2)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT cp1.conversation_id
            FROM conversation_participants cp1
            INNER JOIN conversation_participants cp2
                ON cp1.conversation_id = cp2.conversation_id
            WHERE cp1.user_id = :user1
            AND cp2.user_id = :user2
            GROUP BY cp1.conversation_id
            HAVING COUNT(*) = 2
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
    private static function createNewConversation($user1, $user2)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // Create new conversation
        $sql = "
            INSERT INTO conversations
            VALUES ();
        ";

        $query = $database->prepare($sql);
        $query->execute();
        $conversationId = $database->lastInsertId();

        // add participants
        $sql = "
            INSERT INTO conversation_participants(conversation_id, user_id)
            VALUES 
                (:conversation_id, :user1),
                (:conversation_id, :user2)
        ";

        $query = $database->prepare($sql);
        $query->execute([
            ':conversation_id' => $conversationId,
            ':user1' => $user1,
            ':user2' => $user2
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
            SELECT m.message_id, m.sender_id, u.user_name, m.message_text, m.created_at
            FROM messages m
            INNER JOIN users u
                ON m.sender_id = u.user_id
            WHERE m.conversation_id = :conversationId
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
            SELECT
                c.conversation_id,
                u.user_id,
                u.user_name,
                m.message_text,
                m.created_at

            FROM conversations c

            INNER JOIN conversation_participants cp
                ON c.conversation_id = cp.conversation_id

            INNER JOIN conversation_participants cp2
                ON c.conversation_id = cp2.conversation_id
                AND cp2.user_id != :user_id

            INNER JOIN users u
                ON cp2.user_id = u.user_id

            LEFT JOIN messages m
                ON m.message_id = (
                    SELECT m2.message_id
                    FROM messages m2
                    WHERE m2.conversation_id = c.conversation_id
                    ORDER BY m2.created_at DESC
                    LIMIT 1
                )

            WHERE cp.user_id = :user_id

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
                AND m.sender_id != :current_user
                AND (
                    cp.last_read_at IS NULL
                    OR m.created_at > cp.last_read_at
                )
        ";

        $query = $database->prepare($sql);
        $query->execute([':current_user' => $userId]);
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
    public static function deleteConversation($conversationId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            DELETE FROM conversations
            WHERE conversation_id = :conversation_id
        ";

        $query = $database->prepare($sql);
        $query->execute([':conversation_id' => $conversationId]);

        return;
    }
}