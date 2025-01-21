<?php 
namespace Controllers;

use Lib\Pages;
use Models\User;
use Services\UserService;

class AuthController
{
    private Pages $pages;
    private UserService $userService;

    public function __construct()
    {
        error_log("Checkpoint: Entrando al constructor de AuthController");
        $this->pages = new Pages();
        $this->userService = new UserService();
    }

    public function index()
    {
        error_log("Checkpoint: Entrando al método index");
        $this->pages->render('inicio'); 
    }

    public function login()
    {
        error_log("Checkpoint: Entrando al método login");
        $this->pages->render('Auth/login');
    }

    public function register()
    {
        error_log("Checkpoint: Entrando al método register");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Checkpoint: Método POST recibido en register");
            if (isset($_POST['data']) && !empty($_POST['data'])) {
                $userData = $_POST['data'];
                $usuario = User::fromArray($userData);

                if ($usuario->validar()) {
                    $password = password_hash($usuario->getPassword(), PASSWORD_BCRYPT, ['cost' => 5]);
                    $usuario->setPassword($password);

                    // Verificar si se ha enviado el rol y es válido
                    $role = isset($userData['role']) && in_array($userData['role'], ['user', 'admin']) ? $userData['role'] : 'user';
                    $usuario->setRol($role);

                    try {
                        $this->userService->registerUser($usuario);
                        $_SESSION['register'] = 'success';
                        error_log("Checkpoint: Usuario registrado exitosamente con rol " . $role);
                    } catch (\Exception $e) {
                        $_SESSION['register'] = 'fail';
                        $_SESSION['error'] = $e->getMessage();
                        error_log("Error en register: " . $e->getMessage());
                    }
                } else {
                    $_SESSION['register'] = 'fail';
                    $_SESSION['errors'] = $usuario->getErrors();
                    error_log("Errores de validación en register: " . implode(", ", $usuario->getErrors()));
                }
            } else {
                $_SESSION['register'] = 'fail';
                error_log("Checkpoint: Datos POST no válidos en register");
            }
        }

        $this->pages->render('Auth/registerForm');
    }

    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
        
            $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
            $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        
            // Vincular variable
            $stmt->bindParam(':email', $email);
        
            // Ejecutar la consulta
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
            if ($user && password_verify($password, $user['password'])) {
                // Iniciar sesión
                $_SESSION['user'] = $user;
                header('Location: ' . BASE_URL);
            } else {
                // Error en el login
                $_SESSION['login_error'] = 'Correo o contraseña incorrectos.';
                header('Location: ' . BASE_URL . 'login');
            }
        }
    }
    
}
