<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= URL . 'inc/CSS/style.css'?>" >
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <title> 1er projet - e-boutique - Firstshop </title>    
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <script src="<?= URL . 'inc/js/function.js' ?>"></script>
    </head>
    <body>
        <header> 
            <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
                <img src="<?= URL . '/photo/shopping_bag.png' ?>" id="logo" alt="logo shopping"><a class="navbar-brand" href="<?= URL ?>">Firstshop</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mx-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL ?>"><i class="fas fa-tshirt"></i> Boutique <span class="sr-only">(current)</span></a>
                        </li>

                        <?php
                            if( !isConnected() ){
                        ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL . 'inscription.php' ?>"><i class="fab fa-wpforms"></i> Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL . 'connexion.php' ?>"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        </li>
                        <?php
                            }
                            else{
                        ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL . 'compte.php' ?>"><i class="fas fa-user-circle"></i> Mon Compte</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL . 'commandes.php' ?>"><i class="fas fa-clipboard-list"></i> Mes commandes</a>
                        </li>
                        <li class="nav-item"><!-- on crée une action dans le fichier connexion pour éviter 2 fichiers-->
                            <a class="nav-link" href="<?= URL . 'connexion.php?action=deconnexion' ?>"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
                        </li>

                        <?php
                            }
                            if( isAdmin() ){
                        ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="menuadmin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-tools"></i> Admin</a>
                            <div class="dropdown-menu" aria-labelledby="menuadmin">
                                <a class="dropdown-item" href="<?= URL . 'admin/gestion_produits.php' ?>">Gestion des produits</a>
                                <a class="dropdown-item" href="<?= URL . 'admin/gestion_membre.php' ?>">Gestion des membres</a>
                                <a class="dropdown-item" href="<?= URL . 'admin/gestion_commandes.php' ?>">Gestion des commandes</a>
                            </div>
                        </li>

                        <?php
                            }   
                        ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL . 'panier.php' ?>"><i class="fas fa-shopping-cart"></i> Panier <?= nbArticle() ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL . 'presentation.php' ?>"><i class="far fa-file-alt"></i> Presentation Admin</a>
                        </li>
                    </ul>

                    <form class="form-inline mt-2 mt-md-0" method="POST" action="<?= URL . 'recherche.php' ?>">
                        <input class="form-control mr-sm-2" type="text" placeholder="article" aria-label="Search" name="critere" value="<?= $_POST['critere'] ?? '' ?>">
                        <button class="btn btn-outline-warning my-2 my-sm-0" type="submit">Rechercher</button>
                    </form>
                </div>
            </nav>
        </header>
        <div class="container maincontainer">
