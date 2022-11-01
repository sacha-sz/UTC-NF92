<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Ajout d'un thème</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <?php
                if (empty($_POST["id_theme"]) || empty($_POST["desc_theme"]) || empty($_POST['conf_ajout'])|| empty($_POST['nom_theme'])) {?>
                    <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                    <p> Vous allez être redirigé vers la page d'ajout thème.</p>
                    <?php
                    header ("Refresh: 5;URL=../HTML/ajout_theme.html");
                } else {
                    // Les modifications pour éviter les failles XSS ont été faites dans la page précédente donc pas besoin de les faire ici
                    $id = htmlspecialchars($_POST["id_theme"], ENT_QUOTES);
                    $desc = htmlspecialchars($_POST["desc_theme"], ENT_QUOTES);
                    $rep = htmlspecialchars($_POST["conf_ajout"], ENT_QUOTES);
                    $nom_theme = $_POST["nom_theme"];

                    // Affichage sous forme de tableau
                    echo "<h2>Informations du thème saisies :</h2>
                           <p class='titre'>Récapitulatif des informations saisies :</p>
                           <table class='table_recap'>
                                <tr>
                                  <th>Nom :</th><th>Description :</th>
                                </tr>
                                <tr>
                                  <td>$nom_theme</td><td>$desc</td>
                                </tr>
                           </table>";
                    // Si toutes les informations sont correctes

                    // Connexion à la BDD via le fichier connexion.php
                    include('connexion.php');

                    if ($rep == 1) { // Oui avec modification de la description
                        $query = "UPDATE themes
                                  SET descriptif ='$desc', supprime = 0
                                  WHERE idtheme=$id";

                        $result = mysqli_query($connect, $query);

                        if (!$result)
                        {// Si la requête a rencontré une erreur
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        echo "<p>Le thème a bien été réactivé avec changement de description.</p>";
                    } else if ($rep == 2) { // Oui sans modification de la description
                        $query = "UPDATE themes
                                  SET supprime = 0
                                  WHERE idtheme=$id";

                        $result = mysqli_query($connect, $query);

                        if (!$result)
                        {// Si la requête a rencontré une erreur
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        echo "<p>Le thème a bien été réactivé sans changement de description.</p>";
                    } else if ($rep == 3) { // Non pas de réactivation
                        echo "<p>Le thème n'a pas été réactivé.</p>";
                    }
                    mysqli_close($connect);
                    echo"<p>Vous allez être redirigé vers la page d'ajout d'un thème.</p>";
                    header ("Refresh: 10;URL=../HTML/ajout_theme.html");
                }
                ?>
            </div>
        </div>
    </body>
</html>
