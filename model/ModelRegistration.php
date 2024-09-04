<?php
include_once("DBCon.php");
include_once("Driver.php");

class ModelRegistration
{

    public function getDriverList()
    {
        global $mysqli;
        $env = parse_ini_file('.env');
        $privateKeyPath = $env['PRIVKEY_PATH'];
        $privateKey = file_get_contents($privateKeyPath);

        if ($privateKey === false) {
            die("Failed to load private key from " . $privateKeyPath);
        }

        $privKeyResource = openssl_get_privatekey($privateKey, 'Kaimoli94');
        if ($privKeyResource === false) {
            die('Failed to load private key.');
        }

        $sql = "SELECT * FROM F1Drivers";

        $result = $mysqli->query($sql);

        $arr = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $encryptedName = $row['name'];
                $decryptedName = '';

                if (openssl_private_decrypt(base64_decode($encryptedName), $decryptedName, $privKeyResource)) {
                    $arr[] = [
                        'id' => $id,
                        'name' => $decryptedName
                    ];
                } else {
                    echo "Failed to decrypt a driver name.<br>";
                }
            }
        } else {
            echo "No drivers found or query failed.<br>";
        }
        return $arr;

    }

    public function getDriverName($driverId)
    {
        global $mysqli;
        $env = parse_ini_file('.env');
        $privateKeyPath = $env['PRIVKEY_PATH'];
        $privateKey = file_get_contents($privateKeyPath);

        if ($privateKey === false) {
            die("Failed to load private key from " . $privateKeyPath);
        }

        $privKeyResource = openssl_get_privatekey($privateKey, 'Kaimoli94');
        if ($privKeyResource === false) {
            die('Failed to load private key.');
        }

        $sql = "SELECT name FROM F1Drivers WHERE id = ?";

        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("s", $driverId);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            return "";
        }
        $row = $result->fetch_assoc();
        $encryptedName = $row['name'];
        $decryptedName = '';

        if (openssl_private_decrypt(base64_decode($encryptedName), $decryptedName, $privKeyResource)) {
            return $decryptedName;
        } else {
            echo "Failed to decrypt a driver name.<br>";
            return "";
        }
    }

    public function registerUser(
        $username,
        $hashedPassword,
        $salt,
        $driver,
        $description
    ) {
        global $mysqli;
        $stmt = $mysqli->prepare(
            "INSERT INTO users (username, salt, password, driver_id, role_id, description) 
                VALUES (?, ?, ?, ?, 2, ?)"
        );
        if ($this->checkUserExists($username)) {
            $duplicatedUser = new User(-2, "", -1, -1, "");
            return $duplicatedUser->id;
        }
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("sssss", $username, $salt, $hashedPassword, $driver, $description);
        $result = $stmt->execute();

        if ($result === false) {
            echo "Error occurred while registering the user: " . $stmt->error;
            $stmt->close();
            $emptyUser = new User(-1, "", -1, -1, "");
            return $emptyUser->id;
        } else {
            $userId = $mysqli->insert_id;
            $stmt->close();
            $user = new User($userId, $username, 2, $driver, $description);
            return $user->id;
        }
    }

    public function getUserById($id)
    {
        global $mysqli;
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            return new User(-1, "", -1, -1, "");
        }
        $row = $result->fetch_assoc();
        $user = new User($row['id'], $row['username'], $row['role_id'], $row['driver_id'], $row['description']);
        return $user;
    }

    public function checkUserExists($username)
    {
        global $mysqli;
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            return false;
        }
        return true;
    }

    public function getCredentialByUsername($username)
    {
        global $mysqli;
        $sql = "SELECT id, username, salt, password FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die("Failed to prepare the SQL statement: " . $mysqli->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 0) {
            return [
                'id' => "",
                'username' => "",
                'salt' => "",
                'password' => ""
            ];
        }
        $row = $result->fetch_assoc();
        return [
            'id' => $row['id'],
            'username' => $row['username'],
            'salt' => $row['salt'],
            'password' => $row['password']
        ];
    }

    public function fetchSynonyms($user)
    {
        $env = parse_ini_file('.env');
        $description = $user->description;
        $url = 'https://wordsapiv1.p.rapidapi.com/words/' . $description . '/synonyms';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url, [
            'headers' => [
                'x-rapidapi-host' => 'wordsapiv1.p.rapidapi.com',
                'x-rapidapi-key' => $env['API_KEY'],
            ],
        ]);
        $synonyms = json_decode($response->getBody(), true);
        $synonymsList = isset($synonyms['synonyms']) ? implode(', ', $synonyms['synonyms']) : 'No synonyms found';
        return $synonymsList;
    }
}