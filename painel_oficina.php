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

$oficina_id = $_SESSION['id']; // Obtendo o ID da oficina da sessão
$nome_oficina = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Oficina'; // Define um padrão

// Consultar peças cadastradas na tabela pecas_oficinas
$sql_pecas = "SELECT * FROM pecas_oficinas WHERE oficina_id = ?"; // Usando prepared statement
$stmt_pecas = $conn->prepare($sql_pecas);
$stmt_pecas->bind_param("i", $oficina_id); // Associando o ID da oficina
$stmt_pecas->execute();
$result_pecas = $stmt_pecas->get_result();

// Preparar a lista de peças
$pecas_html = "";
if ($result_pecas->num_rows > 0) {
    while ($row = $result_pecas->fetch_assoc()) {
        $pecas_html .= "<li>Nome: " . htmlspecialchars($row['nome_peca']) . "</li>";
        $pecas_html .= "<li>Valor: R$ " . htmlspecialchars($row['valor']) . "</li>";
        // Verifica se a coluna "estoque" existe e se não for nula
        $estoque = isset($row['estoque']) ? htmlspecialchars($row['estoque']) : 'Indisponível';
        $pecas_html .= "<li>Estoque: " . $estoque . "</li>";
        $pecas_html .= "<br>";
    }
} else {
    $pecas_html = "<li>Nenhuma peça cadastrada.</li>";
}

$stmt_pecas->close(); // Fecha o prepared statement

// Consultar serviços cadastrados na tabela servicos_oficinas
$sql_servicos = "SELECT * FROM servicos_oficinas WHERE oficina_id = ?"; // Usando prepared statement
$stmt_servicos = $conn->prepare($sql_servicos);
$stmt_servicos->bind_param("i", $oficina_id); // Associando o ID da oficina
$stmt_servicos->execute();
$result_servicos = $stmt_servicos->get_result();

// Preparar a lista de serviços
$servicos_html = "";
if ($result_servicos->num_rows > 0) {
    while ($row = $result_servicos->fetch_assoc()) {
        $servicos_html .= "<li>Nome: " . htmlspecialchars($row['nome_servico']) . "</li>";
        $servicos_html .= "<li>Valor: R$ " . htmlspecialchars($row['valor_minimo']) . " - R$ " . htmlspecialchars($row['valor_maximo']) . "</li>";
        $servicos_html .= "<li>Tempo Estimado: " . htmlspecialchars($row['tempo_minimo']) . " - " . htmlspecialchars($row['tempo_maximo']) . " horas</li>";
        $servicos_html .= "<br>";
    }
} else {
    $servicos_html = "<li>Nenhum serviço cadastrado.</li>";
}

$stmt_servicos->close(); // Fecha o prepared statement
$conn->close(); // Fecha a conexão com o banco de dados

include 'painel_oficina.html'; // Inclui o HTML do painel da oficina
?>
