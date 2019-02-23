<?php

require_once('../inc/init.php');
//1.Vérifier que l'on est admin
if( !isAdmin() ){
    header('location:' . URL . 'connexion.php');
    exit();
}

//6. suppression

if( isset($_GET['action']) && $_GET['action'] == 'suppr' && isset($_GET['id_produit']) ){
//on recupère ensuite la photo afin de supprimer les 2 en même temps

    $resultat = execRequete("SELECT photo FROM produit WHERE id_produit = :id_produit", array( 'id_produit' => $_GET['id_produit']) );
    

    if( $resultat->rowCount() > 0 ){ // on vérifie si ya le produit

        $produit = $resultat->fetch();
        $fichier_a_supp = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/produits/' . $produit['photo'];
        if( !empty($produit['photo']) && file_exists($fichier_a_supp) ){ // on verifie si ya une photo (si c'est pas vide et qu'li existe)
    //Suppression de la photo
            unlink( $fichier_a_supp );
        }
    //supprimer le produit
        execRequete("DELETE FROM produit WHERE id_produit = :id_produit", array('id_produit' => $_GET['id_produit']) );
        $contenu .= '<div class="alert alert-success">Le produit a bien été supprimer!</div>';
        $_GET['action'] = 'affichage';
        
    }

}


$nbr_champs_vides = '';

//2.verification avant enregistrement-------------------------------------------------------


if( !empty($_POST) ){
    //var_dump($_POST);
    //var_dump( $_FILES);
    $photo_bdd = $_POST['photo_courante'] ?? ''; //nouvelle syntaxe, $photo_bdd vaux soit $_POST['photo_courante] sinon 'rien'
    $errors = 0;
    

    if( !empty($_FILES['photo']['name']) ){

        $photo_bdd = $_POST['reference'] . '-' . $_FILES['photo']['name']; // on crée un nom de photo à rentrer dans la bdd
        $dossier_photo = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/produits/'; // DOCUMENT ROOT est une superglobal pour le chemin physique à la racine du serveur
        $ext_auto = ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'];

        if( in_array($_FILES['photo']['type'], $ext_auto )){
            move_uploaded_file($_FILES['photo']['tmp_name'], $dossier_photo . $photo_bdd); // on demande de copier la photo dans le fichier temporaire sur le chemin du dossier +concaténe avec le nom du fichier.
        } 
        else{
            $contenu .= '<div class="alert alert-danger">La photo n\'a pas été enregistrer!<br>Format acceptés: jpeg, jpg, png, gif</div>';
            $errors++;
        }
    }
    // D'autres contrôles sur la saisi----------------------------------------



    //2.enregistrement--------------------------------------------
    if( $errors == 0){
        extract($_POST); // pour créer des variables directes
        //on procède à l'enregistrement
        execRequete("REPLACE INTO produit VALUES (:id_produit, :reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)", array(
            'id_produit' => $id_produit,
            'reference' => $reference,
            'categorie' => $categorie,
            'titre' => $titre,
            'description' => $description,
            'couleur' => $couleur,
            'taille' => $taille,
            'public' => $public,
            'photo' => $photo_bdd,
            'prix' => $prix,
            'stock' => $stock
        ));
        $contenu .= '<div class="alert alert-success">Le produit a été enregistré!</div>';
        $_GET['action'] = 'affichage'; //pour forcé le menu à l'affichage 
    }

}

//4.onglet affichage et ajout---------------------------------------

