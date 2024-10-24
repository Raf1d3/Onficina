<?php
// Iniciar a sessão
session_start();

// Conectar ao banco de dados
$servername = "localhost";
$username = "root"; // Altere conforme necessário
$password = ""; // Altere conforme necessário
$dbname = "onficina_bd"; // Nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para inserir o endereço e retornar o ID
function inserirEndereco($conn, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep) {
    $stmt = $conn->prepare("INSERT INTO enderecos_usuarios (logradouro_endereco, numero_endereco, complemento_endereco, bairro_endereco, cidade_endereco, estado_endereco, pais_endereco, cep_endereco) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep);
    
    if ($stmt->execute()) {
        return $conn->insert_id; // Retorna o ID do endereço inserido
    } else {
        return false; // Caso ocorra algum erro
    }
}

// Função para validar campos
function validarCampos($campos) {
    foreach ($campos as $campo) {
        if (empty(trim($campo))) {
            return false; // Retorna falso se algum campo estiver vazio
        }
    }
    return true; // Retorna verdadeiro se todos os campos estiverem preenchidos
}

// Processar o cadastro baseado no tipo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados de endereço
    $logradouro = $_POST['logradouro'] ?? null;
    $numero = $_POST['numero'] ?? null;
    $complemento = $_POST['complemento'] ?? '';
    $bairro = $_POST['bairro'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $pais = $_POST['pais'] ?? null;
    $cep = $_POST['cep'] ?? null;

    // Validar campos de endereço
    if (!validarCampos([$logradouro, $numero, $bairro, $cidade, $estado, $pais, $cep])) {
        $_SESSION['mensagem'] = "Por favor, preencha todos os campos de endereço.";
        header("Location: cadastro.html");
        exit();
    }

    // Inserir o endereço e obter o ID
    $endereco_id = inserirEndereco($conn, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep);

    if (!$endereco_id) {
        $_SESSION['mensagem'] = "Erro ao inserir o endereço!";
        header("Location: cadastro.html");
        exit();
    }

    // Cadastro de Usuário
    if (isset($_POST['nome_usuario'])) {
        // Capturar os dados do formulário
        $nome = $_POST['nome_usuario'];
        $sobrenome = $_POST['sobrenome_usuario'];
        $email = $_POST['email_usuario'];
        $telefone = $_POST['telefone_usuario'];
        $senha = $_POST['senha_usuario'];
        $cpf = $_POST['cpf_usuario'];

        // Validar campos de usuário
        if (!validarCampos([$nome, $sobrenome, $email, $telefone, $senha, $cpf])) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de usuário.";
            header("Location: cadastro.html");
            exit();
        }

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir o usuário com o ID do endereço
        $stmt = $conn->prepare("INSERT INTO usuarios (nome_usuario, sobrenome_usuario, email_usuario, telefone_usuario, senha_usuario, cpf_usuario) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $sobrenome, $email, $telefone, $senhaHash, $cpf);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro: " . $stmt->error;
        }
        $stmt->close();

    // Cadastro de Oficina
    } elseif (isset($_POST['nome_oficina'])) {
        // Capturar os dados do formulário
        $nome_responsavel = $_POST['nome_responsavel_oficina'];
        $cpf_responsavel = $_POST['cpf_responsavel_oficina'];
        $nome_oficina = $_POST['nome_oficina'];
        $email = $_POST['email_oficina'];
        $telefone = $_POST['telefone_oficina'];
        $senha = $_POST['senha_oficina'];
        $cnpj = $_POST['cnpj_oficina'];

        // Validar campos de oficina
        if (!validarCampos([$nome_responsavel, $cpf_responsavel, $nome_oficina, $email, $telefone, $senha, $cnpj])) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de oficina.";
            header("Location: cadastro.html");
            exit();
        }

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir a oficina
        $stmt = $conn->prepare("INSERT INTO oficinas (nome_responsavel_oficina, cpf_responsavel_oficina, nome_oficina, email_oficina, telefone_oficina, senha_oficina, cnpj_oficina) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome_responsavel, $cpf_responsavel, $nome_oficina, $email, $telefone, $senhaHash, $cnpj);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Oficina cadastrada com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro: " . $stmt->error;
        }
        $stmt->close();

    // Cadastro de Desmontadora
    } elseif (isset($_POST['nome_desmontadora'])) {
        // Capturar os dados do formulário
        $nome_responsavel = $_POST['nome_responsavel_desmontadora'];
        $cpf_responsavel = $_POST['cpf_responsavel_desmontadora'];
        $nome_desmontadora = $_POST['nome_desmontadora'];
        $email = $_POST['email_desmontadora'];
        $telefone = $_POST['telefone_desmontadora'];
        $senha = $_POST['senha_desmontadora'];
        $cnpj = $_POST['cnpj_desmontadora'];

        // Validar campos de desmontadora
        if (!validarCampos([$nome_responsavel, $cpf_responsavel, $nome_desmontadora, $email, $telefone, $senha, $cnpj])) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de desmontadora.";
            header("Location: cadastro.html");
            exit();
        }

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir a desmontadora
        $stmt = $conn->prepare("INSERT INTO desmontadoras (nome_responsavel_desmontadora, cpf_responsavel_desmontadora, nome_desmontadora, email_desmontadora, telefone_desmontadora, senha_desmontadora, cnpj_desmontadora) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome_responsavel, $cpf_responsavel, $nome_desmontadora, $email, $telefone, $senhaHash, $cnpj);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Desmontadora cadastrada com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro: " . $stmt->error;
        }
        $stmt->close();

    // Cadastro de Prestador
    } elseif (isset($_POST['nome_prestador'])) {
        // Capturar os dados do formulário
        $nome = $_POST['nome_prestador'];
        $sobrenome = $_POST['sobrenome_prestador'];
        $email = $_POST['email_prestador'];
        $telefone = $_POST['telefone_prestador'];
        $senha = $_POST['senha_prestador'];
        $cpf = $_POST['cpf_prestador'];
        $cnpj = $_POST['cnpj_prestador'] ?? null; // CNPJ é opcional

        // Validar campos de prestador
        if (!validarCampos([$nome, $sobrenome, $email, $telefone, $senha, $cpf])) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de prestador.";
            header("Location: cadastro.html");
            exit();
        }

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir o prestador de serviço
        $stmt = $conn->prepare("INSERT INTO prestadores (nome_prestador, sobrenome_prestador, email_prestador, telefone_prestador, senha_prestador, cpf_prestador, cnpj_prestador) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome, $sobrenome, $email, $telefone, $senhaHash, $cpf, $cnpj);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Prestador de serviço cadastrado com sucesso!";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
