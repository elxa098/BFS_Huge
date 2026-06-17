<div class="container">
    <h1>Tic Tac Toe</h1>

    <div class="box">
        <div class="player-selection">
            <label>Play with:</label>
            <select id="opponent">
                <option value="">Gegner auswählen</option>

                <?php foreach($this->data['users'] as $user): ?>
                    <option value="<?php echo htmlspecialchars($user->user_id); ?>">
                        <?php echo htmlspecialchars($user->user_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

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
        <div class="game-status">
            <span id="statusLabel">Gegner aussuchen um Spiel zu starten.</span>
        </div>

        <div class="actions">
            <button id="resetGame">Spiel zurücksetzen</button>
        </div>
    </div>
</div>