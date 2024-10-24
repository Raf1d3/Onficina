<?php
// Inicia a sessão
session_start();

// Conexão ao banco de dados
$mysqli = new mysqli("localhost", "root", "", "onficina_bd");

// Verifica se a conexão foi bem-sucedida
if ($mysqli->connect_error) {
    die("Conexão falhou: " . $mysqli->connect_error);
}

// Variável para armazenar erro
$erro = "";

// Captura email e senha do formulário
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

// Verifica se os campos estão preenchidos
if (empty($email) || empty($senha)) {
    $erro = "Por favor, preencha todos os campos.";
} else {
    // Tenta encontrar o usuário na tabela de usuários
    $query = "SELECT 'usuario' AS tipo, id, nome_usuario AS nome, senha_usuario AS senha FROM usuarios WHERE email_usuario = ?";
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se encontrou na tabela de usuários
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            // Verifica a senha
            if (password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido, cria a sessão e redireciona para o painel de usuário
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['tipo'] = $usuario['tipo'];
                $_SESSION['nome'] = $usuario['nome']; // Adicione esta linha
                header("Location: painel_usuario.php");
                exit();
            } else {
                $erro = "Senha incorreta para usuário!";
            }
        } else {
            // Tenta encontrar o prestador na tabela de prestadores
            $query = "SELECT 'prestador' AS tipo, id, nome_prestador AS nome, senha_prestador AS senha FROM prestadores_servicos WHERE email_prestador = ?";
            $stmt = $mysqli->prepare($query);
            
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                // Verifica se encontrou na tabela de prestadores
                if ($result->num_rows > 0) {
                    $prestador = $result->fetch_assoc();

                    // Verifica a senha
                    if (password_verify($senha, $prestador['senha'])) {
                        // Login bem-sucedido, cria a sessão e redireciona para o painel de prestador
                        $_SESSION['id'] = $prestador['id'];
                        $_SESSION['tipo'] = $prestador['tipo'];
                        $_SESSION['nome'] = $prestador['nome']; // Adicione esta linha
                        header("Location: painel_prestador.php");
                        exit();
                    } else {
                        $erro = "Senha incorreta para prestador!";
                    }
                } else {
                    // Tenta encontrar a oficina na tabela de oficinas
                    $query = "SELECT 'oficina' AS tipo, id AS id, nome_oficina AS nome, senha_oficina AS senha FROM oficinas WHERE email_oficina = ?";
                    $stmt = $mysqli->prepare($query);

                    if ($stmt) {
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Verifica se encontrou na tabela de oficinas
                        if ($result->num_rows > 0) {
                            $oficina = $result->fetch_assoc();

                            // Verifica a senha
                            if (password_verify($senha, $oficina['senha'])) {
                                // Login bem-sucedido, cria a sessão e redireciona para o painel de oficina
                                $_SESSION['id'] = $oficina['id'];
                                $_SESSION['tipo'] = $oficina['tipo'];
                                $_SESSION['nome'] = $oficina['nome']; // Adicione esta linha
                                header("Location: painel_oficina.php");
                                exit();
                            } else {
                                $erro = "Senha incorreta para oficina!";
                            }
                        } else {
                            // Tenta encontrar a desmontadora na tabela de desmontadoras
                            $query = "SELECT 'desmontadora' AS tipo, id, nome_desmontadora AS nome, senha_desmontadora AS senha FROM desmontadoras WHERE email_desmontadora = ?";
                            $stmt = $mysqli->prepare($query);

                            if ($stmt) {
                                $stmt->bind_param("s", $email);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Verifica se encontrou na tabela de desmontadoras
                                if ($result->num_rows > 0) {
                                    $desmontadora = $result->fetch_assoc();

                                    // Verifica a senha
                                    if (password_verify($senha, $desmontadora['senha'])) {
                                        // Login bem-sucedido, cria a sessão e redireciona para o painel de desmontadora
                                        $_SESSION['id'] = $desmontadora['id'];
                                        $_SESSION['tipo'] = $desmontadora['tipo'];
                                        $_SESSION['nome'] = $desmontadora['nome']; // Adicione esta linha
                                        header("Location: painel_desmontadora.php");
                                        exit();
                                    } else {
                                        $erro = "Senha incorreta para desmontadora!";
                                    }
                                } else {
                                    $erro = "Usuário não encontrado!";
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

// Se houve erro, redireciona com a mensagem
if ($erro) {
    header("Location: login.html?error=true");
    exit();
}

// Fecha a conexão
$mysqli->close();
?>
