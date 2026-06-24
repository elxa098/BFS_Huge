<div class="container">
    <h1>Tic Tac Toe</h1>

    <?php
        $url = Config::get('URL');
        $selectedOpponent = Session::get('current_opponent');
        $board = $this->data['board'];
        $isUserTurn = $this->data['isUserTurn'];
        $gameFinished = $this->data['gameFinished'] ?? false;

        $rows = [
            ['A1', 'A2', 'A3'],
            ['B1', 'B2', 'B3'],
            ['C1', 'C2', 'C3']
        ];
    ?>

    <div class="box">
        <!-- Dropdown + Status -->
        <div class="tictactoe-header-row">

            <form method="POST" action="<?= $url ?>tictactoe/setOpponent" class="player-selection-form">
                <div class="player-selection">
                    <label>Play with:</label>

                    <select name="opponentId" id="opponentId" onchange="this.form.submit()">
                        <option value="" <?= empty($selectedOpponent) ? 'selected' : '' ?>>
                            Gegner auswählen
                        </option>

                        <?php foreach ($this->data['users'] as $user): ?>
                            <option value="<?= htmlspecialchars($user->user_id) ?>"
                                <?= ($selectedOpponent == $user->user_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user->user_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="status-panel">
                <p id="statusLabel"><?= htmlspecialchars($this->data['status']) ?></p>
            </div>

        </div>

        <!-- Reset -->
        <div class="reset-wrapper">
            <form method="POST" action="<?= $url ?>tictactoe/resetGame">
                <button type="submit">Spiel zurücksetzen</button>
            </form>
        </div>
    </div>

    <!-- GAME BOARD -->
    <form method="POST" action="<?= $url ?>tictactoe/playGame">
        <table class="tictactoe-table">

            <?php foreach ($rows as $row): ?>
                <tr>
                    <?php foreach ($row as $pos): ?>
                        <?php
                            $taken = isset($board[$pos]);
                            $value = $taken ? htmlspecialchars($board[$pos]) : '';
                            $disabled = $taken || !$isUserTurn || $gameFinished;
                        ?>

                        <td>
                            <button type="submit"
                                    name="move"
                                    value="<?= $pos ?>"
                                    <?= $disabled ? 'disabled' : '' ?>>
                                <?= $value ?>
                            </button>
                        </td>

                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

        </table>
    </form>
</div>