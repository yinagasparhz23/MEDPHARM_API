<?php
header("Content-Type: application/json");
require_once 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idINVENTARIO']) ? intval($_GET['idINVENTARIO']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM inventario WHERE idINVENTARIO = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM inventario");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO inventario (PRODUCTO_idPRODUCTO, LOTE_idLOTE, CANTIDAD_DISPONIBLE, UBICACION) VALUES (:pro, :lot, :can, :ubi)");
        $stmt->execute([':pro'=>$d['PRODUCTO_idPRODUCTO'], ':lot'=>$d['LOTE_idLOTE'], ':can'=>$d['CANTIDAD_DISPONIBLE'], ':ubi'=>$d['UBICACION']]);
        echo json_encode(["mensaje"=>"Creado", "id"=>$pdo->lastInsertId()]);
        break;
    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE inventario SET PRODUCTO_idPRODUCTO=:pro, LOTE_idLOTE=:lot, CANTIDAD_DISPONIBLE=:can, UBICACION=:ubi WHERE idINVENTARIO=:id");
        $stmt->execute([':id'=>$d['idINVENTARIO'], ':pro'=>$d['PRODUCTO_idPRODUCTO'], ':lot'=>$d['LOTE_idLOTE'], ':can'=>$d['CANTIDAD_DISPONIBLE'], ':ubi'=>$d['UBICACION']]);
        echo json_encode(["mensaje"=>"Actualizado"]);
        break;
    case 'DELETE':
        $id = intval($_GET['idINVENTARIO']);
        $stmt = $pdo->prepare("DELETE FROM inventario WHERE idINVENTARIO = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(["mensaje"=>"Eliminado"]);
        break;
}
?>