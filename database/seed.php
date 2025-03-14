<?php
require_once "vendor/autoload.php"; //pour inclure faker
$faker = Faker\Factory::create();

//Connexion a la base de donénes
$pdo = new PDO('mysql:host=localhost;dbname=batiment','root','');

// Effacer toutes les données avant d'insérer de nouvelles entrées
$tables = ['user_actions', 'logs', 'energy_usage', 'devices', 'rooms', 'users'];
foreach ($tables as $table) {
    $pdo->exec("DELETE FROM $table");
    $pdo->exec("ALTER TABLE $table AUTO_INCREMENT = 1"); // Réinitialiser les IDs
}


//Generation des utilisateurs
for($i = 0 ; $i < 10 ; $i++){
    $username = $faker->userName;
    $email = $faker->email;
    $password = password_hash('password123',PASSWORD_BCRYPT);
    $role = $faker->randomElement(['visiteur','simple','complexe','admin']);

    $pdo->exec("INSERT INTO users(username,email,password,role) VALUES ('$username','$email','$password','$role')");
}


//Generation des pieces

for($i = 0 ; $i < 5 ; $i++){
    $name = $faker->word;
    $description = $faker->sentence;

    $pdo->exec("INSERT INTO rooms(name,description) VALUES ('$name','$description')");
}

//Generation des actions utilisateurs
for($i = 0 ; $i < 20 ; $i++){
    $user_id = rand(1,10);
    $device_id = rand(1,15);
    $action_type = $faker->randomELement(['allumer','éteindre','ajuster','autre']);
    $action_value = $faker->word;

    $pdo->exec("INSERT INTO user_actions (user_id, device_id, action_type, action_value) VALUES ($user_id,$device_id,'$action_type','$action_value')");
}

//Generation des devices
for($i = 0 ; $i<20; $i++){
    $name = $faker->word;
    $type = $faker->randomELement(['thermostat','lumière','caméra','capteur','autre']);
    $status = $faker->randomELement(['actif','inactif']);

    $pdo->exec("INSERT INTO devices (name,type,status) VALUES('$name','$type','$status')");
}

//Generation des energy_usage

for($i = 0 ; $i < 20 ; $i++){
    $device_id = rand(1,20);
    $consumption = $faker->randomFloat(2,0.5,10);
    $recorded_at = $faker->dateTimeThisMonth()->format('Y-m-d H:i:s');

    $pdo->exec("INSERT INTO energy_usage (device_id,consumption,recorded_at) VALUES($device_id,$consumption,'$recorded_at')");
}


//Generation des logs

for($i = 0 ; $i < 30 ; $i++){
    $user_id = rand(1,10);
    $device_id = rand(1,20);
    $log_msg = $faker->sentence();
    $log_time = $faker->dateTimeThisMonth()->format('Y-m-d H:i:s');

    $pdo->exec("INSERT INTO logs (user_id,device_id,log_message,log_time) VALUES($user_id,$device_id,'$log_msg','$log_time')");
}

echo "Données générées avec succès ! ";
?>