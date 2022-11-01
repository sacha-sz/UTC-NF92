<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Visualisation des séances d'un élève</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Consultation des séances futures d'un élève</h2>
                <?php
                include('connexion.php');

                $query = "SELECT ideleve, nom, prenom, dateNaiss
                          FROM eleves
                          ORDER BY prenom, nom";

                // echo "<br> $query <br>";
                $result = mysqli_query($connect, $query);
                if (!$result){
                    echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                    mysqli_close($connect);
                    exit;
                }

                // Formulaire
                echo "<FORM METHOD='POST' ACTION='visualiser_calendrier_eleve.php' >
                            <label for='id_eleve'>Choix de l'élève :</label>
                            <SELECT name='id_eleve' required >
                                <option value='' selected disabled>-- Sélection un élève --</option>";
                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                    echo "<OPTION value='$row[0]'>$row[2] $row[1] né(e) le $row[3]</OPTION>";
                }
                echo "     </SELECT>
                           <div class='conf_res'>
                              <div class='conf'>
                                <input type='submit' value='Consulter élève'>
                              </div>
                          </FORM>";

                mysqli_close($connect);
                ?>
             </div>
         </div>
    </body>
</html>
