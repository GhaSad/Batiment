<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maison Connectée - Tableau de Bord</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body class="admin"> <!-- Dynamique: admin, parent, enfant, invite -->
  

<!-- ########################################## NAVBAR ########################################## -->
  <nav class="navbar-top">
    <div class="nav-container">
      <div class="logo">
        <i class="fas fa-house-user" style="font-size: 24px; color: #4C5561;"></i>
        <span class="logo-text">Maison Connectée</span>
      </div>

      <div class="search-container">
        <input type="text" id="search-bar" placeholder="Rechercher...">
        <div class="suggestions hidden"></div>
      </div>

      <div class="nav-links">
        <button class="nav-btn role-admin role-parent role-enfant" data-tab="securite">
          <i class="fas fa-lock"></i>
          <span class="nav-text">Sécurité</span>
        </button>

        <button class="nav-btn role-admin role-parent" data-tab="energie">
          <i class="fas fa-bolt"></i>
          <span class="nav-text">Énergie</span>
        </button>

        <button class="nav-btn role-admin role-parent role-enfant role-invite" data-tab="objets">
          <i class="fas fa-lightbulb"></i>
          <span class="nav-text">Objets</span>
        </button>

        <button class="nav-btn role-admin role-parent role-enfant role-invite" data-tab="pieces">
          <i class="fas fa-house"></i>
          <span class="nav-text">Pièces</span>
        </button>

        <button class="nav-btn role-admin" data-tab="utilisateurs">
          <i class="fas fa-users-cog"></i>
          <span class="nav-text">Gérer</span>
        </button>

        <button class="nav-btn role-admin" data-tab="creer">
          <i class="fas fa-user-plus"></i>
          <span class="nav-text">Créer</span>
        </button>
      </div>
    </div>
  </nav>

  <!-- ########################################## CONTENU PRINCIPAL ########################################## -->
  <main class="container">

<!-- ########################################## Sécurité ########################################## -->

  <section id="securite" class="tab-section hidden">
    <h2><i class="fas fa-lock"></i> Sécurité</h2>
    <p>Gérez l'état des portes, fenêtres et l'alarme de votre maison.</p>

    <div class="button-container">
      <button class="btn2" data-target="portes-section">
        <i class="fas fa-door-closed"></i>
        <span class="btn2-text">Portes</span>
      </button>

      <button class="btn2" data-target="fenetres-section">
        <i class="fas fa-border-all"></i>
        <span class="btn2-text">Fenêtres</span>
      </button>

      <button class="btn2" data-target="alarme-section">
        <i class="fas fa-bell"></i>
        <span class="btn2-text">Alarme</span>
      </button>
    </div>
  </section>

  <!----------------------------------------- SOUS-ONGLETS ----------------------------------------->

    <div class="sub-tab-container">

    <section id="portes-section" class="sub-tab hidden">
      <h3>Portes</h3>
    
      <!-- Liste des portes -->
      <div class="item-list">
        <div class="item">
          <span class="item-name">Porte d'entrée</span>
          <div class="item-actions">
            <strong class="status">Fermée</strong>
            <label class="switch">
              <input type="checkbox" class="toggle-switch">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    
      <!-- Bouton Ajouter -->
      <button class="btn small-btn role-admin btn-ajouter-porte" onclick="ouvrirModal('porte')">Ajouter une porte</button>

    </section>

    <section id="fenetres-section" class="sub-tab hidden">
      <h3>Fenêtres</h3>
    
      <div class="item-list">
        <div class="item">
          <span class="item-name">Fenêtre 1</span>
          <div class="item-actions">
            <strong class="status">Fermée</strong>
            <label class="switch">
              <input type="checkbox" class="toggle-switch">
              <span class="slider"></span>
            </label>
          </div>
        </div>

        <div class="item">
          <span class="item-name">Fenêtre 2</span>
          <div class="item-actions">
            <strong class="status">Fermée</strong>
            <label class="switch">
              <input type="checkbox" class="toggle-switch">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    
      <!-- Bouton Ajouter -->
      <button class="btn small-btn role-admin" onclick="ouvrirModal('fenetre')">Ajouter une fenêtre</button>
    </section>

    <section id="alarme-section" class="sub-tab hidden">
      <h3>Alarme</h3>
    
      <div class="item-list">
        <div class="item">
          <span class="item-name">Alarme principale 1</span>
          <div class="item-actions">
            <strong class="status">Désactivé</strong>
            <label class="switch">
              <input type="checkbox" class="toggle-switch-alarme">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    
      <!-- Bouton Ajouter -->
      <button class="btn small-btn role-admin btn-ajouter-alarme" onclick="ouvrirModal('alarme')">Ajouter une alarme</button>

    </section>

    </div>

  <!-- ########################################## Ajouter ########################################## -->

    <!-- ########################################## Overlay flouté -->

    <div id="modal-overlay" class="modal-overlay hidden"></div>

    <!-- ########################################## Formulaire  -->

    <div id="modal-ajout-objet" class="modal hidden">
      <div class="modal-content">
        <h2 id="modal-title">Ajouter une fenêtre</h2>
        <form id="ajoutObjetForm" method="POST" action="{{ route('create-device') }}">
      @csrf
      <div class="form-group">
        <label for="objet-nom">Nom</label>
        <input type="text" id="objet-nom" name="nom" required>
      </div>

      <div class="form-group">
        <label for="objet-piece">Pièce</label>
        <select id="objet-piece" name="piece_id">
          <option value="">-- Aucune pièce --</option>
          <!-- Options générées dynamiquement via JS -->
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }}</option> <!-- Affichage du nom -->
          @endforeach
        </select>
      </div>

      <input type="hidden" id="objet-type" name="type" value="fenetre"> <!-- Dynamically change the type -->

      <div class="modal-actions">
        <button type="submit" class="btn">Ajouter</button>
        <button type="button" class="modal-close">Annuler</button>
      </div>
    </form>

      </div>
    </div>

