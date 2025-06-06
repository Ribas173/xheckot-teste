<?php
// pagamento.php - Compatível com Ghostspay + index avançado

header('Content-Type: application/json');

// ✅ Chave da API Ghostspay
$secretKey = '8569651b-9145-46b5-9173-a3498274c017';

// ✅ Endpoint oficial Ghostspay
$url = 'https://app.ghostspaysv1.com/api/v1/transaction.purchase';

// ✅ Dados fixos válidos (substitua por dados reais se necessário)
$data = [
  "name" => "Maria de Souza",
  "email" => "maria@email.com",
  "cpf" => "12345678909",  // CPF sintético válido
  "phone" => "11999999999",
  "paymentMethod" => "PIX",
  "amount" => 6783, // R$ 67,83 em centavos
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
  // Inicializar cURL
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Authorization: $secretKey"
  ]);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  // Executar e obter resposta
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $error = curl_error($ch);
  curl_close($ch);

  // Validar resposta
  if ($httpCode >= 400 || !$response) {
    echo json_encode([
      "success" => false,
      "message" => "Erro HTTP $httpCode",
      "erro" => $error,
      "resposta" => $response
    ]);
    exit;
  }

  // Resposta da API Ghostspay
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
  echo json_encode([
    "success" => false,
    "message" => "Erro ao processar pagamento.",
    "exception" => $e->getMessage()
  ]);
}
?>
