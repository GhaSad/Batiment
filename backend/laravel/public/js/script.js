// ########################################## NAVBAR & ONGLET PRINCIPAL ##########################################

const navButtons = document.querySelectorAll('.nav-btn');
const mainTabs = document.querySelectorAll('.tab-section');

navButtons.forEach(button => {
  button.addEventListener('click', () => {
    // Masquer tous les onglets principaux
    mainTabs.forEach(tab => tab.classList.add('hidden'));

    // Masquer tous les sous-onglets
    const allSubTabs = document.querySelectorAll('.sub-tab');
    allSubTabs.forEach(sub => sub.classList.add('hidden'));

    // Retirer classe active des boutons sous-onglets
    btn2s.forEach(b => b.classList.remove('active'));

    // Afficher onglet cliqué
    const targetTab = document.getElementById(button.dataset.tab);
    targetTab.classList.remove('hidden');
    targetTab.classList.add('fade-in');
  });
});


// ########################################## SOUS-ONGLETS ##########################################

const btn2s = document.querySelectorAll('.btn2');

btn2s.forEach(button => {
  button.addEventListener('click', () => {
    const targetId = button.dataset.target;
    const targetSection = document.getElementById(targetId);

    // Toggle affichage sous-onglet
    targetSection.classList.toggle('hidden');
    button.classList.toggle('active');
  });
});


// ########################################## SWITCHES (Ouvert / Fermé) ##########################################

const switches = document.querySelectorAll('.toggle-switch');

switches.forEach(switchEl => {
  switchEl.addEventListener('change', (e) => {
    const parentItem = e.target.closest('.item');
    const statusText = parentItem.querySelector('.status');

    // Modifier le texte en live
    statusText.textContent = e.target.checked ? 'Ouverte' : 'Fermée';

    // Exemple AJAX pour enregistrer l'état
    const newState = e.target.checked ? 'open' : 'closed';

    fetch('/update-door', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        doorId: parentItem.dataset.id,  // Ajoute un data-id dans HTML
        state: newState
      })
    }).then(res => res.json())
      .then(data => console.log('État mis à jour :', data))
      .catch(err => console.error(err));
  });
});


// ########################################## SWITCHES ALARME ##########################################

const alarmes = document.querySelectorAll('.toggle-switch-alarme');

alarmes.forEach(switchEl => {
  switchEl.addEventListener('change', (e) => {
    const parentItem = e.target.closest('.item');
    const statusText = parentItem.querySelector('.status');
    statusText.textContent = e.target.checked ? 'Activé' : 'Désactivé';

    // Ajouter appel API ici si besoin
  });
});


// ########################################## SEARCH ENGINE ##########################################

const searchInput = document.getElementById('search-bar');
const suggestionsBox = document.querySelector('.suggestions');

// === Liste TEMPORAIRE ===
let objets = [
  { id: 'porte-entree', name: 'Porte d\'entrée', tab: 'securite', target: 'portes-section' },
  { id: 'fenetre1', name: 'Fenêtre 1', tab: 'securite', target: 'fenetres-section' },
  { id: 'fenetre2', name: 'Fenêtre 2', tab: 'securite', target: 'fenetres-section' },
  { id: 'alarme1', name: 'Alarme principale 1', tab: 'securite', target: 'alarme-section' }
];

// === Suggestions dynamiques ===
searchInput.addEventListener('input', () => {
  const query = searchInput.value.toLowerCase();
  suggestionsBox.innerHTML = '';

  if (query.length > 1) {
    const filtered = objets.filter(obj => obj.name.toLowerCase().includes(query));

    filtered.forEach(result => {
      const div = document.createElement('div');
      div.textContent = result.name;

      div.addEventListener('click', () => {
        openTab(result.tab);

        // Ouvre le sous-onglet si précisé
        if (result.target) {
          const subButton = document.querySelector(`.btn2[data-target="${result.target}"]`);
          if (subButton && !subButton.classList.contains('active')) {
            subButton.click();
          }
        }

        highlightItem(result.name);
        suggestionsBox.classList.add('hidden');
        searchInput.value = '';
      });

      suggestionsBox.appendChild(div);
    });

    suggestionsBox.classList.remove('hidden');
  } else {
    suggestionsBox.classList.add('hidden');
  }
});

