<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'onficina_bd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Captura os dados do formulário
    $nome_responsavel = $_POST['nome_responsavel_oficina'];
    $cpf_responsavel = $_POST['cpf_responsavel_oficina'];
    $nome_oficina = $_POST['nome_oficina'];
    $email = $_POST['email_oficina'];
    $telefone = $_POST['telefone_oficina'];
    $senha = password_hash($_POST['senha_oficina'], PASSWORD_DEFAULT);
    $cnpj = $_POST['cnpj_oficina'];

    // Prepara a inserção na tabela oficinas
    $stmt = $conn->prepare("INSERT INTO oficinas (nome_responsavel_oficina, cpf_responsavel_oficina, nome_oficina, email_oficina, telefone_oficina, senha_oficina, cnpj_oficina) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nome_responsavel, $cpf_responsavel, $nome_oficina, $email, $telefone, $senha, $cnpj);

    if ($stmt->execute()) {
        // Captura o ID da oficina inserida
        $oficina_id = $stmt->insert_id;

        // Captura os dados do endereço
        $logradouro = $_POST['logradouro'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $pais = $_POST['pais'];
        $cep = $_POST['cep'];

        // Prepara a inserção na tabela endereços_oficinas
        $stmt = $conn->prepare("INSERT INTO enderecos_oficinas (oficina_id, logradouro_endereco, numero_endereco, complemento_endereco, bairro_endereco, cidade_endereco, estado_endereco, pais_endereco, cep_endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $oficina_id, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro ao inserir endereço: " . $stmt->error;
            header("Location: cadastro_oficina.html");
            exit();
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao inserir oficina: " . $stmt->error;
        header("Location: cadastro_oficina.html");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
