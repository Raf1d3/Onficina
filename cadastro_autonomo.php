<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'onficina_bd');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Dados do formulário
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha']; // Captura a senha

// Hash da senha para segurança
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

// Inserir dados no banco de dados
$sql = "INSERT INTO autonomos (nome, cpf, telefone, email, senha) 
        VALUES ('$nome', '$cpf', '$telefone', '$email', '$senha_hash')";

if ($conn->query($sql) === TRUE) {
    // Mensagem de sucesso e redirecionamento para o painel do autônomo
    echo "<script>
            alert('Cadastro do profissional autônomo realizado com sucesso!');
            window.location.href = 'painel-autonomo.php';
          </script>";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
