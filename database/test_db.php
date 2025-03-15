<?php
require_once "db_connexion.php";

try{
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Connexion réussie ! Nombre d'utilisateurs : ".($result['total']);
}catch(PDOException $e){
    echo "Erreur : ".$e->getMessage();
}
?>
