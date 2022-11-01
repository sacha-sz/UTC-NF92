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
                    // Récupération de la date
                    date_default_timezone_set('Europe/Paris');
                    $date = date("Y-m-d");

                    include('connexion.php');


                    $query = "SELECT s.idseance, t.nom, s.DateSeance
                              FROM seances s
                              INNER JOIN themes t
                              ON s.Idtheme=t.idtheme
                              WHERE s.DateSeance > '$date'";

                    // echo "<br> $query <br>";

                    $result = mysqli_query($connect, $query);
                    if (!$result){
                      echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                      mysqli_close($connect);
                      exit;
                    }

                    // Formulaire
                    echo "<FORM METHOD='POST' ACTION='supprimer_seance.php' >
                            <label for='id_seance'>Choix de la séance :</label>
                            <SELECT name='id_seance' required >
                                <option value='' selected disabled>-- Sélectionnez une séance future --</option>";
                                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                                    echo "<OPTION value='$row[0]'>$row[1] le $row[2]</OPTION>";
                                }
                    echo "  </SELECT>
                            <div class='conf_res'>
                                <div class='conf'>
                                    <input type='submit' value='Choisir séance'>
                                </div>
                            </div>
                          </FORM>";

                    mysqli_close($connect);
                ?>
            </div>
        </div>
    </body>
</html>
