<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($url[1])) {
    try {
        global $conn;
        $stmt = $conn->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($users)) {
            retorno(['mensagem' => 'Sem registro no banco de dados!']);
            exit;
        }
        retorno($users);
        exit;
    } catch (PDOException $e) {
        logMe(['error' => $e->getMessage()], 'error');
        retorno(['error' => 'Ops! Ocorreu um erro ao tentar listar os 
        usuários'], 400);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['name'])) {
        logMe(['error' => 'O nome do usuário é obrigatório'], 'error');
        retorno(['error' => 'O nome do usuário é obrigatório'], 400);
        exit;
    }
    if (empty($data['email'])) {
        logMe(['error' => 'O e-mail do usuário é obrigatório'], 'error');
        retorno(['error' => 'O e-mail do usuário é obrigatório'], 400);
        exit;
    }

    $name = $data['name'];
    $email = $data['email'];
    $password = password($data['password']);
    $status = $data['status'];
    try {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO users (`name`, `email`, `password`, `status`) VALUES (:name, :email, :password, :status)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $usuario_id = $conn->lastInsertId();
        retorno(['id' => $usuario_id, 'nome' => $name, 'email' => $email], 201);
        exit;
    } catch (PDOException $e) {
        logMe(['error' => $e->getMessage()], 'error');
        retorno(['error' => 'Ocorreu um erro ao tentar salvar os dados no banco.'], 400);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['nome'])) {
        retorno(['error' => 'O nome do usuario é obrigatório'], 400);
        exit;
    }
    $cod_user = $data['id'];
    $name = $data['name'];
    $password = password($data['password']);
    $email = $data['email'];
    try {
        $stmt = $conn->prepare('UPDATE users SET name = :name, password = :password, email = :email WHERE cod_user = :cod_user');
        $stmt->bindParam(':cod_user', $usuario_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
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

    if (empty($data['usuario_id'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($url[1])) {
    
    if ($url[1] === 'usuario') {
        $usuario = (int)$url[2];
        try {
            $stmt = $conn->prepare('SELECT * FROM users WHERE cod_user = :id');
            $stmt->bindParam(':id', $usuario);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            retorno($usuario);
        } catch (PDOException $e) {
            logMe(['error' => 'Usuário não encontrado. Codigo = '.$usuario], 'error');
            retorno(['error' => 'Usuário não encontrado.'], 400);
            exit;
        }
    }
    
    if ($url[1] === 'buscar') {
        $buscar = (string)$url[2];
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE `name` like CONCAT('%', :buscar,'%') or `email` like CONCAT('%', :buscar,'%')");
            $stmt->bindParam(':buscar', $buscar);
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
