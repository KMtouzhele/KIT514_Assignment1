<?php
require 'vendor/autoload.php';
include_once("model/ModelOAuth.php");
include_once("model/OAuth.php");

use GuzzleHttp\Client;
class ControllerOAuth
{
    private $client;
    private $apiUrl;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $modelOAuth;

    public function __construct()
    {
        $env = parse_ini_file('.env');
        $this->client = new Client();
        $this->apiUrl = $env['API_URL'];
        $this->clientId = $env['CLIENT_ID'];
        $this->clientSecret = $env['DISCORD_CLIENT_SECRET'];
        $this->redirectUri = $env['REDIRECT_URI'];
        $this->modelOAuth = new ModelOAuth();
    }
    public function handleOAuth()
    {
        $oauth = new OAuth();
        $token = $this->exchangeCode();
        $_SESSION['token'] = $token;
        $user = $this->getUser($_SESSION['token']);
        $oauth_id = $user['id'];

        $oauth->oauth_id = $oauth_id;
        $oauth->user_id = $_SESSION['id'];
        $oauth->token = $token;
        $this->modelOAuth->saveDiscordToken($oauth);
        header("Location: /?action=home");
        exit();
    }

    public function getOAuthById($user_id)
    {
        return $this->modelOAuth->getDiscordOAuth($user_id);
    }

    public function exchangeCode()
    {
        $code = $_GET['code'];
        $param = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        ];

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $response = $this->client->request(
            'POST',
            $this->apiUrl . '/oauth2/token',
            [
                'form_params' => $param,
                'headers' => $headers,
                'auth' => [$this->clientId, $this->clientSecret]
            ]
        );
        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];
        return $token;
    }

    public function getUser($token)
    {
        $url = $this->apiUrl . '/users/@me';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $token
        ];

        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => $headers
            ]
        );
        //echo "user got: " . $response->getBody();
        return json_decode($response->getBody(), true);
    }

    public function getUserGuilds($token)
    {
        $url = $this->apiUrl . '/users/@me/guilds';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $token
        ];

        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => $headers
            ]
        );
        //echo "user guild got: " . $response->getBody();
        return json_decode($response->getBody(), true);
    }
}