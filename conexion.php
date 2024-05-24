<?php
class Cconexion{
    public static function ConexionBD() {
        $host = 'localhost';
        $port = '3308'; //Mi puerto XAMPP
        $dbname = 'proyectolp2';
        $username = 'root';
        $password = '';
        $conn = null;
        try{
            $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            //echo "Se conectó a la base de datos" //Lo puse en comentario para que no salga en los otros .php porque es solo para probar si funciona;
        } catch (PDOException $exp) {
            //echo "No se conectó a la base de datos: $dbname";
        }
        return $conn;
    }
}
?>
