<div class="chat-header">
    <a href="<?= Config::get('URL'); ?>messenger/index">← Back</a>
</div>

<div class="discussion" >

    <?php foreach ($this->data['messages'] as $message): ?>

        <?php $isOwn = ($message->sender_id == Session::get('user_id')); ?>

        <?php if (!$isOwn): ?>
        <div class="message-sender" style="font-size:0.85em; color:#666; margin:6px 6px 4px;">
            <?= htmlspecialchars($message->user_name); ?>
        </div>
        <?php endif; ?>

        <div class="bubble <?= $isOwn ? 'recipient' : 'sender'; ?>">
            <?= htmlspecialchars($message->message_text); ?>
        </div>

    <?php endforeach; ?>

</div>

<form method="post"
      action="<?= Config::get('URL'); ?>messenger/send/<?= $this->data['conversationId']; ?>"
      class="message-form">

    <input type="text" name="message" placeholder="Type..." required>
    <button>Send</button>

</form>