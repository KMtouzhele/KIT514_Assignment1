<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once("controller/ControllerAuth.php");
include_once("controller/ControllerHome.php");
include_once("controller/ControllerLog.php");
include_once("controller/ControllerPermission.php");

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
    case 'toaccesslog':
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
    case 'accesslog':
        $search = isset($_POST['search']) ? $_POST['search'] : null;
        $controllerLog->showLogs($search);
        $log = new Log();
        $log->user_id = $_SESSION['id'];
        $log->status = "success";
        $controllerLog->recordLog($log);
        break;
    case 'topermission':
        if (!isset($_SESSION['id']) || $_SESSION['id'] < 0) {
            echo "Session expired. Please login again.";
            header("Location: ?action=tologin");
            exit();
        }
        $controllerHome->handlePermission($_SESSION['id']);
        $log = new Log();
        $log->user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $log->status = isset($_SESSION['id']) ? "success" : "failure";
        $controllerLog->recordLog($log);
        break;
    case 'permission':
        $controllerPermission->showPermission();
        $log = new Log();
        $log->user_id = $_SESSION['id'];
        $log->status = "success";
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
    default:
        break;
}