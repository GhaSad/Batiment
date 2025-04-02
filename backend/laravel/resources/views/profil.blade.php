<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil - Maison Connectée</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>

<body class="bg-gradient-to-r from-gray-100 to-purple-300 h-screen">

<div class="modal">
  <div class="modal-content">
    <h2>Mon Profil</h2>

    <!-- Affichage des informations de l'utilisateur -->
    <div class="form-group">
      <label for="username">Nom d'utilisateur</label>
      <input type="text" id="username" name="username" value="{{ $user->username }}" disabled />
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="{{ $user->email }}" disabled />
    </div>

    <div class="form-group">
      <label for="date_de_naissance">Date de naissance</label>
      <input type="date" id="date_de_naissance" name="date_de_naissance" value="{{ $user->date_de_naissance }}" disabled />
    </div>

    <div class="form-group">
      <label for="sexe">Sexe</label>
      <input type="text" id="sexe" name="sexe" value="{{ $user->sexe }}" disabled />
    </div>

    <div class="form-group">
      <label for="role">Rôle</label>
      <input type="text" id="role" name="role" value="{{ $user->role }}" disabled />
    </div>

    <!-- Formulaire de mise à jour des informations -->
    <h3>Modifier mes informations</h3>
    <form action="{{ route('profile.update') }}" method="POST">
      @csrf
      @method('PUT')  <!-- Permet de spécifier que c'est une mise à jour -->

      <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="{{ $user->prenom }}" required />
      </div>

      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="{{ $user->nom }}" required />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ $user->email }}" required />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Nouveau mot de passe (optionnel)" />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" />
      </div>

      <button type="submit" class="btn">Mettre à jour</button>
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

@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

</body>

</html>
