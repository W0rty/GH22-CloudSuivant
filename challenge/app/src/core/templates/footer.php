<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
?>
<div style="position: absolute; bottom: 0;"><p class="lead" style="color: white;">Powered by CloudSuivant© 2022</p></div>
</html>