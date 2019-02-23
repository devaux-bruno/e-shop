<?php   
require_once('inc/init.php');

require_once('inc/header.php');


?>
<h2 class="text-center mb-3 mt-4">Présentation de la partie Back-end</h2>
<article>
    <h5 class=" text-center mt-2">1/Menu de connexion et affichage des produits</h5>
    <p class="text-center mt-3 mb-3">
    Pour les administrateurs, un menu back-end est accessible dans la barre de navigation, leur permettant d’accéder aux différentes pages de gestion.
    Pour le moment, il n’y a que les pages gestion des produits, gestion des membres et gestion des commandes.
    </p>
    <img src="docs/presentation_backend/1-menuAdmin.jpg" alt="Menu admin" class="imgpresentation">
</article>
<article>
<h5 class="text-center mt-2 mt-4">2/Enregistrement des nouveaux produits</h5>
    <p class="text-center mt-3 mb-3">
    Cette page, permets de rajouter/modifier ou supprimer les différents produits possible sur le site internet.
    On peut définir ici toutes les catégories qui seront présente sur la page d’accueil (seul filtre possible pour le moment), mais aussi le nombre de produits en stock qui sera déduit en fonction du nombre commandés. Nous définissons aussi la photo, la référence, le nom, la description, la couleur, la taille, le public (H/F/mixte) et le prix.

    </p>
    <img src="docs/presentation_backend/2-produitEnregistrement.jpg" alt="Menu admin" class="imgpresentation">
</article>
<article>
<h5 class=" text-center mt-2 mt-4">3/Gestion des membres</h5>
    <p class="text-center mt-3 mb-3">
    Pour le moment la page gestion des membres, ne permet que d’afficher tous les membres inscrits sur le site et de les promouvoir en tant qu’administrateur ou rétrograder au statut de membre.
    </p>
    <img src="docs/presentation_backend/3-gestionMembre.jpg" alt="Menu admin" class="imgpresentation">
</article>
<article>
<h5 class=" text-center mt-2 mt-4">4/Gestion des commandes</h5>
    <p class="text-center mt-3 mb-3">
    La dernière page back-end est un récapitulatif  des commandes passées sur le site.
    On y retrouve tous les détails du clients et des produits achetés, puis sont triées par membre et ensuite par statut, la commandes change aussi de couleur selon l’état du statut afin de faciliter la lecture du tableau.   
    </p>
    <img src="docs/presentation_backend/4-gestionCommande.jpg" alt="Menu admin" class="imgpresentation mb-5">
</article>



<?php
require_once('inc/footer.php');