<?php
include_once("DBCon.php");
include_once("model/OAuth.php");
class ModelOAuth
{
    public function saveDiscordToken($oauth)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO oauth (oauth_id, user_id, token) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $oauth_id = $oauth->oauth_id;
        $user_id = $_SESSION['id'];
        $token = $oauth->token;
        $stmt->bind_param("sss", $oauth_id, $user_id, $token);
        $result = $stmt->execute();
        if ($result === false) {
            echo "Error occurred while saving discord token: " . $stmt->error;
            $stmt->close();
            return false;
        } else {
            $stmt->close();
            return true;
        }
    }

    public function getDiscordOAuth($user_id)
    {
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT oauth_id, user_id, token FROM oauth WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $result = $stmt->execute();
        if ($result === false) {
            echo "Error occurred while fetching discord token: " . $stmt->error;
            $stmt->close();
            $oauth = new OAuth();
            $oauth->oauth_id = "";
            $oauth->user_id = "";
            $oauth->token = "";
            return $oauth;
        } else {
            $stmt->bind_result($oauth_id, $user_id, $token);
            $stmt->fetch();
            $stmt->close();
            $oauth = new OAuth();
            $oauth->oauth_id = $oauth_id;
            $oauth->user_id = $user_id;
            $oauth->token = $token;
            return $oauth;
        }
    }
}