<?php
class ViewHome
{
    private $user;
    private $synonyms;

    public function output($user, $buttons, $oauth, $synonyms = '')
    {
        $env = parse_ini_file('.env');
        ?>
        <html>

        <head>
            <title>Home</title>
            <link rel="stylesheet" type="text/css" href="/css/style.css">
        </head>

        <body>
            <h1>Welcome, <?php echo htmlspecialchars($user->username); ?>!</h1>
            <?php
            if ($oauth->oauth_id != "") {
                ?>
                <div class="container">
                <h2>Discord Username: <?php echo $oauth->username ?></h2>
                <img src="https://cdn.discordapp.com/avatars/<?php echo $oauth->oauth_id ?>/<?php echo $oauth->avatar ?>.png"
                    alt="Discord Avatar">
                <h3>Discord Servers:</h3>
                <ul>
                    <?php
                    foreach ($oauth->servers as $server) {
                        ?>
                        <li><?php echo $server ?></li>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
            } else {
                ?>
                    <form method="POST" action="<?php echo $env['DISCORD_URL'] ?>">
                        <input type="submit" value="Link with Discord">
                    </form>
                    <?php
            }
            ?>

                <form method="POST" action="?action=fetchsynonyms">
                    <h2>Description: <?php echo htmlspecialchars($user->description); ?></h2>
                    <h2>Favorite F1 Driver: <?php echo htmlspecialchars($user->driverName()); ?></h2>
                    <input type="submit" value="Synonyms">
                    <?php if ($synonyms): ?>
                        <div class="synonyms">
                            <p><?php echo htmlspecialchars($synonyms); ?></p>
                        </div>
                    <?php endif; ?>
                </form>
                <?php foreach ($buttons as $button):
                    $subpage = urlencode(strtolower(htmlspecialchars($button)));
                    $actionUrl = "?action={$subpage}";
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