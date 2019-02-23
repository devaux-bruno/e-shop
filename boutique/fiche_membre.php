$resultatmembre = execRequete("SELECT m.id_membre, c.id_membre, c.id_commande, d.id_comande, d.id_produit, p.id_produit
FROM membre AS m, commande AS c, produit AS p, details_commande AS d
WHERE a.id_membre = c.id_membre 
AND c.id_commande = d.id_commande 
AND p.id_produit = d.id_produit" , 
array( 'id_membre' => $_SESSION['membre']['id_membre']));