<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once __DIR__ . "/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $usuario = $input['usuario'] ?? '';
    $contrasena = $input['contrasena'] ?? '';

    if (!$usuario || !$contrasena) {
        echo json_encode(["estado" => "error", "mensaje" => "Usuario y contraseña son obligatorios"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, usuario, nombre, rol FROM usuarios WHERE usuario = ? AND contrasena = ? LIMIT 1");
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        echo json_encode([
            "estado" => "exitoso",
            "mensaje" => "Inicio de sesión correcto",
            "usuario" => $fila
        ]);
    } else {
        echo json_encode(["estado" => "error", "mensaje" => "Usuario o contraseña incorrectos"]);
    }
    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["estado" => "error", "mensaje" => "Método no permitido"]);
}

$conn->close();
?>