<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>Comprobar Usuario</title>
</head>
<body>
<?php

function escribirLog($mensaje, $tipo = 'info') {
    $fecha = date('Y-m-d H:i:s');
    $logFile = 'aplicacion.log'; 
    $mensajeCompleto = "[{$fecha}] [{$tipo}] {$mensaje}\n";
    file_put_contents($logFile, $mensajeCompleto, FILE_APPEND);
    echo "<script>console.log('{$tipo}: " . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . "')</script>"; 
}

$usuario = $_POST["usu"] ?? '';
$clave = $_POST["clave"] ?? '';

// Validaciones iniciales
if (strpos($usuario, "'") !== false || strpos($usuario, "#") !== false || strpos($clave, "'") !== false || strpos($clave, "#") !== false) {
    escribirLog("Posible intento de inyección SQL detectado. Usuario: {$usuario}, Clave: {$clave}", 'warning');
    echo "<br><b>Se han detectado caracteres sospechosos en el usuario o la contraseña. Por seguridad, la operación se ha detenido.</b><br>\n";
    exit(); 
}

// Estableciendo conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "");
if (!$conexion) {
    escribirLog("ERROR: Imposible establecer conexión con la base de datos.", 'error');
    die("ERROR: Imposible establecer conexión con la base de datos.<br>\n");
}

$db = mysqli_select_db($conexion, "ejemplo");
if (!$db) {
    escribirLog("ERROR: Imposible seleccionar la base de datos 'ejemplo'.", 'error');
    die("ERROR: Imposible seleccionar la base de datos.<br>\n");
}

$sql = "SELECT * FROM acceso WHERE login = ? AND clave = md5(?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $usuario, $clave);
$stmt->execute();
$resul = $stmt->get_result();

if (!$resul) {
    escribirLog("ERROR: Imposible realizar la consulta SQL.", 'error');
    echo "ERROR: Imposible realizar consulta.<br>\n";
} else {
    escribirLog("Consulta SQL realizada satisfactoriamente.");
    echo "Consulta realizada satisfactoriamente.<br>\n";
    
    if ($resul->num_rows === 0) {
        escribirLog("Intento de inicio de sesión fallido. Usuario: {$usuario}", 'warning');
        echo "<br><b>Usuario y/o clave incorrectos.</b><br>\n";
    } else {
        escribirLog("Inicio de sesión exitoso. Usuario: {$usuario}", 'info');
        echo "<br>REGISTROS ENCONTRADOS:<br>\n";

        while ($fila = $resul->fetch_row()) {
            echo "<b>USUARIO:</b>$fila[0] <b>CLAVE:</b>$fila[1] <b>NOMBRE:</b>$fila[2] <b>HAS CONSEGUIDO ENTRAR EN LA PÁGINA WEB!</b><br>";
        }
    }
}

// Añadir botón para redirigir a altas.html
echo '<br><a href="altas.html"><button>Ir a Altas</button></a>';

// Cerrar conexión
mysqli_close($conexion);

?>
</body>
</html>