<?php
session_start(); // Inicia a sessão

// Exibir mensagem, se existir
if (isset($_SESSION['mensagem'])) {
    echo "<div style='background-color: #dff0d8; color: #3c763d; padding: 10px; margin: 20px; border: 1px solid #d6e9c6; border-radius: 5px;'>" . $_SESSION['mensagem'] . "</div>";
    unset($_SESSION['mensagem']); // Remove a mensagem após exibição
}

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
    header("Location: login.html");
    exit;
}

$oficina_id = $_SESSION['id']; // Obtendo o ID da oficina da sessão

// Captura os dados do formulário
$nome_servico = $_POST['nome_servico'];
$valor_minimo = $_POST['valor_minimo'];
$valor_maximo = $_POST['valor_maximo'];
$tempo_minimo = $_POST['tempo_minimo'];
$tempo_maximo = $_POST['tempo_maximo'];
$descricao = $_POST['descricao'];
$categoria = $_POST['categoria'];

// Prepare e bind
$stmt = $conn->prepare("INSERT INTO servicos_oficinas (nome_servico, valor_minimo, valor_maximo, tempo_minimo, tempo_maximo, descricao, categoria, disponibilidade, oficina_id) VALUES (?, ?, ?, ?, ?, ?, ?, TRUE, ?)");
$stmt->bind_param("sddiiisi", $nome_servico, $valor_minimo, $valor_maximo, $tempo_minimo, $tempo_maximo, $descricao, $categoria, $oficina_id);

// Executa a inserção
if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Serviço cadastrado com sucesso!";
    header("Location: painel_oficina.php"); // Redireciona para o painel da oficina
} else {
    $_SESSION['mensagem'] = "Erro ao cadastrar serviço: " . $stmt->error;
    header("Location: cadastrar_servico_oficina.html"); // Redireciona de volta ao formulário
}

$stmt->close(); // Fecha o prepared statement
$conn->close(); // Fecha a conexão com o banco de dados
?>
