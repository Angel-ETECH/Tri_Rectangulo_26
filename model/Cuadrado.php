<?php
require_once __DIR__ . '/../config/Database.php';

class Cuadrado
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->conectar();
    }

    public function listar(string $buscar = ''): array
    {
        if ($buscar !== '') {
            $sql = "SELECT * FROM cuadrado
                    WHERE detalle LIKE :buscarDetalle
                       OR CAST(lado AS CHAR) LIKE :buscarLado
                       OR CAST(area AS CHAR) LIKE :buscarArea
                       OR CAST(perimetro AS CHAR) LIKE :buscarPerimetro
                    ORDER BY id_cuadrado DESC";
            $stmt = $this->pdo->prepare($sql);

            $valorBuscar = '%' . $buscar . '%';

            $stmt->execute([
                'buscarDetalle' => $valorBuscar,
                'buscarLado' => $valorBuscar,
                'buscarArea' => $valorBuscar,
                'buscarPerimetro' => $valorBuscar,
            ]);

            return $stmt->fetchAll();
        }

        $sql = "SELECT * FROM cuadrado ORDER BY id_cuadrado DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM cuadrado WHERE id_cuadrado = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $registro = $stmt->fetch();

        return $registro ?: null;
    }

    public function guardar(string $detalle, float $lado): bool
    {
        $area = $lado * $lado;
        $perimetro = $lado * 4;

        $sql = "INSERT INTO cuadrado(detalle, lado, area, perimetro)
                VALUES(:detalle, :lado, :area, :perimetro)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'detalle' => $detalle,
            'lado' => $lado,
            'area' => $area,
            'perimetro' => $perimetro,
        ]);
    }

    public function actualizar(int $id, string $detalle, float $lado): bool
    {
        $area = $lado * $lado;
        $perimetro = $lado * 4;

        $sql = "UPDATE cuadrado
                SET detalle = :detalle,
                    lado = :lado,
                    area = :area,
                    perimetro = :perimetro
                WHERE id_cuadrado = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'detalle' => $detalle,
            'lado' => $lado,
            'area' => $area,
            'perimetro' => $perimetro,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM cuadrado WHERE id_cuadrado = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
