<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $stmt = $conn->query('SELECT * FROM users');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        retorno($users);
        exit;
    } catch (PDOException $e) {
        logMe(['error' => $e->getMessage()], 'error');
        retorno(['error' => 'Deu um erro ao salvar no banco, avise ao administrador'], 400);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if(empty($data['nome'])){
        logMe(['error' => 'O nome do usuário é obrigatório'], 'error');
        retorno(['error' => 'O nome do usuário é obrigatório'], 400);
        exit;
    }
    if(empty($data['email'])){
        logMe(['error' => 'O e-mail do usuário é obrigatório'], 'error');
        retorno(['error' => 'O e-mail do usuário é obrigatório'], 400);
        exit;
    }

    $nome = $data['nome'];
    $email = $data['email'];
    $senha = password($data['senha']);
    $data_cadastro = date("Y-m-d H:i:s");
    try {
        $stmt = $conn->prepare('INSERT INTO usuarios (nome, email, senha, criado_em, atualizado_em) VALUES (:nome, :email, :senha, :criado_em, :atualizado_em)');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':criado_em', $data_cadastro);
        $stmt->bindParam(':atualizado_em', $data_cadastro);
        $stmt->execute();
        $usuario_id = $conn->lastInsertId();
        retorno(['id' => $usuario_id, 'nome' => $nome, 'email' => $email, 'senha' => $senha, 'criado_em' => $data_cadastro, 'atualizado_em' => $data_cadastro], 201);
        exit;
    } catch (PDOException $e) {
        logMe(['error' => $e->getMessage()], 'error');
        retorno(['error' => 'Ocorreu um erro ao tentar salvar os dados no banco.'], 400);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if(empty($data['nome'])){
        retorno(['error' => 'O nome do usuario é obrigatório'], 400);
        exit;
    }
    $usuario_id = $data['id'];
    $nome = $data['nome'];
    $senha = password($data['senha']);
    $email = $data['email'];
    $data = date("Y-m-d H:i:s");
    try {
        $stmt = $conn->prepare('UPDATE usuarios SET nome = :nome,senha = :senha, email = :email, atualizado_em = :atualizado_em WHERE id = :id');
        $stmt->bindParam(':id', $usuario_id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':atualizado_em', $data);
        $stmt->execute();

        retorno(['success' => 'Dados atualizados com sucesso']);
        exit;
    } catch (PDOException $e) {
        logMe(['error' => $e->getMessage()], 'error');
        retorno(['error' => 'Não foi possível atualizar o usuario'], 400);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if(empty($data['usuario_id'])){
        retorno(['error' => 'O id do usuário é obrigatório'], 400);
        exit;
    }
    $usuario_id = $data['usuario_id'];
    try {
        $stmt = $conn->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->bindParam(':id', $usuario_id);
        $stmt->execute();
        retorno(['success' => true]);
        exit;
    } catch (PDOException $e) {
        logMe(['error' => $e->getMessage()], 'error');
        retorno(['error' => 'Não foi possível deletar o produto'], 400);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    if(!empty($_GET['usuario_id'])){
        $usuario_id = (int)$_GET['usuario_id'];
        try {
            $stmt = $conn->prepare('SELECT * FROM usuarios 
            WHERE id = :id');
            $stmt->bindParam(':id', $usuario_id);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($usuarios);
        } catch (PDOException $e) {
            logMe(['error' => 'Usuário não encontrado.'], 'error');
            retorno(['error' => 'Usuário não encontrado.'], 400);
            exit;
        }
    }
    if(!empty($_GET['buscar'])){
        $buscar = (string) $_GET["buscar"];
        try {
            $stmt = $conn->prepare("SELECT * FROM usuarios
            WHERE nome like CONCAT('%', :buscar,'%')");
            $stmt->bindParam(':buscar', $buscar );
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            retorno($usuarios);
            exit;
        } catch (PDOException $e) {
            logMe(['error' => $e->getMessage()], 'error');
            retorno(['error' => 'Usuário não encontrado.'], 400);
            exit;
        }
    }
}