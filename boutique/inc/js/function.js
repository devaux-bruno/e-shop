$(document).ready(function(){

    //jquery qui permet de demander une confirmation
    $('.confsup').on('click', function(){
        return(confirm('Etes vous certain de supprimer ce produit?'));
    });


    //jquery permet d'afficher une confirmation
    if( $('#maModale').length == 1 );{
        $('#maModale').modal('show');
    }

//animation et création cookies
    if($('.cookies').length == 1 ){ //si le cookie existe
        $('.cookies').animate({'bottom':0}, 1500); // on passe en bottom 0 en 1500ms

        $('#accept').on('click', function(){
            $('.cookies').animate({'bottom': '-1000px'}, 1500);//au click nous enlevons la barre

            d=new Date(); //on redéfinie le durée du cookie
            d.setTime( d.getTime() + 90*24*60*60*1000 ); // la date d'aujourd'hui + le temps
            document.cookie = "acceptCookies=true;expires=" + d.toGMTString();
        });
    }

});//fin doc ready