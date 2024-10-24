<?php
session_start();

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
$mysqli = new mysqli("localhost", "root", "", "onficina_bd");

// Verificar conexão
if ($mysqli->connect_error) {
    die("Conexão falhou: " . $mysqli->connect_error);
}

// Função para validar campos
function validarCampos($campos) {
    $erros = [];
    foreach ($campos as $campo => $valor) {
        if (empty($valor)) {
            $erros[] = $campo . " é obrigatório.";
        }
    }
    return $erros;
}

// Validação de email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validação de CPF
function validarCPF($cpf) {
    return (strlen($cpf) === 11 && preg_match('/^[0-9]+$/', $cpf));
}

// Validação de CNPJ
function validarCNPJ($cnpj) {
    return (strlen($cnpj) === 14 && preg_match('/^[0-9]+$/', $cnpj));
}

// Função para inserir endereço
function inserirEndereco($mysqli, $tabela, $entidade_id, $dados_endereco) {
    $stmtEndereco = $mysqli->prepare("INSERT INTO $tabela (usuario_id, logradouro_endereco, numero_endereco, complemento_endereco, bairro_endereco, cidade_endereco, estado_endereco, pais_endereco, cep_endereco) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtEndereco->bind_param("issssssss", $entidade_id, $dados_endereco['logradouro'], $dados_endereco['numero'], $dados_endereco['complemento'], $dados_endereco['bairro'], $dados_endereco['cidade'], $dados_endereco['estado'], $dados_endereco['pais'], $dados_endereco['cep']);
    return $stmtEndereco->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_usuario_id = $_POST['tipo_usuario'];

    if (empty($tipo_usuario_id)) {
        $_SESSION['mensagem'] = "Por favor, selecione um tipo de usuário.";
        header("Location: cadastro.html");
        exit();
    }

    // Cadastro de Usuário
    if ($tipo_usuario_id == 1) {
        $nome_usuario = htmlspecialchars($_POST['nome_usuario']);
        $sobrenome_usuario = htmlspecialchars($_POST['sobrenome_usuario']);
        $email = htmlspecialchars($_POST['email_usuario']);
        $telefone = htmlspecialchars($_POST['telefone_usuario']);
        $senha = $_POST['senha_usuario'];
        $cpf = htmlspecialchars($_POST['cpf_usuario']);

        $errosUsuario = validarCampos([
            'Nome' => $nome_usuario,
            'Sobrenome' => $sobrenome_usuario,
            'Email' => $email,
            'Telefone' => $telefone,
            'Senha' => $senha,
            'CPF' => $cpf,
        ]);

        if (!validarEmail($email)) {
            $errosUsuario[] = "Email inválido.";
        }

        if (!validarCPF($cpf)) {
            $errosUsuario[] = "CPF inválido.";
        }

        if (!empty($errosUsuario)) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de usuário: " . implode(", ", $errosUsuario);
            header("Location: cadastro.html");
            exit();
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO usuarios (nome_usuario, sobrenome_usuario, email_usuario, telefone_usuario, senha_usuario, cpf_usuario) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome_usuario, $sobrenome_usuario, $email, $telefone, $senhaHash, $cpf);

        if ($stmt->execute()) {
            $usuario_id = $stmt->insert_id;
            $dados_endereco = [
                'logradouro' => htmlspecialchars($_POST['logradouro_usuario']),
                'numero' => htmlspecialchars($_POST['numero_usuario']),
                'complemento' => htmlspecialchars($_POST['complemento_usuario']),
                'bairro' => htmlspecialchars($_POST['bairro_usuario']),
                'cidade' => htmlspecialchars($_POST['cidade_usuario']),
                'estado' => htmlspecialchars($_POST['estado_usuario']),
                'pais' => htmlspecialchars($_POST['pais_usuario']),
                'cep' => htmlspecialchars($_POST['cep_usuario'])
            ];

            if (inserirEndereco($mysqli, "enderecos_usuarios", $usuario_id, $dados_endereco)) {
                $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
                header("Location: login.html");
                exit();
            } else {
                $_SESSION['mensagem'] = "Erro ao cadastrar endereço.";
            }
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar usuário: " . $stmt->error;
        }
        $stmt->close();

    // Cadastro de Oficina
    } elseif ($tipo_usuario_id == 2) {
        $nome_responsavel = htmlspecialchars($_POST['nome_responsavel_oficina']);
        $cpf_responsavel = htmlspecialchars($_POST['cpf_responsavel_oficina']);
        $nome_oficina = htmlspecialchars($_POST['nome_oficina']);
        $email = htmlspecialchars($_POST['email_oficina']);
        $telefone = htmlspecialchars($_POST['telefone_oficina']);
        $senha = $_POST['senha_oficina'];
        $cnpj = htmlspecialchars($_POST['cnpj_oficina']);

        $errosOficina = validarCampos([
            'Nome do Responsável' => $nome_responsavel,
            'CPF do Responsável' => $cpf_responsavel,
            'Nome da Oficina' => $nome_oficina,
            'Email da Oficina' => $email,
            'Telefone da Oficina' => $telefone,
            'Senha da Oficina' => $senha,
            'CNPJ da Oficina' => $cnpj,
        ]);

        if (!validarEmail($email)) {
            $errosOficina[] = "Email inválido.";
        }

        if (!validarCPF($cpf_responsavel)) {
            $errosOficina[] = "CPF do responsável inválido.";
        }

        if (!validarCNPJ($cnpj)) {
            $errosOficina[] = "CNPJ inválido.";
        }

        if (!empty($errosOficina)) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de oficina: " . implode(", ", $errosOficina);
            header("Location: cadastro.html");
            exit();
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO oficinas (nome_responsavel_oficina, cpf_responsavel_oficina, nome_oficina, email_oficina, telefone_oficina, senha_oficina, cnpj_oficina) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome_responsavel, $cpf_responsavel, $nome_oficina, $email, $telefone, $senhaHash, $cnpj);

        if ($stmt->execute()) {
            $oficina_id = $stmt->insert_id;

            $dados_endereco = [
                'logradouro' => htmlspecialchars($_POST['logradouro_oficina']),
                'numero' => htmlspecialchars($_POST['numero_oficina']),
                'complemento' => htmlspecialchars($_POST['complemento_oficina']),
                'bairro' => htmlspecialchars($_POST['bairro_oficina']),
                'cidade' => htmlspecialchars($_POST['cidade_oficina']),
                'estado' => htmlspecialchars($_POST['estado_oficina']),
                'pais' => htmlspecialchars($_POST['pais_oficina']),
                'cep' => htmlspecialchars($_POST['cep_oficina'])
            ];

            if (inserirEndereco($mysqli, "enderecos_oficinas", $oficina_id, $dados_endereco)) {
                $_SESSION['mensagem'] = "Oficina cadastrada com sucesso!";
                header("Location: login.html");
                exit();
            } else {
                $_SESSION['mensagem'] = "Erro ao cadastrar endereço.";
            }
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar oficina: " . $stmt->error;
        }
        $stmt->close();

    // Cadastro de Desmontadora
    } elseif ($tipo_usuario_id == 3) {
        $nome_responsavel = htmlspecialchars($_POST['nome_responsavel_desmontadora']);
        $cpf_responsavel = htmlspecialchars($_POST['cpf_responsavel_desmontadora']);
        $nome_desmontadora = htmlspecialchars($_POST['nome_desmontadora']);
        $email = htmlspecialchars($_POST['email_desmontadora']);
        $telefone = htmlspecialchars($_POST['telefone_desmontadora']);
        $senha = $_POST['senha_desmontadora'];
        $cnpj = htmlspecialchars($_POST['cnpj_desmontadora']);

        $errosDesmontadora = validarCampos([
            'Nome do Responsável' => $nome_responsavel,
            'CPF do Responsável' => $cpf_responsavel,
            'Nome da Desmontadora' => $nome_desmontadora,
            'Email da Desmontadora' => $email,
            'Telefone da Desmontadora' => $telefone,
            'Senha da Desmontadora' => $senha,
            'CNPJ da Desmontadora' => $cnpj,
        ]);

        if (!validarEmail($email)) {
            $errosDesmontadora[] = "Email inválido.";
        }

        if (!validarCPF($cpf_responsavel)) {
            $errosDesmontadora[] = "CPF do responsável inválido.";
        }

        if (!validarCNPJ($cnpj)) {
            $errosDesmontadora[] = "CNPJ inválido.";
        }

        if (!empty($errosDesmontadora)) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de desmontadora: " . implode(", ", $errosDesmontadora);
            header("Location: cadastro.html");
            exit();
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO desmontadoras (nome_responsavel_desmontadora, cpf_responsavel_desmontadora, nome_desmontadora, email_desmontadora, telefone_desmontadora, senha_desmontadora, cnpj_desmontadora) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome_responsavel, $cpf_responsavel, $nome_desmontadora, $email, $telefone, $senhaHash, $cnpj);

        if ($stmt->execute()) {
            $desmontadora_id = $stmt->insert_id;

            $dados_endereco = [
                'logradouro' => htmlspecialchars($_POST['logradouro_desmontadora']),
                'numero' => htmlspecialchars($_POST['numero_desmontadora']),
                'complemento' => htmlspecialchars($_POST['complemento_desmontadora']),
                'bairro' => htmlspecialchars($_POST['bairro_desmontadora']),
                'cidade' => htmlspecialchars($_POST['cidade_desmontadora']),
                'estado' => htmlspecialchars($_POST['estado_desmontadora']),
                'pais' => htmlspecialchars($_POST['pais_desmontadora']),
                'cep' => htmlspecialchars($_POST['cep_desmontadora'])
            ];

            if (inserirEndereco($mysqli, "enderecos_desmontadoras", $desmontadora_id, $dados_endereco)) {
                $_SESSION['mensagem'] = "Desmontadora cadastrada com sucesso!";
                header("Location: login.html");
                exit();
            } else {
                $_SESSION['mensagem'] = "Erro ao cadastrar endereço.";
            }
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar desmontadora: " . $stmt->error;
        }
        $stmt->close();

    // Cadastro de Prestador
    } elseif ($tipo_usuario_id == 4) {
        $nome_prestador = htmlspecialchars($_POST['nome_prestador']);
        $sobrenome_prestador = htmlspecialchars($_POST['sobrenome_prestador']);
        $email = htmlspecialchars($_POST['email_prestador']);
        $telefone = htmlspecialchars($_POST['telefone_prestador']);
        $senha = $_POST['senha_prestador'];
        $cpf = htmlspecialchars($_POST['cpf_prestador']);
        $cnpj = htmlspecialchars($_POST['cnpj_prestador']);

        $errosPrestador = validarCampos([
            'Nome do Prestador' => $nome_prestador,
            'Sobrenome do Prestador' => $sobrenome_prestador,
            'Email' => $email,
            'Telefone' => $telefone,
            'Senha' => $senha,
            'CPF' => $cpf,
        ]);

        if (!validarEmail($email)) {
            $errosPrestador[] = "Email inválido.";
        }

        if (!validarCPF($cpf)) {
            $errosPrestador[] = "CPF inválido.";
        }

        if (!empty($errosPrestador)) {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos de prestador: " . implode(", ", $errosPrestador);
            header("Location: cadastro.html");
            exit();
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO prestadores (nome_prestador, sobrenome_prestador, email_prestador, telefone_prestador, senha_prestador, cpf_prestador, cnpj_prestador) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome_prestador, $sobrenome_prestador, $email, $telefone, $senhaHash, $cpf, $cnpj);

        if ($stmt->execute()) {
            $prestador_id = $stmt->insert_id;

            $dados_endereco = [
                'logradouro' => htmlspecialchars($_POST['logradouro_prestador']),
                'numero' => htmlspecialchars($_POST['numero_prestador']),
                'complemento' => htmlspecialchars($_POST['complemento_prestador']),
                'bairro' => htmlspecialchars($_POST['bairro_prestador']),
                'cidade' => htmlspecialchars($_POST['cidade_prestador']),
                'estado' => htmlspecialchars($_POST['estado_prestador']),
                'pais' => htmlspecialchars($_POST['pais_prestador']),
                'cep' => htmlspecialchars($_POST['cep_prestador'])
            ];

            if (inserirEndereco($mysqli, "enderecos_prestadores", $prestador_id, $dados_endereco)) {
                $_SESSION['mensagem'] = "Prestador cadastrado com sucesso!";
                header("Location: login.html");
                exit();
            } else {
                $_SESSION['mensagem'] = "Erro ao cadastrar endereço.";
            }
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar prestador: " . $stmt->error;
        }
        $stmt->close();
    }

    // Redirecionar para a página de cadastro em caso de erro
    header("Location: cadastro.html");
    exit();
}

$mysqli->close();
?>
