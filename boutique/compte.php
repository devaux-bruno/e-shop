<?php

require_once('inc/init.php');

if ( !isConnected() ){
    header('location:' . URL . 'connexion.php');
    exit();
}

$contenu .= '<h2 class="text-center">Bienvenue '.$_SESSION['membre']['pseudo'] . '</h2>';

if( isset($_SESSION['v']) && $_SESSION['v'] == 'ok' ){
    $contenu .= '<div class="alert alert-success">Le mot de passe a été modifié avec succès</div>';
    unset($_SESSION['v']);
}

// --------------------- COORDONNEES -------------------------------

if ( isset($_POST['validmodifcoord']) ){ // j'ai valider le formulaire lié aux coordonnées
    $nb_champs_vides = 0;
    foreach($_POST as $indice => $valeur){
        $_POST[$indice] = trim($valeur);
        if ( empty($valeur) )  $nb_champs_vides++;
    }
 
    if ( $nb_champs_vides == 0 ){

        // controles
        $nberrors=0;
        if( !preg_match('#^[0-9]{5}$#',$_POST['code_postal']) )
        {
            $contenu .= '<div class="alert alert-danger">Code postal invalide</div>';
            $nberrors++;
        }
        if( $nberrors == 0 ){
            // je mets à jour la BDD
            execRequete("UPDATE membre 
            SET civilite = :civilite,
                nom = :nom,
                prenom = :prenom,
                adresse = :adresse,
                code_postal = :code_postal,
                ville = :ville
            WHERE id_membre = :id_membre",array(
                'civilite' => $_POST['civilite'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'adresse' => $_POST['adresse'],
                'code_postal' => $_POST['code_postal'],
                'ville' => $_POST['ville'],
                'id_membre' => $_SESSION['membre']['id_membre']
            ));
            // je mets à jour la session
            $_SESSION['membre']['civilite'] = $_POST['civilite'];
            $_SESSION['membre']['nom'] = $_POST['nom'];
            $_SESSION['membre']['prenom'] = $_POST['prenom'];
            $_SESSION['membre']['adresse'] = $_POST['adresse'];
            $_SESSION['membre']['code_postal'] = $_POST['code_postal'];
            $_SESSION['membre']['ville'] = $_POST['ville'];
            header('location:' . URL . 'compte.php');
            exit();
        }
    }
    else
    {
        $contenu .= '<div class="alert alert-danger">Merci de remplir le(s) '.$nb_champs_vides.' champ(s) manquant(s)</div>';
    }
}
//------------------------------------------------------------------

if ( isset($_POST['validmodifmdp']) ){ // j'ai validé le formulaire de modif de mdp

    $nb_champs_vides = 0;
    foreach($_POST as $indice => $valeur){
        $_POST[$indice] = trim($valeur);
        if ( empty($valeur) )  $nb_champs_vides++;
    }
 
    if ( $nb_champs_vides == 0 ){
        $resultat = execRequete("SELECT * FROM membre WHERE mdp=:mdp AND id_membre=:id_membre",array(
            'mdp' => md5( $_POST['ancienmdp'] . CODEMDP ),
            'id_membre' => $_SESSION['membre']['id_membre']
        ));
        if ($resultat->rowCount() == 1){

            if ( $_POST['ancienmdp'] === $_POST['nouveaumdp']){
                $contenu .= '<div class="alert alert-danger">Le nouveau mot de passe ne peut être identique à l\'ancien mot de passe</div>';
            }
            else{
                if( $_POST['nouveaumdp'] === $_POST['confirmmdp'] )
                {
                    execRequete("UPDATE membre SET mdp = :mdp WHERE id_membre=:id_membre",array(
                        'mdp' => md5($_POST['nouveaumdp'] . CODEMDP),
                        'id_membre' => $_SESSION['membre']['id_membre']
                    ));
                    $_SESSION['v'] = 'ok';
                    header('location:' . URL . 'compte.php');
                    exit();
                }
                else{
                    $contenu .= '<div class="alert alert-danger">Le nouveau mot de passe et la confirmation ne concordent pas</div>';
                }
            }  
        }
        else{
            $contenu .= '<div class="alert alert-danger">Mot de passe incorrect</div>';
        }
 
    }
    else
    {
        $contenu .= '<div class="alert alert-danger">Merci de remplir tous les champs</div>';
    }

}


