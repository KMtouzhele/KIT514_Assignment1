<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once("controller/ControllerAuth.php");
include_once("controller/ControllerHome.php");
include_once("controller/ControllerLog.php");
include_once("controller/ControllerPermission.php");
include_once("controller/ControllerOAuth.php");

$action = $_GET['action'] ?? 'tologin';
$method = $_SERVER['REQUEST_METHOD'];

$viewHome = new ViewHome();
$viewRegistration = new ViewRegistration();
$viewLogin = new ViewLogin();
$modelRegistration = new ModelRegistration();
$controllerAuth = new ControllerAuth();
$controllerHome = new ControllerHome();
$controllerLog = new ControllerLog();
$controllerPermission = new ControllerPermission();
$controllerOAuth = new ControllerOAuth();


switch ($action) {
    case 'toregister':
        $controllerAuth->showRegistration();
        $log = new Log();
        $log->user_id = null;
        $log->status = "success";
        $controllerLog->recordLog($log);
        break;
    case 'register':
        $controllerAuth->handleRegistration();
        $log = new Log();
        $log->user_id = null;
        $log->status = "success";
        $controllerLog->recordLog($log);
        break;
    case 'tologin':
        $controllerAuth->showLogin();
        $log = new Log();
        $log->user_id = null;
        $log->status = "success";
        $controllerLog->recordLog($log);
        break;
    case 'logout':
        $controllerHome->handleLogout();
        $log = new Log();
        $log->user_id = null;
        $log->status = "success";
        $controllerLog->recordLog($log);
        break;
    case 'login':
        $controllerAuth->handleLogin();
        $log = new Log();
        $log->user_id = null;
        $log->status = "success";
        $controllerLog->recordLog($log);
        break;
    case 'home':
        if (!isset($_SESSION['id']) || $_SESSION['id'] < 0) {
            echo "Session expired. Please login again.";
            header("Location: ?action=tologin");
            exit();
        }
        $controllerHome->showUserHome($_SESSION['id']);
        $log = new Log();
        $log->user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $log->status = isset($_SESSION['id']) ? "success" : "failure";
        $controllerLog->recordLog($log);
        break;
    case 'accesslog':
        if (!isset($_SESSION['id']) || $_SESSION['id'] < 0) {
            echo "Session expired. Please login again.";
            header("Location: ?action=tologin");
            exit();
        }
        $controllerHome->handleAccessLog($_SESSION['id']);
        $log = new Log();
        $log->user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $log->status = isset($_SESSION['id']) ? "success" : "failure";
        $controllerLog->recordLog($log);

        break;
    case 'permission':
        if (!isset($_SESSION['id']) || $_SESSION['id'] < 0) {
            header("Location: ?action=tologin");
            exit();
        }
        $controllerHome->handlePermission($_SESSION['id']);
        $log = new Log();
        $log->user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $log->status = isset($_SESSION['id']) ? "success" : "failure";
        $controllerLog->recordLog($log);
        break;
    case 'setrole':
        if (!isset($_SESSION['id']) || $_SESSION['id'] < 0) {
            echo "Session expired. Please login again.";
            header("Location: ?action=tologin");
            exit();
        }
        $controllerPermission->handleSetRole();
        $log = new Log();
        $log->user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $log->status = isset($_SESSION['id']) ? "success" : "failure";
        $controllerLog->recordLog($log);
        break;
    case 'fetchsynonyms':
        if (!isset($_SESSION['id']) || $_SESSION['id'] < 0) {
            echo "Session expired. Please login again.";
            header("Location: ?action=tologin");
            exit();
        }
        $controllerHome->showSynonyms($_SESSION['id']);
        $log = new Log();
        $log->user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $log->status = isset($_SESSION['id']) ? "success" : "failure";
        $controllerLog->recordLog($log);
    case 'discordlogin':
        if (!isset($_GET['code'])) {
            header("Location ?action=home");
            exit();
        }
        $controllerOAuth->handleOAuth();
    default:
        break;
}