<?php

//Locale wird auf Deutsch gesetzt und die Monate in ein Array getan
setlocale(LC_ALL, "de_DE.utf8");

$monate = array(1=>"Januar",
                2=>"Februar",
                3=>"M&auml;rz",
                4=>"April",
                5=>"Mai",
                6=>"Juni",
                7=>"Juli",
                8=>"August",
                9=>"September",
                10=>"Oktober",
                11=>"November",
                12=>"Dezember");
$monat = date("n");

$date_error = false;

// Überprüfe ob der User angemeldet ist und eine Gültige Session hat, falls nicht wird er zur Login Page weitergeleitet
session_start();

if(!isset($_SESSION['svnummer'])) {
    header("Location: index.php");
    return;
}

// SQL File wird includiert
include_once('sql.php');

//wenn ein neuer Kurs hinzugefügt wird, dann wird das hier ausgeführt und ein Eintrag in der Datenbank erstellt
if(isset($_POST['addkurs']) && isset($_POST['kurse'])) {
    $conn = connect();
    
    
    $conn->query("INSERT INTO user_kurse(svnummer, kursnummer, leistungsart, datum, vontime, bistime, reiseziel, ort, teilnehmer, notiz) VALUES ('" . $_SESSION['svnummer'] . "', '" . $_POST['kurse'] . "', '" . $_POST['new_leistungsart'] . "', '" . $_POST['new_date'] . "', '" . $_POST['new_vontime'] . "', '" . $_POST['new_bistime'] . "', '" . $_POST['new_reiseziel'] . "', '" . $_POST['new_ort'] . "', '" . $_POST['new_teilnehmer'] . "', '" . $_POST['new_notiz'] . "')");
    
    $conn->close();
    
    header("Location: dashboard.php");
    return;
}

?>

