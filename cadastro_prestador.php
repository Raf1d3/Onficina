<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'onficina_bd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Captura os dados do formulário
    $nome_prestador = $_POST['nome_prestador'];
    $sobrenome_prestador = $_POST['sobrenome_prestador'];
    $email = $_POST['email_prestador'];
    $telefone = $_POST['telefone_prestador'];
    $senha = password_hash($_POST['senha_prestador'], PASSWORD_DEFAULT);
    $cpf = $_POST['cpf_prestador'];
    $cnpj = $_POST['cnpj_prestador'] ?? null; // CNPJ é opcional

    // Insere os dados do prestador
    $stmt = $conn->prepare("INSERT INTO prestadores (nome_prestador, sobrenome_prestador, email_prestador, telefone_prestador, senha_prestador, cpf_prestador, cnpj_prestador) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nome_prestador, $sobrenome_prestador, $email, $telefone, $senha, $cpf, $cnpj);

    if ($stmt->execute()) {
        $prestador_id = $stmt->insert_id; // Captura o ID do prestador inserido

        // Captura os dados de endereço
        $logradouro = $_POST['logradouro'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $pais = $_POST['pais'];
        $cep = $_POST['cep'];

        // Insere os dados de endereço
        $stmt = $conn->prepare("INSERT INTO enderecos_prestadores (prestador_id, logradouro_endereco, numero_endereco, complemento_endereco, bairro_endereco, cidade_endereco, estado_endereco, pais_endereco, cep_endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $prestador_id, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro ao inserir endereço: " . $stmt->error;
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao inserir prestador: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: form_cadastro_prestador.php");
    exit();
}
?>
