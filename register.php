<?php

// Überprüft ob der User bereits angemeldet ist
session_start();

if(isset($_SESSION['svname'])) {
    header('Location: dashboard.php');
    return;
}

// Variablen für Fehlermeldungen
$already_exists = false;
$password_fail = false;
$svnummer_error = false;

// Überprüfe ob alle Eingaben von der Registrierung getätigt wurden.
if(isset($_POST['svnummer']) && isset($_POST['firmenmail']) && isset($_POST['geburtsdatum'])
    && isset($_POST['code']) && isset($_POST['code2']) && isset($_POST['location'])) {
    
    $svnummer = $_POST['svnummer'];
    $firmenmail = $_POST['firmenmail'];
    $geburtsdatum = $_POST['geburtsdatum'];
    $location = $_POST['location'];
    $code = $_POST['code'];
    $code2 = $_POST['code2'];
    
    $svnummer_length = strlen((string) $svnummer);
    
    if($svnummer_length == 10) {
        $svnummer_error = true;
    }else{
        // Überprüft ob der User bereits existiert und erstellt anschließend einen in der Datenbank. Der User wird anschließend weiter zum dashboard geleitet.
        
        include_once('sql.php');
        $conn = connect();

        $result = $conn->query("SELECT svnummer FROM users WHERE svnummer='" . $svnummer . "' OR mail='" . $firmenmail . "'");

        if($result != null && $result->num_rows > 0) {
            $already_exists = true;
        }else{
            if($code != $code2) {
                $password_fail = true;
            }else{
                $conn->query("INSERT INTO users VALUES (" . $svnummer . ", 'Max', 'Mustermann', '" . $firmenmail . "', '" . $geburtsdatum . "', '" . $code . "', '" . $location . "')");

                $_SESSION['svnummer'] = $svnummer;
                $_SESSION['name'] = 'Max';
                $_SESSION['lastname'] = 'Mustermann';
                $_SESSION['mail'] = $firmenmail;
                $_SESSION['birthday'] = $geburtsdatum;
                $_SESSION['location'] = $location;
                
                header('Location: dashboard.php');
                $conn->close();
                return;
            }

        }
        $conn->close();
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
        
        <title>Registrierung</title>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center mb-5">
                    <div class="col-lg-6 text-center">
                        <h2>Registrierung</h2>
                    </div>
                </div>
            <?php 
            // Fehlermeldung wenn der Account bereits existiert
            if($already_exists == true) { 
            ?>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Fehler beim Registrieren...</h4>
                            <p>Die Sozialversicherungsnummer und/oder die Firmenmail sind bereits registriert.</p>
                            <hr>
                            <p class="mb-0">Klicken Sie <a href="index.php" class="alert-link">HIER</a> um sich anzumelden.</p>
                        </div>
                    </div>
                </div>
            <?php 
            // Fehlermeldung wenn die Passwörter nicht übereinstimmen
            }else if($password_fail == true) { 
            ?>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="alert alert-danger pb-0" role="alert">
                            <p>Die Codes stimmen nicht überein.</p>
                        </div>
                    </div>
                </div>
            <?php
            // Fehlermeldung wenn die SVNummer zu kurz ist.
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
            <!-- Eingabefelder für die Registrierung -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form method="post">
                        <div class="form-group">
                            <label for="svnummer">SV-Nummer</label>
                            <input id="svnummer" name="svnummer" type="number" class="form-control" value="<?php if(isset($_POST['svnummer'])) echo $_POST['svnummer']; ?>" required>
                            <small class="form-text text-muted">Die Sozialversicherungsnummer finden Sie auf Ihrer E-Card.</small>
                        </div>
                        <div class="form-group">
                            <label for="location">Firmen Standort</label>
                            <input id="location" name="location" type="text" class="form-control" value="<?php if(isset($_POST['location'])) echo $_POST['location']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="firmenmail">Firmen E-Mail</label>
                            <input id="firmenmail" name="firmenmail" type="email" class="form-control" value="<?php if(isset($_POST['firmenmail'])) echo $_POST['firmenmail']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="geburtsdatum">Geburtsdatum</label>
                            <input type="date" name="geburtsdatum" class="form-control" value="<?php if(isset($_POST['geburtsdatum'])) echo $_POST['geburtsdatum']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input id="code" name="code" type="password" class="form-control"  required>
                        </div>
                        <div class="form-group">
                            <label for="code2">Code wiederholen</label>
                            <input id="code2" name="code2" type="password" class="form-control" required>
                        </div>
                        
                        <br>
                        <button type="submit" class="btn btn-success">Registrieren</button>
                        <a class="btn btn-link" href="index.php">Anmelden</a>
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