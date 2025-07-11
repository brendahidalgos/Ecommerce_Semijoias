<?php
// api/login.php
require_once 'config.php'; // Inclui o arquivo de configuração e conexão com o DB

// Permite requisições de origens diferentes (CORS) - ajuste em produção
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Garante que apenas requisições POST sejam processadas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega o corpo da requisição JSON
    $data = json_decode(file_get_contents('php://input'), true);

    $username = isset($data['username']) ? sanitize_input($data['username']) : '';
    $password = isset($data['password']) ? sanitize_input($data['password']) : '';

    // Credenciais fixas para demonstração (NÃO USAR EM PRODUÇÃO)
    $valid_username = 'admin';
    $valid_password = 'admin123'; // Senha simples para teste

    if ($username === $valid_username && $password === $valid_password) {
        // Login bem-sucedido
        echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso!']);
    } else {
        // Login falhou
        echo json_encode(['success' => false, 'message' => 'Usuário ou senha inválidos.']);
    }

} else {
    // Método de requisição inválido
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}

$conn->close(); // Fecha a conexão com o banco de dados
?>