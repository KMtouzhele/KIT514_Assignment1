<?php
class ViewLogin
{
    public function output($error = null)
    {
        ?>
        <html>

        <head>
            <title>
                Login
            </title>
            <link rel="stylesheet" type="text/css" href="../css/style.css">
        </head>

        <body>
            <h1>Login</h1>
            <?php
            if ($error !== null) {
                echo "
                <div class='container'>
                <div class='error-message'>
                "
                    . htmlspecialchars($error)
                    . "
                </div>
                </div>
                ";
            }
            ?>
            <form method="POST" action="index.php?action=login">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"></input>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"></input>
                <input type="submit" value="Login">
            </form>
            <form method="POST" action="index.php?action=toregister">
                <input type="submit" value="Register">
            </form>
        </body>

        </html>
        <?php
    }
}
?>