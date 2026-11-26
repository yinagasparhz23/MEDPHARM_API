<?php
header("Content-Type: application/json");
require_once 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idDETALLE']) ? intval($_GET['idDETALLE']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM detalle_venta WHERE idDETALLE = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM detalle_venta");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO detalle_venta (CANTIDAD, PRECIO, VENTA_idVENTA, PRODUCTO_idPRODUCTO) VALUES (:can, :pre, :ven, :pro)");
        $stmt->execute([':can'=>$d['CANTIDAD'], ':pre'=>$d['PRECIO'], ':ven'=>$d['VENTA_idVENTA'], ':pro'=>$d['PRODUCTO_idPRODUCTO']]);
        echo json_encode(["mensaje"=>"Creado", "id"=>$pdo->lastInsertId()]);
        break;
    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE detalle_venta SET CANTIDAD=:can, PRECIO=:pre, VENTA_idVENTA=:ven, PRODUCTO_idPRODUCTO=:pro WHERE idDETALLE=:id");
        $stmt->execute([':id'=>$d['idDETALLE'], ':can'=>$d['CANTIDAD'], ':pre'=>$d['PRECIO'], ':ven'=>$d['VENTA_idVENTA'], ':pro'=>$d['PRODUCTO_idPRODUCTO']]);
        echo json_encode(["mensaje"=>"Actualizado"]);
        break;
    case 'DELETE':
        $id = intval($_GET['idDETALLE']);
        $stmt = $pdo->prepare("DELETE FROM detalle_venta WHERE idDETALLE = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(["mensaje"=>"Eliminado"]);
        break;
}
?>