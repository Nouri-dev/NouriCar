window.onload = () => {
  let boutons = document.querySelectorAll(".custom-control-input");

  for (let bouton of boutons) {
    bouton.addEventListener("click", activer);
  }
};

function activer() {
  let xmlhttp = new XMLHttpRequest();

  let url = '';
  if (window.location.pathname.includes('admin')) {
    url = '/admin/activeAnnonce/' + this.dataset.id;
  } else if (window.location.pathname.includes('profil')) {
    url = '/profil/activeAnnonce/' + this.dataset.id;
  }

  xmlhttp.open('GET', url);
  xmlhttp.send();
}

