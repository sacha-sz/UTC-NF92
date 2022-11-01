<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../CSS/form.css">
        <title>Consulter les informations</title>
    </head>
    <body class="form">
        <div class="page">
            <div class="cadre">
                <?php
                echo "<h2>Information de l'élève</h2>";
                if (empty($_POST['id_eleve'])){?>
                    <p class="erreur">Erreur : Vous n'avez pas saisi d'élève.</p>
                    <p> Vous allez être redirigé vers la page de consultation des élèves.</p>
                    <?php
                    header ("Refresh: 5;URL=consultation_eleve.php");
                } else {
                    include('connexion.php');
                    $id = htmlspecialchars($_POST['id_eleve'], ENT_QUOTES);

                    $query = "SELECT * FROM eleves WHERE ideleve=$id";
                    // echo "<br> $query <br>";
                    $result = mysqli_query($connect, $query);
                    if (!$result){
                        echo "<p class='erreur'>Erreur rencontrée : ".mysqli_error($connect)."</p>";
                        exit;
                    }
                    $row = mysqli_fetch_array($result, MYSQLI_NUM);
                    echo "L'élève s'appelle $row[2] $row[1] est né le $row[3] et est inscrit depuis le $row[4].";
                    mysqli_close($connect);
                    ?>
                    <p> Vous allez être redirigé vers la page de consultation.</p>
                    <?php
                    header ("Refresh: 8;URL=consultation_eleve.php");
                }
                ?>
            </div>
        </div>
    </body>
</html>
