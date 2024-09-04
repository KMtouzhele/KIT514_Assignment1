<?php
class ViewPermission
{
    public function output($permissions)
    {
        ?>
        <html>

        <head>
            <title>
                Permission
            </title>
            <link rel="stylesheet" type="text/css" href="/css/style.css">
        </head>

        <body>
            <h1>Permission</h1>
            <form method="POST" action="?action=home">
                <input type="submit" value="Go Back to Home">
            </form>
            <div class="container">
                <table>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($permissions as $permission): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($permission->user_id); ?></td>
                            <td><?php echo htmlspecialchars($permission->username); ?></td>
                            <td><?php echo htmlspecialchars($permission->role); ?></td>
                            <td>
                                <form method="POST" action="?action=setrole">
                                    <input type="hidden" name="user_id"
                                        value="<?php echo htmlspecialchars($permission->user_id); ?>">
                                    <?php if ($permission->role_id != 1): ?>
                                        <input type="submit" name="setrole" value="admin">
                                    <?php endif; ?>
                                    <?php if ($permission->role_id != 2): ?>
                                        <input type="submit" name="setrole" value="basic">
                                    <?php endif; ?>
                                    <?php if ($permission->role_id != 3): ?>
                                        <input type="submit" name="setrole" value="moderator">
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

        </body>

        </html>
        <?php
    }
}
?>