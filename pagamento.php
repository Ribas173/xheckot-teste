<?php
header('Content-Type: application/json');

$secretKey = '8569651b-9145-46b5-9173-a3498274c017';
$url = 'https://app.ghostspaysv1.com/api/v1/transaction.purchase';

$data = [
  "name" => "Maria de Souza",
  "email" => "maria@email.com",
  "cpf" => "12345678909",
  "phone" => "11999999999",
  "paymentMethod" => "PIX",
  "amount" => 6783,
  "traceable" => true,
  "items" => [
    [
      "unitPrice" => 6783,
      "title" => "Exame Admissional",
      "quantity" => 1,
      "tangible" => false
    ]
  ]
];

try {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Authorization: $secretKey"
  ]);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $error = curl_error($ch);
  curl_close($ch);

  echo json_encode([
    "success" => $httpCode < 400,
    "httpCode" => $httpCode,
    "rawResponse" => $response,
    "curlError" => $error
  ]);
} catch (Exception $e) {
  echo json_encode([
    "success" => false,
    "exception" => $e->getMessage()
  ]);
}
?>
