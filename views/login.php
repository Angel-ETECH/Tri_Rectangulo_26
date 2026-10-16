<?php
session_start();
require_once __DIR__ . '/../controller/AuthController.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($correo === '' || $clave === '') {
        $mensaje = 'Complete todos los campos';
    } else {
        $auth = new AuthController();

        if ($auth->login($correo, $clave)) {
            header('Location: home.php');
            exit;
        }

        $mensaje = 'Correo o clave incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="description" content="Calculo del area y perimetro del triángulo rectángulo">
    <meta name="keywords" content="triángulo rectángulo, sistema, base de datos">
    <meta name="application-name" content="Triángulo rectángulo">

    <link rel="icon" href="../imagenes/triangulo.svg">
    <link rel="stylesheet" href="../assetes/css/style.css">

    <title>Ingresar | Triángulo rectángulo</title>
</head>
<body>
    <header>

    </header>
    <main>
        <div class="caja"><aside class="login-story"><div class="login-brand"><span class="brand-symbol">◿</span><div>Triángulo rectángulo<small>GEOMETRÍA A TU MANERA</small></div></div><div class="story-copy"><span class="eyebrow">MENOS VUELTAS. MÁS RESULTADOS.</span><h2>Encuentra el lado<br><span>fácil de calcular.</span></h2><p>De tus dos catetos al resultado. Un espacio para calcular, guardar y seguir aprendiendo.</p><div class="story-figure"><img src="../imagenes/triangulo.svg" alt="Diagrama de un triángulo rectángulo con sus tres lados"></div></div><div class="story-bottom"><span>Hipotenusa · Área · Perímetro</span><span>a² + b² = c²</span></div></aside><div class="login-panel">
            <form action="" method="post" id="frmLogin">
                <img src="../imagenes/triangulo.svg" alt="Triángulo rectángulo">
                <span class="access-tag">TODO LISTO PARA EMPEZAR</span><h1>Vamos a calcular.</h1>
                <p>Inicia sesión para guardar tus cálculos.</p>
                <?php if ($mensaje !== ''): ?><p class="mensaje" role="alert"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
                <label for="correo">Correo</label>
                <input required autocomplete="username" type="email" name="correo" id="correo" placeholder="tu@correo.com">
                <label for="clave">Contraseña</label>
                <input required autocomplete="current-password" type="password" name="clave" id="clave" placeholder="Tu contraseña">
                <button type="submit" id="btnIngresar">Ingresar</button>
            <div class="login-foot">Tus cálculos, en un solo lugar.</div></form></div></div>
    </main>
    <footer>

    </footer>
    <script src="../assetes/js/app.js"></script>
</body>
</html>