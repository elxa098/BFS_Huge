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
                <td>
                    <?php $pos = 'A1'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="A1" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
                <td>
                    <?php $pos = 'A2'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="A2" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
                <td>
                    <?php $pos = 'A3'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="A3" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
            </tr>
            <tr>
                <td>
                    <?php $pos = 'B1'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="B1" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
                <td>
                    <?php $pos = 'B2'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="B2" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
                <td>
                    <?php $pos = 'B3'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="B3" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
            </tr>
            <tr>
                <td>
                    <?php $pos = 'C1'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="C1" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
                <td>
                    <?php $pos = 'C2'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="C2" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
                <td>
                    <?php $pos = 'C3'; $taken = isset($this->data['board'][$pos]); ?>
                    <button type="submit" name="move" value="C3" <?php echo ($taken || !$this->data['isUserTurn'] || ($this->data['gameFinished'] ?? false)) ? 'disabled' : ''; ?>><?php echo $taken ? htmlspecialchars($this->data['board'][$pos]) : ''; ?></button>
                </td>
            </tr>
        </table>
    </form>

    <div class="box">
        <!-- STATUS -->
        <p id="statusLabel"><?php echo htmlspecialchars($this->data['status']); ?></p>
        

        <!-- RESET GAME -->
        <form method="POST" action="<?= Config::get('URL'); ?>tictactoe/resetGame">
            <button type="submit">Spiel zurücksetzen</button>
        </form>
    </div>
</div>