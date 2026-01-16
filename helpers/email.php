<?php

function sendEmail($ReceiverEmail, $ReceiverName, $EmailSubject, $EmailBody)
{
    $url = "https://rapidsystem.teammisfitz.com/email.php";

    $data = [
        'Email_Receiver' => $ReceiverEmail,
        'Email_Receiver_Name' =>  $ReceiverName,
        'Email_Subject' => $EmailSubject,
        'Email_Body' => $EmailBody
    ];


    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    return $response;
}
