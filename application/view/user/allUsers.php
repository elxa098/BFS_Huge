<div class="container">
    <h1>User And Group Overview</h1>
    <table id="usersTable" class="overview-table">
        <thead>
            <tr>
                <td>Id</td>
                <td>Avatar</td>
                <td>Username</td>
                <td>User's email</td>
                <td>Activated ?</td>
                <td>Link to user's profile</td>
                <td>Group</td>
                <td>Suspension Time (days)</td>
                <td>Soft delete</td>
            </tr>
        </thead>

        <?php foreach ($this->users as $user) { ?>
            <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">

                <td><?= $user->user_id; ?></td>

                <td class="avatar">
                    <?php if (!empty($user->user_avatar_link)) { ?>
                        <img src="<?= $user->user_avatar_link; ?>" />
                    <?php } ?>
                </td>

                <td><?= $user->user_name; ?></td>

                <td><?= $user->user_email; ?></td>

                <td><?= ($user->user_active == 0 ? 'No' : 'Yes'); ?></td>

                <td>
                    <a href="<?= Config::get('URL') . 'profile/showProfile/' . $user->user_id; ?>">
                        Profile
                    </a>
                </td>

                <td>
                    <?= htmlspecialchars($user->group_name ?? $user->user_account_type); ?>
                </td>

                <td>
                    <?= $user->suspension_time ?? '-'; ?>
                </td>

                <td>
                    <?= ($user->user_deleted ? 'Yes' : 'No'); ?>
                </td>

            </tr>
        <?php } ?>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('#usersTable').DataTable();
    });
</script>