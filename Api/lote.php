<?php
header("Content-Type: application/json");
require_once 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idLOTE']) ? intval($_GET['idLOTE']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM lote WHERE idLOTE = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM lote");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO lote (NUMERO_LOTE, FECHA_INGRESO, FECHA_VENCIMIENTO, PROVEEDOR_idPROVEEDOR) VALUES (:num, :ing, :ven, :pro)");
        $stmt->execute([':num'=>$d['NUMERO_LOTE'], ':ing'=>$d['FECHA_INGRESO'], ':ven'=>$d['FECHA_VENCIMIENTO'], ':pro'=>$d['PROVEEDOR_idPROVEEDOR']]);
        echo json_encode(["mensaje"=>"Creado", "id"=>$pdo->lastInsertId()]);
        break;
    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE lote SET NUMERO_LOTE=:num, FECHA_INGRESO=:ing, FECHA_VENCIMIENTO=:ven, PROVEEDOR_idPROVEEDOR=:pro WHERE idLOTE=:id");
        $stmt->execute([':id'=>$d['idLOTE'], ':num'=>$d['NUMERO_LOTE'], ':ing'=>$d['FECHA_INGRESO'], ':ven'=>$d['FECHA_VENCIMIENTO'], ':pro'=>$d['PROVEEDOR_idPROVEEDOR']]);
        echo json_encode(["mensaje"=>"Actualizado"]);
        break;
    case 'DELETE':
        $id = intval($_GET['idLOTE']);
        $stmt = $pdo->prepare("DELETE FROM lote WHERE idLOTE = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(["mensaje"=>"Eliminado"]);
        break;
}
?>