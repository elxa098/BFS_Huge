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

        $sql = "CALL get_conversation_id(:user1, :user2);";

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
        $sql = "CALL create_new_conversation(:user1, :user2);";

        $query = $database->prepare($sql);
        $query->execute([
            ':user1' => $user1,
            ':user2' => $user2
        ]);
        $conversationId = $query->fetch()['conversation_id'];

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

        $sql = "CALL send_message(:conversation_id, :sender_id, :message);";

        $query = $database->prepare($sql);
        $query->execute([
            ':conversation_id' => $conversationId,
            ':sender_id' => $senderId,
            ':message' => $message
        ]);

        return;
    }

    /**
     * Getting all messages of a conversation
     * @param mixed $conversationId
     * @return array
     */
    public static function getMessages($conversationId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL get_all_messenges_from_conversation(:conversationId);";

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

        $sql = "CALL get_conversations_from_user(:user_id);";

        $query = $database->prepare($sql);
        $query->execute([':user_id' => $userId]);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Create a conversation and add multiple participants (group chat support)
     * @param array $userIds
     * @return void
     */
    public static function createConversationWithParticipants(array $userIds)
    {
        if (empty($userIds)) {
            throw new InvalidArgumentException('At least one user id required');
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL create_conversation_with_participants(:user_ids);";

        $query = $database->prepare($sql);
        $query->execute([':user_ids' => implode(',', $userIds)]);
        
        return;
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

        $sql = "CALL mark_conversation_as_read(:current_user, :conversation_id);";

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
        
        $sql = "CALL total_unread_message_count_per_user(:current_user);";

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

        $sql = "CALL has_user_access_to_conversation(:user_id, :conversation_id);";

        $query = $database->prepare($sql);
        $query->execute([
            ':conversation_id' =>$conversationId,
            ':user_id' => $userId
        ]);

        return ($query -> rowCount() > 0);
    }

}