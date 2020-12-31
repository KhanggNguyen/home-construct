
function addCheckedIcone(){
    "use strict";
    // icones devant prix du type de couverture et tarif main d'oeuvre sont checked de base pcq un type sera tjrs select et un tarif aussi
    document.getElementById('block_checked_couverture').innerHTML='<i class="fas fa-check-circle variable-presente"></i>';
    document.getElementById('block_checked_tarif').innerHTML='<i class="fas fa-check-circle variable-presente"></i>';

    // on teste si le nb de metre carre a été renseigné
    if(document.getElementById('homeconstruct_buildbundle_toiture_m2').value == ""){
        document.getElementById('block_checked_surface').innerHTML='<i class="fas fa-check-circle variable-non-presente"></i>';
    }else{
        document.getElementById('block_checked_surface').innerHTML='<i class="fas fa-check-circle variable-presente"></i>';
    }
}

function showSurfaceTotale(){
    "use strict";
    var element=document.getElementById('homeconstruct_buildbundle_toiture_m2');
    var surface=element.value;
    if(surface!= ""){
        document.getElementById('surface_toiture').innerHTML=surface+"m²";
    }else{
        document.getElementById('surface_toiture').innerHTML="";
    }
}

function updateEstimationPrice(){
    "use strict";
    var prix=document.getElementById("prix_calcule");
    var tarif=document.getElementById("tarif_main_doeuvre").innerHTML;
    var surface=document.getElementById('surface_toiture').innerHTML;
    var couverture=document.getElementById('prix_type_couverture').innerHTML;
    if(tarif!="" && surface!=""&&couverture!=""){
        var valeurSurface=document.getElementById('homeconstruct_buildbundle_toiture_m2').value;
        var valeurCouverture=document.getElementById('prix_type_couverture').innerHTML;
        valeurCouverture=valeurCouverture.substring(0,valeurCouverture.length-1);
        var valeurMainDoeuvre=document.getElementById('homeconstruct_buildbundle_toiture_tarifMainDoeuvre').value;
        prix.innerHTML=(valeurSurface*valeurCouverture)+(valeurSurface*valeurMainDoeuvre)+"€";
        document.getElementById("rappel").innerHTML="";
    }else{
        prix.innerHTML="";
        document.getElementById("rappel").innerHTML="Pour pouvoir estimer un prix total veuillez ajouter les entités manquantes (marquées d'une croix rouge)";
    }
}