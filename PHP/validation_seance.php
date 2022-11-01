<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Validation seance</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Validation d'une séance</h2>
                <?php
                    // Récupération de la date
                    date_default_timezone_set('Europe/Paris');
                    $date = date("Y-m-d");

                    include('connexion.php');

                    $query = "SELECT idseance, nom, DateSeance
                              FROM seances
                              INNER JOIN themes on seances.Idtheme = themes.idtheme
                              WHERE DateSeance<'$date'
                              ORDER BY DateSeance DESC";

                    // echo"<br>$query<br>";

                    $result = mysqli_query($connect, $query);
                    if(!$result) {
                        echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                        mysqli_close($connect);
                        exit;
                    }

                    // Formulaire choix séance :
                    echo "<FORM METHOD='POST' ACTION='valider_seance.php' >
                            <table class='table_recap'>
                                <tr>
                                    <td>Choix de la séance : </td>
                                    <td>
                                      <SELECT name='id_seance' required >
                                        <option value='' selected disabled>-- Sélection une séance --</option>";
                                            while ($row = mysqli_fetch_array($result, MYSQLI_NUM))
                                            {
                                                echo "<OPTION value='$row[0]'> $row[1] du $row[2]</OPTION>";
                                            }
                           echo "     </SELECT>
                                    </td>
                                </tr>
                           </table>
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
