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

                if (empty($_POST["nom_theme"]) || empty($_POST["desc_theme"])) {?>
                    <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                    <p> Vous allez être redirigé vers la page d'ajout thème.</p>
                    <?php
                    header ("Refresh: 5;URL=../HTML/ajout_theme.html");
                } else {
                    // Si toutes les informations sont correctes

                    // Transformation de certains caractères HTML pour éviter les failles XSS, éviter l'interprétation de saisie comme du code
                    $nom_theme = htmlspecialchars($_POST["nom_theme"], ENT_QUOTES);
                    $desc_theme = htmlspecialchars($_POST["desc_theme"], ENT_QUOTES);

                    // Mise en majuscule du premier caractère du nom du thème et le reste en minuscule
                    $nom_theme = ucfirst(strtolower($nom_theme));

                    // Affichage sous forme de tableau
                    echo "<h2>Informations du thème saisies :</h2>
                           <p class='titre'>Récapitulatif des informations saisies :</p>
                           <table class='table_recap'>
                                <tr>
                                  <th>Nom :</th><th>Description :</th>
                                </tr>
                                <tr>
                                  <td>$nom_theme</td><td>$desc_theme</td>
                                </tr>
                           </table>";


                    // Connexion à la BDD via le fichier connexion.php
                    include('connexion.php');
                    $query = "SELECT *
                              FROM themes
                              WHERE nom='$nom_theme'";

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

                    $compteur__t = mysqli_num_rows($result);
                    if ($compteur__t == 0) { // S'il n'y a pas de thème déjà existante avec ce nom
                        $query_i = "INSERT INTO themes
                                    VALUES (NULL,'$nom_theme','$desc_theme', 0)";

                        // Affichage de la requête
                        // echo "<br>$query_i<br>";

                        // Exécution de la requête
                        $result_i = mysqli_query($connect, $query_i);

                        // Test de l'exécution de la requête
                        if (!$result_i)
                        {// Si la requête a rencontré une erreur
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        mysqli_close($connect);
                        echo "<p>Le thème $nom_theme a bien été ajouté à la base de données.</p>";?>
                        <p> Vous allez être redirigé vers la page d'ajout thème.</p>
                        <?php
                        header ("Refresh: 10;URL=../HTML/ajout_theme.html");

                    } else {
                        $row = mysqli_fetch_array($result, MYSQLI_NUM); // Récupération des informations du thème
                        echo "<p class='information'>Il existe déjà un thème du même nom:</p>";

                        if ($row[3] == 1) { // Si un thème a été supprimé et a le même nom que celui saisi
                            echo "<p>Ce thème est actuellement désactivé.</br></br>
                                  Voulez-vous le réactiver ?</p>
                                  <form action='ajouter_theme.php' method='post'>
                                    <INPUT TYPE='radio' NAME='conf_ajout' VALUE='1' CHECKED>Oui, avec changement de description.<BR>
                                    <INPUT TYPE='radio' NAME='conf_ajout' VALUE='2'>Oui, sans changement de description.<BR>
                                    <INPUT TYPE='radio' NAME='conf_ajout' VALUE='3'>Non.<BR>
                                    <INPUT type='hidden' name='id_theme' value='$row[0]'>
                                    <INPUT type='hidden' name='nom_theme' value='$nom_theme'>
                                    <INPUT type='hidden' name='desc_theme' value='$desc_theme'>
                                    <div class='conf_res'>
                                     <div class='conf'>
                                         <INPUT type='submit' value='Enregistrer'>
                                     </div>
                                   </div>
                                  </form>";
                                  mysqli_close($connect);
                        } else { // Si un thème existe déjà avec ce nom
                            ?>
                            <p class="erreur">Erreur : Le thème du même nom n'est pas supprimé.<p>
                            <p> Vous allez être redirigé vers la page d'ajout thème.</p>
                            <?php
                            mysqli_close($connect);
                            header ("Refresh: 10;URL=../HTML/ajout_theme.html");
                        }
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>
