<?php
require_once('../inc/init.php');

// 1. verifier que j'ai le droit d'acceder à cette page
if ( !isAdmin() ){
    header('location:' . URL . 'connexion.php');
    exit();
}

// modif de commande

if ( !empty($_POST) ){

    $resultat = execRequete("SELECT etat FROM commande WHERE id_commande=:id_commande",array('id_commande'=>$_POST['id_commande']));
    if ($resultat->rowCount() > 0){
        $commande = $resultat->fetch();
        $etatactuel = $commande['etat'];
        
        if (  $etatactuel != $_POST['newetat'] ){
            execRequete("UPDATE commande SET etat = :newetat WHERE id_commande=:id_commande",array(
            'newetat' => $_POST['newetat'],
            'id_commande'=>$_POST['id_commande']
            ));
            $contenu .= '<div class="alert alert-success">La commande '.$_POST['id_commande'] .' est passée de l\'état <strong>'.$etatactuel.'</strong> à l\'état <strong>'.$_POST['newetat'].'</strong></div>';
        }
    }
}


$commandes = execRequete("
SELECT *,c.id_commande as nbcmd, p.id_produit  as idprod, d.prix as prix, m.id_membre as idmembre 
FROM commande c, details_commande d, produit p, membre m
WHERE d.id_commande = c.id_commande
AND d.id_produit = p.id_produit
AND c.id_membre = m.id_membre
ORDER BY c.id_membre,  c.etat ASC, c.date_enregistrement DESC,c.id_commande");

if ( $commandes->rowCount() > 0){

    $contenu .= '<table class="table table-bordered table-striped">';

    $oldcmd=0;
    $oldusr=0;
    while( $cmd = $commandes->fetch() ){

         // entete de commande à n'écrire qu'une fois par commande
         if ($oldusr != $cmd['idmembre']){

            $civilite = ( $cmd['civilite'] == 'm' ) ? 'Monsieur' :'Madame';

            $contenu .= '<tr class="bg-warning">
            <th colspan="3"><i class="fas fa-user"></i> '.$civilite.' '.strtoupper($cmd['nom']).' '.ucfirst($cmd['prenom']).'</th>
            <th colspan="3" ><i class="far fa-address-card"></i> '.$cmd['adresse'].' '.$cmd['code_postal'].' '.$cmd['ville'].'</th>
            <th colspan="3"><i class="far fa-envelope"></i> <a href="mailto:'.$cmd['email'].'">'.$cmd['email'].'</a></th> 
            </tr>';  
        }

        // Objet date pour la reformater 
        $datecmd = new DateTime($cmd['date_enregistrement']);

        switch($cmd['etat']){
            case 'en cours de traitement' : $class="bg-danger"; break;
            case 'envoyée' : $class="bg-primary"; break;
            case 'livrée' : $class="bg-success"; break;
        }

        // entete de commande à n'écrire qu'une fois par commande
        if ($oldcmd != $cmd['nbcmd']){
            $contenu .= '<tr class="'. $class .'">
            <th>Commande&nbsp;'.$cmd['nbcmd'].'</th>
            <th colspan="2">Date : '.$datecmd->format('d/m/Y à H:i:s').'</th>
            <th colspan="3" > 
            <form action="" method="post" class="form-inline"> 
            <input type="hidden" name="id_commande" value="'.$cmd['nbcmd'].'">
            <label for="etat">Etat</label> 
                <select name="newetat" class="form-control mx-3 ">
                    <option '.( ($cmd['etat'] == 'en cours de traitement') ? 'selected':'' ).'>en cours de traitement</option>
                    <option '.( ($cmd['etat'] == 'envoyée') ? 'selected':'' ).'>envoyée</option>
                    <option '.( ($cmd['etat'] == 'livrée') ? 'selected':'' ).'>livrée</option>
                </select>
                <input type="submit" value="valider" class="btn btn-primary mx-3">
            </form>
            </th>
            <th colspan="3">Montant '.$cmd['montant'].'€</th> 
            </tr>';  
        }
        // Lignes de détail
        $contenu .= '<tr>
        <td>'.$cmd['reference'].'</td>
        <td><a href="'.URL . 'fiche_produit.php?id_produit='.$cmd['idprod'].'">'.$cmd['titre'].'</a></td>
        <td>'.$cmd['description'].'</td>
        <td> Taille : '.$cmd['taille'].'</td>
        <td> '.$cmd['categorie'].'</td>
        <td><img src="'.URL . 'photo/' . $cmd['photo'].'" alt="" class="vignettecommande"></td>
        <td>'. $cmd['prix'] .'€</td>
        <td> ' . $cmd['quantite'] . '</td>
        <td>'.$cmd['prix']*$cmd['quantite'].'€</td>
        </tr>';

        // je mémorise la commande
        $oldcmd=$cmd['nbcmd'];
        // je mémorise le membre
        $oldusr=$cmd['idmembre'];
    }

    $contenu .= '</table>';
}
else  {
    $contenu .= '<div class="alert alert-info">Il n\'y a pas encore de commandes</div>';
}




require_once('../inc/header.php');
?>
<h2>Gestion des commandes</h2>
<?= $contenu ?>
<?php
require_once('../inc/footer.php');
