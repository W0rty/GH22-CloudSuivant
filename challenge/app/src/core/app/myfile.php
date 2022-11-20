<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
if(!isset($_SESSION["email"]))
{
    header("Location: /?page=login");
    die();
}
include("core/database/db_connect.php");
$sth = $db->prepare("SELECT id FROM users WHERE email = :e");
$sth->bindValue(":e",htmlspecialchars($_SESSION["email"]));
$sth->execute();
$res = $sth->fetchAll();
if(sizeof($res) > 0)
{
    $id = $res[0][0];
    $sth = $db->prepare("SELECT id, name, uuid FROM files WHERE associateUser = :i");
    $sth->bindValue(":i",$id);
    $sth->execute();
    $res_files = $sth->fetchAll();
    if(sizeof($res_files) <= 0) $msgError = "Vous n'avez aucun fichier. Vous pouvez en téléverser <a href='/?page=upload' style='color: black;'>ici.</a>";
}
else
{
    header("Location: /?page=logout");
    die();
}
include("core/templates/header.php");
?>
<body style="background-color: #0082c8">
<div class="container">
    <div class="row">
        <div class="col text-center">
            <img src="/assets/img/logo.png" width="100" height="100">
            <p class="lead" style="color: white;">Bienvenue sur votre cloud !</p>
            <p class="lead" style="color: white;" id="errormsg"></p>
            <?php if(isset($msgError)){ 
                    echo '<p class="lead" style="color: white;">'.$msgError.'</p>';
                  }else{ ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">name</th>
                            <th scope="col">uuid</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i=0;$i<sizeof($res_files);$i++){ ?>
                            <tr>
                                <th scope="row"><?=$res_files[$i][0];?></th>
                                <td><?php echo(htmlspecialchars($res_files[$i][1])); ?></td>
                                <td><?php echo(htmlspecialchars($res_files[$i][2])); ?></td>
                                <td><input type="submit" class="btn btn-warning" value="Télécharger" onclick="window.location.href='/?page=download&id=<?=$res_files[$i][0];?>'"></td>
                                <td><input type="submit" class="btn btn-success" value="Partager" onclick="alert('Vous pouvez envoyer cet URL à la personne qui doit télécharger le fichier: /?page=download&id=<?=$res_files[$i][0];?>&uuid=<?=$res_files[$i][2];?>')"></td>
                                <td><input type="submit" class="btn btn-danger" value="Supprimer" onclick="window.location.href='/?page=delete&id=<?=$res_files[$i][0];?>'"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    var params = document.location.search.split("&")
    if(params.some(e => /msg/g.test(e)))
    {
        for(var i=0; i<params.length; i++)
        {
            if(params[i].includes("msg"))
            { 
                msg_from_serv = decodeURIComponent(params[i].split("=")[1])
            }
        }
        $("#errormsg").text(msg_from_serv)
    }
</script>
</body>
<?php include("core/templates/footer.php"); ?>