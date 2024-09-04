<?php

// Enable PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Import the Guzzle plugin via Composer (autoload.php)
require 'vendor/autoload.php';
use GuzzleHttp\Exception\RequestException;

// We are rendering out a plain-text file in this example, not an HTML file
header('Content-type: text/plain');
echo "==========================================================\n";

try 
{
    // Create a new Guzzle client
    $client = new GuzzleHttp\Client(['verify' => false]);
    
    // Set the URL and headers
    $url = "https://numbersapi.p.rapidapi.com/6/21/date";
    $headers = [
        'x-rapidapi-host' => 'numbersapi.p.rapidapi.com',
        'x-rapidapi-key' => '33a5fd9d3amsh4ff85e00c7208f1p14e4f8jsnc35e327404ac'
    ];
    
    // Make the GET request
    $response = $client->request('GET', $url, [
        'headers' => $headers
    ]);
    
    // Handle the response
    if($response->getStatusCode() == 200) 
    {
        echo "Response: " . $response->getBody();
    } 
    else 
    {
        echo "Error : " . $response->getStatusCode();
    }
}
catch (RequestException $e) 
{
    // Catch any exceptions and print the error
    echo "Error: " . $e->getMessage();
}

echo "\n==========================================================";

?>
