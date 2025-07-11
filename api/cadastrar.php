<?php
// api/cadastrar.php
require_once 'config.php'; // Inclui o arquivo de configuração e conexão com o DB

// Permite requisições de origens diferentes (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Garante que apenas requisições POST sejam processadas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Valida e sanitiza os dados de entrada
    $nome = isset($data['nome']) ? sanitize_input($data['nome']) : '';
    $descricao = isset($data['descricao']) ? sanitize_input($data['descricao']) : '';
    $preco = isset($data['preco']) ? floatval($data['preco']) : 0.00; // Converte para float
    $quantidade = isset($data['quantidade']) ? intval($data['quantidade']) : 0; // Converte para int

    // Validação básica
    if (empty($nome) || $preco <= 0 || $quantidade < 0) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos: Nome, Preço ou Quantidade são obrigatórios e válidos.']);
        $conn->close();
        exit();
    }

    // Prepara a declaração SQL para inserção segura
    $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES (?, ?, ?, ?)");
    // "s" para string, "s" para string, "d" para double (float), "i" para integer
    $stmt->bind_param("ssdi", $nome, $descricao, $preco, $quantidade);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Produto cadastrado com sucesso!', 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar produto: ' . $stmt->error]);
    }

    $stmt->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}

$conn->close();
?>