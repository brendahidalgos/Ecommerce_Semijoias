<?php
// api/config.php

// Define as constantes para as credenciais do banco de dados
define('DB_SERVER', 'localhost'); // Geralmente 'localhost' para ambiente local
define('DB_USERNAME', 'root');    // Seu nome de usuário do MySQL
define('DB_PASSWORD', '');        // Sua senha do MySQL (vazio se não houver)
define('DB_NAME', 'loja_semijoias'); // O nome do banco de dados que criaremos

/* Conecta ao banco de dados MySQL */
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Checa a conexão
if ($conn->connect_error) {
    // Se houver um erro na conexão, interrompe o script e exibe a mensagem de erro
    die("Erro de Conexão com o Banco de Dados: " . $conn->connect_error);
}

// Opcional: Define o charset para evitar problemas de codificação de caracteres
$conn->set_charset("utf8mb4");

// Esta função será usada para sanitizar entradas de dados
function sanitize_input($data) {
    global $conn; // Usar a conexão definida globalmente
    $data = trim($data); // Remove espaços em branco do início e fim
    $data = stripslashes($data); // Remove barras invertidas
    $data = htmlspecialchars($data); // Converte caracteres especiais em entidades HTML
    // Opcional: Para evitar SQL Injection, é recomendado usar prepared statements.
    // Para inputs que irão diretamente para uma query SQL, usar prepared statements é crucial.
    // Para esta função de sanitização geral, o htmlspecialchars é bom para exibição,
    // mas para SQL, confie nos prepared statements do PHP com MySQLi.
    return $data;
}

// Define o cabeçalho para todas as respostas da API como JSON
header('Content-Type: application/json');

?>