<?php   
require_once('inc/init.php');

//1- afficher les catégories
$categorie = execRequete("SELECT DISTINCT categorie FROM produit ORDER BY categorie"); // SELECT DISTINCT pour éviter les doublon

$contenu_gauche .= '<p class="lead ml-5 pt-5">Categories</P>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="?categ=*">Toutes</a></li>';
    while ( $cat = $categorie->fetch() ){ // on fait une boucle pour répeter toute les categories avec le fetch dans $cat.
        $contenu_gauche .= '<li class="list-group-item"><a href="?categ='.$cat['categorie'].'">'.ucfirst($cat['categorie']).'</a></li>';
    }
$contenu_gauche .= '</ul>';

//2. afficher les produits en tenant compte d'un éventuel catégorie
$whereclause = '';
$arg = array();//tableau vide

if(isset($_GET['categ']) && $_GET['categ'] !== '*' ){
    $whereclause = 'WHERE categorie=:categorie'; // si autre catégorie que * on crée une variable pour compléter la requete
    $arg['categorie'] = $_GET['categ']; // on crée un tableau avec catégorie
}

$produits = execRequete("SELECT * FROM produit $whereclause", $arg); // on définit alors produit

$contenu_droite .= '<div class="row mt-5">';

    while( $produit = $produits -> fetch() ){
        $contenu_droite .='<div class="card col-4 mb-3 style="width: 18rem;"">
                                    <a href="fiche_produit.php?id_produit='.$produit['id_produit'].'">
                                    <img src="'. URL . 'photo/produits/' . $produit['photo'] . '" class="card-img-top" alt="'.$produit['titre'].'"></a>
                                <div class="card-body">
                                    <a class="lienCard" href="fiche_produit.php?id_produit='.$produit['id_produit'].'">
                                    <h5 class="float-right card-title pl-2">'. $produit['prix'] .' &euro;</h5>
                                    <h5 class="card-title">'. $produit['titre'] .'</h5>
                                    <p class="card-text">'.$produit['description'].'</p></a>
                            </div>
                        </div>';
    }
$contenu_droite .= '<div>';



require_once('inc/header.php');
?>
<div class="row">
    <div class="col-3">
        <?= $contenu_gauche ?>
    </div>
    <div class="col-9">
        <?= $contenu_droite ?>
    </div>
</div>
</div>
</div>


<?php
require_once('inc/footer.php');