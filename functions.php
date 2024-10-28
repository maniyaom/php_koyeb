<?php

// Retrieve the security level stored in the cookie, default to "High" if not set
$security_level = isset($_COOKIE['securityLevel']) ? ucfirst($_COOKIE['securityLevel']) : 'High';

function getUserIP()
{
    // Check for shared internet/ISP IPs
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    // Check for proxies
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    // Default to REMOTE_ADDR
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function detectIntrusion($input_text)
{
    global $security_level;

    if ($security_level === 'Low') {
        return false;
    }
    
    $user_ip = getUserIP();
    $data = json_encode([
        'payload' => $input_text,
        'ip_address' => $user_ip
    ]);

    $ch = curl_init('https://rubber-dianne-bookly-detection-8c4e24c6.koyeb.app/predict');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ],
        CURLOPT_POSTFIELDS => $data
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('cURL Error: ' . curl_error($ch));
    }

    curl_close($ch);

    $responseData = json_decode($response, true);
    return $responseData['message'];
}
?>