<?php
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = $_POST['nick'] ?? '';
    $passw = $_POST['passw'] ?? '';

    // Conectar a la base de datos
    $conect = mysqli_connect("localhost", "root", "7042", "lista");

    // Verificar si la conexión fue exitosa
    if (!$conect) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Consulta preparada para evitar inyecciones SQL
    $stmt = $conect->prepare("SELECT * FROM admin WHERE Nick = ? AND Clave = ?");
    $stmt->bind_param("ss", $nick, $passw);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        // Iniciar sesión y guardar variables de sesión
        $_SESSION['S_Nick'] = $fila['Nick'];
        $_SESSION['S_Rol'] = $fila['roles']; // Almacena el rol en la sesión
        
        // Redirigir según el rol
        if ($fila['roles'] == "Administrador") {
            header("Location: administrador.php");
        } elseif ($fila['roles'] == "Director") {
            header("Location: director.php");
        } else {
            header("Location: login.php"); // Si el rol no es válido
        }
        exit(); // Detener el script después de la redirección
    } else {
        // Si las credenciales son incorrectas
        echo "Nick o contraseña incorrectos. <a href='login.php'>Volver a intentar</a>";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    mysqli_close($conect);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="">
        Nick: <input type="text" name="nick" required>
        <br>
        Contraseña: <input type="password" name="passw" required>
        <br>
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