require_once('inc/header.php');
echo $contenu;
?>
<div class="row justify-content-center">
    <div class="col-sm-6">
        <h3>Coordonnées</h3>
    <?php
    if ( isset($_GET['action']) && $_GET['action']=='modifcoord' ) :
        $civilite = $_POST['civilite'] ?? $_SESSION['membre']['civilite'];
    ?>
    <form method="post" action="">
        <div class="form-row">
            <div class="form-group col-4">
                <label for="civilite">Civilité</label>
                <select name="civilite" class="form-control">
                    <option value="m" 
                    <?= ( $civilite == 'm') ? 'selected' : '' ?>>Monsieur</option>
                    <option value="f" <?= ( $civilite  == 'f') ? 'selected' : '' ?>>Madame</option>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="nom">Nom</label>
                <input type="text" name="nom" value="<?= $_POST['nom'] ??$_SESSION['membre']['nom'] ?>"  class="form-control">
            </div>
            <div class="form-group col-4">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" value="<?= $_POST['prenom'] ?? $_SESSION['membre']['prenom'] ?>"  class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse</label>
            <textarea class="form-control" id="adresse" name="adresse"><?= $_POST['adresse'] ?? $_SESSION['membre']['adresse']?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group col-3">
                <label for="code_postal">Code Postal</label>
                <input type="text" id="code_postal" name="code_postal" value="<?= $_POST['code_postal'] ?? $_SESSION['membre']['code_postal'] ?>"  class="form-control">
            </div>
            <div class="form-group col-9">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" value="<?= $_POST['ville'] ?? $_SESSION['membre']['ville'] ?>"  class="form-control">
            </div>
        </div>
        <input type="submit" name="validmodifcoord" value="Enregistrer" class="btn btn-primary">
        <a href="<?= URL . 'compte.php' ?>" class="btn btn-warning">Annuler</a>

    </form>
    <?php
    else :
    ?>
        <p><?= ( $_SESSION['membre']['civilite'] == 'm' ) ? 'Monsieur' : 'Madame' ?></p>
        <p>Nom : <?= $_SESSION['membre']['nom'] ?></p>
        <p>Prénom : <?= $_SESSION['membre']['prenom'] ?></p>
        <p>Adresse
            <address>
                <?=  $_SESSION['membre']['adresse'] ?><br>
                <?=  $_SESSION['membre']['code_postal'] . ' ' .  $_SESSION['membre']['ville'] ?>
            </address>
        </p>
        <a href="?action=modifcoord" class="btn btn-primary">Modifier mes coordonnées</a>
    <?php
    endif;
    ?>
    </div>
    <div class="col-sm-6">
        <h3>Identification</h3>
        <img src="<?= URL . 'photo/membres/' . $_SESSION['membre']['image_profil'] ?>" class="vignettephoto" alt="Photo de profil">
        <p>Pseudo : <?= $_SESSION['membre']['pseudo'] ?></p>
        <p>Email : <?= $_SESSION['membre']['email'] ?></p>
        <?php 
        if ( isset($_GET['action']) && $_GET['action'] == 'modifmdp') :
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="ancienmdp">Ancien mot de passe</label>
                <input type="password" name="ancienmdp" id="ancienmdp" class="form-control">
            </div>
            <div class="form-group">
                <label for="nouveaumdp">Nouveau mot de passe</label>
                <input type="password" name="nouveaumdp" id="nouveaumdp" class="form-control">
            </div>
            <div class="form-group">
                <label for="confirmmdp">Confirmation</label>
                <input type="password" name="confirmmdp" id="confirmmdp" class="form-control">
            </div>
            <input type="submit" name="validmodifmdp" value="Enregistrer" class="btn btn-primary">
            <a href="<?= URL . 'compte.php' ?>" class="btn btn-warning">Annuler</a>
        </form>
        <?php
        else :
        ?>
        <a href="?action=modifmdp" class="btn btn-primary">Modifier mon mot de passe</a>
        <?php
        endif;
        ?>
    </div>

</div>


<?php
require_once('inc/footer.php');