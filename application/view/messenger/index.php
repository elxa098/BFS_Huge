<div class="container messenger-wrapper">

    <h1>Messenger</h1>

    <!-- ===================================================== -->
    <!-- CREATE NEW CONVERSATION (by user ID input) -->
    <!-- ===================================================== -->
    <div class="new-conversation">

        <form method="post"
              action="<?= Config::get('URL'); ?>messenger/createConversation">

            <input type="number"
                   name="user_id"
                   placeholder="Enter user ID..."
                   required>

            <button type="submit">Start chat</button>

        </form>

    </div>

    <hr>

    <!-- ===================================================== -->
    <!-- MAIN LAYOUT -->
    <!-- ===================================================== -->
    <div class="messenger-layout" style="display:flex; gap:20px;">

        <!-- ===================================================== -->
        <!-- LEFT: CONVERSATIONS -->
        <!-- ===================================================== -->
        <div class="conversation-list" style="width:30%;">

            <?php if (!empty($this->data['conversations'])) : ?>

                <?php foreach ($this->data['conversations'] as $conversation) : ?>

                    <a class="conversation-item"
                       href="<?= Config::get('URL'); ?>messenger/chat/<?= $conversation->conversation_id; ?>">

                        <div class="conversation-user">
                            <?= htmlspecialchars($conversation->user_name); ?>
                        </div>

                        <div class="conversation-preview">
                            <?= htmlspecialchars($conversation->message_text ?? 'No messages yet'); ?>
                        </div>

                    </a>

                <?php endforeach; ?>

            <?php else : ?>

                <p>No conversations yet.</p>

            <?php endif; ?>

        </div>

        <!-- ===================================================== -->
        <!-- RIGHT: ACTIVE CHAT -->
        <!-- ===================================================== -->
        <div class="chat-window" style="width:70%;">

            <?php if (!empty($this->data['messages'])) : ?>

                <div class="discussion">

                    <?php foreach ($this->data['messages'] as $message) : ?>

                        <?php
                            $isOwn = ($message->sender_id == Session::get('user_id'));
                        ?>

                        <div class="bubble <?= $isOwn ? 'recipient' : 'sender'; ?>">
                            <?= htmlspecialchars($message->message_text); ?>
                        </div>

                    <?php endforeach; ?>

                </div>

                <!-- SEND MESSAGE -->
                <form method="post"
                      action="<?= Config::get('URL'); ?>messenger/send/<?= $this->data['selectedConversationId']; ?>">

                    <input type="text"
                           name="message"
                           placeholder="Write a message..."
                           required>

                    <button type="submit">Send</button>

                </form>

            <?php else : ?>

                <p>Select a conversation on the left to start chatting.</p>

            <?php endif; ?>

        </div>

    </div>

</div>