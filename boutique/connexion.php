<?php

require_once('inc/init.php');

//deconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
    session_destroy(); //s'éxécute toujours à la fin du script
}

//Internaute déjà connecter
if( isConnected()){
    header('location:' . URL . 'compte.php'); //si connecter on le renvoi sur ca page de compte
    exit(); //Bonne pratique après un header afin d'être sur de ne pas lire la suite du script
}

//le formulaire est posté
if( !empty($_POST)){

    $resultat = execRequete("SELECT * FROM membre WHERE pseudo=:pseudo AND mdp=:mdp", array(
        'pseudo' => $_POST['pseudo'],
        'mdp' => md5($_POST['mdp'] . CODEMDP)
    ));

    if($resultat->rowCount() != 0){
        $membre = $resultat->fetch(); // fonction FETCH_MODE dans le dossier init.php donc pas besion de faire plus.

        $_SESSION['membre'] = $membre; 
        unset( $_SESSION['membre']['mdp'] ); // on enleve juste le mdp de la SESSION membre un fois contecte
        header('location:' . URL . 'compte.php');
        exit();

    }
    else{
        $contenu .= '<div class="alert alert-danger"> Erreur sur les identifiants ou utilisateur introuvable !  </div>';
    }
}



require_once('inc/header.php');

echo $contenu;
?>

<div class="row">
    <div class="col-3"></div>
    <div class="col-6">
        <h3>Veillez renseigner vos identifiants</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label for="pseudo">Pseudo : </label>
                <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $_POST['pseudo'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe : </label>
                <input type="password" name="mdp" id="mdp" class="form-control"  value="">
            </div>
            <hr>
            <input type="submit" class="form-control btn btn-warning" value="Se connnecter!">
        </form>
    </div>
    <div class="col-3"></div>
</div>

<?php
require_once('inc/footer.php');