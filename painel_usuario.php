<?php
session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'onficina_bd';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$usuario_id = $_SESSION['id'];
$nome_usuario = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';

$sql = "SELECT * FROM veiculos_usuarios WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$veiculos_html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $veiculos_html .= "<li>Marca: " . htmlspecialchars($row['marca']) . "</li>";
        $veiculos_html .= "<li>Modelo: " . htmlspecialchars($row['modelo']) . "</li>";
        $veiculos_html .= "<li>Cor: " . htmlspecialchars($row['cor']) . "</li>";
        $veiculos_html .= "<li>Ano: " . htmlspecialchars($row['ano']) . "</li>";
        $veiculos_html .= "<li>Placa: " . htmlspecialchars($row['placa']) . "</li>";
        $veiculos_html .= "<li>Tipo: " . htmlspecialchars($row['tipo_veiculo']) . "</li>";
        $veiculos_html .= "<br>";
    }
} else {
    $veiculos_html = "<li>Nenhum veículo cadastrado.</li>";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Oficina - ONFICINA</title>
    <link rel="icon" href="assets/img/icon_Onficina.png">
    <link rel="stylesheet" href="style.css"> <!-- Arquivo CSS externo -->
</head>
<body class="painel-usuario">
    <header class="painel-header">  <!-- Adicionado class="painel-header" -->
        
        <h1>Bem vindo, <?php echo htmlspecialchars($nome_usuario); ?>!</h1>
        <form method="POST" action="painel_usuario.php">
            <button class="logout" name="logout">Sair</button>
        </form>
    </header>
    <div class="container">
        <div class="card">
            <h2>Meus Veículos</h2>
            <ul id="veiculos-list">
                <?php echo $veiculos_html; ?>
            </ul>
            <a href="cadastrar_veiculo_usuario.html" class="cadastrar">Cadastrar novo veículo</a>
        </div>
    </div>
</body>
</html>
