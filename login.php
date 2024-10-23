<?php
session_start();
include 'conexao_bd.php'; // Arquivo de conexão com o banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifica se a senha está correta
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario_id'] = $user['id']; // Alterado para 'usuario_id'
            $_SESSION['nome_usuario'] = $user['nome']; // Armazenar o nome do usuário na sessão
            header("Location: painel-usuario.php");
            exit;
        } else {
            // Mensagem de erro para senha incorreta
            echo "<script>alert('Senha incorreta.'); window.location.href='login.html';</script>";
        }
    } else {
        // Mensagem de erro para usuário não encontrado
        echo "<script>alert('Usuário não encontrado.'); window.location.href='login.html';</script>";
    }
}
?>
