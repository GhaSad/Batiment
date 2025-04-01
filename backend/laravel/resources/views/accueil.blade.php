<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maison Connectée - Accueil</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>


<nav class="navbar-top">
    <div class="nav-container">
      <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 3l9 6.75M4.5 10.5V20h15V10.5M9 20v-6h6v6" />
        </svg>
        <span class="logo-text">SmartHome</span>
      </div>
      <div class="nav-links">
        <!-- Futurs boutons ici -->
      </div>
    </div>
</nav>


<body>


  <header>
    <div class="container">
      <h1>Prenez le contrôle de votre maison.</h1>
      <p>Contrôlez vos lumières, chauffages, caméras et bien plus, où que vous soyez. Tout est centralisé, simple à gérer, et entièrement sécurisé.</p>
      <button class="btn" id="openModal">Se connecter / S'inscrire</button>
      <button class="btn demo-btn" id="demoButton">Voir la démo</button>
    </div>
  </header>

  <!-- Modal -->
  <div id="modal" class="modal hidden">
    <div class="modal-content">
      <button id="closeModal" class="modal-close">&times;</button>
      <h2>Connexion</h2>

      <form action="{{ route('login') }}" method="POST" class="form">
    @csrf <!-- Ceci génère un token CSRF -->
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required />
        @error('email') 
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required />
        @error('password') 
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn">Se connecter</button>
    <p class="form-link">Pas encore de compte ? <a href="{{ route('register.form') }}">S'inscrire</a></p>
</form>

    </div>
  </div>

 <!-- ########################################## SCRIPT ########################################## -->

  <script>
    const modal = document.getElementById('modal');
    const openModal = document.getElementById('openModal');
    const closeModal = document.getElementById('closeModal');
  
    // Fonction pour afficher le modal
    function showModal() {
      modal.classList.remove('hidden');
    }
  
    // Fonction pour cacher le modal
    function hideModal() {
      modal.classList.add('hidden');
    }
  
    // Event clic sur bouton Se connecter
    openModal.addEventListener('click', showModal);
  
    // Event clic sur bouton fermer
    closeModal.addEventListener('click', hideModal);
  
    // Vérification de l'URL
    const params = new URLSearchParams(window.location.search);
    if (params.get('openModal') === 'true') {
      showModal();
  
      // Supprimer le paramètre de l'URL SANS recharger la page
      const newUrl = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);
    }
  </script>
  

</body>

</html>
