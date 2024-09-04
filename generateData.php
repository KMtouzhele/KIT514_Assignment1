<?php
include_once("DBCon.php");

$env = parse_ini_file('.env');

if (!$env) {
    die('Failed to parse .env file. <br>');
}

$driverNames = [
    "Lewis Hamilton",
    "Max Verstappen",
    "Charles Leclerc",
    "Sebastian Vettel",
    "Fernando Alonso",
    "Lando Norris"
];

$pubKeyPath = $env['PUBKEY_PATH'];
echo "Public key path: $pubKeyPath<br>";

if (!file_exists($pubKeyPath)) {
    die('Public key file does not exist at: '. realpath($pubKeyPath) . '<br>');
}

//$publicKey = file_get_contents('/home/kit214/public.pem');
$publicKey = file_get_contents($pubKeyPath);
$pubKeyResource = openssl_get_publickey($publicKey);

if ($pubKeyResource === false) {
    die('Failed to load public key. <br>');
} else {
    echo "Public key is loaded <br>";
}

foreach ($driverNames as $name) {
    $encrypted = "";

    if (!openssl_public_encrypt($name, $encryptedName, $pubKeyResource)) {
        echo "Failed to encrypt name for driver: $name<br>";
        continue;
    }

    $base64EncryptedName = base64_encode($encryptedName);
    $query = $mysqli->prepare("INSERT INTO F1Drivers (name) VALUES (?)");

    if ($query === false) {
        die('Prepare failed: ' . $mysqli->error);
    }

    $query->bind_param("s", $base64EncryptedName);

    if ($query->execute()) {
        echo "Inserted encrypted name for driver: $name<br>";
    } else {
        echo "Failed to insert encrypted name for driver: $name<br>";
    }

    $query->close();
}

$mysqli->close();