// Fermer suggestions si clic hors search
document.addEventListener('click', (e) => {
  if (!e.target.closest('.search-container')) {
    suggestionsBox.classList.add('hidden');
  }
});

// Ouvre onglet principal
function openTab(tabId) {
  mainTabs.forEach(tab => tab.classList.add('hidden'));
  const targetTab = document.getElementById(tabId);
  targetTab.classList.remove('hidden');
}

// Scroll + Highlight visuel
function highlightItem(itemName) {
  const items = document.querySelectorAll('.item-name, .btn2-text');

  items.forEach(item => {
    if (item.textContent.trim().toLowerCase() === itemName.toLowerCase()) {
      item.scrollIntoView({ behavior: 'smooth', block: 'center' });
      item.style.backgroundColor = '#B29BEB';
      setTimeout(() => {
        item.style.backgroundColor = 'transparent';
      }, 1500);
    }
  });
}

//########################################## Sécurité   ##########################################

function ouvrirModal(type) {
  document.getElementById('modal-overlay').classList.remove('hidden');
  const modal = document.getElementById('modal-ajout-objet');
  const title = document.getElementById('modal-title');
  const form = document.getElementById('ajoutObjetForm');

  modal.classList.remove('hidden');
  document.querySelector('.navbar-top').classList.add('modal-open');

  // Changer le titre dynamiquement
  if (type === 'porte') {
    title.textContent = 'Ajouter une porte';
  } else if (type === 'alarme') {
    title.textContent = 'Ajouter une alarme';
  } else {
    title.textContent = 'Ajouter une fenêtre';
  }

  // Stocker le type dans le formulaire
  form.dataset.type = type;
}



fetch('/api/rooms')
  .then(res => res.json())
  .then(data => {
    const select = document.getElementById('objet-piece');
    data.forEach(piece => {
      const option = document.createElement('option');
      option.value = piece.id;
      option.textContent = piece.nom;
      select.appendChild(option);
    });
  })
  .catch(err => console.error('Erreur chargement pièces :', err));

// Fermer le modal avec le bouton "Annuler"
document.querySelectorAll('.modal-close').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('modal-ajout-objet').classList.add('hidden');
    document.getElementById('modal-overlay').classList.add('hidden');
    document.querySelector('.navbar-top').classList.remove('modal-open');
  });
});

//########################################## Pièces  ##########################################

function ouvrirModalPiece() {
  document.getElementById('modal-overlay').classList.remove('hidden');
  document.getElementById('modal-ajout-piece').classList.remove('hidden');
  document.querySelector('.navbar-top').classList.add('modal-open');
}

document.querySelectorAll('#modal-ajout-piece .modal-close').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('modal-ajout-piece').classList.add('hidden');
    document.getElementById('modal-overlay').classList.add('hidden');
    document.querySelector('.navbar-top').classList.remove('modal-open');
  });
});

document.getElementById('form-ajout-piece').addEventListener('submit', function (e) {
  e.preventDefault();
  const nom = document.getElementById('nom-piece').value;

  fetch('/api/pieces', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nom })
  })
    .then(res => res.json())
    .then(piece => {
      ajouterBoutonPiece(piece);
      document.getElementById('modal-ajout-piece').classList.add('hidden');
      document.querySelector('.navbar-top').classList.remove('modal-open');
      this.reset();
    })
    .catch(err => console.error('Erreur ajout pièce:', err));
});


