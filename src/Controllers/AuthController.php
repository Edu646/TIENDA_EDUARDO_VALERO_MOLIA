<?php 
namespace Controllers;

use Lib\Pages;
use Models\User;
use Services\UserService;
use Repositories\UserRepository;

class AuthController
{
    private Pages $pages;
    private UserService $userService;
    private UserRepository $userRepository;

    public function __construct()
    {
        error_log("Checkpoint: Entrando al constructor de AuthController");
        $this->pages = new Pages();
        $this->userService = new UserService();
        $this->userRepository = new UserRepository();
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
                
                // Redirigir según el rol
                if ($user['rol'] === 'admin') {
                    header('Location: ' . BASE_URL );
                } else {
                    header('Location: ' . BASE_URL);
                }
            } else {
                // Error en el login
                $_SESSION['login_error'] = 'Correo o contraseña incorrectos.';
                header('Location: ' . BASE_URL . 'login');
            }
        }
    }

    public function listUsers()
    {
        error_log("Checkpoint: Listando usuarios");
        $usuarios = $this->userRepository->findAll();
        $this->pages->render('Auth/list', ['usuarios' => $usuarios]);
    }

    // Función para editar un usuario
    public function editUser($id)
    {
        error_log("Checkpoint: Editando usuario con ID $id");
    
        // Verificar si el ID es válido antes de buscar el usuario
        if ($id) {
            $usuario = $this->userRepository->findById($id);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userData = $_POST['data'];
                $usuario->setNombre($userData['nombre']);
                $usuario->setEmail($userData['email']);
                $usuario->setRol($userData['role']);
                
                // Validar y actualizar
                if ($usuario->validar()) {
                    try {
                        $this->userRepository->update($usuario);
                        $_SESSION['edit'] = 'success';
                        header('Location: ' . BASE_URL . 'list');
                        exit(); // Es importante que uses exit después de header para evitar continuar con el código.
                    } catch (\Exception $e) {
                        $_SESSION['edit'] = 'fail';
                        $_SESSION['error'] = $e->getMessage();
                        error_log("Error en editUser: " . $e->getMessage());
                    }
                } else {
                    $_SESSION['edit'] = 'fail';
                    $_SESSION['errors'] = $usuario->getErrors();
                }
            }
    
            // Renderizar formulario de edición
            $this->pages->render('Auth/editForm', ['usuario' => $usuario]);
        } else {
            error_log("Error: El ID del usuario no es válido.");
        }
    }

    

    // Función para eliminar un usuario
    public function deleteUser($id)
    {
        error_log("Checkpoint: Eliminando usuario con ID $id");
    
        try {
            // Verificamos si el usuario existe antes de eliminar
            $usuario = $this->userRepository->findById($id);
            if ($usuario) {
                $this->userRepository->delete($id);
                $_SESSION['delete'] = 'success';
                error_log("Usuario eliminado con éxito.");
            } else {
                throw new \Exception("El usuario no existe.");
            }
        } catch (\Exception $e) {
            $_SESSION['delete'] = 'fail';
            $_SESSION['error'] = $e->getMessage();
            error_log("Error en deleteUser: " . $e->getMessage());
        }
    
        // Redirigimos a la lista de usuarios después de eliminar
        header('Location: ' . BASE_URL );
        exit(); // Usamos exit para evitar continuar con el código después de la redirección
    }


    public function addUser()
{
    error_log("Checkpoint: Añadiendo nuevo usuario");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recibimos los datos del formulario
        $userData = $_POST['data'];

        if (isset($userData) && !empty($userData)) {
            $usuario = User::fromArray($userData);

            // Validamos los datos del usuario
            if ($usuario->validar()) {
                $password = password_hash($usuario->getPassword(), PASSWORD_BCRYPT, ['cost' => 5]);
                $usuario->setPassword($password);

                // Asignamos un rol por defecto o el que viene del formulario
                $role = isset($userData['role']) && in_array($userData['role'], ['user', 'admin']) ? $userData['role'] : 'user';
                $usuario->setRol($role);

                try {
                    $this->userService->registerUser($usuario);
                    $_SESSION['add'] = 'success';
                    error_log("Checkpoint: Usuario añadido exitosamente con rol " . $role);
                } catch (\Exception $e) {
                    $_SESSION['add'] = 'fail';
                    $_SESSION['error'] = $e->getMessage();
                    error_log("Error en addUser: " . $e->getMessage());
                }
            } else {
                $_SESSION['add'] = 'fail';
                $_SESSION['errors'] = $usuario->getErrors();
                error_log("Errores de validación en addUser: " . implode(", ", $usuario->getErrors()));
            }
        } else {
            $_SESSION['add'] = 'fail';
            error_log("Checkpoint: Datos POST no válidos en addUser");
        }
    }

    // Redirigimos de vuelta a la lista de usuarios
    header('Location: ' . BASE_URL );
}

}
