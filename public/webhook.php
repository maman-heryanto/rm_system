<?php

// Secret key to verify the webhook (optional but recommended)
// You should set this in your GitHub webhook settings and here.
$secret = 'rmRizkiMandiriSystem';


// Path to your git repository
$path = 'd:\RMMandiri'; // Adjust this if the script is not in the root

// Log file
$logFile = 'webhook.log';

// Function to log messages
function writeLog($message)
{
    global $logFile;
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verify signature if secret is set
    if ($secret !== 'GANTI_DENGAN_SECRET_KAMU') {
        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
        $payload = file_get_contents('php://input');

        if (!$signature) {
            http_response_code(403);
            writeLog('Error: No signature provided.');
            die('Forbidden');
        }

        list($algo, $hash) = explode('=', $signature, 2);
        $payloadHash = hash_hmac($algo, $payload, $secret);

        if ($hash !== $payloadHash) {
            http_response_code(403);
            writeLog('Error: Invalid signature.');
            die('Forbidden');
        }
    }

    // Execute git pull
    writeLog('Webhook triggered. Starting deployment...');

    // Change directory to the repository
    chdir($path);

    // 2>&1 redirects stderr to stdout so we capture errors too
    $output = [];
    $return_var = 0;

    // You might need to specify the path to git executable if it's not in the path for the web user
    // e.g., 'C:\Program Files\Git\bin\git.exe'
    exec('git pull origin main 2>&1', $output, $return_var);

    // Log output
    foreach ($output as $line) {
        writeLog($line);
    }

    if ($return_var === 0) {
        writeLog('Deployment successful.');
        echo 'Deployment successful.';
    }
    else {
        writeLog('Deployment failed. Return code: ' . $return_var);
        http_response_code(500);
        echo 'Deployment failed.';
    }

}
else {
    http_response_code(405);
    die('Method Not Allowed');
}
