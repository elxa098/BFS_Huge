<div class="chat-header">
    <a href="<?= Config::get('URL'); ?>messenger/index">← Back</a>
</div>

<div class="discussion" >

    <?php foreach ($this->data['messages'] as $message): ?>

        <?php $isOwn = ($message->sender_id == Session::get('user_id')); ?>

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