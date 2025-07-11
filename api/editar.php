<?php
// api/editar.php
require_once 'config.php'; // Inclui o arquivo de configuração e conexão com o DB

// Permite requisições de origens diferentes (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Garante que apenas requisições POST sejam processadas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Valida e sanitiza os dados de entrada, incluindo o ID
    $id = isset($data['id']) ? intval($data['id']) : 0;
    $nome = isset($data['nome']) ? sanitize_input($data['nome']) : '';
    $descricao = isset($data['descricao']) ? sanitize_input($data['descricao']) : '';
    $preco = isset($data['preco']) ? floatval($data['preco']) : 0.00;
    $quantidade = isset($data['quantidade']) ? intval($data['quantidade']) : 0;

    // Validação básica
    if ($id <= 0 || empty($nome) || $preco <= 0 || $quantidade < 0) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos: ID, Nome, Preço ou Quantidade são obrigatórios e válidos.']);
        $conn->close();
        exit();
    }

    // Prepara a declaração SQL para atualização segura
    $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, quantidade = ? WHERE id = ?");
    // "ssdi" para nome, descricao, preco, quantidade; "i" para id
    $stmt->bind_param("ssdii", $nome, $descricao, $preco, $quantidade, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) { // Verifica se alguma linha foi realmente afetada
            echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma alteração feita ou produto não encontrado.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar produto: ' . $stmt->error]);
    }

    $stmt->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}

$conn->close();
?>