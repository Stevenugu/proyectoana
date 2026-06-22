<?php
require_once 'conexion.php';

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_usuario = trim($_POST['usuario']);
    $nueva_password = $_POST['password'];
    // El rol ahora se asigna AUTOMÁTICAMENTE como 'estudiante' por seguridad
    $rol_asignado = 'estudiante'; 

    if (!empty($nuevo_usuario) && !empty($nueva_password)) {
        try {
            // Verificamos si el usuario ya existe para evitar duplicados
            $check = $pdo->prepare("SELECT COUNT(*) FROM data WHERE nombre = :nombre");
            $check->execute(['nombre' => $nuevo_usuario]);
            
            if ($check->fetchColumn() > 0) {
                $error = "El nombre de usuario ya está registrado.";
            } else {
                // Insertamos los datos de manera segura
                $stmt = $pdo->prepare("INSERT INTO data (nombre, contraseña, rol) VALUES (:nombre, :contrasena, :rol)");
                $stmt->execute([
                    'nombre' => $nuevo_usuario,
                    'contrasena' => $nueva_password,
                    'rol' => $rol_asignado
                ]);
                
                $mensaje = "¡Tu cuenta de Estudiante se ha creado con éxito!";
            }
        } catch (PDOException $e) {
            $error = "Error al guardar en la base de datos: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, llena todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Universitario - Anáhuac</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f5f7; 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        }
        
        .register-box { 
            background: #4a259c; /* Morado institucional */
            padding: 35px; 
            border-radius: 12px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.3); 
            width: 340px; 
            text-align: center;
            border: 3px solid #000; /* Estilo marcado */
        }
        
        h2 {
            color: #fff;
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .subtitle {
            color: #ffcc00;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        input[type="text"], input[type="password"] { 
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

        input[type="submit"] { 
            background: #28a745; 
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
            background: #218838; 
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

        .success { 
            background: #2ea44f;
            color: white; 
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px; 
            font-size: 14px; 
            font-weight: bold;
            border: 1px solid #000;
        }
        
        .login-link {
            display: block;
            margin-top: 20px;
            color: #ffcc00; 
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Crear Cuenta</h2>
    <div class="subtitle">Área de Estudiantes</div>
    
    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(!empty($mensaje)): ?>
        <div class="success"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <input type="text" name="usuario" placeholder="Elige tu Nombre de Usuario" required>
        <input type="password" name="password" placeholder="Crea tu Contraseña" required>
        
        <input type="submit" value="Registrar Estudiante">
    </form>

    <a href="login.php" class="login-link">← Volver al Inicio de Sesión</a>
</div>

</body>
</html>