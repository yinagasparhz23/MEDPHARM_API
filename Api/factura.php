<?php
header("Content-Type: application/json");
require_once 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idFACTURA']) ? intval($_GET['idFACTURA']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM factura WHERE idFACTURA = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM factura");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO factura (FECHA, NOMBRE_CLIENTE, CORREO, TELEFONO, DIRECCION, TOTAL, ESTADO) VALUES (:fec, :nom, :cor, :tel, :dir, :tot, :est)");
        $stmt->execute([':fec'=>$d['FECHA'], ':nom'=>$d['NOMBRE_CLIENTE'], ':cor'=>$d['CORREO'], ':tel'=>$d['TELEFONO'], ':dir'=>$d['DIRECCION'], ':tot'=>$d['TOTAL'], ':est'=>$d['ESTADO']]);
        echo json_encode(["mensaje"=>"Creada", "id"=>$pdo->lastInsertId()]);
        break;
    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE factura SET FECHA=:fec, NOMBRE_CLIENTE=:nom, CORREO=:cor, TELEFONO=:tel, DIRECCION=:dir, TOTAL=:tot, ESTADO=:est WHERE idFACTURA=:id");
        $stmt->execute([':id'=>$d['idFACTURA'], ':fec'=>$d['FECHA'], ':nom'=>$d['NOMBRE_CLIENTE'], ':cor'=>$d['CORREO'], ':tel'=>$d['TELEFONO'], ':dir'=>$d['DIRECCION'], ':tot'=>$d['TOTAL'], ':est'=>$d['ESTADO']]);
        echo json_encode(["mensaje"=>"Actualizada"]);
        break;
    case 'DELETE':
        $id = intval($_GET['idFACTURA']);
        $stmt = $pdo->prepare("DELETE FROM factura WHERE idFACTURA = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(["mensaje"=>"Eliminada"]);
        break;
}
?>