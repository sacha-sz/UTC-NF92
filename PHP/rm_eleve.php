<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Désinscription séance</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Désinscription d'un élève d'une séance</h2>
                <?php
                    if (empty($_POST["id_eleve"]) || empty($_POST["id_seance"]))
                    { // Si les champs sont vides
                        ?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                        <p> Vous allez être redirigé vers la page d'ajout élève.</p>
                        <?php
                        header ("Refresh: 5;URL=desinscription_seance.php");
                    } else { // Si les champs sont remplis
                        include('connexion.php');

                        $id_e = htmlspecialchars($_POST["id_eleve"], ENT_QUOTES);
                        $id_s = htmlspecialchars($_POST["id_seance"], ENT_QUOTES);

                        // On supprime l'élève de la table inscription
                        $query = "DELETE FROM inscription
                                  WHERE idseance=$id_s
                                  AND ideleve=$id_e";

                        // echo "<br> $query <br>";

                        $result = mysqli_query($connect, $query);
                        if (!$result){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }

                        // On récupère les informations de l'élève
                        $query_e = "SELECT nom, prenom
                                    FROM eleves
                                    WHERE ideleve=$id_e";

                        // echo "<br> $query_e <br>";

                        $result_e = mysqli_query($connect, $query_e);
                        if (!$result_e){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        $info_e = mysqli_fetch_array($result_e);

                        // On récupère les informations de la séance
                        $query_s = "SELECT t.nom, s.DateSeance
                                    FROM seances s
                                    INNER JOIN themes t on t.idtheme = s.idtheme
                                    WHERE s.idseance=$id_s";

                        // echo "<br> $query_s <br>";

                        $result_s = mysqli_query($connect, $query_s);
                        if (!$result_s){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        $info_s = mysqli_fetch_array($result_s);

                        echo "<p>La désinscription de l'élève $info_e[1] $info_e[0] de la séance de $info_s[0] le $info_s[1] s'est bien déroulé</p>";
                        echo "<p>Vous allez être redirigé vers la page de désinscription d'un élève.</p>";
                        header ("Refresh: 10;URL=desinscription_seance.php");
                        mysqli_close($connect);
                    }
                ?>
            </div>
        </div>
    </body>
</html>
