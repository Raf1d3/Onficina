<?php
session_start(); // Inicia a sessão

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'onficina_bd');

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) { // Mudado para 'id' conforme a sessão
    // Redireciona para a página de login se não estiver logado
    echo "<script>alert('Você precisa estar logado para cadastrar um veículo.'); window.location.href='login.html';</script>";
    exit;
}

// Receber dados do formulário
$usuario_id = $_SESSION['id']; // Obtém o ID do usuário da sessão
$marca = $conn->real_escape_string($_POST['marca']);
$modelo = $conn->real_escape_string($_POST['modelo']);
$cor = $conn->real_escape_string($_POST['cor']); // Novo campo para cor
$ano = (int)$_POST['ano'];
$placa = $conn->real_escape_string($_POST['placa']);
$tipo_veiculo = $conn->real_escape_string($_POST['tipo_veiculo']);

// Inserir veículo no banco de dados
$sql = "INSERT INTO veiculos_usuarios (usuario_id, marca, modelo, cor, ano, placa, tipo_veiculo)
        VALUES ('$usuario_id', '$marca', '$modelo', '$cor', '$ano', '$placa', '$tipo_veiculo')";

if ($conn->query($sql) === TRUE) {
    // Mensagem de sucesso e redirecionamento
    echo "<script>alert('Veículo cadastrado com sucesso!'); window.location.href='painel_usuario.php';</script>";
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>
