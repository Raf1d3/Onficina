<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'onficina_bd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare e bind para inserir dados do usuário
    $stmt = $conn->prepare("INSERT INTO usuarios (nome_usuario, sobrenome_usuario, email_usuario, telefone_usuario, senha_usuario, cpf_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $sobrenome, $email, $telefone, $senha, $cpf);

    // Captura os dados do formulário
    $nome = $_POST['nome_usuario'];
    $sobrenome = $_POST['sobrenome_usuario'];
    $email = $_POST['email_usuario'];
    $telefone = $_POST['telefone_usuario'];
    $senha = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT);
    $cpf = $_POST['cpf_usuario'];

    if ($stmt->execute()) {
        // Captura o ID do usuário inserido
        $usuario_id = $stmt->insert_id;

        // Prepare e bind para inserir dados de endereço
        $stmt = $conn->prepare("INSERT INTO enderecos_usuarios (usuario_id, logradouro_endereco, numero_endereco, complemento_endereco, bairro_endereco, cidade_endereco, estado_endereco, pais_endereco, cep_endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $usuario_id, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep);

        // Captura os dados de endereço
        $logradouro = $_POST['logradouro'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $pais = $_POST['pais'];
        $cep = $_POST['cep'];

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro ao inserir endereço: " . $stmt->error;
            header("Location: cadastro_usuario.html");
            exit();
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao inserir usuário: " . $stmt->error;
        header("Location: cadastro_usuario.html");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
