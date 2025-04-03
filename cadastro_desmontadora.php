<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'onficina_bd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare e bind para inserir dados da desmontadora
    $stmt = $conn->prepare("INSERT INTO desmontadoras (nome_responsavel_desmontadora, cpf_responsavel_desmontadora, nome_desmontadora, email_desmontadora, telefone_desmontadora, senha_desmontadora, cnpj_desmontadora) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nome_responsavel, $cpf_responsavel, $nome_desmontadora, $email, $telefone, $senha, $cnpj);

    // Captura os dados do formulário
    $nome_responsavel = $_POST['nome_responsavel_desmontadora'];
    $cpf_responsavel = $_POST['cpf_responsavel_desmontadora'];
    $nome_desmontadora = $_POST['nome_desmontadora'];
    $email = $_POST['email_desmontadora'];
    $telefone = $_POST['telefone_desmontadora'];
    $senha = password_hash($_POST['senha_desmontadora'], PASSWORD_DEFAULT);
    $cnpj = $_POST['cnpj_desmontadora'];

    if ($stmt->execute()) {
        $desmontadora_id = $stmt->insert_id;

        // Inserção do endereço
        $stmt = $conn->prepare("INSERT INTO enderecos_desmontadoras (desmontadora_id, logradouro_endereco, numero_endereco, complemento_endereco, bairro_endereco, cidade_endereco, estado_endereco, pais_endereco, cep_endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $desmontadora_id, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep);

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
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao inserir desmontadora: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: cadastro_desmontadora.html");
    exit();
}
?>
