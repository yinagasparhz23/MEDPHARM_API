<?php
header("Content-Type: application/json");
require_once 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idPRODUCTO']) ? intval($_GET['idPRODUCTO']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM producto WHERE idPRODUCTO = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM producto");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO producto (NOMBRE, DESCRIPCION, PRECIO, STOCK, LOTE_idLOTE) VALUES (:nom, :des, :pre, :stk, :lote)");
        $stmt->execute([':nom'=>$d['NOMBRE'], ':des'=>$d['DESCRIPCION'], ':pre'=>$d['PRECIO'], ':stk'=>$d['STOCK'], ':lote'=>$d['LOTE_idLOTE']]);
        echo json_encode(["mensaje"=>"Creado", "id"=>$pdo->lastInsertId()]);
        break;
    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE producto SET NOMBRE=:nom, DESCRIPCION=:des, PRECIO=:pre, STOCK=:stk, LOTE_idLOTE=:lote WHERE idPRODUCTO=:id");
        $stmt->execute([':id'=>$d['idPRODUCTO'], ':nom'=>$d['NOMBRE'], ':des'=>$d['DESCRIPCION'], ':pre'=>$d['PRECIO'], ':stk'=>$d['STOCK'], ':lote'=>$d['LOTE_idLOTE']]);
        echo json_encode(["mensaje"=>"Actualizado"]);
        break;
    case 'DELETE':
        $id = intval($_GET['idPRODUCTO']);
        $stmt = $pdo->prepare("DELETE FROM producto WHERE idPRODUCTO = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(["mensaje"=>"Eliminado"]);
        break;
}
?>