<?php
require_once('inc/init.php');

$commandes = execRequete("
SELECT *,c.id_commande as nbcmd, p.id_produit  as idprod, d.prix as prix 
FROM commande c, details_commande d, produit p
WHERE d.id_commande = c.id_commande
AND d.id_produit = p.id_produit
AND c.id_membre = :id_membre
ORDER BY c.date_enregistrement DESC",array('id_membre' => $_SESSION['membre']['id_membre']));

if ( $commandes->rowCount() >0){

    $contenu .= '<table class="table table-bordered table-striped">';

    $oldcmd=0;
    while($cmd = $commandes->fetch() ){

        // Objet date pour la reformater 
        $datecmd = new DateTime($cmd['date_enregistrement']);
        // entete de commande à n'écrire qu'une fois par commande
        if ($oldcmd != $cmd['nbcmd']){
            $contenu .= '<tr class="thead-dark">
            <th>Commande&nbsp;'.$cmd['nbcmd'].'</th>
            <th colspan="2">Date : '.$datecmd->format('d/m/Y à H:i:s').'</th>
            <th colspan="3" >Etat : '.$cmd['etat'].'</th>
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
        <td><img src="'.URL . 'photo/produits/' . $cmd['photo'].'" alt="" class="vignettecommande"></td>
        <td>'. $cmd['prix'] .'€</td>
        <td> ' . $cmd['quantite'] . '</td>
        <td>'.$cmd['prix']*$cmd['quantite'].'€</td>
        </tr>';

        // je mémorise la commande
        $oldcmd=$cmd['nbcmd'];
    }

    $contenu .= '</table>';



}else{
    $contenu .= '<div class="alert alert-info">Vous n\'avez pas encore de commande!</div>';
}
require_once('inc/header.php');
?>
<h2>Mes Commandes</h2>
<?= $contenu ?>
<?php
require_once('inc/footer.php');