<?php
// pagamento.php

header('Content-Type: application/json');

$secretKey = '8569651b-9145-46b5-9173-a3498274c017';
$url = 'https://app.ghostspaysv1.com/api/v1/transaction.purchase';

try {
  $data = [
    "name" => "Usuário PIX",
    "email" => "email@exemplo.com",
    "cpf" => "12345678901",
    "phone" => "11999999999",
    "paymentMethod" => "PIX",
    "amount" => 6783, // R$ 67,83
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

  if ($httpCode >= 400 || !$response) {
    throw new Exception("Erro HTTP $httpCode: $error\nResposta: $response");
  }

  $json = json_decode($response, true);

  echo json_encode([
    "success" => true,
    "id" => $json['id'] ?? null,
    "qrCodeUrl" => $json['pixQrCode'] ?? null,
    "pixCopiaECola" => $json['pixCode'] ?? null,
    "status" => $json['status'] ?? null,
    "valor" => 6783
  ]);
} catch (Exception $e) {
  echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
