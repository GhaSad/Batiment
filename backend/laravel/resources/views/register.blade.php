<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription - Maison Connectée</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>

<body class="bg-gradient-to-r from-gray-100 to-purple-300 h-screen">

<div class="modal">
<div class="modal-content">
    <h2>Créer un compte</h2>

    <!-- Formulaire d'inscription, action dirigée vers la route Laravel -->
    <form action="{{ route('register.submit') }}" method="POST" class="form">
      
      <!-- Protection CSRF -->
      @csrf 

      <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required />
      </div>

      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />
      </div>

      <div class="form-group">
          <label for="date_de_naissance">Date de naissance</label>
          <input type="date" id="date_de_naissance" name="date_de_naissance" required/>
        </div>

        <div class="form-group">
          <label for="sexe">Sexe</label>
          <select id="sexe" name="sexe" required>
            <option value="">-- Sélectionnez --</option>
            <option value="homme">Homme</option>
            <option value="femme">Femme</option>
            <option value="autre">Autre</option>
          </select>
        </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required />
      </div>

      <div class="form-group">
        <label for="home_id">Home Id</label>
        <input type="text" id="home_id" name="home_id" required>
      </div>

     

      <button type="submit" class="btn">S'inscrire</button>

      <p class="form-link">⚠️ Le compte créé aura automatiquement le rôle : <strong>Administrateur</strong>.</p>

      <p class="form-link">Déjà inscrit ? <a href="accueil.html?openModal=true">Se connecter</a></p>
    </form>
  </div>
</div>  


  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


</body>

</html>
