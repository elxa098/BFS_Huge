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

        $sql = "

        ";

        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();

        return $result['conversation_id'];
    }
}