<?php

//Falls der User bereits eingeloggt ist wird dieser zum dashboard weitergeleitet
session_start();

if(isset($_SESSION['svnummer'])) {
    header('Location: dashboard.php');
    return;
}

// Variablen um Fehlermeldungen anzeigen zu lassen
$login_failed = false;
$svnummer_error = false;

// Wenn sich der User versucht einzuloggen wird hier in der Datenbank geprüft
if(isset($_POST['svnummer']) && isset($_POST['code'])) {
    
    $svnummer = $_POST['svnummer'];
    $code = $_POST['code'];
    
    $svnummer_length = strlen((string) $svnummer);
    
    if($svnummer_length == 10) {
        $svnummer_error = true;
    }else{
        include_once('sql.php');
        $conn = connect();

        $result = $conn->query("SELECT * FROM users WHERE svnummer='" . $svnummer . "' AND code='" . $code . "'");

        if($result != null && $result->num_rows > 0 && $row = $result->fetch_assoc()) {

            //Bei richtigem Login: Setzt die Session Variablen und geht weiter zum dashboard
            $_SESSION['svnummer'] = $svnummer;
            $_SESSION['name'] = $row['name'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['mail'] = $row['mail'];
            $_SESSION['birthday'] = $row['birthday'];
            $_SESSION['location'] = $row['location'];

            header('Location: dashboard.php');
            $conn->close();
            return;
        }else{
            $conn->close();
            $login_failed = true;
        }
    }
}

?>

<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSS eingebunden -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        
        <title>Login</title>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center mb-5">
                <!-- Überschrift -->
                    <div class="col-lg-6 text-center">
                        <h2>Anmelden</h2>
                    </div>
                </div>
            <?php 
            //Wenn die Anmeldedaten falsch sind
            if($login_failed == true) { 
            ?>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Fehler beim Login...</h4>
                            <p>Die Sozialversicherungsnummer wurde nicht gefunden oder der Code wurde falsch eingegeben. Bitte überprüfen Sie die Eingaben und versuchen es dann erneut.</p>
                            <hr>
                            <p class="mb-0">Sie sind eventuell noch nicht registriert. Sie können sich jetzt <a href="register.php" class="alert-link">HIER</a> einen Account erstellen.</p>
                        </div>
                    </div>
                </div>
            <?php 
                // Wenn die SVNummer weniger als 10 Zeichen lang ist
            }else if($svnummer_error == true) { 
            ?>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="alert alert-danger pb-0" role="alert">
                            <p>Die Sozialversicherungsnummer muss genau 10 Zeichen lang sein.</p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <!-- Die Eingabe Felder für die Login Seite -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form method="post">
                        <div class="form-group">
                            <label for="svnummer">SV-Nummer</label>
                            <input id="svnummer" name="svnummer" type="number" class="form-control" value="<?php if(isset($_POST['svnummer'])) echo $_POST['svnummer']; ?>" required>
                            <small class="form-text text-muted">Die Sozialversicherungsnummer finden Sie auf Ihrer E-Card.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input id="code" name="code" type="password" class="form-control" required>
                        </div>
                        
                        <br>
                        <button type="submit" class="btn btn-success">Anmelden</button>
                        <a class="btn btn-link" href="register.php">Registrieren</a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- JS eingebunden -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>