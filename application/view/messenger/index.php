<div class="container messenger-wrapper">

    <h1>Messenger</h1>

    <!-- (removed) CREATE NEW CONVERSATION by user ID input -->

    <!-- ===================================================== -->
    <!-- CREATE GROUP CONVERSATION -->
    <!-- ===================================================== -->
    <div class="new-group" style="margin-top:12px;">

        <form method="post"
              action="<?= Config::get('URL'); ?>messenger/createGroup">

            <div style="margin-bottom:6px;">Select members:</div>
            <div class="group-users" style="max-height:160px; overflow:auto; border:1px solid #eee; padding:8px;">
                <?php foreach ($this->data['users'] as $user): ?>
                    <label style="display:block; margin:4px 0;">
                        <input type="checkbox" name="users[]" value="<?= $user->user_id; ?>"> <?= htmlspecialchars($user->user_name); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <button type="submit" style="margin-top:8px;">Create Group</button>

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