// Niveau 2 : consommer une API REST existante avec fetch.
const API = 'https://jsonplaceholder.typicode.com/posts';

const liste = document.getElementById('liste');
const message = document.getElementById('message');
const form = document.getElementById('form-article');

function afficher(texte, classe) {
  message.textContent = texte;
  message.className = classe;
}

// GET : les 5 premiers articles
async function charger() {
  // TODO 1 : appeler `${API}?_limit=5` avec l'en-tête Accept: application/json.
  const reponse = await fetch(`${API}?_limit=5`,{
    method: 'GET',
    headers: {
      'Accept': 'application/json'
    }
  }
  );

  // TODO 2 : si reponse.ok est faux, afficher le code d'erreur et s'arrêter.
  if (!reponse.ok) {
    afficher(`Erreur HTTP ${reponse.status}`,'erreur');
    return;
  }

  // TODO 3 : vider #liste, puis créer un <li> par article (id et title)
  //          avec un bouton « Supprimer » qui appelle supprimer(article.id).
  liste.innerHTML = '';
  const articles = await reponse.json();

  articles.forEach((a) => {
    const li = document.createElement('li');
    li.textContent = a.title + ' ';

    const bouton = document.createElement('button');
    bouton.textContent = 'Supprimer';
    bouton.addEventListener('click', () => supprimer(a.id));

    li.appendChild(bouton);
    liste.appendChild(li);
  });

}

// POST : créer un article
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const { title, body } = Object.fromEntries(new FormData(form));
  // TODO 4 : envoyer { title, body, userId: 1 } en JSON (POST, en-tête Content-Type).
  const reponse = await fetch(`${API}`,{
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({title , body , userId: 1}),
  }
  );

  // TODO 5 : si le statut est 201, afficher l'identifiant attribué puis recharger la liste.
  //          Le nouvel article apparaît-il ? Pourquoi ?
  if (reponse.status === 201) {
    const nouvelle_article = await reponse.json();
    afficher(`Article crée avec l'id ${nouvelle_article.id}`,'succes');
    charger();
  } else {
    afficher(`Erreur HTTP ${reponse.status}`,'erreur');
  }

});

// DELETE : supprimer un article
async function supprimer(id) {
  // TODO 6 : envoyer DELETE sur `${API}/${id}` et afficher le code reçu.
  const reponse = await fetch(`${API}/${id}`,
    {
      method: "DELETE",
    }
  );
  afficher(`${reponse.status}`,'code');

}

charger();
