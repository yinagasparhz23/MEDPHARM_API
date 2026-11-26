<?php
header("Content-Type: application/json");
require_once 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['idUSUARIO']) ? intval($_GET['idUSUARIO']) : 0;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE idUSUARIO = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM usuario");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;
    case 'POST':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO usuario (NOMBRE, USUARIO, CONTRASENA, ROL) VALUES (:nom, :usu, :con, :rol)");
        $stmt->execute([':nom'=>$d['NOMBRE'], ':usu'=>$d['USUARIO'], ':con'=>$d['CONTRASENA'], ':rol'=>$d['ROL']]);
        echo json_encode(["mensaje"=>"Creado", "id"=>$pdo->lastInsertId()]);
        break;
    case 'PUT':
        $d = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE usuario SET NOMBRE=:nom, USUARIO=:usu, CONTRASENA=:con, ROL=:rol WHERE idUSUARIO=:id");
        $stmt->execute([':id'=>$d['idUSUARIO'], ':nom'=>$d['NOMBRE'], ':usu'=>$d['USUARIO'], ':con'=>$d['CONTRASENA'], ':rol'=>$d['ROL']]);
        echo json_encode(["mensaje"=>"Actualizado"]);
        break;
    case 'DELETE':
        $id = intval($_GET['idUSUARIO']);
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE idUSUARIO = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(["mensaje"=>"Eliminado"]);
        break;
}
?>