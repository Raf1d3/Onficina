<?php
session_start(); // Inicia a sessão

include 'conexao_bd.php'; // Inclui o arquivo de conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = $_SESSION['nome_usuario'];

// Consultar veículos cadastrados
$sql = "SELECT * FROM veiculos WHERE usuario_id = $usuario_id";
$result = $conn->query($sql);

// Preparar a lista de veículos
$veiculos_html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $veiculos_html .= "<li>Marca: " . htmlspecialchars($row['marca']) . "</li>";
        $veiculos_html .= "<li>Modelo: " . htmlspecialchars($row['modelo']) . "</li>";
        $veiculos_html .= "<li>Ano: " . htmlspecialchars($row['ano']) . "</li>";
        $veiculos_html .= "<li>Placa: " . htmlspecialchars($row['placa']) . "</li>";
        $veiculos_html .= "<li>Tipo: " . htmlspecialchars($row['tipo_veiculo']) . "</li>";
        $veiculos_html .= "<br>";
    }
} else {
    $veiculos_html = "<li>Nenhum veículo cadastrado.</li>";
}

$conn->close();
include 'painel-usuario.html'; // Inclui o HTML do painel do usuário
?>
