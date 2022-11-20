<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
$db = new PDO("mysql:host=bdd;dbname=cloudsuivant;","cloud_suivant","wjobQwN4X3uwT6zE");