<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSS Files eingebunden -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        
        <!-- JS Files eingebunden -->
        <script src="js/script.js"></script>
        
        <title>Dashboard</title>
    </head>
    <body>
        
        <!-- Navigations Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <!-- Navbar title -->
            <a class="navbar-brand" href="#">Abrechnung</a>
            
            <!-- Navbar Toggler Handy Ansicht -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Die Seiten zwischen denen man wechseln kann in der Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="dashboard.php">Kurse</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="monate.php">Monate</a>
                    </li>
                </ul>
                
                <!-- Navbar rechts, Abmelden und Name -->
                <a class="text-white mr-3"><?php echo $_SESSION['name'] . " " . $_SESSION['lastname']; ?></a>
                <a class="btn btn-danger my-2 my-sm-0" href="logout.php">Abmelden</a>
            </div>
        </nav>
        
        <div class="container mt-5">
            
            <!-- Die Modal Box zum Kurs auswählen -->
            <div class="modal fade" id="kurs_waehlen" tabindex="-1" role="dialog" aria-labelledby="kurs_waehlen_label" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="kurs_waehlen_label">Kurs auswählen</h5>
                      <!-- Der X Button rechts oben von der Modal Box -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                    <!-- Das Form zum Kurse auswählen -->
                    <form method="POST">
                  <div class="modal-body">
                      <div class="form-group">
                          <label for="kurse">Kurs:</label>
                          
                          <!-- Auflistung der Kurse in einem Select -->
                            <select name="kurse" class="form-control">
                            
                            <?php
                            
                            $conn = connect();

                            $result = $conn->query("SELECT * FROM kurs_aufbuchungen WHERE svnummer='" . $_SESSION['svnummer'] . "'");
                            $kurs_found = false;

                            if($result != null && $result->num_rows > 0) {
                                $kurs_found = true;
                                while($row = $result->fetch_assoc()) {
                                    
                                    $kursnummer = $row['kursnummer'];
                                    
                                    $mainkurs = getkurs($kursnummer);
                                    
                                    $name = $mainkurs['name'];
                                    
                                    echo '<option value="' . $kursnummer . '">' . $kursnummer . ' | ' . $name . '</option>';
                                }
                            }else{
                                echo '<option selected>Sie sind in keine Kurse eingeschrieben.</option>';
                            }
                          
                                $conn->close();
                            ?>
                          
                          </select>
                      </div>
                      
                      <!--Wenn ein User in einen Kurs eingeschrieben wurde, dann werden ihm die Eingabefelder Datum, Uhrzeit, Reiseziel, usw... angezeigt -->
                      <?php if($kurs_found == true) { ?>
                      
                      <div class="form-group">
                        <label for="new_leistungsart">Leistungsart:</label>
                          <select name="new_leistungsart" class="form-control">
                            <option>e-learning</option>
                              <option>Coaching</option>
                              <option>Nachbetreuung</option>
                              <option>Sonstige Einsätze</option>
                          </select>
                      </div>
                      
                      <div class="form-group">
                        <label for="new_date">Datum:</label>
                        <input type="date" name="new_date" class="form-control" required>
                      </div>
                      
                      <div class="form-group">
                        <label for="new_vontime">Von:</label>
                        <input type="time" name="new_vontime" class="form-control" required>
                      </div>
                      
                      <div class="form-group">
                        <label for="new_bistime">Bis:</label>
                        <input type="time" name="new_bistime" class="form-control" required>
                      </div>
                      
                      <div class="form-group">
                        <label for="new_reiseziel">Reiseziel:</label>
                        <input type="text" name="new_reiseziel" class="form-control" max="300" required>
                      </div>
                      
                      <div class="form-group">
                        <label for="new_ort">Zweck/Ort:</label>
                        <input type="text" name="new_ort" class="form-control" max="300" required>
                      </div>
                      
                      <div class="form-group">
                        <label for="new_teilnehmer">Teilnehmer:</label>
                          <textarea type="text" name="new_teilnehmer" class="form-control" rows="4" max="300"></textarea>
                      </div>
                      
                      <div class="form-group">
                        <label for="new_notiz">Notizen:</label>
                          <textarea type="text" name="new_notiz" class="form-control" rows="4" max="300"></textarea>
                      </div>
                      
                      <?php } ?>
                  </div>
                <!-- Modal Footer für die Buttons Hinzufügen und Abbrechen -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="success" name="addkurs" class="btn btn-success" <?php if($kurs_found == false) { echo 'disabled'; } ?> >Hinzufügen</button>
                  </div>
                    </form>
                </div>
              </div>
            </div>
            
            <!-- Überschrift der Seite, mit Namen -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6 text-center">
                    <h2>Ihre Kurse von <?php echo $monate[$monat]; ?></h2>
                    <p class="mt-0"><?php echo $_SESSION['name'] . " " . $_SESSION['lastname']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="ml-5 mr-5">
                <!-- Die Buttons zum Kurs auswählen, Monat abschließen und Einträge bearbeiten -->
                <div class="mb-3">
                    <button type="button" class="btn btn-success my-2 my-sm-0" data-toggle="modal" data-target="#kurs_waehlen">Kurs auswählen</button>
                    <button id="bearbeiten" type="button" class="btn btn-primary my-2 my-sm-0" onclick="bearbeiten()" style="visibility:visible;">Einträge bearbeiten</button>
                    <button type="button" class="btn btn-secondary my-2 my-sm-0" style="float: right;">Monat abschließen</button>
                </div>
                    
                    <!-- Tabelle mit der Leistungserfassung -->
                    <table class="table">
                        <thead class="thead-light">
                            <!-- Spalten Angabe -->
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Leistungsart</th>
                                <th scope="col">Datum</th>
                                <th scope="col">Beginn</th>
                                <th scope="col">Ende</th>
                                <th scope="col">Reiseziel</th>
                                <th scope="col">Zweck</th>
                                <th scope="col">Stunden</th>
                                <th scope="col">Teilnehmer</th>
                                <th scope="col">Notizen</th>
                            </tr>
                        </thead>
                        
                        <tbody id="tabelle">
                            
                            <?php
                            
                            //Die Tabelle wird hier mit den Daten aus der Tabelle user_kurse gefüllt.
                            
                            $conn = connect();

                            $result = $conn->query("SELECT * FROM user_kurse WHERE svnummer='" . $_SESSION['svnummer'] . "' ORDER BY datum DESC");

                            if($result != null && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    
                                    $id = $row['ID'];
                                    $kursnummer = $row['kursnummer'];
                                    $leistungsart = $row['leistungsart'];
                                    $datum = $row['datum'];
                                    $von = $row['vontime'];
                                    $bis = $row['bistime'];
                                    $reiseziel = $row['reiseziel'];
                                    $ort = $row['ort'];
                                    $teilnehmer = $row['teilnehmer'];
                                    $notiz = $row['notiz'];
                                    
                                    // Datum und Zeitformate werden in Deutsche umgewandelt
                                    $date = date_create($datum);
                                    $datum = date_format($date,"d.m.Y");
                                    
                                    $vontime = date_create($von);
                                    $von = date_format($vontime,"H:i");
                                    
                                    $bistime = date_create($bis);
                                    $bis = date_format($bistime,"H:i");
                                    
                                    $diff = $bistime->diff($vontime);
                                    $stunden = $diff->format("%h");
                                    
                                    if($vontime->getTimestamp() >= $bistime->getTimestamp()) {
                                        $date_error = true;
                                    }
                                    
                                    if($teilnehmer == null) {
                                        if($teilnehmer == null) {
                                            $teilnehmer = "Keine Teilnehmer";
                                        }
                                    }
                                    
                                    if($notiz == null) {
                                        $notiz = "Keine Notizen";
                                    }
                                    
                                    echo '<tr>';
                                    echo '<th scope="row">' . $kursnummer . '</th>';
                                    echo '<td name="editable_table" type="leistungsart" col="leistungsart" kurs="' . $id . '">' . $leistungsart . '</td>';
                                    echo '<td name="editable_table" type="date" col="datum" kurs="' . $id . '">' . $datum . '</td>';
                                    echo '<td name="editable_table" type="time" col="vontime" kurs="' . $id . '">' . $von . '</td>';
                                    echo '<td name="editable_table" type="time" col="bistime" kurs="' . $id . '">' . $bis . '</td>';
                                    echo '<td name="editable_table" type="maps" col="reiseziel" kurs="' . $id . '">' . $reiseziel . '</td>';
                                    echo '<td name="editable_table" type="maps" col="ort" kurs="' . $id . '">' . $ort . '</td>';
                                    echo '<td>' . $stunden . '</td>';
                                    echo '<td name="editable_table" type="text" col="teilnehmer" kurs="' . $id . '">' . $teilnehmer . '</td>';
                                    echo '<td name="editable_table" type="text" col="notiz" kurs="' . $id . '">' . $notiz . '</td>';
                                    echo '</tr>';
                                }
                            }
                            
                            $conn->close();

                            ?>
                        </tbody>
                    </table>
        </div>
        
        <?php
        
        // Wenn sich das mit den Uhrzeiten nicht ausgeht z.B. wenn die Von Uhrzeit größer als die Bis Uhrzeit ist, muss der User die einträge nocheinmal bearbeiten.
        if($date_error == true) {
            echo '<script>alert("Überprüfen Sie die Uhrzeiten!"); bearbeiten();</script>';
        }
        
        ?>
        
        <!-- Weitere JS Files eingebunden -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>