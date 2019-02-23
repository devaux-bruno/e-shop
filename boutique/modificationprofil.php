<?php   
require_once('inc/init.php');

if( !isConnected() || !isAdmin()){
    header('location:' . URL . 'connexion.php'); 
    exit();
}
$contenu = '';

//------verif modification --------------------

if( !empty($_POST) ){
    //var_dump($_POST);
    //var_dump( $_FILES);
    $image_bdd = $_POST['image_courante'] ?? $_SESSION['membre']['image_profil']; //nouvelle syntaxe, $photo_bdd vaux soit $_POST['photo_courante] sinon 'rien'
    $nb_champs_vides = 0;

    foreach($_POST as $valeur){

        if( empty($valeur) ) $nb_champs_vides++; //on est pas obliger de mettre des accolades si qu'une seul instruction
    }
    if( $nb_champs_vides > 0){
        $contenu .= '<div class="alert alert-danger"> Il manque <b>'. $nb_champs_vides . '</b> information(s)</div>';
    }

    //////a continuer
    
    if( !empty($_FILES['image_courante']['name']) ){

        $image_bdd = 'image_profil-' . $_POST['pseudo']; // on crée un nom de photo à rentrer dans la bdd
        $dossier_image = $_SERVER['DOCUMENT_ROOT'] . URL . 'photo/membres/'; // DOCUMENT ROOT est une superglobal pour le chemin physique à la racine du serveur
        $ext_auto = ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'];

        if( in_array($_FILES['image_profil']['type'], $ext_auto ) ||  $_SESSION['membre']['image_profil']){
            move_uploaded_file($_FILES['image_profil']['tmp_name'], $dossier_image . $image_bdd); // on demande de copier la photo dans le fichier temporaire sur le chemin du dossier +concaténe avec le nom du fichier.
        } 
        else{
            $contenu .= '<div class="alert alert-danger">La photo n\'a pas été Modifier!<br>Format acceptés: jpeg, jpg, png, gif</div>';
        }
    }


    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]{3,20}$#' , $_POST['pseudo']);  //regex101 - site pour vérifier les expressions regulieres

    if( !$verif_caractere){
            $contenu .= '<div class="alert alert-danger"> Le pseudo doit comporter entre 3 et 20 caractères!<br> Caractères autorisés (a à z, A à Z , 0 à 9 et les caractères "." "_" "-"  ! </div>';
    }

    //Verif code postal
        $verif_cp = preg_match('#^[0-9]{5}$#' , $_POST['code_postal']);    
            
        if( !$verif_cp){
            $contenu .= '<div class="alert alert-danger"> Votre code postal n\'est pas valide! </div>';
        }
            
    //verif mail
        if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
            $contenu .= '<div class="alert alert-danger"> L\'email n\'est pas valide! </div>';
        } 
    
    //verif nom
        $verif_nom = preg_match('#^[a-zA-Z- ]{2,20}$#' , $_POST['nom']);    
            
        if( !$verif_nom){
            $contenu .= '<div class="alert alert-danger">Votre nom doit comporter que 2 à 20 caractères de "a" à "z" et éventuellement "-"! </div>';
        }

    //vérif prenom
        
        $verif_prenom = preg_match('#^[a-zA-Z- ]{2,20}$#' , $_POST['prenom']);    
            
        if( !$verif_prenom){
            $contenu .= '<div class="alert alert-danger">Votre prénom doit comporter que 2 à 20 caractères de "a" à "z" et éventuellement "-" ! </div>';
        }

//---------------------------------------
        if( empty($contenu)  ){// si il n'y a pas de message d'erreur alors :

            //vérifier l'unicité du pseudo
            $membre = execRequete("SELECT * FROM membre WHERE pseudo=:pseudo AND id_membre!=:id_membre", array('pseudo'=>$_POST['pseudo'], 'id_membre'=>$_SESSION['membre']['id_membre']) );

            if( $membre->rowCount() > 0){
                $contenu .= '<div class="alert alert-danger"> Pseudo indisponible, merci d\'en choisir un autre! </div>';
            }
            else{
                extract($_POST);// permet de générer des variable avec le nom des index (évite d'écrire $_POST['pseudo'] => $pseudo)


                execRequete("REPLACE INTO membre VALUES (:id_membre, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, :statut, :image_profil)", array(
                    'id_membre' => $_SESSION['membre']['id_membre'],
                    'pseudo' => $pseudo,
                    'mdp' => md5($mdp. CODEMDP), // on concatène un mot a nous en plus du md5 afin de sécuriser encore plus la methode md5. presque  impossible à décoder sans notre mot magique. ATTENTION on ne pourra pas faire de modification après pour les membre déjà enregistré.
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'civilite' => $civilite,
                    'ville' => $ville,
                    'code_postal' => $code_postal,
                    'adresse' => $adresse,
                    'statut' => $_SESSION['membre']['statut'],
                    'image_profil' => $image_bdd
                ));
                $contenu .= '<div class="alert alert-success"> Bravo, vous êtes inscrit sur note site! <a href="'. URL .'connexion.php">Cliquer ici pour vous connecter</a></div>';
                $inscription = true;
            }
        }



}


