<?php
session_start();
require_once 'conexion.php';

$error = "";
$mostrar_bloqueo_estudiante = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $rol_elegido = $_POST['rol'];

    // Consulta limpia a la tabla 'data' de base.db
    $stmt = $pdo->prepare("SELECT * FROM data WHERE nombre = :nombre AND contraseña = :password AND rol = :rol LIMIT 1");
    $stmt->execute([
        'nombre' => $usuario,
        'password' => $password,
        'rol' => $rol_elegido
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['rol'] === 'maestro') {
            // Envía al maestro a tu index original de Anáhuac
            header("Location: index.html");
            exit;
        } else if ($user['rol'] === 'estudiante') {
            // Activa el bloqueo interactivo con el GIF
            $mostrar_bloqueo_estudiante = true;
        }
    } else {
        $error = "Usuario, contraseña o rol incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Universitario - Anáhuac</title>
    <style>
        /* Sincronizado con los colores de tu interfaz principal */
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f5f7; 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        }
        
        .login-box { 
            background: #4a259c; /* Morado institucional */
            padding: 35px; 
            border-radius: 12px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.3); 
            width: 340px; 
            text-align: center;
            border: 3px solid #000; /* Borde marcado estilo cómic/industrial */
        }
        
        h2 {
            color: #fff;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        input[type="text"], input[type="password"], select { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            background: #fff; 
            border: 2px solid #000; 
            color: #333; 
            border-radius: 6px; 
            font-size: 14px; 
            box-sizing: border-box;
            font-weight: bold;
        }

        /* Botón de enviar con el tono naranja del bloque superior */
        input[type="submit"] { 
            background: #ff7f50; 
            color: white; 
            border: 2px solid #000; 
            padding: 12px; 
            width: 100%; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 16px; 
            margin-top: 12px;
            text-transform: uppercase;
            transition: background 0.2s;
        }
        
        input[type="submit"]:hover { 
            background: #e0683a; 
        }

        .error { 
            background: #ff4d4d;
            color: white; 
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px; 
            font-size: 14px; 
            font-weight: bold;
            border: 1px solid #000;
        }
        
        /* Enlace de registro abajo del botón */
        .register-link {
            display: block;
            margin-top: 20px;
            color: #ffcc00; /* Amarillo/Dorado brillante */
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .register-link:hover {
            text-decoration: underline;
        }
        
        /* Alerta personalizada para el estudiante */
        .student-denied { 
            background: #2d1454; /* Morado ultraduro */
            border: 2px solid #ff4d4d; 
            color: #ffb3b3; 
            padding: 15px; 
            border-radius: 8px; 
            margin-top: 20px; 
            font-weight: bold; 
            font-size: 14px;
        }
        
        .student-denied img { 
            width: 100%; 
            max-width: 180px; 
            border-radius: 6px; 
            margin-top: 12px; 
            display: block; 
            margin-left: auto; 
            margin-right: auto;
            border: 2px solid #000;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Iniciar Sesión</h2>
    
    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <input type="text" name="usuario" placeholder="Nombre de Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        
        <select name="rol" required>
            <option value="" disabled selected>Selecciona tu rol</option>
            <option value="maestro">Maestro</option>
            <option value="estudiante">Estudiante</option>
        </select>
        
        <input type="submit" value="Ingresar al Campus">
    </form>

    <a href="registro.php" class="register-link">¿No tienes cuenta? Regístrate aquí ヾ(￣▽￣)</a>

    <?php if ($mostrar_bloqueo_estudiante): ?>
        <div class="student-denied">
            ¡no tienes permiso aun de entrar aqui!! I'm sorry pal －O－
            <img src="bloqueo.gif" alt="Acceso restringido">
        </div>
    <?php endif; ?>
</div>

</body>
</html>