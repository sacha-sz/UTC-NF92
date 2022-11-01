<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Ajout d'une séance</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <?php
                    // Récupération de la date
                    date_default_timezone_set('Europe/Paris');
                    $date = date("Y-m-d");

                    include('connexion.php');

                    // Récupération des élèves
                    $query_eleve = "SELECT *
                                    FROM eleves
                                    ORDER BY nom, prenom";

                    $result_eleve = mysqli_query($connect, $query_eleve);

                    if(!$result_eleve)
                    {
                        echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                        exit;
                    }

                    // Récupération des séances
                    $query_seance = "SELECT s.idseance, t.nom,s.DateSeance
                                     FROM seances s
                                     INNER JOIN themes t on s.Idtheme=t.idtheme
                                     WHERE DateSeance > '$date'";

                    $result_seance = mysqli_query($connect, $query_seance);

                    if(!$result_seance) {
                        echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                        exit;
                    }

                    // Formulaire
                    echo "<h2>Inscription d'un élève :</h2>
                          <FORM METHOD='POST' ACTION='inscription_eleve.php' >
                          <table class='table_recap'>
                            <tr>
                                <td>Choix de la séance :</td>
                                <td>
                                    <SELECT name='id_seance' required>
                                      <option value='' selected disabled>-- Sélection une séance --</option>";
                                          while ($row_seance = mysqli_fetch_array($result_seance, MYSQLI_NUM))
                                          {
                                              echo "<OPTION value='$row_seance[0]'> $row_seance[1] du $row_seance[2]</OPTION>";
                                          }
                    echo "          </SELECT>
                                </td>
                            </tr>
                            <tr>
                              <td>Choix de l'élève :</td>
                              <td>
                                <SELECT name='id_eleve' required>
                                    <option value='' selected disabled>-- Sélection un élève --</option>";

                                    while ($row_eleve = mysqli_fetch_array($result_eleve, MYSQLI_NUM))
                                    {
                                        echo "<OPTION value='$row_eleve[0]'>$row_eleve[2] $row_eleve[1] né(e) le $row_eleve[3]</OPTION>";
                                    }
                    echo "      </SELECT>
                              </td>
                            </tr>
                          </table>
                          <div class='conf_res'>
                            <div class='conf'>
                              <input type='submit' value='Inscrire élève'>
                            </div>
                        </FORM>";
                ?>
            </div>
        </div>
    </body>
</html
