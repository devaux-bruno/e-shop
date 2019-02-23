<?php

require_once('inc/init.php');

if ( !empty($_POST['critere']) ){
    extract($_POST);
    $contenu .= '<h2> Recherche de "'.$critere.'"</h2>';

    $resultat = execRequete("SELECT * FROM produit 
    WHERE titre LIKE CONCAT('%',:critere,'%') 
    OR couleur LIKE CONCAT('%',:critere,'%') 
    OR categorie LIKE CONCAT('%',:critere,'%')",
    array('critere' => $critere));

    $nb_resultats = $resultat->rowCount();
    if (  $nb_resultats > 0 ){
        $contenu .= '<h3>Il y a '.$nb_resultats.' résultat(s)</h3>
        <div class="row">';    
        while ( $produit = $resultat->fetch()){

            $contenu .= '
            <div class="card col-4 mb-3 style="width: 18rem;"">
                <a href="fiche_produit.php?id_produit='.$produit['id_produit'].'">
                    <img src="' . URL . 'photo/produits/' . $produit['photo'] . '" alt="'.$produit['titre'].'" class="card-img-top">
                </a>
                <div class="card-body">
                    <a class="lienCard" href="fiche_produit.php?id_produit='.$produit['id_produit'].'">
                        <h5 class="float-right card-title pl-2">'. $produit['prix'] .' &euro;</h5>
                        <h5 class="card-title">'. $produit['titre'] .'</h5>
                        ss="card-text">'.$produit['description'].'</p>
                    </a>
                </div>
            </div>
        ';  
        }
        $contenu .='</div>';
    }
    else{
        $contenu .= '<div class="alert alert-danger">Il n\'y a pas de produits correspondant à votre recherche</div>';
    } 
    
    $contenu .= '
    <div class="mt-4 text-center">
        <a href="'.URL.'" class="btn btn-primary">Retour à la boutique</a>
    </div>';
}
else
{
    header('location:' . URL);
    exit();
}

require_once('inc/header.php');
echo $contenu;
require_once('inc/footer.php');