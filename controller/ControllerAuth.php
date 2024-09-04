<?php
include_once("view/ViewRegistration.php");
include_once("view/ViewHome.php");
include_once("view/ViewLogin.php");
include_once("model/ModelRegistration.php");
include_once("model/User.php");
include_once("model/ModelLog.php");
include_once("model/Log.php");

class ControllerAuth
{
    private $modelRegistration;
    private $modelLog;
    private $viewRegistration;
    private $viewHome;
    private $viewLogin;
    public function __construct()
    {
        $this->modelRegistration = new ModelRegistration();
        $this->modelLog = new ModelLog();
        $this->viewRegistration = new ViewRegistration();
        $this->viewHome = new ViewHome();
        $this->viewLogin = new ViewLogin();
    }

    public function showRegistration($error = null)
    {
        $this->viewRegistration->output($error);
    }

    public function handleRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $verifyPassword = $_POST['verify_password'];
        $driver = $_POST['driver'];
        $description = $_POST['description'];

        if ($password !== $verifyPassword) {
            $log = new Log();
            $log->user_id = null;
            $log->status = "failure";
            $this->modelLog->addNewLog($log);
            $this->showRegistration("Passwords do not match. Please try again.");
            return;
        }

        $hashedData = $this->saltPassword($password);
        $salt = $hashedData['salt'];
        $hashedPassword = $hashedData['hashedPassword'];
        $userId = $this
            ->modelRegistration
            ->registerUser(
                $username,
                $hashedPassword,
                $salt,
                $driver,
                $description
            );

        if ($userId === -1) {
            $this->showRegistration("Registration failed. Please try again.");
        } elseif ($userId === -2) {
            $this->showRegistration("Username has been used. Please try another one.");
        } else {
            $_SESSION['id'] = $userId;
            $log = new Log();
            $log->user_id = $userId;
            $log->status = "success";

            $this->modelLog->addNewLog($log);
            header("Location: index.php?action=home");
            exit();
        }
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        $username = $_POST['username'];
        $password = $_POST['password'];
        $credential = $this->modelRegistration->getCredentialByUsername($username);
        $salt = $credential['salt'];
        $storedPassword = $credential['password'];
        $userId = $credential['id'];
        if ($salt === "") {
            $this->showLogin("No matching user found.");
            return;
        } else {
            $hashedPassword = sha1($salt . $password);
        }
        if ($hashedPassword !== $storedPassword) {
            $this->showLogin("Incorrect password.");
            $log = new Log();
            $log->user_id = $userId;
            $log->status = "failed";

            $this->modelLog->addNewLog($log);
            return;
        } else {
            $_SESSION['id'] = $userId;
            $log = new Log();
            $log->user_id = $userId;
            $log->status = "success";

            $this->modelLog->addNewLog($log);
            header('Location: /index.php?action=home');
            exit();
        }
    }

    public function showLogin($error = null)
    {
        $this->viewLogin->output($error);
    }

    private function saltPassword($password)
    {
        $salt = bin2hex(random_bytes(16));
        $saltedPassword = $salt . $password;
        $hashedSaltedPassword = sha1($saltedPassword);
        return [
            'salt' => $salt,
            'hashedPassword' => $hashedSaltedPassword
        ];
    }
}