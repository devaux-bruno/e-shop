<?php   
require_once('inc/init.php');

//ajout d'un produit dans le panier
if( isset($_POST['ajout_panier']) ){

    $resultat = execRequete("SELECT titre,prix FROM produit WHERE id_produit=:id_produit", array('id_produit' => $_POST['id_produit']) );

    if( $resultat->rowCount() > 0 ){
        $produit = $resultat->fetch();
        addPanier($_POST['id_produit'], $produit['titre'], $_POST['quantite'], $produit['prix']);
    }
    header('location:fiche_produit.php?id_produit='.$_POST['id_produit'].'&statut_produit=ajoute');
    exit();
}

//vider le panier
if( isset($_GET['action']) && $_GET['action'] == 'vider' ){
    unset($_SESSION['panier']);
}
//suppression d'une ligne de panier
if( isset($_GET['action']) && $_GET['action'] == 'suppr' && isset($_GET['id_produit']) ){
    retraitDuPanier($_GET['id_produit']);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Validation du panier (=> transformation en commande)
if( isset($_GET['action']) && $_GET['action'] == 'valider'){

    $feu_rouge = 0; // on crée un indicateur sir il reste a 0 on peut valider la commande s'il passe a 1 on ne peut pas

    //Controle du panier avant commande(les mêmes controles que vu sur le panier)
    for( $i = 0 ; $i < count($_SESSION['panier']['id_produit']); $i++){

        $resultat = execRequete("SELECT * FROM produit WHERE id_produit=:id_produit", array('id_produit' => $_SESSION['panier']['id_produit'][$i])); 
        $produit = $resultat->fetch();
        $message = '';

//5 articles maximum
        if( $_SESSION['panier']['quantite'][$i] > 5){
            $feu_rouge = 1;
        }

//on compare les quantites du panier utilisateur au stock machine
        if( $produit['stock'] < $_SESSION['panier']['quantite'][$i] ){// si demande superieur au stock
            $feu_rouge = 1;
        }
        if( $_SESSION['panier']['prix'][$i] != $produit['prix'] ){
            $feu_rouge = 1;
        }
    } 
    if( $feu_rouge == 0){// si aucun feu rouge on peut valider la commande

        $id_membre = $_SESSION['membre']['id_membre'];
        $montant_total = montantTotal()*1.20;

        execRequete("INSERT INTO commande VALUES (NULL, :id_membre, :montant, NOW(), 'en cours de traitement')", array(
            'id_membre' => $id_membre,
            'montant' => $montant_total
        ));


////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
/////////////////          De Panier à commande           //////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
    $id_commande = $pdo->lastInsertId(); // on récupère la commande que l'on vien de créer

        for( $i=0; $i < count( $_SESSION['panier']['id_produit']); $i++ ){//1.on boucle sur le panier   

            $id_produit = $_SESSION['panier']['id_produit'][$i];
            $quantite = $_SESSION['panier']['quantite'][$i];
            $prix = $_SESSION['panier']['prix'][$i];
            //2.on alimente detail commande
            execRequete("INSERT INTO details_commande VALUES (NULL, :id_commande, :id_produit, :quantite, :prix)", array(
                'id_commande' => $id_commande,
                'id_produit' => $id_produit,
                'quantite' => $quantite,
                'prix' => $prix
            ));
            //3.on décrémente le stock
            execRequete("UPDATE produit SET stock = stock - :quantite WHERE id_produit=:id_produit", array(
                'quantite' => $quantite,
                'id_produit' =>$id_produit
            ));
        }

        //4.après insertion, on détruit le panier
                unset($_SESSION['panier']);
//-----------------------------------------------------------------------  
/*        //5.envoyer par mail la confirmation

        ini_set('SMTP', 'smtp.sfr.fr');//changer ou initialiser le SMTP de messagerie
        //ini_set('sendmail_from', 'noreply@maboutique.fr');//pour mettre l'adresse d'ou vont être envoyer les mails (ex: noreply@blabla.fr) ou faire un header.

        $rc = "\r\n"; //bien le faire entre ""
        $headers = 'From: "MABOUTIQUE" <noreply@maboutique.fr> '.$rc; // pour donner un nom a notre mail
        $headers .= 'Reply-to : MaBoutique <contat@maboutique.fr> '.$rc;  //pour faire une reponse
        $headers .= 'MIME-version: 1.0'.$rc;// pour faire au format HTML et apres on fait le type de mail
        $headers .= 'Content-Type: text/html; charset="utf-8'.$rc;// ou multipart/mixte pour les piece jointe

        $destinataire = $SESSION['menbre']['email'];
        $objet = 'Confirmation de commande';

        $message .= 'Merci pour votre commande. '.$rc.'Votre numero de suivi est le' . $id_commande . ' .';// on peut ensuite faire des balise HTML
        mail($destinataire, $objet, $message, $headers);
        
*/    
//-----------------------------------------------------------------------  
        //6.on peu aussi le renvoyer sur la page mes commandes   .
        
        header('location:' . URL . 'commandes.php');
        exit();


//-----------------------------------------------------------------------  

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////        
    }
    else{
        $contenu .= '<div class="alert alert-danger">La commande n\'a pas été validée en raison de modification concernant le stock ou les prix des produits de votre panier!<br>Merci de valider à nouveau votre panier après vérification!</div>';
    }

}




////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
require_once('inc/header.php');
echo $contenu;
?>
<h2 class="text-center">Votre Panier</h2>
<?php
if( empty($_SESSION['panier']['id_produit']) ){
    ?>
    <div class="alert alert-info">Votre Panier est vide!</div>
    <?php
}
else{
    ?>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Référence</th>
            <th>Titre</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php
        //Controle et réécriture éventuelle du panier
        for( $i = 0 ; $i < count($_SESSION['panier']['id_produit']); $i++){

            $resultat = execRequete("SELECT * FROM produit WHERE id_produit=:id_produit", array('id_produit' => $_SESSION['panier']['id_produit'][$i])); 
            $produit = $resultat->fetch();
            $message = '';

//5 articles maximum
            if( $_SESSION['panier']['quantite'][$i] > 5){
                $_SESSION['panier']['quantite'][$i] = 5;
            }

//on compare les quantites du panier utilisateur au stock machine
            if( $produit['stock'] < $_SESSION['panier']['quantite'][$i] ){// si demande superieur au stock

                $_SESSION['panier']['quantite'][$i] = $produit['stock']; //on ajuste la demande par rapport au stock
                $message .= '<div class="alert alert-info">La quantité a été réajustée en fonction du stock restant et dans la limite de 5 maximum par article!</div>';
            }
            if( $_SESSION['panier']['prix'][$i] != $produit['prix'] ){
                $_SESSION['panier']['prix'][$i] = $produit['prix'];
                $message .= '<div class="alert alert-info">Attention! Le prix a été réactualisé</div>';
            }
        
        ?>
        <tr>
            <td><a class="lienCard" href="fiche_produit.php?id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>"><?= $produit['reference'] ?></a></td>
            <td><?= $produit['titre'] . $message ?></td>
            <td><?= $_SESSION['panier']['quantite'][$i] ?></td>
            <td><?= $_SESSION['panier']['prix'][$i] ?></td>
            <td><?= $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i] ?>&euro;</td>
            <td><a href="?action=suppr&id_produit=<?=$_SESSION['panier']['id_produit'][$i] ?>">&#128465;</a></td>
        </tr>
        <?php


        }
        ?>
        <tr class="info">
            <th colspan="4" class="text-right">Total HTC: </th>
            <th colspan="2"><?= montantTotal() ?>&euro;</th>
        </tr>
        <tr class="info">
            <th colspan="4" class="text-right">Total TTC (TVA 20%): </th>
            <th colspan="2"><?= montantTotal()*1.20 ?>&euro;</th>
        </tr>
        <?php 
        if( isConnected() ){
        ?>
        <tr>
            <td colspan="6" class="text-center">
                <button class="btn btn-warning"><a class="lienCard" href="?action=valider">Valider votre panier</a></button>
            </td>
        </tr>
        <?php
        }
        else{
        ?>
        <tr>
            <td colspan="6" class="text-center">
                Veillez-vous <a href="<?= URL . 'inscription.php' ?>">inscrire</a> ou vous <a href="<?= URL . 'connexion.php' ?>"> connecter </a> afin de valider votre panier.
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="6" class="text-center">
                <button class="btn btn-danger"><a class="lienCard" href="?action=vider">Vider le Panier</a></button>
            </td>
        </tr>
    </table>
<?php 
}
require_once('inc/footer.php');
