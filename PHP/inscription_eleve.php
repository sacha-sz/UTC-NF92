<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Inscription d'un élève</title>
    </head>

    <body class="form">
        <div class="page">
            <div class="cadre">
                <?php
                    echo"<h2>Information de l'inscription:</h2>";

                    // Récupération de la date
                    date_default_timezone_set('Europe/Paris');
                    $date = date("Y-m-d");

                    include('connexion.php');

                    if (empty($_POST["id_seance"]) || empty($_POST["id_eleve"])) {?>
                        <p class="erreur">Erreur : Vous n'avez pas saisi toutes les informations.</p>
                        <p> Vous allez être redirigé vers la page d'inscription.</p>
                        <?php
                        header ("Refresh: 5;URL=inscrire_eleve.php");
                    } else {
                        $id_s = htmlspecialchars($_POST["id_seance"], ENT_QUOTES);
                        $id_e = htmlspecialchars($_POST["id_eleve"], ENT_QUOTES);


                        // Requête récupération des élèves inscrit à la séance
                        $query_nb_e = "SELECT *
                                       FROM inscription
                                       WHERE idseance = $id_s";
                                       
                        // echo "<br> $query_nb_e <br>";
                        $result_nb_e = mysqli_query($connect, $query_nb_e);

                        // Test pour débogage
                        if (!$result_nb_e){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        // Nombre d'élèves inscrits à la séance
                        $nb_inscrit = mysqli_num_rows($result_nb_e);


                        // Requête de test si l'élève est déjà inscrit à cette séance
                        $query_e = "SELECT *
                                    FROM inscription
                                    WHERE idseance = $id_s
                                    AND ideleve = $id_e";

                        // echo "<br> $query_e <br>";
                        $result_e = mysqli_query($connect, $query_e);

                        // Test pour débogage
                        if (!$result_e){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        // Si l'élève est déjà inscrit à cette séance
                        $nb_e = mysqli_num_rows($result_e);

                        // Requête pour récupérer les informations de la séance
                        $query_s = "SELECT *
                                    FROM seances
                                    WHERE idseance = $id_s";

                        // echo "<br> $query_s <br>";
                        $result_s = mysqli_query($connect, $query_s);
                        if (!$result_s){
                            echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                            mysqli_close($connect);
                            exit;
                        }
                        // Récupération des informations de la séance
                        $seance = mysqli_fetch_array($result_s,MYSQLI_BOTH);


                        if ($nb_e != 0) { // L'élève est déjà inscrit
                            mysqli_close($connect); ?>
                            <p class="erreur">Erreur : L'élève est déjà inscrit à cette séance.</p>
                            <p> Vous allez être redirigé vers la page d'inscription.</p>
                            <?php
                            header ("Refresh: 5;URL=inscrire_eleve.php");
                        } else {
                            if ($nb_inscrit + 1 > $seance[2]){// Il y a déjà le maximum d'élève
                                mysqli_close($connect);?>
                                <p class="erreur">Erreur : Il y a déjà le maximum d'élève inscrit à cette séance.</p>
                                <p> Vous allez être redirigé vers la page d'inscription.</p>
                                <?php
                                header ("Refresh: 5;URL=inscrire_eleve.php");
                            }
                            else {
                                $query ="INSERT INTO inscription
                                         VALUES($id_s, $id_e, -1)";

                                // echo "<br> $query <br>";
                                $result = mysqli_query($connect, $query);
                                if (!$result){
                                    echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                                    mysqli_close($connect);
                                    exit;
                                }

                                $query_info = "SELECT prenom, nom, dateNaiss
                                               FROM eleves
                                               WHERE ideleve = $id_e";

                                // echo "<br> $query_info <br>";
                                $result_info = mysqli_query($connect, $query_info);
                                if (!$result_info){
                                    echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                                    mysqli_close($connect);
                                    exit;
                                }
                                $info = mysqli_fetch_array($result_info,MYSQLI_BOTH);
                                echo "<p>L'élève $info[0] $info[1] né(e) le $info[2] a bien été inscrit à la séance.</p>";
                                ?>
                                <p> Vous allez être redirigé vers la page d'inscription.</p>
                                <?php
                                header ("Refresh: 5;URL=inscrire_eleve.php");
                                mysqli_close($connect);
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
