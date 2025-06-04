<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$servidor = "localhost:3306";
$usuario = "jardila_reportes";
$contrasena = "Zsw2Xaq1";
$base_datos = "jardila_reportes";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    //Consulta SQL
    $consulta = "SELECT usuario, nombre FROM usuarios WHERE usuario = '$username' AND contrasena = '$password'";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows == 1) {
        // Inicio de sesión exitoso
        $fila = $resultado->fetch_assoc();
        $_SESSION["username"] = $fila["usuario"];
        $_SESSION["nombre"] = $fila["nombre"];
        header("Location: index.php"); // Redireccionar a la página de inicio del usuario
    } else {
        // Credenciales inválidas
        echo "Correo electrónico o contraseña incorrectos";
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f2f2f2;
        }
        
        .container {
          width:80%;
          margin: 0 auto;
          padding: 20px;
          background-color: #ffffff;
          border: 1px solid #cccccc;
          border-radius: 5px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
          text-align: center;
          color: #333333;
        }
        
        form {
          margin-top: 20px;
        }
        
        label {
          display: block;
          margin-bottom: 5px;
          color: #666666;
        }
        
        input[type="text"],
        input[type="password"] {
          width: 96%;
          padding:2%;
          border: 1px solid #cccccc;
          border-radius: 3px;
        }
        
        input[type="submit"] {
          width: 100%;
          padding: 10px;
          background-color: #337ab7;
          color: #ffffff;
          border: none;
          border-radius: 3px;
          cursor: pointer;
        }
        
        input[type="submit"]:hover {
          background-color: #286090;
        }
        
        .error-message {
          color: #ff0000;
          margin-top: 10px;
        }
        @media screen and (min-width: 1200px) {
            .container {
                max-width: 400px;
                width:80%;
            }
        }
        @media screen and (max-width: 1090px) {
            form {
                width:100%;
                margin: 0 auto;
                background-color: #fff;
                padding: 4%;
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .container {
                width:80%;
            }
            body {
                font-size: 40px;
            }
            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                font-size: 40px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                font-size: 40px;
            }
        }
        @media screen and (max-width: 1200px) {
            form {
                max-width: 82%;
                margin: 0 auto;
                background-color: #fff;
                padding: 4%;
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            body {
                font-size: 40px;
            }
            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                font-size: 40px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST" action="" class="container">
        <label for="username">Usuario:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Clave:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>