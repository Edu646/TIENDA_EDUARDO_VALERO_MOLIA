<?php 
namespace Controllers;

use Lib\Pages;
use Models\User;
use Services\UserService;
use Repositories\UserRepository;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private Pages $pages;
    private UserService $userService;
    private UserRepository $userRepository;
    private string $jwtSecret;

    public function __construct()
    {
        error_log("Checkpoint: Entrando al constructor de AuthController");
        $this->pages = new Pages();
        $this->userService = new UserService();
        $this->userRepository = new UserRepository(); // Inicializar userRepository
        $this->jwtSecret = 'Vq3p$z!J8m@XcL5dN2^wYk9T&B7oGfQh'; // Replace 'your_secret_key' with your actual secret key
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
    
                    // Establecer el rol por defecto como 'user'
                    $usuario->setRol('user');
    
                    // Generar token JWT
                    $payload = [
                        'sub' => $usuario->getEmail(),
                        'name' => $usuario->getNombre(),
                        'password' => $usuario->getPassword(),
                        'iat' => time(),
                        'exp' => time() + 3600 // Token expira en 1 hora
                    ];
                    $token = JWT::encode($payload, $this->jwtSecret, 'HS256');
                    $usuario->setToken($token);
                    // Guardar el token en la base de datos
                    $db = new \PDO('mysql:host=localhost;dbname=tienda', 'root', '');
                    $stmt = $db->prepare('UPDATE usuarios SET token = :token WHERE email = :email');
                    $stmt->bindParam(':token', $token);
                    $stmt->bindParam(':email', $usuario->getEmail());
                    $stmt->execute();
    
                    // Enviar correo de confirmación
                    // Guardar el token en la sesión
                    $_SESSION['confirm_token'] = $token;
    
                    // Enlace limpio sin token en la URL
                    $confirmationLink = BASE_URL . 'confirmRegistration';
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.mailtrap.io';
                        $mail->SMTPAuth = true;
                        $mail->Username = '828a04b69cf388';
                        $mail->Password = 'd5c6c03cad7d30';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
    
                        $mail->CharSet = 'UTF-8';
                        $mail->setFrom('tu_correo@example.com', 'Nombre');
                        $mail->addAddress($usuario->getEmail());
                        $mail->isHTML(true);
                        $mail->Subject = 'Confirmación de registro';
                        $mail->Body = "Para confirmar su registro, haga clic en el siguiente enlace: <a href='$confirmationLink'>$confirmationLink</a>";
    
                        $mail->send();
                        $_SESSION['register'] = 'success';
                        error_log("Checkpoint: Correo de confirmación enviado exitosamente.");
                    } catch (Exception $e) {
                        $_SESSION['register'] = 'fail';
                        $_SESSION['error'] = 'No se pudo enviar el correo de confirmación. Error: ' . $mail->ErrorInfo;
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
   // Función para editar un usuario
public function editUser($id)
{
    error_log("Checkpoint: Editando usuario con ID $id");

    // Verificar si el ID es válido antes de buscar el usuario
    if ($id) {
        // Buscar el usuario por ID
        $usuario = $this->userRepository->findById($id);
        
        // Si el usuario no existe
        if (!$usuario) {
            $_SESSION['edit'] = 'fail';
            $_SESSION['error'] = 'Usuario no encontrado.';
            header('Location: ' . BASE_URL . 'list');
            exit();
        }

        // Si el formulario fue enviado por POST, se actualizan los datos
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = $_POST['data'];
            $usuario->setNombre($userData['nombre']);
            $usuario->setEmail($userData['email']);
            $usuario->setRol($userData['role']);

            // Validar los datos antes de actualizar
            if ($usuario->validar()) {
                try {
                    // Actualizar el usuario en la base de datos
                    $this->userRepository->update($usuario);
                    $_SESSION['edit'] = 'success';
                    error_log("Usuario editado exitosamente con ID " . $id);
                    header('Location: ' . BASE_URL . 'list');
                    exit();
                } catch (\Exception $e) {
                    $_SESSION['edit'] = 'fail';
                    $_SESSION['error'] = $e->getMessage();
                    error_log("Error al editar el usuario: " . $e->getMessage());
                }
            } else {
                $_SESSION['edit'] = 'fail';
                $_SESSION['errors'] = $usuario->getErrors();
                error_log("Errores de validación en la edición de usuario: " . implode(", ", $usuario->getErrors()));
            }
        }

        // Renderizar el formulario de edición
        $this->pages->render('Auth/editForm', ['usuario' => $usuario]);
    } else {
        error_log("Error: El ID del usuario no es válido.");
        $_SESSION['edit'] = 'fail';
        $_SESSION['error'] = 'ID de usuario no válido.';
        header('Location: ' . BASE_URL . 'list');
        exit();
    }
}


    

   // Función para eliminar un usuario
   public function deleteUser($email)
   {
       error_log("Checkpoint: Eliminando usuario con email $email");
   
       // Verificar si el email es válido antes de proceder
       if ($email) {
           try {
               // Buscar el usuario en la base de datos por email
               $usuario = $this->userRepository->findByEmail($email);
               
               if ($usuario) {
                   // Eliminar el usuario de la base de datos
                   $this->userRepository->delete($usuario->getId());  // Suponiendo que usas el ID internamente para borrar
                   $_SESSION['delete'] = 'success';
                   error_log("Usuario eliminado exitosamente con email " . $email);
               } else {
                   throw new \Exception("El usuario con el email $email no existe.");
               }
           } catch (\Exception $e) {
               $_SESSION['delete'] = 'fail';
               $_SESSION['error'] = $e->getMessage();
               error_log("Error al eliminar el usuario: " . $e->getMessage());
           }
   
           // Redirigir a la lista de usuarios después de eliminar
           header('Location: ' . BASE_URL );
           exit();
       } else {
           // Si el email no es válido, redirigir
           $_SESSION['delete'] = 'fail';
           $_SESSION['error'] = 'Email no válido.';
           header('Location: ' . BASE_URL . 'list');
           exit();
       }
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
public function confirmRegistration()
{
    // Obtener el token desde la sesión en lugar de la URL
    $token = $_SESSION['confirm_token'] ?? '';

    if (!$token) {
        $_SESSION['confirm'] = 'fail';
        $_SESSION['error'] = 'Token no válido.';
        $this->pages->render('Auth/confirmRegistration');
        exit();
    }

    try {
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        unset($_SESSION['confirm_token']); // Eliminar el token de la sesión después de usarlo

        $email = $decoded->sub;

        // Verificar si el usuario ya existe
        $usuarioExistente = $this->userRepository->findByEmail($email);
        if ($usuarioExistente) {
            $_SESSION['confirm'] = 'fail';
            $_SESSION['error'] = 'El usuario ya está registrado.';
            $this->pages->render('Auth/confirmRegistration');
            exit();
        }

        // Crear el usuario y guardarlo en la base de datos
        $userData = [
            'email' => $email,
            'nombre' => $decoded->name,
            'password' => $decoded->password,
            'rol' => 'user',
            'Token' => $decoded->token
        ];
        $usuario = User::fromArray($userData);
        $this->userService->registerUser($usuario);

        $_SESSION['confirm'] = 'success';
        $this->pages->render('Auth/confirmRegistration');
        exit();
    } catch (\Firebase\JWT\ExpiredException $e) {
        error_log("Error: El token ha expirado: " . $e->getMessage());
        $_SESSION['confirm'] = 'fail';
        $_SESSION['error'] = 'El token ha expirado.';
        $this->pages->render('Auth/confirmRegistration');
        exit();
    } catch (\Exception $e) {
        error_log("Error al decodificar el token: " . $e->getMessage());
        $_SESSION['confirm'] = 'fail';
        $_SESSION['error'] = 'Token no válido o expirado.';
        $this->pages->render('Auth/confirmRegistration');
        exit();
    }
}

public function sendPasswordRecoveryToken()
{
    error_log("Checkpoint: Iniciando envío de token de recuperación de contraseña");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recibimos el correo del formulario
        $email = $_POST['email'] ?? '';

        if ($email) {
            // Verificar si el usuario existe en la base de datos
            $usuario = $this->userRepository->findByEmail($email);

            if ($usuario) {
                // Generar token para la recuperación de contraseña
                $payload = [
                    'sub' => $usuario->getEmail(),
                    'iat' => time(),
                    'exp' => time() + 3600 // El token expirará en 1 hora
                ];
                $token = JWT::encode($payload, $this->jwtSecret, 'HS256');

                // Guardamos el token en la sesión
                $_SESSION['reset_token'] = $token;

                // Generar enlace para el restablecimiento de contraseña
                $resetLink = BASE_URL . "resetPassword";

                // Enviar el correo con el enlace de recuperación
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Username = '828a04b69cf388'; // Cambiar con tus credenciales SMTP
                    $mail->Password = 'd5c6c03cad7d30';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->CharSet = 'UTF-8';
                    $mail->setFrom('tu_correo@example.com', 'Soporte');
                    $mail->addAddress($usuario->getEmail());
                    $mail->isHTML(true);
                    $mail->Subject = 'Recuperación de Contraseña';
                    $mail->Body = "Para restablecer su contraseña, haga clic en el siguiente enlace: <a href='$resetLink'>$resetLink</a>";

                    $mail->send();
                    $_SESSION['reset'] = 'success';
                    error_log("Correo de recuperación enviado con éxito.");
                } catch (Exception $e) {
                    $_SESSION['reset'] = 'fail';
                    $_SESSION['error'] = 'No se pudo enviar el correo. Error: ' . $mail->ErrorInfo;
                    error_log("Error al enviar el correo de recuperación: " . $mail->ErrorInfo);
                }
            } else {
                $_SESSION['reset'] = 'fail';
                $_SESSION['error'] = 'Correo no encontrado.';
                error_log("Correo no encontrado en la base de datos.");
            }
        } else {
            $_SESSION['reset'] = 'fail';
            $_SESSION['error'] = 'Por favor, ingrese un correo válido.';
            error_log("Correo vacío o no válido.");
        }
    }

    // Mostrar el formulario de recuperación o redirigir
    $this->pages->render('Auth/recoveryForm');
}



