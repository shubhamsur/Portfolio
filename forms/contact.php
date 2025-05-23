<?php

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Collect form data
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $subject = $_POST['subject'] ?? '';
  $message = $_POST['message'] ?? '';

  // Prepare data
  $vercel_config_id = 'ecfg_rwhegrozvev8aknw7kbybhwtpaed'; // 🔁 Replace with your actual config ID
  $vercel_token = '5bf6b008a9ec05f6870c476d10b53211797aa000f95aae344ae60f9b422286da'; // 🔁 Replace with your Vercel token
  $unique_key = 'form_' . time(); // unique key like form_1710000000

  $data = [
    "items" => [
      [
        "operation" => "upsert",
        "key" => $unique_key,
        "value" => [
          "name" => $name,
          "email" => $email,
          "subject" => $subject,
          "message" => $message,
          "time" => date('Y-m-d H:i:s')
        ]
      ]
    ]
  ];

  // Call Vercel Edge Config API
  $ch = curl_init("https://api.vercel.com/v1/edge-config/{$vercel_config_id}/items");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $vercel_token",
    "Content-Type: application/json"
  ]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

  $response = curl_exec($ch);
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($status === 200 || $status === 201) {
    echo "Form submitted successfully!";
  } else {
    echo "Error submitting form. Status code: $status";
  }
} else {
  http_response_code(405);
  echo "Method Not Allowed";
}
