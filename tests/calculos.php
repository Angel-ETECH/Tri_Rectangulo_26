<?php
require_once __DIR__ . '/../model/Triangulo.php';
function comprobar(bool $condicion, string $mensaje): void {
    if (!$condicion) throw new RuntimeException($mensaje);
}
foreach ([[3,4,5,6,12], [5,12,13,30,30], [1.5,2,2.5,1.5,6]] as [$a,$b,$h,$area,$perimetro]) {
    $r = Triangulo::calcular($a,$b);
    comprobar(abs($r['hipotenusa']-$h) < 0.000001, 'Hipotenusa incorrecta');
    comprobar(abs($r['area']-$area) < 0.000001, 'Area incorrecta');
    comprobar(abs($r['perimetro']-$perimetro) < 0.000001, 'Perimetro incorrecto');
}
foreach ([[0,4],[-3,4],[3,INF],[NAN,4],[1e10,4]] as [$a,$b]) {
    $rechazado = false;
    try { Triangulo::calcular($a,$b); } catch (InvalidArgumentException $e) { $rechazado = true; }
    comprobar($rechazado, 'Se aceptaron catetos invalidos');
}
echo "OK: 3 triangulos conocidos y 5 casos invalidos.\n";
