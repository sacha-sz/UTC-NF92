<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="../CSS/form.css">
      <title>Valider un élève</title>
  </head>

  <body class="form">
    <div class="page">
        <div class="cadre">
            <?php
            // Récupération de la date
            date_default_timezone_set('Europe/Paris');
            $date = date("Y-m-d");

            // Test si le formulaire a été rempli
            if (empty($_POST["nom_eleve"]) || empty($_POST["prenom_eleve"]) || empty($_POST["date_eleve"]))
            { // Si un des champs est vide
                ?>
                <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                <p> Vous allez être redirigé vers la page d'ajout élève.</p>
                <?php
                header ("Refresh: 5;URL=../HTML/ajout_eleve.html");
            } else if ((date_diff(date_create($_POST["date_eleve"]), date_create($date))->format('%y') < 15) || ($_POST["date_eleve"] > $date)) {?>
                <p class="erreur">Erreur : Vous n'avez pas saisi une date de naissance correcte.</p>
                <p> Vous allez être redirigé vers la page d'ajout élève.</p>
                <?php
                header ("Refresh: 5;URL=../HTML/ajout_eleve.html");
            } else {
                // Si toutes les informations sont correctes

                // Transformation de certains caractères HTML pour éviter les failles XSS, éviter l'interprétation de saisie comme du code
                $prenom_eleve = htmlspecialchars($_POST["prenom_eleve"], ENT_QUOTES);
                $nom_eleve = htmlspecialchars($_POST["nom_eleve"], ENT_QUOTES);
                $dnn_eleve = htmlspecialchars($_POST["date_eleve"], ENT_QUOTES);

                // Mise en majuscule du nom
                $nom_eleve = strtoupper($nom_eleve);

                // Mis en minuscule du prénom sauf la première lettre qui est en majuscule
                $prenom_eleve = ucfirst(strtolower($prenom_eleve));


                // Affichage sous forme de tableau
                echo "<h2>Informations de l'élève saisies :</h2>
                      <p class='titre'>Récapitulatif des informations saisies :</p>
                      <table class='table_recap'>
                        <tr>
                          <th>Nom :</th>
                          <th>Prenom :</th>
                          <th>Date naissance :</th>
                        </tr>
                        <tr>
                          <td>$nom_eleve</td>
                          <td>$prenom_eleve</td>
                          <td>$dnn_eleve</td>
                        </tr>
                      </table>";

                // Connexion à la BDD via le fichier connexion.php
                include('connexion.php');
                $query = "SELECT *
                          FROM eleves
                          WHERE nom = '$nom_eleve'
                          AND prenom = '$prenom_eleve'";

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

                $compteur_homonyme = mysqli_num_rows($result);
                if ($compteur_homonyme == 0) { // S'il n'y a pas d'homonyme
                    // Requête SQL
                    $query = "INSERT INTO eleves
                              VALUES (NULL, '$nom_eleve', '$prenom_eleve', '$dnn_eleve', '$date')";

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
                    echo "<p>L'élève $prenom_eleve $nom_eleve né(e) le $dnn_eleve a été ajouté avec succés à la base de données.</p>";
                    mysqli_close($connect);
                    echo "<p>Vous allez être redirigés vers la page d'ajout d'un élève.</p>";
                    header ("Refresh: 10;URL=../HTML/ajout_eleve.html");
                } else { // S'il y a au moins un homonyme
                    if ($compteur_homonyme == 1) {
                        echo "<p class='information'>Il existe déjà une personne s'appelant $prenom_eleve $nom_eleve:</p>";
                    }
                    else {
                        echo "<p class='information'>Il existe déjà $compteur_homonyme personnes s'appelant $prenom_eleve $nom_eleve:</p>";
                    }
                    echo "Voulez-vous tout de même l'ajouter ?";
                    echo "<form action='ajout_eleve.php' method='post'>
                          <input TYPE='radio' NAME='conf_ajout' VALUE='1' CHECKED>Oui<BR>
                          <input TYPE='radio' NAME='conf_ajout' VALUE='2'>Non<BR>
                          <input type='hidden' name='nom_eleve' value='$nom_eleve'>
                          <input type='hidden' name='prenom_eleve' value='$prenom_eleve'>
                          <input type='hidden' name='date_eleve' value='$dnn_eleve'>
                          <div class='conf_res'>
                            <div class='conf'>
                                <input type='submit' value='Enregistrer'>
                            </div>
                          </div>
                          </form>";
                    mysqli_close($connect);
                }
            }
            ?>
        </div>
    </div>
  </body>
</html>