<!-- ########################################## Énergie ########################################## -->
    <section id="energie" class="tab-section hidden">
      <h2><i class="fas fa-bolt"></i> Énergie</h2>
      <p>Surveillez la consommation et production électrique de votre maison.</p>
    </section>

<!-- ########################################## Objets connectés ########################################## -->
    <section id="objets" class="tab-section hidden">
      <h2><i class="fas fa-lightbulb"></i> Objets Connectés</h2>
      <p>Contrôlez vos lampes, enceintes et autres appareils connectés.</p>
    </section>

<!-- ########################################## Pièces ########################################## -->
  <section id="pieces" class="tab-section hidden">
    <h2><i class="fas fa-house"></i> Pièces</h2>
    <p>Gérez les pièces de votre maison et les objets associés.</p>

    <div class="button-container" id="liste-pieces">
      <!-- Boutons pièces générés dynamiquement ici -->
    </div>

    <button class="btn small-btn role-admin" id="btn-ajouter-piece" onclick="ouvrirModalPiece()">Ajouter une pièce</button>
  </section>

  <div class="sub-tab-container" id="onglets-pieces">
  <!-- Les sous-onglets des pièces apparaîtront ici -->
  </div>

  <!-- ########################################## Modal ########################################## -->
    <!-- ########################################## Overlay flouté -->

    <div id="modal-overlay" class="modal-overlay hidden"></div>
    
  <div id="modal-ajout-piece" class="modal hidden">
  <div class="modal-content">
    <h2>Ajouter une pièce</h2>
    <form id="form-ajout-piece">
      <div class="form-group">
        <label for="nom-piece">Nom de la pièce</label>
        <input type="text" id="nom-piece" name="nom" required>
      </div>
      <button type="submit" class="btn">Ajouter</button>
      <button type="button" class="modal-close">Annuler</button>
    </form>
  </div>
</div>

<!-- ########################################## Gérer utilisateurs ########################################## -->
    <section id="utilisateurs" class="tab-section hidden">
      <h2><i class="fas fa-users-cog"></i> Gérer utilisateurs</h2>
      <p>Liste des utilisateurs à gérer...</p>
    </section>

<!-- ########################################## Créer utilisateur ########################################## -->
    <section id="creer" class="tab-section hidden">
      <h2><i class="fas fa-user-plus"></i> Créer un nouvel utilisateur</h2>
      <form action="{{ route('create.user') }}" method="POST" class="form">
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
          <label for="profil">Type de profil</label>
          <select id="role" name="role">
            <option value="">-- Sélectionner --</option>
            <option value="admin">Administrateur</option>
            <option value="complexe">Parent</option>
            <option value="simple">Enfant</option>
            <option value="visiteur">Invité</option>
          </select>
        </div>
        <div class="form-group">
        <label for="home_id">Home Id</label>
        <input type="text" id="home_id" name="home_id" required>
      </div>
        <button type="submit" class="btn">Créer le compte</button>
      </form>
    </section>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

  </main>

<!-- ########################################## SCRIPT ########################################## -->


  <script src="{{ asset('js/script.js') }}"></script>


</body>

</html>
