<?php

namespace Controllers;

use Lib\Pages;
use Models\User;
use Repositories\UserRepository;

class UserController
{
    private Pages $pages;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->pages = new Pages(); 
        $this->userRepository = new UserRepository(); 
    }

    /**
     * Cargar la vista con el formulario de registro
     */
    public function mostrarFormularioRegistro(): void
    {
        $this->pages->render('Auth/registerForm');
    }

    /**
     * Procesar el registro de un nuevo usuario
     */
  
     public function registrarUsuario(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST['data'] ?? null;

        if ($data) {
            $errors = [];

            // Validar los campos
            if (empty($data['nombre'])) {
                $errors[] = 'El nombre es obligatorio.';
            }
            if (empty($data['apellidos'])) {
                $errors[] = 'Los apellidos son obligatorios.';
            }
            if (empty($data['email'])) {
                $errors[] = 'El correo electrónico es obligatorio.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'El correo electrónico no tiene un formato válido.';
            }
            if (empty($data['password'])) {
                $errors[] = 'La contraseña es obligatoria.';
            }

            // Validar el rol
            $role = 'user'; // Valor predeterminado
            if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
                if (isset($data['role']) && in_array($data['role'], ['user', 'admin'])) {
                    $role = $data['role'];
                } else {
                    $errors[] = 'El rol seleccionado no es válido.';
                }
            }

            // Si hay errores, guardar en la sesión y redirigir
            if (!empty($errors)) {
                $_SESSION['register_error'] = implode('<br>', $errors);
                header('Location: ' . BASE_URL . 'register');
                exit;
            }

            // Crear el usuario con los datos validados
            $user = new User(
                null,
                $data['nombre'] ?? '',
                $data['apellidos'] ?? '',
                $data['email'] ?? '',
                password_hash($data['password'] ?? '', PASSWORD_BCRYPT),
                $role
            );

            try {
                // Guardar el usuario en la base de datos
                $this->userRepository->save($user);

                // Mensaje de éxito
                $_SESSION['register_success'] = 'Usuario registrado exitosamente.';
                header('Location: ' . BASE_URL . 'login');
                exit;
            } catch (\PDOException $e) {
                $errorMessage = $e->getMessage();
                if (str_contains($errorMessage, 'Duplicate entry')) {
                    $_SESSION['register_error'] = 'El correo electrónico ya está registrado. Por favor, utiliza otro correo.';
                } else {
                    $_SESSION['register_error'] = 'Error al registrar el usuario. Intenta de nuevo más tarde.';
                }
                header('Location: ' . BASE_URL . 'register');
                exit;
            }
        } else {
            $_SESSION['register_error'] = 'No se enviaron datos válidos.';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }
    }
}

}
     
