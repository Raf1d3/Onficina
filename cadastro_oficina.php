<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'onficina_bd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare e bind para inserir dados da oficina
    $stmt = $conn->prepare("INSERT INTO oficinas (nome_responsavel_oficina, cpf_responsavel_oficina, nome_oficina, email_oficina, telefone_oficina, senha_oficina, cnpj_oficina) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nome_responsavel, $cpf_responsavel, $nome_oficina, $email, $telefone, $senha, $cnpj);

    // Captura os dados do formulário
    $nome_responsavel = $_POST['nome_responsavel_oficina'];
    $cpf_responsavel = $_POST['cpf_responsavel_oficina'];
    $nome_oficina = $_POST['nome_oficina'];
    $email = $_POST['email_oficina'];
    $telefone = $_POST['telefone_oficina'];
    $senha = password_hash($_POST['senha_oficina'], PASSWORD_DEFAULT);
    $cnpj = $_POST['cnpj_oficina'];

    if ($stmt->execute()) {
        // Captura o ID da oficina inserida
        $oficina_id = $stmt->insert_id;

        // Prepare e bind para inserir dados de endereço
        $stmt = $conn->prepare("INSERT INTO enderecos_oficinas (oficina_id, logradouro_endereco, numero_endereco, complemento_endereco, bairro_endereco, cidade_endereco, estado_endereco, pais_endereco, cep_endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $oficina_id, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep);

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
            header("Location: cadastro_oficina.php");
            exit();
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao inserir oficina: " . $stmt->error;
        header("Location: cadastro_oficina.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/icon_Onficina.png">
    <title>Cadastro de Oficina - ONFICINA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            padding: 12px 25px;
            margin: 10px;
            background-color: #4157FF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #3545d4;
        }
        .session-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Cadastro de Oficina</h2>
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="session-message">
                <?php echo $_SESSION['mensagem']; ?>
                <?php unset($_SESSION['mensagem']); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="cadastro_oficina.php">
            <input type="text" name="nome_responsavel_oficina" placeholder="Nome do Responsável" required>
            <input type="text" name="cpf_responsavel_oficina" placeholder="CPF do Responsável" required>
            <input type="text" name="nome_oficina" placeholder="Nome da Oficina" required>
            <input type="email" name="email_oficina" placeholder="Email" required>
            <input type="text" name="telefone_oficina" placeholder="Telefone" required>
            <input type="password" name="senha_oficina" placeholder="Senha" required>
            <input type="text" name="cnpj_oficina" placeholder="CNPJ" required>
            <h3>Endereço</h3>
            <input type="text" name="logradouro" placeholder="Logradouro" required>
            <input type="text" name="numero" placeholder="Número" required>
            <input type="text" name="complemento" placeholder="Complemento (Opcional)">
            <input type="text" name="bairro" placeholder="Bairro" required>
            <input type="text" name="cidade" placeholder="Cidade" required>
            <select name="estado" required>
                <option value="" disabled selected>Selecione o Estado</option>
                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SP">São Paulo</option>
                <option value="SE">Sergipe</option>
                <option value="TO">Tocantins</option>
            </select>
            <input type="text" name="pais" placeholder="País" value="Brasil" required>
            <input type="text" name="cep" placeholder="CEP" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>
