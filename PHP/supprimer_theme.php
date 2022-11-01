<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Suppression d'un thème</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Suppression d'un thème</h2>
                <?php
                if (empty($_POST['id_theme'])){?>
                    <p class="erreur">Erreur : Vous n'avez pas saisi de thème.</p>
                <?php
                } else {
                    include('connexion.php');
                    $id = htmlspecialchars($_POST['id_theme'], ENT_QUOTES);

                    $query_e = "UPDATE themes
                                SET supprime=1
                                WHERE idtheme=$id";

                    // echo "<br> $query_e <br>";

                    $result_e = mysqli_query($connect, $query_e);
                    if (!$result_e){
                        echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                        mysqli_close($connect);
                        exit;
                    }

                    echo "<p>Le thème a bien été supprimé.</p>";
                    mysqli_close($connect);
                }?>
                <p> Vous allez être redirigé vers la page de suppression de thème.</p>
                <?php
                header ("Refresh: 5;URL=suppression_theme.php");
                ?>
            </div>
        </div>
    </body>
</html>
