<?php
class ViewHome
{
    private $user;
    private $synonyms;

    public function output($user, $buttons, $synonyms = '')
    {
        ?>
        <html>

        <head>
            <title>Home</title>
            <link rel="stylesheet" type="text/css" href="/css/style.css">
        </head>

        <body>
            <h1>Welcome, <?php echo htmlspecialchars($user->username); ?>!</h1>
            <form method="POST" action="?action=fetchsynonyms">
                <p>Description: <?php echo htmlspecialchars($user->description); ?></p>
                <p>Favorite F1 Driver: <?php echo htmlspecialchars($user->driverName()); ?></p>
                <input type="submit" value="Synonyms">
                <?php if ($synonyms): ?>
                    <div class="synonyms">
                        <p><?php echo htmlspecialchars($synonyms); ?></p>
                    </div>
                <?php endif; ?>
            </form>
            <?php foreach ($buttons as $button):
                $subpage = urlencode(strtolower(htmlspecialchars($button)));
                $actionUrl = "?action=to{$subpage}";
                ?>
                <form method="POST" action="<?php echo htmlspecialchars($actionUrl); ?>">
                    <p>You have access to <?php echo $button ?></p>
                    <input type="submit" value="<?php echo htmlspecialchars($button); ?>">
                </form>
            <?php endforeach; ?>
            <form method="POST" action="?action=logout">
                <p>You can logout here ⬇️</p>
                <input type="submit" value="Logout">
            </form>
        </body>

        </html>
        <?php
    }
}
?>