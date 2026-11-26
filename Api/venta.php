<?php
header("Content-Type: application/json");
require_once 'conexion.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idVENTA']) ? intval($_GET['idVENTA']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM venta WHERE idVENTA = :id");
            $stmt->execute([':id' => $id]);
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query("SELECT * FROM venta");
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($datos ?: []);
        break;

    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO venta (FECHA, TOTAL, USUARIO_idUSUARIO, CLIENTE_idCLIENTE) 
            VALUES (:fecha, :total, :usuario, :cliente)");
        $stmt->execute([
            ':fecha' => $d['FECHA'],
            ':total' => $d['TOTAL'],
            ':usuario' => $d['USUARIO_idUSUARIO'],
            ':cliente' => $d['CLIENTE_idCLIENTE']
        ]);
        echo json_encode(["mensaje" => "Venta creada", "id" => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE venta SET FECHA=:fecha, TOTAL=:total, 
            USUARIO_idUSUARIO=:usuario, CLIENTE_idCLIENTE=:cliente WHERE idVENTA=:id");
        $stmt->execute([
            ':id' => $d['idVENTA'],
            ':fecha' => $d['FECHA'],
            ':total' => $d['TOTAL'],
            ':usuario' => $d['USUARIO_idUSUARIO'],
            ':cliente' => $d['CLIENTE_idCLIENTE']
        ]);
        echo json_encode(["mensaje" => "Venta actualizada"]);
        break;

    case 'DELETE':
        $id = intval($_GET['idVENTA']);
        $stmt = $pdo->prepare("DELETE FROM venta WHERE idVENTA = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(["mensaje" => "Venta eliminada"]);
        break;
}
?>