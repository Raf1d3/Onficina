<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'onficina_bd');

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$usuario_id = 1; // Supondo que o usuário está logado (você pode usar sessões para isso)
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$ano = $_POST['ano'];
$placa = $_POST['placa'];
$tipo_veiculo = $_POST['tipo_veiculo'];

// Inserir veículo no banco de dados
$sql = "INSERT INTO veiculos (usuario_id, marca, modelo, ano, placa, tipo_veiculo)
        VALUES ('$usuario_id', '$marca', '$modelo', '$ano', '$placa', '$tipo_veiculo')";

if ($conn->query($sql) === TRUE) {
    // Mensagem de sucesso e redirecionamento
    echo "<script>alert('Veículo cadastrado com sucesso!'); window.location.href='painel-usuario.php';</script>";
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>
