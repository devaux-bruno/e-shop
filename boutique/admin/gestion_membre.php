<?php

require_once('../inc/init.php');
//1.Vérifier que l'on est admin
if( !isAdmin() ){
    header('location:' . URL . 'connexion.php');
    exit();
}
$contenu = '';
//3. Changement de statut

if( isset($_GET['action']) && $_GET['action'] == 'changestatut' && isset($_GET['id_membre']) && $_GET['id_membre'] != $_SESSION['membre']['id_membre']){ //si on a l'action du click sur l'id_membre et que ce n'est pas notre id

    $resultat = execRequete("SELECT statut FROM membre WHERE id_membre = :id_membre", array(
        'id_membre' => $_GET['id_membre'] //on récupère tous les id de la bdd
    ));

    if( $resultat -> rowCount() == 1 ){ // si on trouve une correspondance dans la bdd

        $membre = $resultat->fetch();
        $nouveaustatut = ( $membre['statut'] == 0 ) ? 1 : 0;//nouveaustatut va alterner entre 0 et 1
        //on va ensuite faire la mise à jour du statut pour l'id selection par action
        execRequete("UPDATE membre SET statut = :nouveaustatut WHERE id_membre = :id_membre", array(
            'nouveaustatut' => $nouveaustatut,
            'id_membre' => $_GET['id_membre']
        ));
    }
}


//2. affichage des membres

$resultat = execRequete("SELECT * FROM membre ORDER BY nom");
$contenu .= '</div><div class="container-fluid" id="tablemembre">
            <div class="row">
<table class="table table-bordered table-striped text-center"><tr>';

for($i=0; $i < $resultat->columnCount(); $i++){ // on crée une boucle pour faire le champ des titre pa rapport au nombre d'index.

    $colonne = $resultat->getColumnMeta($i); // Retourne les métadonnées pour une colonne d'un jeu de résultats
    if( $colonne['name'] != 'mdp'){//on enleve la donnée mdp
        $contenu .= '<th>'. ucfirst($colonne['name']) . '</th>';
    }
}
$contenu .='<th>Action</th>
</tr>';
while( $membre = $resultat->fetch() ){ // on utilse while pour les fetch

    $contenu .= '<tr>';
    foreach( $membre as $indice => $valeur){ // boucle pour afficher le tableau avec toutes les donée
        if( $indice != 'mdp'){// sauf mdp

            if( $indice == 'statut'){ 
                $valeur = ($valeur==1) ? 'Administrateur' : 'Membre'; // pour statut on renplace les 0 et 1 part des nom
            }
            if( $indice == 'image_profil'){
                $valeur = '<img class="vignetteprofil" src="'.URL . 'photo/membres/' . $valeur .'" alt="Photo de profil">';
            }
           $contenu .= '<td>'. $valeur . '</td>'; 
        }       
    }
    if( $membre['id_membre'] != $_SESSION['membre']['id_membre']){ //on peut changer que les autre statut que le notre
    $contenu .= '<td class="text-center"><a href="?action=changestatut&id_membre='.$membre['id_membre'].'">&#128393;</a></td>';
    }
    else{
        $contenu .= '<td>*</td>';//on affiche étoile sur notre statuts
    }
    $contenu .= '</tr>';
}
$contenu .= '</table></div></div>';

require_once('../inc/header.php');
?>
<h2>Gestion des membres</h2>
<?= $contenu ?>

<?php
require_once('../inc/footer.php');