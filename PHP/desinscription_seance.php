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
                    // Récupération de la date
                    date_default_timezone_set('Europe/Paris');
                    $date = date("Y-m-d");
                    include('connexion.php');


                    $query = "SELECT e.ideleve, e.nom, e.prenom, e.dateNaiss
                              FROM eleves e
                              INNER JOIN inscription i ON i.ideleve = e.ideleve
                              INNER JOIN seances s ON i.idseance = s.idseance
                              WHERE s.DateSeance > '$date'
                              ORDER BY e.prenom, e.nom";

                    // echo "<br> $query <br>";

                    $result = mysqli_query($connect, $query);
                    if (!$result){
                        echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                        mysqli_close($connect);
                        exit;
                    }

                    // Formulaire
                    echo "<FORM METHOD='POST' ACTION='desinscrire_seance.php' >
                            <label for='id_eleve'>Choix de l'élève :</label>
                            <SELECT name='id_eleve' required >
                                <option value='' selected disabled>-- Sélection un élève --</option>";
                                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                                    echo "<OPTION value='$row[0]'>$row[2] $row[1] né(e) le $row[3]</OPTION>";
                                }
                    echo "  </SELECT>
                            <div class='conf_res'>
                                <div class='conf'>
                                    <input type='submit' value='Choisir élève'>
                                </div>
                            </div>
                          </FORM>";

                    mysqli_close($connect);
                ?>
            </div>
        </div>
    </body>
</html>
