<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Recap notes</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Récapitulatif des notes des élèves de la séance</h2>
                <?php
                    if (empty($_POST['idseance']))
                    { // Si l'id de la séance n'est pas passé en paramètre
                        ?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                        <p> Vous allez être redirigé vers la valider séance.</p>
                        <?php
                        header ("Refresh: 5;URL=valider_seance.php");
                    } else { // Si l'id de la séance est passé en paramètre

                        include('connexion.php');
                        $id_seance = htmlspecialchars($_POST['idseance'], ENT_QUOTES);

                        $query_p = "SELECT e.nom, e.prenom, e.ideleve
                                    FROM eleves e
                                    INNER JOIN inscription i on i.ideleve = e.ideleve
                                    WHERE i.idseance = $id_seance";

                        // echo "<br>$query_p<br>";

                        $result_p=mysqli_query($connect,$query_p);
                        if(!$result_p) {
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        echo "<table class='table_recap'>
                                <tr>
                                    <th> Elève </th>
                                    <th>Note (/40)</th>
                                </tr>";
                                while ($row = mysqli_fetch_array($result_p, MYSQLI_NUM)){ // On affiche les résultats pour chaque élève
                                    $id_eleve = $row[2];
                                    $nb_fautes = htmlspecialchars($_POST["$id_eleve"], ENT_QUOTES);

                                    if (empty($nb_fautes) and $nb_fautes != '0')
                                    { // Aucune note saisie alors on met la valeur par défaut -1
                                        $query_d = "UPDATE inscription
                                                    SET note = -1
                                                    WHERE idseance = $id_seance
                                                    AND ideleve = $id_eleve";

                                        // echo "<br> $query_d <br>";

                                        $result_d = mysqli_query($connect, $query_d);

                                        if (!$result_d){
                                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                                            mysqli_close($connect);
                                            exit;
                                        }
                        echo "  <tr>
                                    <td> $row[1] $row[0] </td>
                                    <td>Note remise à -1</td>
                                </tr>";
                                    } else { // Le nombre de fautes a été saisie on calcul la note : note = 40 - nb_fautes
                                        $note = 40 - $nb_fautes;

                                        $query_u = "UPDATE inscription
                                                    SET note = $note
                                                    WHERE idseance = $id_seance
                                                    AND ideleve = $id_eleve";

                                        // echo "<br> $query_u <br>";

                                        $result_u = mysqli_query($connect, $query_u);

                                        if (!$result_u){
                                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                                            mysqli_close($connect);
                                            exit;
                                        }
                        echo "  <tr>
                                    <td> $row[1] $row[0] </td>
                                    <td> $note </td>
                                </tr>";
                                    }
                                }
                        echo "</table>";
                        mysqli_close($connect);
                    }
                    echo "<p>Vous allez être redirigé vers la page de validation de séance<p>";
                    header("Refresh: 15; URL=validation_seance.php");
                ?>
            </div>
        </div>
    </body>
</html>
