<?php
session_start(); // Inicia a sessão

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'onficina_bd');

// Verificar a conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Falha na conexão: ' . $conn->connect_error]);
    exit;
}

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Você precisa estar logado para cadastrar um veículo.']);
    exit;
}

// Receber dados do formulário
$usuario_id = $_SESSION['id'];
$marca = $conn->real_escape_string($_POST['marca']);
$modelo = $conn->real_escape_string($_POST['modelo']);
$cor = $conn->real_escape_string($_POST['cor']);
$ano = (int)$_POST['ano'];
$placa = $conn->real_escape_string($_POST['placa']);
$tipo_veiculo = $conn->real_escape_string($_POST['tipo_veiculo']);

// Inserir veículo no banco de dados
$sql = "INSERT INTO veiculos_usuarios (usuario_id, marca, modelo, cor, ano, placa, tipo_veiculo)
        VALUES ('$usuario_id', '$marca', '$modelo', '$cor', '$ano', '$placa', '$tipo_veiculo')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Veículo cadastrado com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar veículo: ' . $conn->error]);
}

$conn->close();
?>
