// ########################################## NAVBAR & ONGLET PRINCIPAL ##########################################

const navButtons = document.querySelectorAll('.nav-btn');
const mainTabs = document.querySelectorAll('.tab-section');

navButtons.forEach(button => {
    button.addEventListener('click', () => {
      mainTabs.forEach(tab => tab.classList.add('hidden'));
  
      // Fermer tous sous-onglets
      const allSubTabs = document.querySelectorAll('.sub-tab');
      allSubTabs.forEach(sub => sub.classList.add('hidden'));
  
      // Retirer classe active
      btn2s.forEach(b => b.classList.remove('active'));
  
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

    // Toggle classe active sur le bouton
    button.classList.toggle('active');
  });
});

// ########################################## SWITCHES ##########################################

const switches = document.querySelectorAll('.toggle-switch');

switches.forEach(switchEl => {
  switchEl.addEventListener('change', (e) => {
    const parentItem = e.target.closest('.item');
    const statusText = parentItem.querySelector('.status');
    
    // Modifier le texte en live
    if (e.target.checked) {
      statusText.textContent = 'Ouverte';
    } else {
      statusText.textContent = 'Fermée';
    }

    // Préparer appel API vers BDD
    const newState = e.target.checked ? 'open' : 'closed';

    // Exemple AJAX (à adapter côté back)
    fetch('/update-door', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        doorId: parentItem.dataset.id, // à remplir dynamiquement avec l'ID
        state: newState
      })
    }).then(res => res.json())
      .then(data => console.log('État mis à jour :', data))
      .catch(err => console.error(err));
  });
});

const alarmes = document.querySelectorAll('.toggle-switch-alarme');

alarmes.forEach(switchEl => {
  switchEl.addEventListener('change', (e) => {
    const parentItem = e.target.closest('.item');
    const statusText = parentItem.querySelector('.status');
    
    // Modifier le texte
    if (e.target.checked) {
      statusText.textContent = 'Activé';
    } else {
      statusText.textContent = 'Désactivé';
    }

    // Envoyer état BDD ici
  });
});


