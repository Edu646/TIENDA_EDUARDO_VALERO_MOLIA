<?php 
namespace Repositories;

use Models\User;
use PDOException;

class UserRepository
{
    public function save(User $user): void
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('INSERT INTO usuarios (nombre, apellidos, email, password, rol) VALUES (:nombre, :apellidos, :email, :password, :rol)');
    
        // Asignar valores a variables antes de pasarlos a bindParam
        $nombre = $user->getNombre();
        $apellidos = $user->getApellidos();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $rol = $user->getRol();
    
        // Vincular variables
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
    
        if (!$stmt->execute()) {
            error_log("Error al ejecutar consulta: " . print_r($stmt->errorInfo(), true));
            throw new \Exception('Error al ejecutar la consulta de inserción.');
        }
    }
    

    public function findByEmail(string $email): ?User
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? User::fromArray($data) : null;
    }

    public function findAll(): array
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('SELECT * FROM usuarios');
        $stmt->execute();
        $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Transformar los resultados en instancias de User
        return array_map(function ($usuarioData) {
            return User::fromArray($usuarioData);
        }, $usuarios);
    }

    public function findById($id)
{
    // Prepara la consulta SQL para buscar el usuario por ID
    $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    
    // Vincula el parámetro de ID
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    
    // Ejecuta la consulta
    $stmt->execute();
    
    // Obtiene los resultados y los convierte en un objeto User
    $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if ($userData) {
        // Si existe un usuario, lo retornamos como un objeto User
        return User::fromArray($userData);
    }
    
    // Si no se encuentra, retornamos null
    return null;
}

public function update(User $usuario)
{
    // Prepara la consulta SQL para actualizar los datos del usuario
    $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
    $stmt = $db->prepare('UPDATE users SET nombre = :nombre, email = :email, rol = :rol WHERE id = :id');
    
    // Vincula los parámetros
    $stmt->bindParam(':nombre', $usuario->getNombre());
    $stmt->bindParam(':email', $usuario->getEmail());
    $stmt->bindParam(':rol', $usuario->getRol());
    $stmt->bindParam(':id', $usuario->getId(), \PDO::PARAM_INT);
    
    // Ejecuta la consulta
    $stmt->execute();
}



    public function delete(int $id): void
    {
        $db = new \PDO('mysql:host=localhost;dbname=miTienda', 'root', '');
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function saveRecoveryToken($userId, $token)
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('UPDATE usuarios SET recovery_token = :token WHERE id = :id');
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }


    public function findByRecoveryToken($token)
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE recovery_token = :token LIMIT 1');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            return User::fromArray($user);
        }

        return null;
    }

    public function updatePassword($userId, $hashedPassword)
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('UPDATE usuarios SET password = :password WHERE id = :id');
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }

    public function clearRecoveryToken($userId)
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('UPDATE usuarios SET recovery_token = NULL WHERE id = :id');
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }

    
}
?>
