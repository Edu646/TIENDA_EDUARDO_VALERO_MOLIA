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
    public function registerUser(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function updateUser(User $user): void
    {
        $this->userRepository->update($user);
    }

    public function deleteUser(int $id): void
    {
        $this->userRepository->delete($id);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function findAllUsers(): array
    {
        return $this->userRepository->findAll();
    }
}
?>
