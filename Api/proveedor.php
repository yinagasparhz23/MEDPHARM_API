<?php
header("Content-Type: application/json");
require_once 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idPROVEEDOR']) ? intval($_GET['idPROVEEDOR']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM proveedor WHERE idPROVEEDOR = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM proveedor");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO proveedor (NOMBRE, TELEFONO, DIRECCION, CORREO) VALUES (:nom, :tel, :dir, :cor)");
        $stmt->execute([':nom'=>$d['NOMBRE'], ':tel'=>$d['TELEFONO'], ':dir'=>$d['DIRECCION'], ':cor'=>$d['CORREO']]);
        echo json_encode(["mensaje"=>"Creado", "id"=>$pdo->lastInsertId()]);
        break;
    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE proveedor SET NOMBRE=:nom, TELEFONO=:tel, DIRECCION=:dir, CORREO=:cor WHERE idPROVEEDOR=:id");
        $stmt->execute([':id'=>$d['idPROVEEDOR'], ':nom'=>$d['NOMBRE'], ':tel'=>$d['TELEFONO'], ':dir'=>$d['DIRECCION'], ':cor'=>$d['CORREO']]);
        echo json_encode(["mensaje"=>"Actualizado"]);
        break;
    case 'DELETE':
        $id = intval($_GET['idPROVEEDOR']);
        $stmt = $pdo->prepare("DELETE FROM proveedor WHERE idPROVEEDOR = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(["mensaje"=>"Eliminado"]);
        break;
}
?>