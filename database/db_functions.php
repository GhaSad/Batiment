<?php
require_once "db_connexion.php";


//Fonction pour recuperer tous les utilisateurs
function getUsers(){
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//Fonction pour ajouter un utlisateur
function addUser($username, $email, $password, $role){
    global $pdo;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES(?,?,?,?)");
    return $stmt->execute([$username,$email,$passwordHash,$role]);
}

//Fonction pour récupere un objet connecté
function getDevices(){
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM devices");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//Fonction pour modifier un utlisateur 
function updateUserRole($user_id,$new_role){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    return $stmt->execute([$new_role,$user_id]);
}

//Fonction pour supprimer un utilisateur
function deleteUser($user_id){
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$user_id]);
}

?>