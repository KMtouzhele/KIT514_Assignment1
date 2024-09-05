<?php

include_once('model/ModelPermission.php');
include_once('view/ViewPermission.php');

class ControllerPermission
{
    private $modelPermission;
    private $viewPermission;

    public function __construct()
    {
        $this->modelPermission = new ModelPermission();
        $this->viewPermission = new ViewPermission();
    }

    public function showPermission()
    {
        $permissions = $this->modelPermission->getAllPermissions();
        $this->viewPermission->output($permissions);
    }

    public function handleSetRole()
    {
        $user_id = $_POST['user_id'];
        $newRole = $_POST['setrole'];
        switch ($newRole) {
            case 'admin':
                $role_id = 1;
                break;
            case 'basic':
                $role_id = 2;
                break;
            case 'moderator':
                $role_id = 3;
                break;
            default:
                break;
        }
        $this->modelPermission->setRole($user_id, $role_id);
        header("Location: ?action=permission");
    }
}