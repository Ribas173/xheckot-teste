<?php
// pagamento.php

// Coloque aqui a sua chave real da Ghostspay
$secretKey = '8569651b-9145-46b5-9173-a3498274c017';

// URL da API da Ghostspay para criar pagamento
$url = 'https://app.ghostspaysv1.com/api/v1/transaction.purchase';

// Dados do formulário
$name = $_POST['name'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$phone = $_POST['phone'];
$amount = 10000; // em centavos (ex: 10000 = R$100,00)

// Itens da compra
$items = [
  [
    "unitPrice" => $amount,
    "title" => "Compra Checkout PIX",
    "quantity" => 1,
    "tangible" => false
  ]
];

// Montar JSON da requisição
$data = [
  "name" => $name,
  "email" => $email,
  "cpf" => $cpf,
  "phone" => $phone,
  "paymentMethod" => "PIX",
  "amount" => $amount,
  "traceable" => true,
  "items" => $items
];

// Enviar requisição
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  "Authorization: $secretKey"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

// Resposta da API em JSON
header('Content-Type: application/json');
echo $response;
?>
