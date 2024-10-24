<?php
// Conectar ao banco de dados
include('conexao_bd.php');

// Iniciar a sessão
session_start();

// Verificar se a sessão da empresa está ativa
if (!isset($_SESSION['empresa_id'])) {
    header("Location: login.html");
    exit();
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy(); // Destroi todas as informações registradas na sessão
    header("Location: login.html"); // Redireciona para a página de login
    exit();
}

// Requisitar o HTML
include('painel-empresa.html');

// Exibir nome da empresa
$empresa_id = $_SESSION['empresa_id'];
$query_empresa = "SELECT nome FROM empresas WHERE id = ?";
$stmt_empresa = $pdo->prepare($query_empresa);
$stmt_empresa->execute([$empresa_id]);
$empresa = $stmt_empresa->fetch();

// Exibir serviços cadastrados
$query_servicos = "SELECT * FROM servicos_empresas WHERE empresa_id = ?";
$stmt_servicos = $pdo->prepare($query_servicos);
$stmt_servicos->execute([$empresa_id]);
$servicos = $stmt_servicos->fetchAll();

echo "<div class='section'><h2>Bem-vindo(a), {$empresa['nome']}</h2></div>";
echo "<div class='section'><h2>Serviços Cadastrados</h2>";
foreach ($servicos as $servico) {
    echo "<div class='card'>
        <strong>Serviço:</strong> {$servico['nome_servico']}<br>
        <strong>Valor Mínimo:</strong> R$ {$servico['valor_minimo']}<br>
        <strong>Tempo Estimado:</strong> {$servico['tempo_estimado']}<br>
    </div>";
}
echo "</div>";

// Exibir peças cadastradas
$query_pecas = "SELECT * FROM pecas_empresas WHERE empresa_id = ?";
$stmt_pecas = $pdo->prepare($query_pecas);
$stmt_pecas->execute([$empresa_id]);
$pecas = $stmt_pecas->fetchAll();

echo "<div class='section'><h2>Peças Cadastradas</h2>";
foreach ($pecas as $peca) {
    echo "<div class='card'>
        <strong>Peça:</strong> {$peca['nome_peca']}<br>
        <strong>Marca:</strong> {$peca['marca']}<br>
        <strong>Tipo de Veículo:</strong> {$peca['tipo_veiculo']}<br>
        <strong>Valor:</strong> R$ {$peca['valor']}<br>
    </div>";
}
echo "</div>";
?>
