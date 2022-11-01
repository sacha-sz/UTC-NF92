<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Suppression séance</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Supprimer une séance</h2>
                <?php
                    if (empty($_POST['id_seance']))
                    { // Si l'id_seance n'est pas défini
                        ?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi de thème.</p>
                        <p> Vous allez être redirigé vers la page de suppression de thème.</p>
                        <?php
                        header ("Refresh: 5;URL=suppression_theme.php");
                    } else { // Si l'id_seance est défini
                        include('connexion.php');
                        $id = htmlspecialchars($_POST['id_seance'], ENT_QUOTES);

                        $query_d = "DELETE FROM inscription
                                    WHERE idseance = $id";

                        // echo "<br> $query_d <br>";

                        $result_d = mysqli_query($connect, $query_d);
                        if (!$result_d){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }

                        $query_d2 = "DELETE FROM seances
                                     WHERE idseance = $id";

                        // echo "<br> $query_d2 <br>";

                        $result_d2 = mysqli_query($connect, $query_d2);
                        if (!$result_d2){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }

                        echo "<p>La séance a été supprimée.</p>";
                        echo "<p>Vous allez être redirigé vers la page de suppression de séance.</p>";
                        header ("Refresh: 5;URL=suppression_seance.php");
                        mysqli_close($connect);
                    }
                ?>
            </div>
        </div>
    </body>
</html>
