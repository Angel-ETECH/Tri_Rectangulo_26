<?php
session_start();
require_once __DIR__ . '/../controller/AuthController.php';
require_once __DIR__ . '/../controller/TrianguloController.php';
AuthController::verificarSesion();
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
$controller = new TrianguloController();
$error = $controller->procesarFormulario();
$mensajes = ['guardado' => 'Triángulo guardado.', 'actualizado' => 'Triángulo actualizado.', 'eliminado' => 'Registro eliminado.'];
$mensaje = $error ?: ($mensajes[$_GET['mensaje'] ?? ''] ?? '');
$buscar = trim($_GET['buscar'] ?? '');
$editar = $controller->obtenerParaEditar((int) ($_GET['editar'] ?? 0));
$registros = $controller->listar($buscar);
function e($valor): string { return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8'); }
$form = $error !== '' ? ['id_triangulo' => $_POST['id_triangulo'] ?? '', 'cateto_a' => $_POST['txtCatetoA'] ?? '', 'cateto_b' => $_POST['txtCatetoB'] ?? '', 'detalle' => $_POST['txtDetalle'] ?? ''] : ($editar ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triángulo rectángulo | Calculadora e historial</title>
    <link rel="icon" href="../imagenes/triangulo.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assetes/css/style_home.css">
</head>
<body>
<header class="headeerHome">
    <div class="lineaSup">
        <div class="logo"><img src="../imagenes/marca.svg" alt=""><div><h1>Triángulo rectángulo</h1><small>Geometría a tu manera</small></div></div>
        <p>Usuario: <?= e($_SESSION['usuario']) ?></p>
    </div>
    <div class="lineaInf"><nav class="menu" aria-label="Principal">
        <a href="home.php">Calculadora</a><a href="#historial">Historial</a>
        <a href="logout.php" onclick="return confirm('¿Desea cerrar sesión?');">Cerrar sesión</a>
    </nav></div>
</header>
<main>
    <div class="page-intro"><div><span class="eyebrow">TU ESPACIO DE CÁLCULO</span><h1>Dos catetos.<br>Todo resuelto.</h1><p>Calcula, guarda y lleva el control de tus resultados.</p></div><span class="intro-tag">a² + b² = c²</span></div>
    <section class="datos">
        <div class="titulo"><h2>Calculadora de triángulo rectángulo</h2>
        <p>Ingresa los dos catetos en la misma unidad.</p></div>
        <div class="explicacion"><div class="diagram-heading"><span>Conoce tu triángulo</span><span>90°</span></div><img src="../imagenes/triangulo.svg" alt="Cateto a horizontal, cateto b vertical e hipotenusa c">
        <p><span class="formula"><span>Hipotenusa</span>c = √(a² + b²)</span><span class="formula"><span>Área</span>(a × b) / 2</span><span class="formula"><span>Perímetro</span>a + b + c</span></p><small class="diagram-note">a y b son los catetos · c es la hipotenusa</small></div>
        <?php if ($mensaje !== ''): ?><p class="mensaje" role="status"><?= e($mensaje) ?></p><?php endif; ?>
        <form method="post" class="form2" id="frmFormulario">
            <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
            <input type="hidden" name="id_triangulo" value="<?= e($form['id_triangulo'] ?? '') ?>">
            <?php foreach (['a' => 'A (base)', 'b' => 'B (altura)'] as $letra => $etiqueta): ?>
            <div class="inputs"><label for="txtCateto<?= strtoupper($letra) ?>">Cateto <?= $etiqueta ?></label>
            <input class="inputHome" type="number" step="any" min="0" max="1000000000" required
                name="txtCateto<?= strtoupper($letra) ?>" id="txtCateto<?= strtoupper($letra) ?>"
                placeholder="Ejemplo: <?= $letra === 'a' ? '3' : '4' ?>" value="<?= e($form['cateto_' . $letra] ?? '') ?>"></div>
            <?php endforeach; ?>
            <div class="inputs"><label for="txtDetalle">Comentario</label><input class="inputHome" type="text" maxlength="500" name="txtDetalle" id="txtDetalle" placeholder="Opcional" value="<?= e($form['detalle'] ?? '') ?>"></div>
            <?php foreach (['H' => 'Hipotenusa', 'A' => 'Área', 'P' => 'Perímetro'] as $id => $label): ?>
            <div class="inputs"><label for="txt<?= $id ?>"><?= $label ?></label><input class="inputHome" id="txt<?= $id ?>" type="text" readonly placeholder="<?= $label ?>"></div>
            <?php endforeach; ?>
            <p id="avisoCalculo" role="status">Los resultados se calculan al ingresar los catetos.</p>
            <div class="botones"><button type="button" id="btnCalcular">Calcular</button><a class="btn-link cancelar" href="home.php">Cancelar</a>
            <button type="submit" name="accion" value="guardar"><?= !empty($form['id_triangulo']) ? 'Actualizar' : 'Guardar' ?></button></div>
        </form>
    </section>
    <section class="buscar" aria-label="Buscar registros"><form action="home.php" method="get">
        <label for="txtBuscar">Buscar</label><input type="search" name="buscar" id="txtBuscar" placeholder="Comentario o medida" value="<?= e($buscar) ?>">
        <button type="submit">Buscar</button><a class="btn-link" href="home.php">Limpiar</a>
    </form></section>
    <section class="tabla" id="historial"><div class="table-heading"><h2>Tu historial de cálculos</h2><span class="record-count"><?= count($registros) ?> registros<?= $buscar !== '' ? ' encontrados' : '' ?></span></div>
    <div class="tabla-scroll"><table>
        <thead><tr><th>ID</th><th>Cateto A</th><th>Cateto B</th><th>Hipotenusa</th><th>Área</th><th>Perímetro</th><th>Comentario</th><th>Fecha y hora</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($registros as $fila): ?><tr>
            <td><?= e($fila['id_triangulo']) ?></td>
            <?php foreach (['cateto_a', 'cateto_b', 'hipotenusa', 'area', 'perimetro'] as $campo): ?><td><?= e(number_format((float) $fila[$campo], 2, '.', '')) ?></td><?php endforeach; ?>
            <td><?= e($fila['detalle']) ?></td><td><?= e($fila['fecha_registro']) ?></td>
            <td class="acciones"><a class="btn-tabla editar" href="home.php?editar=<?= (int) $fila['id_triangulo'] ?>">Editar</a>
                <form method="post" onsubmit="return confirm('¿Desea eliminar este registro?');">
                    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                    <input type="hidden" name="id_triangulo" value="<?= (int) $fila['id_triangulo'] ?>">
                    <button class="btn-tabla eliminar" type="submit" name="accion" value="eliminar">Eliminar</button>
                </form>
            </td></tr><?php endforeach; ?>
        <?php if (!$registros): ?><tr><td colspan="9">No hay registros para mostrar.</td></tr><?php endif; ?>
        </tbody>
    </table></div></section>
<footer class="page-footer"><span>Triángulo rectángulo · Tu espacio de geometría</span><span>Calcula. Guarda. Sigue.</span></footer>
</main>
<script src="../assetes/js/app.js"></script>
</body></html>
