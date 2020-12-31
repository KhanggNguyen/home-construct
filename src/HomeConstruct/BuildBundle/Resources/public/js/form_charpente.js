function updateEstimationPrice(){
    "use strict";
    var prix=document.getElementById("prix_calcule");
    var tarif=document.getElementById("tarif_main_doeuvre").innerHTML;
    var surface=document.getElementById('surface_toiture').innerHTML;
    var type=document.getElementById('prix_type').innerHTML;
    if(tarif!="" && surface!=""&&type!=""){
        // on recupere la valeur de la surface qui fini avec m2 donc on enleve deux char
        var valeurSurface=document.getElementById('surface_toiture').innerHTML;
        valeurSurface=valeurSurface.substring(0,valeurSurface.length-1);
        valeurSurface=valeurSurface.substring(0,valeurSurface.length-1);
        // pareil mais on recupere le type et on enleve le €
        var valeurType=type;
        valeurType=valeurType.substring(0,valeurType.length-1);
        // on recupere le tarix de la main d'oeuvre
        var valeurMainDoeuvre=document.getElementById('homeconstruct_buildbundle_charpente_tarifMainDoeuvre').value;
        // on inclut le prix dans le code HTML
        prix.innerHTML=(valeurSurface*valeurType)+(valeurSurface*valeurMainDoeuvre)+"€";
        document.getElementById("rappel").innerHTML="";
    }else{
        prix.innerHTML="";
        document.getElementById("rappel").innerHTML="Pour pouvoir estimer un prix total veuillez ajouter les entités manquantes (marquées d'une croix rouge)";
    }
}