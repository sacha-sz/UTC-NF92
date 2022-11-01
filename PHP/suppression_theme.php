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
                include('connexion.php');


                $query = "SELECT idtheme, nom
                          FROM themes
                          WHERE supprime=0
                          ORDER BY nom";

                // echo "<br> $query <br>";

                $result = mysqli_query($connect, $query);

                if (!$result){
                    echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                    mysqli_close($connect);
                    exit;
                }

                // Formulaire
                echo "<FORM METHOD='POST' ACTION='supprimer_theme.php' >
                        <label for='id_theme'>Choix du thème :</label>
                        <SELECT name='id_theme' required >
                            <option value='' selected disabled>-- Sélectionnez un thème --</option>";
                            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                                echo "<OPTION value='$row[0]'>$row[1]</OPTION>";
                            }
                            echo "
                        </SELECT>

                        <div class='conf_res'>
                            <div class='conf'>
                              <input type='submit' value='Supprimer thème'>
                        </div>
                      </FORM>";
                mysqli_close($connect);
                ?>
            </div>
        </div>
    </body>
</html>