$active_affichage = ( !isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage') ) ? 'active' : '';
$active_ajout = ( isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification' ) ) ? 'active' : '';

$contenu .='
<ul class="nav nav-tabs nav-justified">
  <li class="nav-item">
    <a class="nav-link ' . $active_affichage . ' " href="?action=affichage">Affichage des produits</a>
  </li>
  <li class="nav-item">
    <a class="nav-link ' . $active_ajout . '" href="?action=ajout">Ajout d\'un produit</a>
  </li>
</ul>
';


//5.Affichage des produits-------------------------------------------

if( !isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'affichage') ){

    $resultat = execRequete("SELECT * FROM produit");

    if( $resultat -> rowCount() == 0 ){
        $contenu .= '<div class="alert alert-warning">Il n\'y a pas encore de produit enregistrer!</div>';
    }
    else{
        $contenu .= '<p> Il y a ' . $resultat->rowCount() . ' produit(s) dans la boutique.</p>
        <table class="table table-bordered table-striped">
        <tr>
        ';
        // construction des colonnes
        for( $i = 0 ; $i < $resultat->columnCount(); $i++){
            $colonne = $resultat->getColumnMeta($i);
            $contenu .= '<th>' . $colonne['name'] . '</th>';
        }
        $contenu .= '<th colspan="2">Actions</th></tr>';

        while( $ligne = $resultat->fetch() ){
            $contenu .= '<tr>';

            foreach( $ligne as $indice => $valeur ){
                if($indice == 'photo'){
                    $valeur = '<img class="img-fluid" src="'. URL . 'photo/produits/' . $valeur .'" alt="'. $ligne['titre'] .'">';
                }
                $contenu .= '<td>' . $valeur . '</td>';
            }
            $contenu .= '<td>
                <a href="?action=modification&id_produit='.$ligne['id_produit'].'">&#9998;</a>
            </td>
            <td>
                <a class="confsup" href="?action=suppr&id_produit='.$ligne['id_produit'].'">&#128465;</a>
            </td>';
        }

        $contenu .= '</table>';
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


require_once('../inc/header.php');
echo $contenu;

//3.formulaire
if( isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification') ) :


//7. chargement d'un produit en modif
if( isset($_GET['id_produit']) ){
    $resultat = execRequete("SELECT * FROM produit WHERE id_produit = :id_produit", array('id_produit' => $_GET['id_produit']));
    $produit_courant = $resultat->fetch();

}

    ?>
    <h3>Formulairez d'ajout ou de modification de produit</h3>
    <!-- enctype permet d'alimenter la superglobal $_FILE pour upload des fichiers -->
    <form action="" method="POST" enctype="multipart/form-data">
        <fieldset>
            <input type="hidden" name="id_produit" value="<?= $_POST['id_produit'] ?? $produit_courant['id_produit'] ?? 0 ?>">
            <!-- on nomme 3 conditions: soit 0 pour la création d'un produit, il faut bien suivre l'ordre des priorité
            1- Si on a un post avec un id    2- est on en mode modif avec l'id?  3 - mode creation avec 0 -->


            <div class="form-row">
                <div class="form-group col-6">
                    <label for="reference">Référence : </label>
                        <input type="text" name="reference" id="reference" class="form-control" value="<?= $_POST['reference'] ?? $produit_courant['reference'] ?? '' ?>">
                </div>
                <div class="form-group col-6">
                    <label for="categorie">Catégorie : </label>
                    <input type="text" name="categorie" id="categorie" class="form-control" value="<?= $_POST['categorie'] ?? $produit_courant['categorie'] ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="titre">Titre : </label>
                <input type="text" name="titre" id="titre" class="form-control" value="<?= $_POST['titre'] ?? $produit_courant['titre'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="description">Description : </label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="5"><?= $_POST['description'] ?? $produit_courant['description'] ?? '' ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-4">
                    <label for="couleur">Couleur : </label>
                    <input type="text" name="couleur" id="couleur" class="form-control" value="<?= $_POST['couleur'] ?? $produit_courant['couleur'] ?? '' ?>">
                </div>
                <div class="form-group col-4">
                    <label for="taille">Taille : </label>
                    <select class="form-control" name="taille" id="taille">
                        <option value="S" <?= ( (!isset($_POST['taille']) && !isset($produit_courant['taille']) ) || (isset($_POST['taille']) &&  $_POST['taille'] == 'S') || (isset($produit_courant['taille']) && $produit_courant['taille'] == 'S') ) ? 'selected' : '' ?> >S</option>
                        <option value="M" <?= ( (isset($_POST['taille']) &&  $_POST['taille'] == 'M') || (isset($produit_courant['taille']) && $produit_courant['taille'] == 'M') ) ? 'selected' : '' ?> >M</option>
                        <option value="L" <?= ((isset($_POST['taille']) &&  $_POST['taille'] == 'L') || (isset($produit_courant['taille']) && $produit_courant['taille'] == 'L') ) ? 'selected' : '' ?> >L</option>
                        <option value="XL" <?= ((isset($_POST['taille']) &&  $_POST['taille'] == 'XL') || (isset($produit_courant['taille']) && $produit_courant['taille'] == 'XL') ) ? 'selected' : '' ?> >XL</option>
                    </select>      
                </div>
                <div class="form-group col-4">
                    <label for="public">Public : </label>
                    <select class="form-control" name="public" id="public">
                        <option value="m" <?= ( (!isset($_POST['public']) && !isset($produit_courant['public'])) || (isset($_POST['public']) && $_POST['public'] == 'm') || (isset($produit_courant['public']) && $produit_courant['public'] == 'm')  ) ? 'selected' : '' ?> >Homme</option>
                        <option value="f" <?= ((isset($_POST['public']) && $_POST['public'] == 'f') || (isset($produit_courant['public']) && $produit_courant['public'] == 'f')) ? 'selected' : '' ?> >Femme</option>
                        <option value="mixte" <?= ((isset($_POST['public']) && $_POST['public'] == 'mixte') || (isset($produit_courant['public']) && $produit_courant['public'] == 'mixte')) ? 'selected' : '' ?> >Mixte</option>
                    </select>      
                </div>
            </div>

            <div class="form-group">
                <label for="photo">Photo : </label>
                <input class="form-control" type="file" name="photo" id="photo">
                <?php
                    if( !empty($produit_courant['photo']) ){
                        ?>
                        <em>Vous pouvez uploader une nouvelle photo.<br></em>
                        <img class="imgModif ml-5 pl-5 mt-4 mb-4" src="<?= URL . 'photo/produits/' . $produit_courant['photo'] ?>" alt="Photo produit">
                        <input type="hidden" name="photo_courante" value="<?= $produit_courant['photo'] ?>">
                        <?php
                    }
                ?>
            </div>


            <div class="form-row justify-content-around">
                <div class="form-group col-3">
                    <label for="prix">Prix : </label>
                    <div class="input-group md-3">
                        <input type="number" name="prix" id="prix" class="form-control" value="<?= $_POST['prix'] ?? $produit_courant['prix'] ?? '' ?>">
                        <div class="input-group-append">
                            <span class="input-group-text">&euro;</span>
                        </div>    
                    </div>
                </div>
                <div class="form-group col-3">
                    <label for="stock">Nombre en stock : </label>
                    <input type="number" name="stock" id="stock" class="form-control" value="<?= $_POST['stock'] ?? $produit_courant['stock'] ?? '' ?>">
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-4"></div>
                <div class="col-4 justify-content-center">
                    <input type="submit" class="form-control btn btn-warning justify-content-center" value="Enregistrer">
                </div>
                <div class="col-4"></div>
            </div>
        </fieldset>
    </form>
<?php
endif;


require_once('../inc/footer.php');