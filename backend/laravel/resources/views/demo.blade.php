<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Maison Connectée - Tableau de Bord</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body class="{{ auth()->user()->role }}"> <!-- Dynamique: admin, parent, enfant, invite -->
  
<!-- Boutons à gauche et à droite en haut -->
<div class="btn-container">
<div class="settings-container">
    <a href="{{ route('profil') }}">
        <button class="settings-btn">
            <i class="fas fa-cogs"></i>
        </button>
    </a>
</div>


  <div class="logout-container">
    <button class="logout-btn" id="logoutBtn">
      <i class="fas fa-power-off"></i>
    </button>
  </div>
</div>

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
      </div>
    
      <!-- Bouton Ajouter -->
      <button class="btn small-btn role-admin btn-ajouter-porte" onclick="ouvrirModal('porte')">Ajouter une porte</button>

    </section>

    <section id="fenetres-section" class="sub-tab hidden">
      <h3>Fenêtres</h3>
    
      <div class="item-list">
      </div>
    
      <!-- Bouton Ajouter -->
      <button class="btn small-btn role-admin" onclick="ouvrirModal('fenetre')">Ajouter une fenêtre</button>
    </section>

    <section id="alarme-section" class="sub-tab hidden">
      <h3>Alarme</h3>
    
      <div class="item-list">
      </div>
    
      <!-- Bouton Ajouter -->
      <button class="btn small-btn role-admin btn-ajouter-alarme" onclick="ouvrirModal('alarme')">Ajouter une alarme</button>

    </section>

    </div>

<!-- ########################################## Énergie ########################################## -->
<section id="energie" class="tab-section hidden">
  <h2><i class="fas fa-bolt"></i> Énergie</h2>
  <p>Surveillez la consommation et production énergétique de votre maison.</p>

  <div class="button-container">
    <button class="btn2" data-target="elec-section">
      <i class="fas fa-bolt"></i>
      <span class="btn2-text">Électricité</span>
    </button>

    <button class="btn2" data-target="chauffage-section">
      <i class="fas fa-fire"></i>
      <span class="btn2-text">Chauffage</span>
    </button>
</section>

  
  <!-- Sous-sections Énergie -->
  <div class="sub-tab-container">
    <section id="elec-section" class="sub-tab hidden">
        <h3>Électricité</h3>
        
        <!-- Graphique pour la consommation -->
        <canvas id="graphElectricite" width="400" height="200"></canvas>
        
        <!-- Div pour stocker les données des dispositifs -->
        <div id="devicesData" data-devices="{{ json_encode($devices) }}"></div>

        <!-- Tableau des appareils et de leur consommation -->
        <table>
            <thead>
                <tr>
                    <th>Appareil</th>
                    <th>Consommation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($devices as $device)
                    <!-- Affiche la consommation d'énergie -->
                    <tr>
                        <td>{{ $device->name }}</td>
                        <td>{{ number_format($device->energy_usage, 2) }} kWh</td>  <!-- Affiche la consommation -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Calcul du total de consommation -->
        <div class="total">
            Total : 
            @php
                $totalConsumption = $devices->sum('energy_usage');  // Additionne la consommation de tous les appareils
            @endphp
            {{ number_format($totalConsumption, 2) }} kWh
        </div>
    </section>
  </div>

  
    <section id="chauffage-section" class="sub-tab hidden">
    <h3>Chauffage</h3>

<!-- Tableau des pièces et de leur consommation de chauffage -->
<table>
    <thead>
        <tr>
            <th>Zone</th>
            <th>Consommation</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                <td>{{ number_format($room->heating_consumption, 2) }} kWh</td>  <!-- Affiche la consommation de chauffage -->
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Calcul de la consommation totale -->
<div class="total">
    Total : 
    @php
        $totalConsumption = $rooms->sum('heating_consumption');  // Additionne la consommation de chauffage de toutes les pièces
    @endphp
    {{ number_format($totalConsumption, 2) }} kWh
</div>
    </section>

<!-- ########################################## Objets connectés ########################################## -->


<section id="objets" class="tab-section hidden">
  <h2><i class="fas fa-lightbulb"></i> Objets Connectés</h2>
  <p>Contrôlez vos lumières, enceintes et autres appareils connectés.</p>

  <!-- Liste dynamique des objets connectés -->
  <div id="liste-objets" class="sub-tab-container item-list">
    <!-- Objets générés dynamiquement via PHP Blade -->
    @foreach($devices as $device)
      <div class="objet-item">
        <p><strong>{{ $device->name }}</strong></p>
        <p>{{ $device->description }}</p>
        <p>Status: {{ $device->status ? 'Actif' : 'Inactif' }}</p>
      </div>
    @endforeach
  </div>

  <!-- Bouton pour ouvrir le modal d'ajout -->
  <button class="btn role-admin" onclick="ouvrirModalObjet()">Ajouter un objet</button>
</section>

<!-- MODAL OVERLAY (en dehors de toute section) -->
<div id="modal-overlay"></div>

<!-- MODAL AJOUT OBJET CONNECTÉ (placer en bas de la page) -->
<div id="modal-ajout-objet-connecte" class="modal hidden">
  <div class="modal-content">
    <h2>Ajouter un objet connecté</h2>
    <form id="form-ajout-objet-connecte" method="POST" action="{{ route('add-connected-object') }}">
  @csrf
  <!-- Type d'objet -->
  <div class="form-group">
    <label for="type-objet">Type d’objet</label>
    <select id="type-objet" name="type" required>
      <option value="">-- Choisir --</option>
      <option value="lumiere">💡 Lumière</option>
      <option value="tele">📺 Télé</option>
      <option value="enceinte">🔊 Enceinte</option>
      <option value="appareil">🍽️ Appareil ménager</option>
      <option value="aspirateur">🤖 Robot aspirateur</option>
      <option value="tondeuse">🤖 Robot tondeuse</option>
      <option value="prise">🔌 Prise connectée</option>
      <option value="arrosage">💧 Arrosage auto.</option>
      <option value="thermostat">🌡️ Thermostat</option>
      <option value="volet">🪟 Volets roulants</option>
      <option value="serrure">🔒 Serrure connectée</option>
      <option value="lave_linge">🮚 Lave-linge / sèche-linge</option>
      <option value="lave_vaisselle">🍽️ Lave-vaisselle</option>
      <option value="four">🔥 Four</option>
      <option value="autre">🔧 Autre</option>
    </select>
  </div>

  <!-- Nom de l'objet -->
  <div class="form-group">
    <label for="nom-objet">Nom de l’objet</label>
    <input type="text" id="nom-objet" name="nom" placeholder="Ex: Lumière du salon" required />
  </div>

  <!-- Pièce -->
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

  <!-- Actions -->
  <div class="modal-actions">
    <button type="submit" class="btn">Ajouter</button>
    <button type="button" class="modal-close">Annuler</button>
  </div>
</form>

  </div>
</div>

</body>
</html>
