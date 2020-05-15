<?php

//
//
// WARNUNG: AUF DIESER SEITE IST NUR DESIGN, HIER WURDE NOCHT NICHTS PROGRAMMIERT!!!!
//
//

//Login Überprüfung
session_start();

if(!isset($_SESSION['svnummer'])) {
    header("Location: index.php");
    return;
}
?>

<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        
        <title>Dashboard</title>
    </head>
    <body>
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Abrechnung</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Kurse</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="monate.php">Monate</a>
                    </li>
                </ul>
                <a class="btn btn-danger my-2 my-sm-0" href="logout.php">Abmelden</a>
            </div>
        </nav>
        
        <div class="container mt-5">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6 text-center">
                    <h2>Abgeschlossene Monate</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-3">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Monat</th>
                                <th scope="col">Kurse</th>
                                <th scope="col">Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">April</th>
                                <td>3</td>
                                <td><a class="btn btn-primary my-2 my-sm-0" href="dashboard.php?monat=April">Ansehen</a></td>
                            </tr>
                        </tbody>
                    </table>
        </div>
        
        
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>