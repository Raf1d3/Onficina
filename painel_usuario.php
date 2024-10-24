<?php
session_start(); // Inicia a sessão

// Verifica se o usuário clicou em sair
if (isset($_POST['logout'])) {
    // Destruir todas as variáveis de sessão
    session_unset(); // Limpa todas as variáveis de sessão
    session_destroy(); // Destrói a sessão
    header("Location: login.html"); // Redireciona para a página de login
    exit();
}

// Conexão com o banco de dados
$host = 'localhost'; // Host do banco de dados
$user = 'root';      // Usuário do banco de dados
$password = '';      // Senha do banco de dados (deixe vazio se não houver senha)
$database = 'onficina_bd'; // Nome do banco de dados

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

$usuario_id = $_SESSION['id']; // Obtendo o ID do usuário da sessão
$nome_usuario = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário'; // Define um padrão

// Consultar veículos cadastrados na tabela veiculos_usuarios
$sql = "SELECT * FROM veiculos_usuarios WHERE usuario_id = ?"; // Usando prepared statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id); // Associando o ID do usuário
$stmt->execute();
$result = $stmt->get_result();

// Preparar a lista de veículos
$veiculos_html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $veiculos_html .= "<li>Marca: " . htmlspecialchars($row['marca']) . "</li>";
        $veiculos_html .= "<li>Modelo: " . htmlspecialchars($row['modelo']) . "</li>";
        $veiculos_html .= "<li>Cor: " . htmlspecialchars($row['cor']) . "</li>"; // Adicionado o campo 'cor'
        $veiculos_html .= "<li>Ano: " . htmlspecialchars($row['ano']) . "</li>";
        $veiculos_html .= "<li>Placa: " . htmlspecialchars($row['placa']) . "</li>";
        $veiculos_html .= "<li>Tipo: " . htmlspecialchars($row['tipo_veiculo']) . "</li>";
        $veiculos_html .= "<br>";
    }
} else {
    $veiculos_html = "<li>Nenhum veículo cadastrado.</li>";
}

$stmt->close(); // Fecha o prepared statement
$conn->close(); // Fecha a conexão com o banco de dados

include 'painel_usuario.html'; // Inclui o HTML do painel do usuário
?>
