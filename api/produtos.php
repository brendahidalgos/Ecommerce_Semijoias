<?php
// api/produtos.php
require_once 'config.php'; // Inclui o arquivo de configuração e conexão com o DB

// Permite requisições de origens diferentes (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Garante que apenas requisições GET sejam processadas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica se um ID de produto foi passado na URL (para buscar um único produto)
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = sanitize_input($_GET['id']);

        // Prepara a declaração SQL para evitar SQL Injection
        $stmt = $conn->prepare("SELECT id, nome, descricao, preco, quantidade FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id); // "i" indica que o parâmetro é um inteiro
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            echo json_encode($product); // Retorna o produto encontrado
        } else {
            echo json_encode(['error' => 'Produto não encontrado.']);
        }
        $stmt->close();
    } else {
        // Se nenhum ID foi passado, lista todos os produtos
        $sql = "SELECT id, nome, descricao, preco, quantidade FROM produtos ORDER BY id DESC";
        $result = $conn->query($sql);

        $products = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        echo json_encode($products); // Retorna um array de produtos (pode ser vazio)
    }
} else {
    // Método de requisição inválido
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}

$conn->close(); // Fecha a conexão com o banco de dados
?>