<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Ajouter une séance</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Informations de la séance saisies :</h2>
                <?php
                    // Récupération de la date
                    date_default_timezone_set('Europe/Paris');
                    $date = date("Y-m-d");
                    if (empty($_POST["date_seance"]) || empty($_POST["id_theme"]))
                    { // Si la date ou le thème n'est pas renseigné
                        ?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                        <?php
                    } else if ($_POST["effmax_seance"] < 0)
                    { // Si l'effmax est négatif
                        ?>
                        <p class="erreur">Erreur : Vous avez saisi un effectif négatif.</p>
                        <?php
                    } else if (empty($_POST["effmax_seance"]) || $_POST["effmax_seance"] == 0)
                    { // Si l'effmax est vide ou égal à 0
                        ?>
                        <p class="erreur">Erreur : Vous avez saisi un effectif vide ou nul.</p>
                        <?php
                    } else if ($_POST["date_seance"] < $date)
                    { // Si la date est dans le passé
                        ?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi une date de séance correcte.</p>
                        <?php
                    } else {
                        // Si toutes les informations sont correctes

                        // Connexion à la BDD via le fichier connexion.php
                        include('connexion.php');

                        // Transformation de certains caractères HTML pour éviter les failles XSS, éviter l'interprétation de saisie comme du code
                        $eff = htmlspecialchars($_POST["effmax_seance"], ENT_QUOTES);
                        $id = htmlspecialchars($_POST["id_theme"], ENT_QUOTES);
                        $date = htmlspecialchars($_POST["date_seance"], ENT_QUOTES);

                        // Requête SQL pour vérifier s'il n'existe pas déjà une séance avec cette date et ce thème
                        $query_v = "SELECT *
                                    FROM seances
                                    WHERE Idtheme=$id
                                    AND DateSeance='$date'";
                        // echo "<br>$query_v<br>";
                        // Exécution de la requête
                        $result_v = mysqli_query($connect, $query_v);

                        // Test de l'exécution de la requête
                        if (!$result_v)
                        {// Si la requête a rencontré une erreur
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }


                        // Requête permettant de récupérer le nom du thème correspondant à l'id
                        $query_t = "SELECT nom
                                    FROM themes
                                    WHERE IdTheme=$id";
                        // echo "<br>$query_t<br>";
                        $result_t = mysqli_query($connect, $query_t);
                         if (!$result_t) {
                             echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                             mysqli_close($connect);
                             exit;
                         }
                        // Récupération du nom du thème
                        $row_t = mysqli_fetch_array($result_t);




                        $compteur = mysqli_num_rows($result_v);

                        if ($compteur == 0) { // S'il n'y a pas de séance avec cette date et ce thème
                            // Affichage sous forme de tableau
                            echo "<p class='titre'>Récapitulatif des informations saisies :</p>
                                    <table class='table_recap'>
                                      <tr>
                                        <th>Date :</th>
                                        <th>Nom du thème la seance :</th>
                                        <th>Effectif :</th>
                                      </tr>

                                      <tr>
                                        <td>$date</td>
                                        <td>$row_t[0]</td>
                                        <td>$eff</td>
                                      </tr>
                                     </table>";

                          // Requête SQL pour insérer les informations dans la table seances
                          $query = "INSERT INTO seances
                                    VALUES (NULL,'$date', $eff, $id)";

                          // Affichage de la requête
                          // echo "<br>$query<br>";

                          // Exécution de la requête
                          $result = mysqli_query($connect, $query);

                          // Test de l'exécution de la requête
                          if (!$result)
                          {// Si la requête a rencontré une erreur
                              echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                              mysqli_close($connect);
                              exit;
                          }
                          echo "<br>La seance du thème $row_t[0] le $date pour $eff personnes a été ajouté avec succés à la base de données.<br>";
                          mysqli_close($connect);
                        } else {?>
                            <p class="erreur">Erreur : Une séance de ce thème existe déjà au jour sélectionné.</p>
                            <?php
                            mysqli_close($connect);
                        }
                    }?>
                    <p> Vous allez être redirigé vers la page d'ajout séance.</p>
                    <?php
                    header ("Refresh: 10;URL=ajout_seance.php");
                ?>
            </div>
        </div>
    </body>
</html>