require_once('inc/header.php');
echo $contenu ;


if( isConnected() ){//Formulaire de modification affiché si l'inscription n'a pas abouti
    
    if( isset($_SESSION['id_membre']) ){
        $resultat = execRequete("SELECT * FROM membre WHERE id_membre = :id_membre", array('id_membre' => $_GET['id_membre']));
        $membre_courant = $resultat->fetch();
    
    }
    
    ?>
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6">
        <h3>Veillez renseigner le formulaire de modification</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <fieldset>
                    <legend>Identifiants</legend>

                    <div class="form-group">
                        <label for="pseudo">Pseudo : </label>
                        <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $_SESSION['membre']['pseudo'] ?? $membre_courant['pseudo'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="mdp">Mot de passe : </label>
                        <input type="password" name="mdp" id="mdp" class="form-control"  value="">
                    </div>
                    <div class="form-group">
                        <label for="email">Email : </label>
                        <input type="email" name="email" id="email" class="form-control"  value="<?= $_SESSION['membre']['email'] ?? $membre_courant['email'] ?? '' ?>">
                    </div>
                </fieldset> 

                <fieldset>
                    <legend>Coordonnées</legend>

                    <div class="form-row justify-content-around">
                        <div class="form-group col-6">
                            <label for="nom">Nom : </label>
                            <input type="text" name="nom" id="nom" class="form-control" value="<?= $_SESSION['membre']['nom'] ?? $membre_courant['nom'] ?? '' ?>">
                        </div>
                        <div class="form-group col-6">
                            <label for="prenom">Prénom : </label>
                            <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $_SESSION['membre']['prenom'] ?? $membre_courant['prenom'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="civilite" id="radiom" value="m" <?= (!isset($_POST['civilite']) || (isset($_SESSION['membre']['civilite']) && $_SESSION['membre']['civilite'] == 'm') ) ? 'checked' : '' ?>>
                        <label for="radiom">Monsieur</label>
                        <!-- Pour garder en memoire le bouton radio-->
                        <input type="radio" class="form-check-input " name="civilite" id="radiof" value="f" <?= ( (isset($_SESSION['membre']['civilite']) && $_SESSION['membre']['civilite'] == 'f') ) ? 'checked' : '' ?>>
                        <label for="radiof">Madame</label>
                    </div>
                    <div class="form-row justify-content-around">
                        <div class="form-group col-8">
                            <label for="ville">Ville : </label>
                            <input type="text" name="ville" id="ville" class="form-control" value="<?= $_SESSION['membre']['ville'] ?? $membre_courant['ville'] ?? '' ?>">
                        </div>
                        <div class="form-group col-4">
                            <label for="code_postal">Code Postal : </label>
                            <input type="text" name="code_postal" id="code_postal" class="form-control" value="<?= $_SESSION['membre']['code_postal'] ?? $membre_courant['code_postal'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="adresse">Adresse : </label>
                        <input type="text" name="adresse" id="adresse" class="form-control" value="<?= $_SESSION['membre']['adresse'] ?? $membre_courant['adresse'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="image_profil">Photo de profil : </label>
                        <input class="form-control" type="file" name="image_profil" id="image_profil">
                        <?php
                            if( !empty($membre_courant['image_profil']) ){
                                ?>
                                <em>Vous pouvez uploader une nouvelle photo.<br></em>
                                <img class="imgModif ml-5 pl-5 mt-4 mb-4" src="<?= URL . 'photo/produits/' . $membre_courant['image_profil'] ?>" alt="image_profil produit">
                                <input type="hidden" name="image_courante" value="<?= $produit_courant['image_profil'] ?>">
                                <?php
                            }
                        ?>
                    </div>

                </fieldset> 
                <hr>
                <input type="submit" class="form-control btn btn-warning" value="Valider les modifications">
            </form>
        </div>
        <div class="col-3"></div>
    </div>
    <?php

}
else{

}

require_once('inc/footer.php');