<?php

namespace Services;

use Models\User;
use Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(); // Inicializar el repositorio
    }

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param User $user Instancia del usuario a registrar
     * @return bool
     * @throws \Exception Si ocurre algún problema durante el registro
     */
    public function registerUser(User $user): bool
    {
        // Comprobar si el usuario ya existe en el sistema (por ejemplo, por email)
        $existingUser = $this->userRepository->findByEmail($user->getEmail());
        if ($existingUser !== null) {
            throw new \Exception('El correo electrónico ya está registrado.');
        }

        // Sanitizar los datos del usuario antes de guardarlos
        $user->sanitize();

        // Guardar el nuevo usuario en el sistema
        $result = $this->userRepository->save($user);
        if (!$result) {
            throw new \Exception('Error al registrar el usuario. Inténtalo de nuevo.');
        }

        return true;
    }

    /**
     * Verifica las credenciales de un usuario para iniciar sesión.
     *
     * @param string $email Correo electrónico del usuario
     * @param string $password Contraseña del usuario
     * @return User|null Devuelve el usuario si las credenciales son válidas, de lo contrario, null
     */
    public function loginUser(string $email, string $password): ?User
    {
        // Buscar el usuario por email
        $user = $this->userRepository->findByEmail($email);

        // Verificar si el usuario existe y la contraseña es válida
        if ($user === null || !password_verify($password, $user->getPassword())) {
            return null; // Usuario no encontrado o contraseña incorrecta
        }

        // Verificar el rol del usuario
        if ($user->getRol() !== 'admin') {
            return null; // Usuario no tiene rol de admin
        }

        return $user;
    }

    /**
     * Busca un usuario por su ID.
     *
     * @param int $id ID del usuario a buscar
     * @return User|null Devuelve el usuario si existe, de lo contrario, null
     */
    public function findUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Actualiza la información de un usuario.
     *
     * @param User $user Instancia del usuario con la información actualizada
     * @return bool
     * @throws \Exception Si ocurre un error durante la actualización
     */
    public function updateUser(User $user): bool
    {
        // Verificar si el usuario existe
        $existingUser = $this->userRepository->findById($user->getId());
        if ($existingUser === null) {
            throw new \Exception('El usuario no existe.');
        }

        // Actualizar el usuario
        $result = $this->userRepository->update($user);
        if (!$result) {
            throw new \Exception('Error al actualizar el usuario. Inténtalo de nuevo.');
        }

        return true;
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id ID del usuario a eliminar
     * @return bool
     * @throws \Exception Si ocurre un error durante la eliminación
     */
    public function deleteUser(int $id): bool
    {
        // Verificar si el usuario existe
        $existingUser = $this->userRepository->findById($id);
        if ($existingUser === null) {
            throw new \Exception('El usuario no existe.');
        }

        // Eliminar el usuario
        $result = $this->userRepository->delete($id);
        if (!$result) {
            throw new \Exception('Error al eliminar el usuario. Inténtalo de nuevo.');
        }

        return true;
    }
}
?>
