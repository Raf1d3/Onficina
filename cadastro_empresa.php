<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'onficina_bd');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para validar CPF (simplificada)
// function validarCPF($cpf) {
//     return preg_match('/^\d{11}$/', $cpf);
// }

// Função para validar CNPJ (simplificada)
// function validarCNPJ($cnpj) {
//     return preg_match('/^\d{14}$/', $cnpj);
// }

// Dados do formulário
$nome_responsavel = trim($_POST['nome_responsavel']);
$cpf_responsavel = trim($_POST['cpf_responsavel']);
$nome_empresa = trim($_POST['nome_empresa']);
$endereco = trim($_POST['endereco']);
$telefone = trim($_POST['telefone']);
$email_empresa = trim($_POST['email_empresa']);
$cnpj = trim($_POST['cnpj']);
$senha_empresa = password_hash(trim($_POST['senha_empresa']), PASSWORD_DEFAULT); // Criptografar a senha

// Validações (descomentadas quando necessário)
// if (!validarCPF($cpf_responsavel)) {
//     echo "<script>alert('CPF do responsável inválido!'); window.location.href='cadastro_empresa.php';</script>";
//     exit;
// }

// if (!validarCNPJ($cnpj)) {
//     echo "<script>alert('CNPJ inválido!'); window.location.href='cadastro_empresa.php';</script>";
//     exit;
// }

// Verificar se o e-mail da empresa já está cadastrado
$sql = "SELECT * FROM empresas WHERE email_empresa = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('E-mail da empresa já cadastrado!'); window.location.href='cadastro_empresa.php';</script>";
} else {
    // Inserir dados no banco de dados
    $sql = "INSERT INTO empresas (nome_empresa, endereco, telefone, email_empresa, cnpj, senha_empresa, nome_responsavel, cpf_responsavel) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $nome_empresa, $endereco, $telefone, $email_empresa, $cnpj, $senha_empresa, $nome_responsavel, $cpf_responsavel);

    if ($stmt->execute()) {
        // Exibir mensagem de sucesso e redirecionar
        echo "<script>alert('Empresa cadastrada com sucesso!'); window.location.href='login.html';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao cadastrar a empresa: " . $stmt->error . "'); window.location.href='cadastro_empresa.php';</script>";
    }
}

$stmt->close();
$conn->close();
?>
