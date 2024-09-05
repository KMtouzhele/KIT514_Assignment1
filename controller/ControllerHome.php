<?php

include_once("view/ViewHome.php");
include_once("view/ViewLogin.php");
include_once("view/ViewRegistration.php");
include_once("view/ViewLog.php");
include_once("view/ViewPermission.php");
include_once("model/ModelRegistration.php");
include_once("model/ModelLog.php");
include_once("controller/ControllerOAuth.php");
include_once("controller/ControllerPermission.php");
include_once("controller/ControllerLog.php");

require 'vendor/autoload.php';
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;

class ControllerHome
{
    private $viewHome;
    private $modelRegistration;
    private $modelLog;
    private $modelPermission;
    private $controllerOAuth;
    private $viewRegistration;
    private $viewLog;
    private $controllerPermission;
    private $controllerLog;

    public function __construct()
    {
        $this->viewHome = new ViewHome();
        $this->viewRegistration = new ViewRegistration();
        $this->viewLog = new ViewLog();
        $this->modelRegistration = new ModelRegistration();
        $this->modelLog = new ModelLog();
        $this->controllerOAuth = new ControllerOAuth();
        $this->modelPermission = new ModelPermission();
        $this->controllerPermission = new ControllerPermission();
        $this->controllerLog = new ControllerLog();

    }

    public function showUserHome($userId, $synonymsList = null)
    {
        $user = $this->modelRegistration->getUserById($userId);
        $buttons = $this->assignButtonsOnRole($user->roleId);
        $oauth = $this->controllerOAuth->getOAuthById($userId);
        if ($oauth->oauth_id != "") {
            $discord_user = $this->controllerOAuth->getUser($oauth->token);
            $guilds = $this->controllerOAuth->getUserGuilds($oauth->token);
            $oauth->username = $discord_user['username'];
            $oauth->avatar = $discord_user['avatar'];
            $oauth->servers = [];
            foreach ($guilds as $guild) {
                $oauth->servers[] = $guild['name'];
            }
        }
        $this->viewHome->output($user, $buttons, $oauth, $synonymsList);
    }

    private function assignButtonsOnRole($roleId)
    {
        if ($roleId === 1) {
            return ['Permission', 'AccessLog'];
        } elseif ($roleId === 2) {
            return [];
        } elseif ($roleId === 3) {
            return ['AccessLog'];
        } else {
            return ['ERROR'];
        }
    }

    public function handleLogout()
    {
        session_destroy();
        header("Location: /?action=tologin");
        exit();
    }

    public function handlePermission($user_id)
    {
        $priv = $this->modelPermission->hasPermissionPrivileges($user_id);
        if ($priv === true) {
            $this->controllerPermission->showPermission();
        } else {
            header('HTTP/1.0 403 Forbidden');
            exit();
        }
    }

    public function handleAccessLog($user_id)
    {
        $priv = $this->modelLog->hasLogPrivileges($user_id);
        if ($priv) {
            $search = isset($_POST['search']) ? $_POST['search'] : null;
            $this->controllerLog->showLogs($search);
            exit();
        } else {
            header('HTTP/1.0 403 Forbidden');
            exit();
        }
    }

    public function showSynonyms($user_id)
    {
        $user = $this->modelRegistration->getUserById($user_id);
        $synonymsList = $this->modelRegistration->fetchSynonyms($user);
        $this->showUserHome($user_id, $synonymsList);

    }

}