<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="../CSS/form.css">
      <title>Ajout élève</title>
  </head>

  <body class="form">
      <div class="page">
          <div class="cadre">
            <h2>Informations de l'élève saisies :</h2>
              <?php
                // Récupération de la date
                date_default_timezone_set('Europe/Paris');
                $date = date("Y-m-d");

                // Test si le formulaire a été correctement rempli
                if (empty($_POST["nom_eleve"]) || empty($_POST["prenom_eleve"]) || empty($_POST["date_eleve"] || empty($_POST["conf_ajout"])))
                { // Si un des champs est vide
                    ?>
                    <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                    <p> Vous allez être redirigé vers la page d'ajout élève.</p>
                    <?php
                    header ("Refresh: 5;URL=../HTML/ajout_eleve.html");
                }
                else if ((date_diff(date_create($_POST["date_eleve"]), date_create($date))->format('%y') < 15) || ($_POST["date_eleve"] > $date))
                { // Si l'élève est trop jeune inférieur à 15 ans ou si la date est incorrecte (date future)
                    ?>
                    <p class="erreur">Erreur : Vous n'avez pas saisi une date de naissance correcte.</p>
                    <p> Vous allez être redirigé vers la page d'ajout élève.</p>
                    <?php
                    header ("Refresh: 5;URL=../HTML/ajout_eleve.html");
                }
                else {
                    // Si toutes les informations sont correctes

                    $conf = $_POST["conf_ajout"]; // Récupération de la réponse à la question de confirmation

                    if ($conf == 1) { // Si l'utilisateur veut ajouter l'élève
                        // Transformation de certains caractères HTML pour éviter les failles XSS, éviter l'interprétation de saisie comme du code
                        $prenom_eleve = htmlspecialchars($_POST["prenom_eleve"], ENT_QUOTES);
                        $nom_eleve = htmlspecialchars($_POST["nom_eleve"], ENT_QUOTES);
                        $dnn_eleve = htmlspecialchars($_POST["date_eleve"], ENT_QUOTES);

                        // Mise en majuscule du nom
                        $nom_eleve = strtoupper($nom_eleve);

                        // Mise en minuscule du prénom sauf la première lettre qui est en majuscule
                        $prenom_eleve = ucfirst(strtolower($prenom_eleve));


                        // Affichage sous forme de tableau de l'élève qui sera ajouté
                        echo "<p class='titre'>Récapitulatif des informations saisies :</p>
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

                        // Requête SQL
                        $query = "INSERT INTO eleves VALUES (NULL, '$nom_eleve', '$prenom_eleve', '$dnn_eleve', '$date')";

                        // Affichage de la requête
                        // echo "<br>$query<br>";

                        // Exécution de la requête
                        $result = mysqli_query($connect, $query);

                        // Test de l'exécution de la requête
                        if (!$result) {// Si la requête a rencontré une erreur
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            exit;
                        }

                        // Si la requête a été exécutée correctement
                        echo "<p>L'élève a été ajouté avec succés à la base de données.</p>";
                        echo "<p>Vous allez être redirigé vers la page d'ajout d'un élève.</p>";
                        mysqli_close($connect);
                        header ("Refresh: 10;URL=../HTML/ajout_eleve.html");
                    }
                    else { // Si l'utilisateur ne veut pas l'ajouter
                        echo "<p>Vous avez choisi d'annuler l'inscrption de l'élève.</p>";
                        echo "<p>Vous allez être redirigé vers la page d'ajout d'un élève.</p>";
                        header ("Refresh: 10;URL=../HTML/ajout_eleve.html");
                    }
                }
              ?>
        </div>
    </div>
    </body>
</html>
