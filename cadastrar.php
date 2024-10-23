<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'onficina_bd');

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber dados do formulário e sanitizar
$nome = $conn->real_escape_string(trim($_POST['nome']));
$email = $conn->real_escape_string(trim($_POST['email']));
$senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT); // Criptografar a senha

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('E-mail inválido!'); window.location.href='index.html';</script>";
    exit;
}

// Verificar se o email já está cadastrado
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // E-mail já cadastrado
    echo "<script>alert('E-mail já cadastrado!'); window.location.href='index.html';</script>";
} else {
    // Inserir o usuário no banco de dados
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $nome, $email, $senha);

    if ($stmt->execute()) {
        // Redirecionar após cadastro bem-sucedido com mensagem
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='login.html';</script>";
        exit; // Adicione este exit para garantir que o script pare aqui
    } else {
        // Erro ao inserir
        echo "Erro: " . $stmt->error;
    }
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>