function ajouterBoutonPiece(piece) {
  const container = document.getElementById('liste-pieces');

  const btn = document.createElement('button');
  btn.className = 'btn2';
  btn.dataset.target = `piece-${piece.id}`;
  btn.innerHTML = `<i class="fas fa-door-open"></i> <span class="btn2-text">${piece.nom}</span>`;

  btn.addEventListener('click', () => {
    document.querySelectorAll('.piece-section').forEach(sec => sec.classList.add('hidden'));
    document.getElementById(`piece-${piece.id}`).classList.toggle('hidden');
  });

  container.appendChild(btn);

  // Crée une section vide pour la pièce (à remplir plus tard)
  const section = document.createElement('section');
  section.id = `piece-${piece.id}`;
  section.className = 'sub-tab piece-section hidden';
  section.innerHTML = `<h3>${piece.nom}</h3><p>Objets associés à cette pièce...</p>`;
  document.querySelector('.sub-tab-container').appendChild(section);
}

fetch('/api/pieces')
  .then(res => res.json())
  .then(data => data.forEach(piece => ajouterBoutonPiece(piece)))
  .catch(err => console.error('Erreur chargement pièces:', err));

// TEST -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

  // Liste temporaire des pièces
let pieces = [
  { id: 1, nom: "Salon" },
  { id: 2, nom: "Cuisine" }
];

// Génération dynamique des boutons de pièces
const listePieces = document.getElementById('liste-pieces');
const conteneurOnglets = document.getElementById('onglets-pieces');

pieces.forEach(piece => {
  // bouton style .btn2
  const btn = document.createElement('button');
  btn.className = 'btn2';
  btn.dataset.target = `piece-${piece.id}`;
  btn.innerHTML = `<i class="fas fa-door-open"></i><span class="btn2-text">${piece.nom}</span>`;
  
  btn.addEventListener('click', () => {
    const onglet = document.getElementById(`piece-${piece.id}`);
    onglet.classList.toggle('hidden');
    btn.classList.toggle('active');
  });

  listePieces.appendChild(btn);

  // sous-onglet associé à la pièce
  const onglet = document.createElement('section');
  onglet.id = `piece-${piece.id}`;
  onglet.className = 'sub-tab hidden';
  onglet.innerHTML = `
    <h3>${piece.nom}</h3>
    <p>Objets associés à cette pièce...</p>
  `;

  conteneurOnglets.appendChild(onglet);
});

//########################################## Objets  ##########################################

const suggestions = {
  lumiere: "Lumière du salon",
  tele: "Télé du séjour",
  enceinte: "Enceinte Bluetooth",
  appareil: "Cafetière",
  aspirateur: "Robot aspirateur",
  tondeuse: "Robot tondeuse",
  prise: "Prise connectée",
  arrosage: "Arrosage jardin",
  thermostat: "Thermostat principal",
  volet: "Volet chambre",
  serrure: "Serrure entrée",
  lave_linge: "Lave-linge",
  lave_vaisselle: "Lave-vaisselle",
  four: "Four connecté",
  autre: ""
};

// Suggestion automatique de nom
document.getElementById('type-objet').addEventListener('change', e => {
  const valeur = e.target.value;
  document.getElementById('nom-objet').value = suggestions[valeur] || '';
});

// Remplir la liste des pièces dynamiquement (depuis BDD ou JS temporaire)
fetch('/api/rooms')
  .then(res => res.json())
  .then(data => {
    const select = document.getElementById('objet-piece');
    select.innerHTML = "";
    data.forEach(piece => {
      const opt = document.createElement('option');
      opt.value = piece.id;
      opt.textContent = piece.nom;
      select.appendChild(opt);
    });
  });

// Ouvrir / Fermer le modal
function ouvrirModalObjet() {
  document.getElementById('modal-ajout-objet-connecte').classList.remove('hidden');
  document.getElementById('modal-overlay').classList.remove('hidden');
  document.querySelector('.navbar-top').classList.add('modal-open');
}

document.querySelectorAll('#modal-ajout-objet-connecte .modal-close').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('modal-ajout-objet-connecte').classList.add('hidden');
    document.getElementById('modal-overlay').classList.add('hidden');
    document.querySelector('.navbar-top').classList.remove('modal-open');
  });
});
