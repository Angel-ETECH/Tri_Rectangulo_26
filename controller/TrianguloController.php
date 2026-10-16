<?php
require_once __DIR__ . '/../model/Triangulo.php';

class TrianguloController
{
    private Triangulo $modelo;

    public function __construct()
    {
        $this->modelo = new Triangulo();
    }

    public function procesarFormulario(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return '';
        if (!is_string($_POST['csrf'] ?? null) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
            return 'La sesion del formulario vencio. Recargue la pagina.';
        }
        $accion = $_POST['accion'] ?? '';
        $id = filter_var($_POST['id_triangulo'] ?? '', FILTER_VALIDATE_INT);
        if ($accion === 'eliminar') {
            if (!$id || !$this->modelo->obtenerPorId($id)) return 'El registro no existe.';
            $this->modelo->eliminar($id);
            header('Location: home.php?mensaje=eliminado');
            exit;
        }
        if ($accion !== 'guardar') return 'Accion no valida.';
        $a = filter_var($_POST['txtCatetoA'] ?? '', FILTER_VALIDATE_FLOAT);
        $b = filter_var($_POST['txtCatetoB'] ?? '', FILTER_VALIDATE_FLOAT);
        $detalle = is_string($_POST['txtDetalle'] ?? null) ? trim($_POST['txtDetalle']) : '';
        if ($a === false || $b === false) return 'Ingrese dos catetos numericos validos.';
        if (strlen($detalle) > 500) return 'El comentario debe tener como maximo 500 bytes.';
        try {
            Triangulo::calcular($a, $b);
            if ($id) {
                if (!$this->modelo->obtenerPorId($id)) return 'El registro no existe.';
                $this->modelo->actualizar($id, $detalle, $a, $b);
                header('Location: home.php?mensaje=actualizado');
            } else {
                $this->modelo->guardar($detalle, $a, $b);
                header('Location: home.php?mensaje=guardado');
            }
            exit;
        } catch (InvalidArgumentException $e) {
            return $e->getMessage();
        }
    }

    public function listar(string $buscar = ''): array
    {
        return $this->modelo->listar($buscar);
    }

    public function obtenerParaEditar(int $id): ?array
    {
        return $id > 0 ? $this->modelo->obtenerPorId($id) : null;
    }
}
