<div class="container">
    <h1>Tic Tac Toe</h1>

    <div class="box">
        <!-- DROPDOWN -->

        <form method="POST" action="/tictactoe/setOpponent">
            <div class="player-selection">
                <label>Play with:</label>
                <select name="opponentId" id="opponentId" onchange="this.form.submit()">
                    <option value="">Gegner auswählen</option>

                    <?php foreach($this->data['users'] as $user): ?>
                        <option value="<?php echo htmlspecialchars($user->user_id); ?>">
                            <?php echo htmlspecialchars($user->user_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <!-- GAME BOARD -->
    <table class="tictactoe-table">
        <tr>
            <td><button id="ttt-a1"></button></td>
            <td><button id="ttt-a2"></button></td>
            <td><button id="ttt-a3"></button></td>
        </tr>
        <tr>
            <td><button id="ttt-b1"></button></td>
            <td><button id="ttt-b2"></button></td>
            <td><button id="ttt-b3"></button></td>
        </tr>
        <tr>
            <td><button id="ttt-c1"></button></td>
            <td><button id="ttt-c2"></button></td>
            <td><button id="ttt-c3"></button></td>
        </tr>
    </table>

    <div class="box">
        <!-- STATUS -->
        <div class="game-status">
            <span id="statusLabel">Gegner aussuchen um Spiel zu starten.</span>
        </div>

        <!-- RESET GAME -->
        <form method="POST" action="tictactoe/resetGame">
            <div class="actions">
                <button type="submit">Spiel zurücksetzen</button>
            </div>
        </form>

    </div>
</div>