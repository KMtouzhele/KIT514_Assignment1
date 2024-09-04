<?php
class LogList
{
    public function output($logs)
    {
        ?>
        <div class="container">
            <ul>
                <?php
                foreach ($logs as $log) {
                    ?>
                    <li>
                        <p>Log ID: <?php echo htmlspecialchars($log->id); ?></p>
                        <p>Username: <?php echo htmlspecialchars($log->username); ?></p>
                        <p>URL: <?php echo htmlspecialchars($log->url); ?></p>
                        <p>Timestamp: <?php echo htmlspecialchars($log->timestamp); ?></p>
                        <p>IP Address: <?php echo htmlspecialchars($log->ipAddress); ?></p>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    }
}
?>