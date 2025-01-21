<?php 
namespace Repositories;

use Models\User;

class UserRepository
{
    public function save(User $user): void
    {
        $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
        $stmt = $db->prepare('INSERT INTO usuarios (nombre, apellidos, email, password, rol) VALUES (:nombre, :apellidos, :email, :password, :rol)');
    
        // Vincular variables
        $stmt->bindParam(':nombre', $user->getNombre());
        $stmt->bindParam(':apellidos', $user->getApellidos());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':password', $user->getPassword());
        $stmt->bindParam(':rol', $user->getRol());
    
        error_log("Checkpoint: Ejecutando consulta con datos: " . print_r($user->toArray(), true));
    
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
    $db = new \PDO('mysql:host=localhost;dbname=miTienda', 'root', '');
    $stmt = $db->prepare('SELECT * FROM users');
    $stmt->execute();
    $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // Transformar los resultados en instancias de User
    return array_map(function ($usuarioData) {
        return User::fromArray($usuarioData);
    }, $usuarios);
    }


    public function delete(int $id): void
    {
    $db = new \PDO('mysql:host=localhost;dbname=miTienda', 'root', '');
    $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    }

    

}
?>
