<?php
session_start(); // Inicia a sessão

// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'onficina_bd';

// Cria conexão
$conn = new mysqli($host, $user, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.html"); // Redireciona para a página de login se não estiver logado
    exit;
}

$oficina_id = $_SESSION['id']; // Obtendo o ID da oficina da sessão

// Captura os dados do formulário
$nome_peca = $_POST['nome_peca'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];

// Prepare e bind para inserir a peça
$stmt = $conn->prepare("INSERT INTO pecas_oficinas (nome_peca, descricao, valor, oficina_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssdi", $nome_peca, $descricao, $valor, $oficina_id);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Peça cadastrada com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao cadastrar a peça: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: painel_oficina.php"); // Redireciona para o painel da oficina
exit();
?>
