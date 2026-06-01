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
    <div class="messenger-layout">

        <!-- ===================================================== -->
        <!-- LEFT: CONVERSATIONS -->
        <!-- ===================================================== -->
        <div class="conversation-list">

            <?php if (!empty($this->data['conversations'])) : ?>

                <?php foreach ($this->data['conversations'] as $conversation) : ?>

                    <a class="conversation-item"
                       href="<?= Config::get('URL'); ?>messenger/chat/<?= $conversation->conversation_id; ?>"
                       style="display:block; margin-bottom:10px; padding:12px;">

                        <div class="conversation-user">
                            <?= htmlspecialchars($conversation->user_name); ?>

                            <?php if (isset($conversation->unread_count) && $conversation->unread_count > 0): ?>
                                <span class="notification-badge">
                                    <?= $conversation->unread_count ?>
                                </span>
                            <?php endif; ?>
                        </div>

                    </a>

                <?php endforeach; ?>

            <?php else : ?>

                <p>No conversations yet.</p>

            <?php endif; ?>

        </div>

    </div>

</div>