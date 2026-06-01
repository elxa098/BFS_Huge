<?php

/**
 * MessengerController
 * Controlls everything messenger related.
 */
class MessengerController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class.
     */
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions.
        Auth::checkAuthentication();
    }

    /**
     * Shows messenger options
     * @return void
     */
    public function index()
    {
        $currentUserId = Session::get('user_id');

        $conversationId = isset($_GET['conversation']) 
            ? (int) $_GET['conversation'] 
            : null;

        $messages = [];

        if ($conversationId) {

            // safety check
            if (MessengerModel::hasUserAccessToConversation($conversationId, $currentUserId)) {
                $messages = MessengerModel::getMessages($conversationId);
                MessengerModel::markConversationAsRead($conversationId, $currentUserId);
            } else {
                $conversationId = null;
            }
        }

        $this->View->render('messenger/index', [
            'conversations' => MessengerModel::getUserConversation($currentUserId),
            'users' => UserModel::getAllUsersExcept($currentUserId),
            'messages' => $messages,
            'selectedConversationId' => $conversationId
        ]);
    }

    /**
     * Open single conversation
     * @param mixed $conversationId
     * @return void
     */
    public function chat($conversationId)
    {
        $currentUserId = Session::get('user_id');

        if(!MessengerModel::hasUserAccessToConversation($conversationId, $currentUserId))
        {
            Redirect::to('messenger/index');
            return;
        }

        $messages = MessengerModel::getMessages($conversationId);

        // MessengerModel::markConversationAsRead($conversationId, $currentUserId);

        $this->View->render('messenger/chat', [
            'messages' => $messages,
            'conversationId' =>$conversationId
        ]);
    }


    /**
     * Create or open conversation from dropdown
     * @return void
     */
    public function createConversation()
    {
        $currentUserId = Session::get('user_id');

        $otherUserId = $_POST['user_id'] ?? null;

        if(!$otherUserId){
            Redirect::to('messenger/index');
            return;
        }

        $conversationId = MessengerModel::getOrCreateConversation($currentUserId, $otherUserId);

        Redirect::to('messenger/index?conversation=' . $conversationId);
    }

    /**
     * Send message
     * @param mixed $conversationId
     * @return void
     */
    public function send($conversationId)
    {
        $currentUserId = Session::get('user_id');

        if(!empty($_POST['message'])){
            MessengerModel::sendMessage($conversationId, $currentUserId, $_POST['message']);
        }

        Redirect::to('messenger/chat/' . $conversationId);
    }

}