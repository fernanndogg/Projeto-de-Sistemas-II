<?php
// Conexão com o banco de dados
$localhost = "localhost";
$usuario = "root";
$senha = "";
$banco = "comfuturo";

$conn = new mysqli($localhost, $usuario, $senha, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];

    // Verifica se o e-mail já existe no banco de dados
    $checkQuery = "SELECT id FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
       echo "Este e-mail já está cadastrado. Tente novamente";
    } else {
        // Insere o novo usuário no banco de dados
        $insertQuery = "INSERT INTO usuario (nome, email) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $nome, $email);
        if ($stmt->execute()) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar o usuário: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>