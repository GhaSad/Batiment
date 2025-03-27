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


// ########################################## SOUS-ONGLETS SÉCURITÉ ##########################################

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


// ########################################## SWITCHES (PORTES / FENÊTRES) ##########################################

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

//########################################## modal ajout fenêtre ##########################################

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


