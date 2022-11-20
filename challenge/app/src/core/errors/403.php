<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
include("core/templates/header.php");
?>
<body style="background-color: #0082c8">
<div class="container">
    <div class="row">
        <div class="col text-center">
            <div>
                <p class="lead" style="color: white;">Le cloud ne vous autorise pas à accéder à cette page...</p>
                <p class="lead" style="color: white;" id="errormsg"></p>
            </div>
            <p class="lead" style="color: white;">Retour à la page <a href="?page=/" style="color: black;">d'accueil.</a></p>
        </div>
    </div>
</div>
<script>
    var params = document.location.search.split("&")
    var msg_from_serv = "Raison invoquée : Accès interdit."
    if(params.some(e => /msg/g.test(e)))
    {
        for(var i=0; i<params.length; i++)
        {
            if(params[i].includes("msg"))
            { 
                msg_from_serv = "Raison invoquée : "+decodeURIComponent(params[i].split("=")[1])
            }
        }
    }
    $("#errormsg").text(msg_from_serv)
</script>
</body>
<?php include("core/templates/footer.php"); ?>