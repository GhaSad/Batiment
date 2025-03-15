<?php
require_once "database_functions.php";

// Test : Récupérer les utilisateurs
echo "Liste des utilisateurs :\n";
print_r(getUsers());

// Test : Ajouter un utilisateur
addUser("testuser", "test@email.com", "password123", "simple");
echo "Utilisateur ajouté !\n";

// Test : Modifier un utilisateur
updateUserRole(1, "admin");
echo "Rôle utilisateur mis à jour !\n";

// Test : Supprimer un utilisateur
deleteUser(2);
echo "Utilisateur supprimé !\n";
?>
