<?php
//On commence par ce créer une fonction afin de savoir si l'utilisateur est nouveau ou déjà membre ou membre et admin:

function isConnected(){ // cette fonction permet de dire si l'utilisateur c'est connecté ou pas
    if( isset($_SESSION['membre']) ){
        return true;
    }
    else{
        return false;
    }
}



function isAdmin(){
    if( isconnected() && $_SESSION['membre']['statut'] == 1 ){ // permet d'appeler isconnected() pour vérifier s'il y a eu une connexion + dans la table membre si le statut = 1 .
        return true;
    }
    else{
        return false;
    }
}



// On va créer une fonction pour sécuriser les formulaires avec HTMLspecialchars ou HTMLentites pour éviter l'injection d'SQL ----- SANITIZE (rendre saint l'écriture)
function execRequete($req, $params = array() ){

    if( !empty($params) ){ 

        foreach( $params as $indice => $valeur ){ // pour tous les cas ou l'on a des parametres nous sécurisons ce parametre. (on pourra sécuriser un peu plus en cherchant le type de valeur du parametre (string, int, bool...) et sécurise avec bindparam.... )
            $params[$indice] = htmlspecialchars($valeur, ENT_NOQUOTES);
        }
    }
    global $pdo; // on globalise la requete de connexion à la BDD

    $r = $pdo->prepare($req);
    $r->execute($params);  // au pire l'ARRAY de $params est vide ou sinon il a été en partie sécuriser par la boucle foreach.

    if( !empty($r->errorInfo()[2]) ){ // on lance une condition en cas d'erreur
        die('Erreur rencontrée lors de la requète<br>Message de l\'erreur :'. $r->errorInfo()[2] );// permet d'arreter la fonction et d'afficher l'erreur
    }
    return $r;
}




//Prevoir des fonctions lier au Panier------------------------------------------

    //1-Création du panier

function createPanier(){
        if( !isset($_SESSION['panier']) ){//si le panier n'existe pas
                //on crée des tableau pour tous les champs du panier afin que tous les index corresponde au panier.
            $_SESSION['panier'] = array();
            $_SESSION['panier']['id_produit'] = array();
            $_SESSION['panier']['titre'] = array();
            $_SESSION['panier']['quantite'] = array();
            $_SESSION['panier']['prix'] = array();
        }
}

    //2-ajout du produit dans le panier

function addPanier($id_produit, $titre, $quantite, $prix){

        createPanier(); // si le panier n'existe pas il est crée  sinon c'est déjà bon.

        $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']);//Recherche la position (l'index) dans le tableau si un produit existe déjà

        if( $position_produit === false ){// si c'est un bool 0 "false donc pas de produit déjà ajouter 
            $_SESSION['panier']['id_produit'][] = $id_produit;
            $_SESSION['panier']['titre'][] = $titre;
            $_SESSION['panier']['quantite'][] = $quantite;
            $_SESSION['panier']['prix'][] = $prix;
        }
        else{// si le produit existe déja dans le panier
            $_SESSION['panier']['quantite'][$position_produit] += $quantite; //donc la on rajoute la quantité à partir de l'index du tableau
        }
}

    //3-calcul du montant du panier
function montantTotal(){
    $total= 0;//on commence par zéro et on crée une boucle pour calculer le prix sur les quantités
    for($i = 0 ; $i < count($_SESSION['panier']['id_produit']); $i++ ){ 
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i] ; //on compte tous les id_produit avec le i ET  on multipli $i le prix par le nombre de produit
        
    }
    return $total;
}
    //4-retrait d'un produit du panier
function retraitDuPanier($id_produit){
    
    $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']); //on cherche l'id produit dans le tableau
    if( $position_produit !== false){ // si le produit est bien dans le tableau

        array_splice($_SESSION['panier']['id_produit'], $position_produit, 1); //array_splice permet Efface et remplace une portion de tableau
        array_splice($_SESSION['panier']['titre'], $position_produit, 1);// arg1 :est le tableau
        array_splice($_SESSION['panier']['quantite'], $position_produit, 1); //arg2: est la rechere de l'id
        array_splice($_SESSION['panier']['prix'], $position_produit, 1);//arg3: est la longeur de la supression
        //il existe une arg 4: pour remplacer
    }

}

    //5-comptage du nombre de produit dans le panier

function nbArticle(){
    $nb='';

    if( isset($_SESSION['panier']['id_produit']) ){
        $nb = array_sum($_SESSION['panier']['quantite']); // on compte le nombre de quantite total

        if( $nb != 0 ){
            $nb='<span class="badge badge-warning">'. $nb . '</span>';
        }
        else{
            $nb='';
        }

    }
    return $nb;
}
