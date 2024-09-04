<?php
include_once('model/Permission.php');
class ModelPermission
{
    public function hasPermissionPrivileges($user_id)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT role_id FROM users WHERE id = ?");
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("s", $user_id);
        $result = $stmt->execute();
        if ($result === false) {
            echo "Error occurred while fetching user role: " . $stmt->error;
            $stmt->close();
            return false;
        } else {
            $stmt->store_result();
            $stmt->bind_result($role_id);
            $stmt->fetch();
            $stmt->close();
        }
        if ($role_id === 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllPermissions()
    {
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT users.id, users.username, roles.id, roles.role from users JOIN roles ON users.role_id = roles.id");
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $result = $stmt->execute();
        if ($result === false) {
            echo "Error occurred while fetching user role: " . $stmt->error;
            $stmt->close();
            return false;
        } else {
            $stmt->store_result();
            $stmt->bind_result($id, $username, $role_id, $role);
            $permissions = array();
            while ($stmt->fetch()) {
                $permission = new Permission();
                $permission->user_id = $id;
                $permission->username = $username;
                $permission->role_id = $role_id;
                $permission->role = $role;
                $permissions[] = $permission;
            }
            $stmt->close();
            return $permissions;
        }
    }

    public function setRole($user_id, $role_id)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("UPDATE users SET role_id = ? WHERE id = ?");
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("ss", $role_id, $user_id);
        $result = $stmt->execute();
        if ($result === false) {
            echo "Error occurred while updating user role: " . $stmt->error;
            $stmt->close();
            return false;
        } else {
            $stmt->close();
            return true;
        }
    }
}