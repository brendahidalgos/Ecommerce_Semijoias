<?php
// api/excluir.php
require_once 'config.php'; // Inclui o arquivo de configuração e conexão com o DB

// Permite requisições de origens diferentes (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // DELETE também poderia ser usado
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Garante que apenas requisições POST sejam processadas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Valida e sanitiza o ID
    $id = isset($data['id']) ? intval($data['id']) : 0;

    // Validação básica
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID do produto inválido.']);
        $conn->close();
        exit();
    }

    // Prepara a declaração SQL para exclusão segura
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" para integer

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) { // Verifica se alguma linha foi realmente excluída
            echo json_encode(['success' => true, 'message' => 'Produto excluído com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Produto não encontrado para exclusão.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir produto: ' . $stmt->error]);
    }

    $stmt->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}

$conn->close();
?>