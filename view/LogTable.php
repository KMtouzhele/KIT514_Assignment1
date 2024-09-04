<?php
class LogTable
{
    public function output($logs)
    {
        ?>
        <div class="container">
            <table>
                <tr>
                    <th>Log ID</th>
                    <th>Username</th>
                    <th>URL</th>
                    <th>Timestamp</th>
                    <th>IP Address</th>
                    <th>Status</th>
                </tr>
                <?php
                foreach ($logs as $log) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log->id); ?></td>
                        <td><?php
                        if (empty($log->username)) {
                            echo "Unkonwn user";
                        } else {
                            echo htmlspecialchars($log->username);
                        }
                        ?></td>
                        <td><?php
                        if (empty($log->url)) {
                            echo "N/A";
                        } else {
                            echo htmlspecialchars($log->url);
                        }
                        ?></td>
                        <td><?php echo htmlspecialchars($log->timestamp); ?></td>
                        <td><?php echo htmlspecialchars($log->ipAddress); ?></td>
                        <td><?php echo htmlspecialchars($log->status); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <?php
    }
}
?>