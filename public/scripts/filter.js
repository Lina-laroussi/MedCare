const searchBar = document.getElementById('searchBar');
const url = new URL(window.location.href);
const form = document.getElementById('searchForm');
const eventArea = document.getElementById('events-area');
const h1 = document.querySelector('h1');


//prevent default form
form.addEventListener('submit', (e) => {
    e.preventDefault();

    if (searchBar.value === '') {
        requete = 'all';
    } else {
        requete = searchBar.value;
    }

    fetch('pharmacie/' + requete, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    }).then(response =>
        response.json(),
    ).then(data => {
        if(requete !== 'all') {
            h1.innerHTML = "Résultat de la recherche : " + searchBar.value;
        } else {
            h1.innerHTML = "Les évènements à venir !";
        }

        eventArea.innerHTML = data;
    })
})
