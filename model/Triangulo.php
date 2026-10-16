<?php
require_once __DIR__ . '/../config/Database.php';

class Triangulo
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->conectar();
    }

    public static function calcular(float $a, float $b): array
    {
        // Un limite evita resultados fuera de rango tanto en PHP como en JavaScript.
        if (!is_finite($a) || !is_finite($b) || $a <= 0 || $b <= 0 || $a > 1000000000 || $b > 1000000000) {
            throw new InvalidArgumentException('Ingrese ambos catetos mayores a cero y hasta 1000000000.');
        }
        $hipotenusa = hypot($a, $b);
        return ['hipotenusa' => $hipotenusa, 'area' => ($a * $b) / 2, 'perimetro' => $a + $b + $hipotenusa];
    }

    public function listar(string $buscar = ''): array
    {
        if ($buscar === '') {
            return $this->pdo->query('SELECT * FROM triangulo ORDER BY id_triangulo DESC')->fetchAll();
        }
        $stmt = $this->pdo->prepare('SELECT * FROM triangulo WHERE detalle LIKE :detalle
            OR CAST(cateto_a AS CHAR) LIKE :a OR CAST(cateto_b AS CHAR) LIKE :b
            OR CAST(hipotenusa AS CHAR) LIKE :h OR CAST(area AS CHAR) LIKE :area
            OR CAST(perimetro AS CHAR) LIKE :p ORDER BY id_triangulo DESC');
        $valor = '%' . $buscar . '%';
        $stmt->execute(['detalle' => $valor, 'a' => $valor, 'b' => $valor, 'h' => $valor, 'area' => $valor, 'p' => $valor]);
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM triangulo WHERE id_triangulo = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function guardar(string $detalle, float $a, float $b): bool
    {
        $resultados = self::calcular($a, $b);
        $stmt = $this->pdo->prepare('INSERT INTO triangulo (detalle, cateto_a, cateto_b, hipotenusa, area, perimetro)
            VALUES (:detalle, :a, :b, :hipotenusa, :area, :perimetro)');
        return $stmt->execute(array_merge(['detalle' => $detalle, 'a' => $a, 'b' => $b], $resultados));
    }

    public function actualizar(int $id, string $detalle, float $a, float $b): bool
    {
        $resultados = self::calcular($a, $b);
        $stmt = $this->pdo->prepare('UPDATE triangulo SET detalle = :detalle, cateto_a = :a, cateto_b = :b,
            hipotenusa = :hipotenusa, area = :area, perimetro = :perimetro WHERE id_triangulo = :id');
        return $stmt->execute(array_merge(['id' => $id, 'detalle' => $detalle, 'a' => $a, 'b' => $b], $resultados));
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM triangulo WHERE id_triangulo = :id');
        return $stmt->execute(['id' => $id]);
    }
}
