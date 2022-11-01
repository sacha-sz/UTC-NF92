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
                <h2>Ajout d'une séance</h2>
                <?php
                  // Récupération de la date
                  date_default_timezone_set('Europe/Paris');
                  $date = date("Y-m-d");

                  include('connexion.php');

                  $query = "SELECT *
                            FROM themes
                            WHERE supprime=0";

                  $result = mysqli_query($connect, $query);

                  if(!$result)
                  {
                      echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                      mysqli_close($connect);
                      exit;
                  }

                  // Formulaire
                  echo "<FORM METHOD='POST' ACTION='ajouter_seance.php' >
                            <table class='table_recap'>
                                <tr>
                                  <td>Choix du thème :</td>
                                  <td>
                                    <SELECT name='id_theme' required >
                                      <option value='' selected disabled>-- Sélection thème --</option>";
                                  while ($row = mysqli_fetch_array($result, MYSQLI_NUM))
                                  {
                                      echo "<OPTION value='$row[0]'>$row[1]</OPTION>";
                                  }
                                  echo "     </SELECT>
                                </td>
                              </tr>
                              <tr>
                                <td>Date :</td>
                                <td><input type='date' name='date_seance' min='$date'> </td>
                              </tr>
                              <tr>
                                <td>Effectif :</td>
                                <td><input type='number' name='effmax_seance'></td>
                              </tr>
                            </table>
                            <div class='conf_res'>
                              <div class='conf'>
                                  <input type='submit' value='Enregistrer'>
                              </div>
                            </div>
                            </form>";
                  mysqli_close($connect);
                ?>
            </div>
        </div>
    </body>
</html>
