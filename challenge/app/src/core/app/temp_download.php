<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}

function downloadFile($name,$email)
{
    include("config/conf.php"); 
    $location = $upload_folder.$email."/".$name;
    if(file_exists($location))
    {
        header("Cache-Control: public");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($location));
        header("Content-Disposition: attachment; filename=".$name);
        readfile($location);
        die();
    }else die("Une erreur est survenue.");
}

if(isset($_GET["id"]) && isset($_GET["uuid"]) && !empty($_GET["id"]) && !empty($_GET["uuid"]))
{
    include("core/database/db_connect.php");
    $sth = $db->prepare("SELECT uuid, name, email FROM temp_files WHERE id = :i");
    $sth->bindValue(":i",htmlspecialchars($_GET["id"]));
    $sth->execute();
    $res = $sth->fetchAll();
    if(sizeof($res) > 0)
    {
        if($_GET["uuid"] === $res[0][0])
        {
            downloadFile($res[0][1], $res[0][2]);
        }else header("Location: /?page=403&msg=Le fichier auquel vous tentez d'accéder ne vous appartient pas.");
    }else header("Location: /?page=404&msg=Le fichier spécifié est introuvable.");
}else header("Location: /?page=404&msg=Le fichier spécifié est introuvable.");