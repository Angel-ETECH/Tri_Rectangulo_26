<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->conectar();
    }

    public function login(string $correo, string $clave): ?array
    {
        $sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['correo' => $correo]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            return null;
        }

        // Para este proyecto se permite clave simple. También acepta clave encriptada con password_hash().
        if ($clave === $usuario['clave'] || password_verify($clave, $usuario['clave'])) {
            return $usuario;
        }

        return null;
    }
}
