<?php
// Conexão ao banco de dados
$mysqli = new mysqli("localhost", "root", "", "onficina_bd");

// Verifica se a conexão foi bem-sucedida
if ($mysqli->connect_error) {
    die("Conexão falhou: " . $mysqli->connect_error);
}

// Captura email e senha do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];

// Tenta encontrar o usuário na tabela de usuários
$query = "SELECT 'usuario' AS tipo, id, nome, senha FROM usuarios WHERE email = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se encontrou na tabela de usuários
if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    
    // Verifica a senha
    if (password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido, redireciona para o painel de usuário
        header("Location: painel-usuario.php");
        exit();
    } else {
        echo "Senha incorreta!";
    }
} else {
    // Tenta encontrar a empresa na tabela de empresas
    $query = "SELECT 'empresa' AS tipo, id_empresa AS id, nome_empresa AS nome, senha_empresa AS senha FROM empresas WHERE email_empresa = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifica se encontrou na tabela de empresas
    if ($result->num_rows > 0) {
        $empresa = $result->fetch_assoc();
        
        // Verifica a senha
        if (password_verify($senha, $empresa['senha'])) {
            // Login bem-sucedido, redireciona para o painel de empresas
            header("Location: painel-empresa.php");
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        // Tenta encontrar o autônomo na tabela de autônomos
        $query = "SELECT 'autonomo' AS tipo, id_autonomo AS id, nome, senha FROM autonomos WHERE email = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verifica se encontrou na tabela de autônomos
        if ($result->num_rows > 0) {
            $autonomo = $result->fetch_assoc();
            
            // Verifica a senha
            if (password_verify($senha, $autonomo['senha'])) {
                // Login bem-sucedido, redireciona para o painel de autônomos
                header("Location: painel-autonomo.php");
                exit();
            } else {
                echo "Senha incorreta!";
            }
        } else {
            echo "Usuário não encontrado!";
        }
    }
}

// Fecha a conexão
$stmt->close();
$mysqli->close();