public function resetPassword()
{
    error_log("Checkpoint: Iniciando proceso de restablecimiento de contraseña");

    // Obtener el token desde la sesión
    $token = $_SESSION['reset_token'] ?? '';

    if (!$token) {
        $_SESSION['reset'] = 'fail';
        $_SESSION['error'] = 'Token no válido o no encontrado.';
        $this->pages->render('Auth/resetPasswordForm');
        exit();
    }

    try {
        // Decodificar el token JWT para obtener el email del usuario
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        
        // Verificar si el token ha expirado
        if ($decoded->exp < time()) {
            $_SESSION['reset'] = 'fail';
            $_SESSION['error'] = 'El token ha expirado.';
            $this->pages->render('Auth/resetPasswordForm');
            exit();
        }

        // Si el formulario fue enviado por POST, se actualiza la contraseña
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';

            // Verificar si la nueva contraseña es válida
            if ($newPassword && strlen($newPassword) >= 6) {
                error_log("Nueva contraseña válida: " . $newPassword); // Verificar la contraseña

                // Buscar el usuario por email
                $usuario = $this->userRepository->findByEmail($decoded->sub);

                if ($usuario) {
                    error_log("Usuario encontrado: " . $usuario->getId()); // Verificar si el usuario fue encontrado

                    // Hash de la nueva contraseña
                    $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 5]);

                    // Intentar actualizar la contraseña en la base de datos usando el método updatePassword
                    try {
                        $this->userRepository->updatePassword($usuario->getId(), $newPasswordHashed);
                        error_log("Contraseña actualizada para el usuario: " . $usuario->getId());

                        // Eliminar el token de la sesión
                        unset($_SESSION['reset_token']);

                        // Mostrar mensaje de éxito y redirigir
                        $_SESSION['reset'] = 'success';
                        header('Location: ' . BASE_URL . 'login');
                        exit();
                    } catch (Exception $e) {
                        error_log("Error al actualizar la contraseña: " . $e->getMessage());
                    }
                } else {
                    $_SESSION['reset'] = 'fail';
                    $_SESSION['error'] = 'Usuario no encontrado.';
                    $this->pages->render('Auth/resetPasswordForm');
                    exit();
                }
            } else {
                $_SESSION['reset'] = 'fail';
                $_SESSION['error'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
                $this->pages->render('Auth/resetPasswordForm');
                exit();
            }
        }

        // Renderizar el formulario para restablecer la contraseña
        $this->pages->render('Auth/resetPasswordForm');
        
    } catch (\Firebase\JWT\ExpiredException $e) {
        $_SESSION['reset'] = 'fail';
        $_SESSION['error'] = 'El token ha expirado.';
        $this->pages->render('Auth/resetPasswordForm');
        exit();
    } catch (\Exception $e) {
        $_SESSION['reset'] = 'fail';
        $_SESSION['error'] = 'Token no válido o expirado.';
        $this->pages->render('Auth/resetPasswordForm');
        exit();
    }
}






}
