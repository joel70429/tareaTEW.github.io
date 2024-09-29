<?php
session_start();
if (!isset($_SESSION['S_Rol']) || $_SESSION['S_Rol'] !== "Director") {
    die("Acceso no autorizado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página del Director</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Esta es la página del Director</h1>
        <hr>
        <?php
        echo "<h2 class='text-center'> Bienvenido, " . $_SESSION['S_Rol'] . " " . $_SESSION['S_Nick'] . "</h2><br><hr><br>";
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cod_Usuario_Sesion</th> 
                    <th>Nick_Usuario</th>   
                    <th>Contraseña_Usuario</th> 
                    <th>Rol_Usuario</th>    
                    <th>Cod_Usuario</th>    
                </tr>
            </thead>
            <tbody>
                <?php
                $conect = mysqli_connect("localhost", "root", "7042", "lista");
                if (!$conect) {
                    die("Error al conectar a la base de datos: " . mysqli_connect_error());
                }

                $cadena_sql = "SELECT * FROM admin WHERE roles != 'Administrador'";
                $datos = mysqli_query($conect, $cadena_sql);

                while ($fila = mysqli_fetch_array($datos)) {
                    echo "
                    <tr>
                        <td>" . $fila['id'] . "</td>    
                        <td>" . $fila['Nick'] . "</td>  
                        <td>" . $fila['Clave'] . "</td> 
                        <td>" . $fila['roles'] . "</td>   
                        <td>" . $fila['Nombres'] . "</td>           
                    </tr>";
                }
                mysqli_close($conect);
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
