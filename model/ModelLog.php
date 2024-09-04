<?php
include_once("DBCon.php");
include_once("model/Log.php");

class ModelLog
{
    public function addNewLog($log)
    {
        global $mysqli;
        $user_id = $log->user_id;
        $url = $_SERVER['REQUEST_URI'];
        $status = $log->status;
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $stmt = $mysqli->prepare("INSERT INTO log (user_id, ip_address, url, status) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("ssss", $user_id, $ipAddress, $url, $status);
        $result = $stmt->execute();
        if ($result === false) {
            echo "Error occurred while adding new log: " . $stmt->error;
            $stmt->close();
            return false;
        } else {
            $stmt->close();
            return true;
        }
    }

    public function getLogs()
    {
        global $mysqli;
        $sql = "SELECT log.id, users.username, log.url, log.timestamp, log.ip_address, log.status
            FROM log LEFT JOIN users ON log.user_id = users.id
            ORDER BY log.id DESC";
        $result = $mysqli->query($sql);
        if ($result === false) {
            echo "Error occurred while fetching logs: " . $mysqli->error;
            return false;
        } else {
            $logs = array();
            while ($row = $result->fetch_assoc()) {
                $log = new Log();
                $log->id = $row['id'];
                $log->username = $row['username'];
                $log->timestamp = $row['timestamp'];
                $log->ipAddress = $row['ip_address'];
                $log->url = $row['url'];
                $log->status = $row['status'];
                $logs[] = $log;
            }
            return $logs;
        }
    }

    public function getSearchedLogs($ipAddress)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT log.id, users.username, log.url, log.timestamp, log.ip_address, log.status
            FROM log LEFT JOIN users ON log.user_id = users.id WHERE log.ip_address LIKE ?");
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $ipAddress = "%" . $ipAddress . "%";
        $stmt->bind_param("s", $ipAddress);
        $result = $stmt->execute();
        if ($result === false) {
            echo "Error occurred while fetching logs: " . $stmt->error;
            $stmt->close();
            return false;
        } else {
            $stmt->store_result();
            $stmt->bind_result($id, $username, $url, $timestamp, $ipAddress, $status);
            $logs = array();
            while ($stmt->fetch()) {
                $log = new Log();
                $log->id = $id;
                $log->username = $username;
                $log->url = $url;
                $log->timestamp = $timestamp;
                $log->ipAddress = $ipAddress;
                $log->status = $status;
                $logs[] = $log;
            }
            $stmt->close();
            return $logs;
        }
    }


    public function hasLogPrivileges($userId)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT role_id FROM users WHERE id = ?");
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("s", $userId);
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
            if ($role_id !== 2) {
                return true;
            } else {
                return false;
            }
        }
    }
}