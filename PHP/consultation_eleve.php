<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Consultation des informations d'un élève</title>
    </head>
    <body class="form">
      <div class="page">
        <div class="cadre">
          <?php
              include('connexion.php');
              echo"<h2>Consultation des informations d'un élève</h2>";
              $query = "SELECT ideleve, nom, prenom, dateNaiss FROM eleves ORDER BY nom, prenom";
              // echo "<br> $query <br>";
              $result = mysqli_query($connect, $query);
              if (!$result){
                  echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                  mysqli_close($connect);
                  exit;
              }

              // Formulaire
              echo "<FORM METHOD='POST' ACTION='consulter_eleve.php' >
                          <label for='id_eleve'>Choix de l'élève :</label>
                          <SELECT name='id_eleve' required >
                              <option value='' selected disabled>-- Sélection un élève --</option>";
              while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                  echo "<OPTION value='$row[0]'>$row[1] $row[2] né(e) le $row[3]</OPTION>";
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
