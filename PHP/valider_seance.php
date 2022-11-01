<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Valider séance</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Valider une séance</h2>
                <?php
                    if(empty($_POST['id_seance']))
                    { // Si l'id_seance n'est pas défini
                        ?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi de séance.</p>
                        <p> Vous allez être redirigé vers la page de validation de séance.</p>
                        <?php
                        header ("Refresh: 5;URL=validation_seance.php");
                    } else { // Si l'id_seance est défini

                        $id = htmlspecialchars($_POST["id_seance"], ENT_QUOTES);
                        include('connexion.php');

                        // Récupération du nombre d'élève inscrit
                        $query = "SELECT e.nom, e.prenom, e.ideleve, i.note
                                  FROM eleves e
                                  INNER JOIN inscription i ON i.ideleve = e.ideleve
                                  WHERE i.idseance = $id";

                        // echo "<br>$query<br>";

                        $result=mysqli_query($connect,$query);
                        if(!$result)
                        {
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }

                        $nb_inscrit = mysqli_num_rows($result);

                        if ($nb_inscrit == 0)
                        { // S'il n'y a pas d'élève inscrit à la séance
                            ?>
                            <p class="erreur">Erreur : Aucun élève n'est inscrit.</p>
                            <p> Vous allez être redirigé vers la page de validation de séance.</p>
                            <?php
                            mysqli_close($connect);
                            header ("Refresh: 5;URL=validation_seance.php");
                        } else { // S'il y a au moins un élève inscrit à la séance

                            echo "<FORM  METHOD='POST' ACTION='noter_eleve.php' >
                                        <table class='table_recap'>
                                            <tr>
                                              <th>Prénom et nom de l'élève</th>
                                              <th>Son nombre de fautes déjà présent</th>
                                              <th>Son nombre de fautes à saisir</th>
                                            </tr>";
                                                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) { // Pour chaque élève inscrit à la séance
                                                    $nb_fautes = 40 - $row[3]; // Calcul du nombre de fautes à saisir
                                                    if ($nb_fautes > 40) { // Notes pas encore saisie 40 -(-1) = 41 (>40)
                                            echo "<tr>
                                                      <td>$row[1] $row[0] :</td>
                                                      <td>Non noté</td>
                                                      <td><input type='number' name='$row[2]' min='0' max='40'></td>
                                                  </tr>";
                                                    } else { // Le nombre de notes a déjà était saisie entre 0 et 40 fautes
                                            echo "<tr>
                                                      <td>$row[1] $row[0] :</td>
                                                      <td>$nb_fautes</td>
                                                      <td><input type='number' name='$row[2]' value='$nb_fautes' min='0' max='40'></td>
                                                    </tr>";
                                                    }
                                                }
                                echo "  </table>
                                          <INPUT type='hidden' name='idseance' value='$id'>
                                          <div class='conf_res'>
                                            <div class='conf'>
                                                <input type='submit' value='Enregistrer notes'>
                                            </div>
                                          </div>
                                      </FORM>";
                            mysqli_close($connect);
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
