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
        $_SESSION['S_Rol'] = $fila['roles']; // Asegúrate de usar 'S_Rol' para la consistencia
        
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Login</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Formulario de Inicio de Sesión</h1>
        <form method="POST" action="" class="mt-4">
            <div class="form-group">
                <label for="nick">Nick de Usuario:</label>
                <input type="text" name="nick" id="nick" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="passw">Contraseña:</label>
                <input type="password" name="passw" id="passw" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
