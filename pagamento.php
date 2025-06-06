<?php
// pagamento.php

$secretKey = '8569651b-9145-46b5-9173-a3498274c017';
$url = 'https://app.ghostspaysv1.com/api/v1/transaction.purchase';

header('Content-Type: application/json');

try {
  if (!isset($_POST['name'], $_POST['email'], $_POST['cpf'], $_POST['phone'])) {
    throw new Exception("Campos obrigatórios ausentes.");
  }

  $data = [
    "name" => $_POST['name'],
    "email" => $_POST['email'],
    "cpf" => $_POST['cpf'],
    "phone" => $_POST['phone'],
    "paymentMethod" => "PIX",
    "amount" => 10000,
    "traceable" => true,
    "items" => [
      [
        "unitPrice" => 10000,
        "title" => "Checkout Teste",
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

  echo $response;

} catch (Exception $e) {
  echo json_encode(["erro" => $e->getMessage()]);
}
?>
