<?php
namespace Models;

class User {

    protected static array $errores = [];

    public function __construct(
        private ?int $id = null,
        private string $nombre = '',
        private string $apellidos = '',
        private string $email = '',
        private string $password = '',
        private string $rol = '',
        private string $token = ''
    ) {}

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellidos(): string {
        return $this->apellidos;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRol(): string {
        return $this->rol;
    }

    public static function getErrores(): array {
        return self::$errores;
    }

    // Método adicional para obtener errores (necesario para el AuthController)
    public function getErrors(): array {
        return self::$errores;
    }


    public function gettoken(): string {
        return $this->token;
    }
    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setApellidos(string $apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setRol(string $rol): void {
        $this->rol = $rol;
    }

    public static function setErrores(array $errores): void {
        self::$errores = $errores;
    }

    public function settoken($token): void {
        $this->token = $token;
    }

    // Validación de los datos
    public function validar(): bool {
        self::$errores = [];

        if (empty($this->nombre)) {
            self::$errores[] = "El nombre es obligatorio.";
        }

        if (empty($this->email)) {
            self::$errores[] = "El correo electrónico es obligatorio.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = "El formato del correo electrónico no es válido.";
        }

        if (empty($this->password)) {
            self::$errores[] = "La contraseña es obligatoria.";
        }

        return empty(self::$errores);
    }

    // Sanitizar los datos del usuario
    public function sanitize(): void {
        $this->nombre = htmlspecialchars($this->nombre);
        $this->apellidos = htmlspecialchars($this->apellidos);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
    }

    // Crear una instancia desde un array
    public static function fromArray(array $data): User {
        $user = new self();
        $user->id = $data['id'] ?? 0;
        $user->nombre = $data['nombre'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->rol = $data['rol'] ?? 'user';
        $user->token = $data['token'] ?? '';
        return $user;
    }

    // Convertir el objeto a un array
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'password' => $this->password,
            'rol' => $this->rol,
            'token' => $this->token
        ];
    }
}
?>
