<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Désinscription séance</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <h2>Désinscription d'un élève d'une séance</h2>
                <?php
                    // Récupération de la date
                    date_default_timezone_set('Europe/Paris');
                    $date = date("Y-m-d");
                    include('connexion.php');

                    if (empty($_POST['id_eleve']))
                    { // Si l'id_eleve n'est pas renseigné
                        ?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi d'élève.</p>
                        <p> Vous allez être redirigé vers la page de desinscription d'un élève.</p>
                        <?php
                        header ("Refresh: 5;URL=desinscription_seance.php");
                    } else { // Si l'id_eleve est renseigné
                        $id = htmlspecialchars($_POST['id_eleve'], ENT_QUOTES);

                        // On récupère les informations de l'élève
                        $query_1 = "SELECT ideleve, nom, prenom, dateNaiss
                                    FROM eleves
                                    WHERE ideleve=$id
                                    ORDER BY prenom, nom";

                        // echo "<br> $query_1 <br>";

                        $result_1 = mysqli_query($connect, $query_1);
                        if (!$result_1){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }

                        $query2 = "SELECT s.idseance, s.DateSeance, t.nom
                                   FROM seances s
                                   INNER JOIN themes t ON t.idtheme = s.Idtheme
                                   INNER JOIN inscription i ON i.idseance=s.idseance
                                   WHERE i.ideleve=$id
                                   AND '$date' < s.DateSeance
                                   ORDER BY s.DateSeance";

                        // echo "<br> $query2 <br>";

                        $result_2 = mysqli_query($connect, $query2);
                        if (!$result_2){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        $nb_seances = mysqli_num_rows($result_2);

                        if ($nb_seances==0)
                        { // Si l'élève n'est inscrit à aucune séance future, vérification également faite sur la page précédente
                            ?>
                            <p class="erreur">Erreur : L'élève n'est inscrit à aucune séance future.</p>
                            <p> Vous allez être redirigé vers la page de desinscription d'un élève.</p>
                            <?php
                            header ("Refresh: 5;URL=desinscription_seance.php");
                        } else {
                            // Formulaire choix séance :
                            echo "<FORM METHOD='POST' ACTION='rm_eleve.php' >
                                    <label for='id_seance'>Choisissez une séance future</label>
                                    <SELECT name='id_seance' required >
                                        <option value='' selected disabled>-- Sélection une séance --</option>";
                                            while ($row = mysqli_fetch_array($result_2, MYSQLI_NUM)) {
                                                echo "<OPTION value='$row[0]'> $row[2] du $row[1]</OPTION>";
                                            }
                            echo "  </SELECT>
                                    <input type='hidden' name='id_eleve' value='$id'>
                                    <div class='conf_res'>
                                        <div class='conf'>
                                            <input type='submit' value='Choisir séance'>
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
