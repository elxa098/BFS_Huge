<div class="container">
    <h1>Tic Tac Toe</h1>

    <div class="box">
        <!-- DROPDOWN -->

        <form method="POST" action="<?= Config::get('URL'); ?>tictactoe/setOpponent">
            <div class="player-selection">
                <label>Play with:</label>

                <?php $selectedOpponent = Session::get('current_opponent'); ?>
                <select name="opponentId" id="opponentId" onchange="this.form.submit()" hint="Select opponent">
                    <option value="" <?php if (empty($selectedOpponent)) { echo 'selected'; } ?>>Gegner auswählen</option>

                    <?php foreach($this->data['users'] as $user): ?>
                        <option value="<?php echo htmlspecialchars($user->user_id); ?>" <?php if ($selectedOpponent == $user->user_id) { echo 'selected'; } ?>>
                            <?php echo htmlspecialchars($user->user_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <!-- GAME BOARD -->
     <form method="POST" action="<?= Config::get('URL'); ?>tictactoe/playGame">
        <table class="tictactoe-table">
            <tr>
                <td><button type="submit" name="move" value="A1"></button></td>
                <td><button type="submit" name="move" value="A2"></button></td>
                <td><button type="submit" name="move" value="A3"></button></td>
            </tr>
            <tr>
                <td><button type="submit" name="move" value="B1"></button></td>
                <td><button type="submit" name="move" value="B2"></button></td>
                <td><button type="submit" name="move" value="B3"></button></td>
            </tr>
            <tr>
                <td><button type="submit" name="move" value="C1"></button></td>
                <td><button type="submit" name="move" value="C2"></button></td>
                <td><button type="submit" name="move" value="C3"></button></td>
            </tr>
        </table>
    </form>

    <div class="box">
        <!-- STATUS -->
        <div class="game-status">
            <span id="statusLabel">Gegner aussuchen um Spiel zu starten.</span>
        </div>

        <!-- RESET GAME -->
        <form method="POST" action="<?= Config::get('URL'); ?>tictactoe/resetGame">
            <button type="submit">Spiel zurücksetzen</button>
        </form>
    </div>
</div>