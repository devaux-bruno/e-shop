        </div>
        <div class="container-fluid">
            <footer class="row bg-dark text-center py-3">
                <div class="col" id="footer">
                    &copy;<?= date('Y') ?> - Shop Inc
                </div>
            </footer>        
        </div>
    </div>
</div>
    <!-- création d'es cookie avec autorisation-->
        <?php
        if( !isset($_COOKIE['acceptCookies'])){ //si le cookies des cookies n'existe pas nous le créons en JQUERY
        ?>
        <div class="cookies py-3 text-light text-center bg-primary">
        !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ATTENTION !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>
        Ce site utilise des cookies pour améliorer votre confort de navigation et surtout pour pas vous spammer ce message.<br>
        Il est effectué dans le cadre de mon apprentissage, n'a aucun but commercial et n'est pas encore terminé.<br><br>
        Vous pouvez vous servir du <b>PSEUDO</b> "mario" et du <b>MOT DE PASSE</b> "mario" pour accéder aux différentes fonctionnalités du site au lieu de vous inscrire.<br>
        !! Attention !! le site n’étant pas encore terminé, je vous d'utiliser le compte de Mario pour vous connecter. !! ATTENTION !!<br>
        Si vous avez des questions ou souhaitez me contacter : contact@devaux-bruno.com
        <br>
            <button id="accept" class="btn btn-warning">J'ai Compris!</button>
        </div>
        <?php
        }
        ?>
    </body>
</html>