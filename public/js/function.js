// Menu burger

var sidenav = document.getElementById("mySidenav");
var openBtn = document.getElementById("openBtn");
var closeBtn = document.getElementById("closeBtn");

openBtn.onclick = openNav;
closeBtn.onclick = closeNav;

/* Set the width of the side navigation to 250px */
function openNav() {
  sidenav.classList.add("active");
}

/* Set the width of the side navigation to 0 */
function closeNav() {
  sidenav.classList.remove("active");
}

// Dérouler/Cacher les modules non programmés au clic

let togg1 = document.getElementById("togg1");
let d1 = document.getElementById("d1");

togg1.addEventListener("click", () => {
  if(getComputedStyle(d1).display != "none"){
    d1.style.display = "none";

  } else {
    d1.style.display = "block";
    
  }
})

// Message de prévention avant la suppresion d'un formateur

 function alertSuppFormateur(e) {

  if (!confirm('En effaçant le formateur, vous effacez également toutes les sessions qui lui sont rattachées. Continuer ?')) {
    e.preventDefault();
    e.stopPropagation();

 return false;
  }
  
   // Si l'utilisateur clique sur "Oui", la fonction ne renvoie rien et l'action par défaut se produira
  
}

// Message de prévention avant la suppresion d'une Formation

 function alertSuppFormation(e) {

  if (!confirm('En effaçant la formation, vous effacez également toutes les sessions qui lui sont rattachées. Continuer ?')) {
    e.preventDefault();
    e.stopPropagation();

 return false;
  }
  
   // Si l'utilisateur clique sur "Oui", la fonction ne renvoie rien et l'action par défaut se produira
  
}

// Message de prévention avant la suppresion d'une Catégorie

 function alertSuppCategorie(e) {

  if (!confirm('En effaçant la catégorie, vous effacez également tous les modules qui lui sont rattachés et les programmes rattachés au modules. Continuer ?')) {
    e.preventDefault();
    e.stopPropagation();

 return false;
  }
  
   // Si l'utilisateur clique sur "Oui", la fonction ne renvoie rien et l'action par défaut se produira
  
}