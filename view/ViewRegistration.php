<?php
include_once("model/ModelRegistration.php");
include_once("controller/ControllerAuth.php");

class ViewRegistration
{
    public function output($error = null)
    {
        $modelDriver = new ModelRegistration();
        $driversList = $modelDriver->getDriverList();
        ?>
        <html>

        <head>
            <title>Registration</title>
            <link rel="stylesheet" type="text/css" href="css/style.css">
        </head>

        <body>
            <h1>Welcome Page</h1>
            <?php
            if ($error !== null) {
                echo "<div class='error-message'>" . htmlspecialchars($error) . "</div>";
            }
            ?>
            <form method="POST" action="index.php?action=register">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,}"
                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                    required>

                <label for="verify_password">Verify Password:</label>
                <input type="password" id="verify_password" name="verify_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,}"
                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                    required>
                <label for="driver">Your favorite F1 driver</label>
                <select id="driver" name="driver" required>
                    <?php foreach ($driversList as $driver): ?>
                        <option value="<?php echo htmlspecialchars($driver['id']); ?>">
                            <?php echo htmlspecialchars($driver['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="description">One word to describe you:</label>
                <input type="text" id="description" name="description" required>
                <input type="submit" action="action" value="Register">
            </form>
            <form method="POST" action="index.php?action=tologin">
                <input type="submit" name="action" value="Login">
            </form>
        </body>

        </html>
        <?php
    }
}
?>