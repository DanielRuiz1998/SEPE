<?php
function escribirLog($mensaje, $tipo = 'info') {
    $fecha = date('Y-m-d H:i:s');
    $logFile = 'aplicacion.log'; 
    $mensajeCompleto = "[{$fecha}] [{$tipo}] {$mensaje}\n";
    file_put_contents($logFile, $mensajeCompleto, FILE_APPEND);
}

$usuario = $_POST['usuario'];
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT); // Usa un hash seguro
$nombre = $_POST['nombre'];

$conexion = mysqli_connect("localhost", "root", "");
if (!$conexion) {
    escribirLog("Error de conexión con la base de datos", 'error');
    die("Error de conexión con la base de datos");
}

$db = mysqli_select_db($conexion, "ejemplo");
if (!$db) {
    escribirLog("Error al seleccionar la base de datos", 'error');
    die("Error al seleccionar la base de datos");
}

$sql = "INSERT INTO acceso (login, clave, nombre) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $usuario, $clave, $nombre);

if ($stmt->execute()) {
    escribirLog("Usuario registrado correctamente: {$usuario}", 'info');
    echo "Usuario registrado correctamente.";
} else {
    escribirLog("Error al registrar usuario: " . $stmt->error, 'error');
    echo "Error al registrar usuario.";
}

mysqli_close($conexion);
?>