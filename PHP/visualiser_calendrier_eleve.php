<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Visualiser les séances d'un élève</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Séances futur de l'élève</h2>
                <?php
                // Récupération de la date
                date_default_timezone_set('Europe/Paris');
                $date = date("Y-m-d");


                if (empty($_POST['id_eleve'])){?>
                    <p class="erreur">Erreur : Vous n'avez pas saisi d'élève.</p>
                    <p> Vous allez être redirigé vers la page de consultation des séances d'un élève.</p>
                    <?php
                    header ("Refresh: 5;URL=consultation_eleve.php");
                } else {
                    include('connexion.php');
                    $id = htmlspecialchars($_POST['id_eleve'], ENT_QUOTES);

                    $query_s = "SELECT e.nom, e.prenom, t.nom, s.Dateseance
                                FROM inscription i
                                INNER JOIN seances s ON i.idseance = s.idseance
                                INNER JOIN themes t ON s.Idtheme = t.idtheme
                                INNER JOIN eleves e ON e.ideleve = i.ideleve
                                WHERE i.ideleve = $id
                                AND s.Dateseance > '$date'";

                    // echo "<br> $query_s <br>";
                    $result_s = mysqli_query($connect, $query_s);
                    if (!$result_s){
                        echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                        mysqli_close($connect);
                        exit;
                    }
                    $compteur = mysqli_num_rows($result_s);

                    if ($compteur > 0) {
                        echo "<table class='table_recap'>
                                <tr>
                                  <th>Prénom et nom de l'élève</th>
                                  <th>Thème de la séance</th>
                                  <th>Date de la séance</th>
                                </tr>";
                            while ($row = mysqli_fetch_array($result_s, MYSQLI_NUM)) {
                                echo "<tr>
                                    <td>$row[1] $row[0]</td>
                                    <td>$row[2]</td>
                                    <td>$row[3]</td>
                                  </tr>";
                        }
                        echo " </table>";
                    }
                    else {
                        echo "<p class='information'>Cet élève n'a pas de séance à venir.</p>";
                    }
                    ?>
                    <p> Vous allez être redirigé vers la page de consultation des séances d'un élève.</p>
                    <?php
                    mysqli_close($connect);
                    header ("Refresh: 8;URL=visualisation_calendrier_eleve.php");
                  }
                ?>
            </div>
        </div>
    </body>
</html>
