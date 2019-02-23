<?php   
require_once('inc/init.php');

//-3 Génération de fiche produit
if( isset($_GET['id_produit']) ){
    $resultat = execRequete("SELECT * FROM produit WHERE id_produit=:id_produit", array( 'id_produit' => $_GET['id_produit']) ); //on selectionne tous les produit = à l'id_produit

    if( $resultat->rowCount() == 0){// si on a aucun article on redirige vers la boutique
        header('locayion:'. URL);
        exit();
    }
    $produit = $resultat->fetch(); //sinon on affiche l'article

    //page produit
    $contenu .='<div class="col-12">
                    <h1 class="page-header text-center" >'.$produit['titre'].'</h1>
                </div>
                <div class="row">    
                    <div class="col-8">
                        <img class="img-fluid" src="'. URL . 'photo/produits/' . $produit['photo'] . '" alt="'.$produit['titre'].'">
                    </div>   
                    <div class="col-4">
                        <h3>Description</h3>
                        <p>'.$produit['description'].'</p>
                        <h3>Détails</h3>
                            <ul>
                                <li>Categorie : '.$produit['categorie'].'</li>
                                <li>Couleur : '.$produit['couleur'].'</li>
                                <li>Taille : '.$produit['taille'].'</li>
                            </ul>
                        <p class="lead text-center">Prix : '.number_format($produit['prix'],2,',',' ').' &euro;</p>';
        if( $produit['stock'] > 0){
            //on crée un mini formulaire pour l'ajour au panier
            $contenu .= '<form method="POST" action="panier.php"> 
                            <input type="hidden" name="id_produit" value="'.$produit['id_produit'].'">
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <select name="quantite" class="form-control">';
                                    for( $i=1; $i<=$produit['stock'] && $i<=5; $i++){
                                        $contenu .= '<option>'.$i.'</option>';
                                    }
                                    $contenu .='
                                    </select>
                                </div>
                                <div class="form-group col-8">    
                                    <input type="submit" name="ajout_panier" value="Ajouter au panier" class="btn btn-warning">
                                </div>
                            </div>
                        </form>';
        }
        else{
            $contenu .= '<p>Produit indisponible</p>';
        }
                   
        $contenu .= '</div>
                </div>';
}
else{
    header('locayion:'. URL);
    exit();
}


require_once('inc/header.php');

?>
<div class="row">
    <?= $contenu ?>
</div>
<?php
if(isset($_GET['statut_produit']) && $_GET['statut_produit'] == 'ajoute' ){
    ?>
    <div class="modal fade" id="maModale" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">La réference a bien été ajoutée au panier!</h4>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-around">
                        <button class="btn btn-warning"><a class="lienCard" href="<?= URL . 'panier.php' ?>">Voir le panier</a></button>
                        <button class="btn btn-warning"><a class="lienCard" href="<?= URL ?>">Continuer mes achats</a></button>
                    </div>    
                </div>
            </div>
        </div>
    </div>

<?php
}


require_once('inc/footer.php');