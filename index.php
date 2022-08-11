<?php
        session_save_path("tmp");
        session_start();
$loginError= '';
if (isset($_SESSION['user']) == 'user' && isset($_SESSION['password']) == 'user') {
} else if (!isset($_POST['user']) && !isset($_POST['password'])) {
    include 'login.php';
    return;
} else {
    if ($_POST['user'] == 'user' && $_POST['password'] == 'user'){
        $_SESSION["user"] = $_POST['user'];
        $_SESSION["password"] = $_POST['password'];
    } else {
        $loginError =  "Nepareizi lietotāja dati!";
        include 'login.php';
        return;
    }
}
?>
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'path.php';
    require 'vendor/autoload.php';
    require_once 'SimpleXLSX.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Automatizēta rēķinu sūtīšanas sistēma">
    <meta name="author" content="Nauris Ieviņš">
    <link rel="stylesheet" href="style.css">
    <title>Automatizēta rēķinu sūtīšanas sistēma</title>
</head>
<body>

<div id='header'>
    <div class='floatLeft'><h2>Klientu datu tabula</h2></div>
    <div class='floatRight'>
        <form action="logout.php" method="POST">
            <input type="submit" name="logout" value="Atslēgties" class="redBtn">
        </form>
    </div>
    <div class='floatRight'>
        <form action="" method="POST">
            <input type="submit" name="viewLogFile" value="Apskatīt log failu" class="greenBtn">
        </form>
    </div>
</div>

    <div id='mainTable'>
        <div id='xlsxTable'>
            <?php
            $first = true;
            if ($xlsx = SimpleXLSX::parse($xlsxPath)) {
                echo "<table cellpadding='3'>";
                $data = $xlsx->rows();
                foreach($data as $row) {
                    echo "<tr>";
                    if ($first) {
                        echo "<th>" . implode('</th><th>', $row) . "</th>";
                        $first = false;
                    } else {
                        echo "<td>" . implode('</td><td>', $row) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo SimpleXLSX::parseError();
                return;
            }
            echo "</div>";

            function viewLog() {
                echo "<div id='logFile'>";
                    echo '<form id="logForm" action="" method="POST">';
                    echo '<h2 class="floatLeft">Log ieraksti</h2>';
                        echo '<input type="submit" name="closeLogFile" value="[X]" class="logBtn redBtn">';
                        echo '<div id="logBtnFrame"><input type="submit" name="clearLogFile" value="Iztīrīt log failu" class="logBtn greenBtn">';
                        echo '<input type="checkbox" name="xClearLogFile" class="logBtn"></div>';
                    echo '</form>';
                $logFile = fopen("log.txt", "r") or die("Nevar nolasīt log.txt failu!");
                while (!feof($logFile)) {
                    echo fgets($logFile);
                    echo "<br>";
                }
                echo "</div>";
                fclose($logFile);
            }

            if(isset($_POST['viewLogFile'])) {
                viewLog();
            } else if(isset($_POST['clearLogFile']) && isset($_POST['xClearLogFile'])) {
                $logFile = fopen("log.txt", "r+");
                ftruncate($logFile, 0);
                fclose($logFile);
                viewLog();
            } else if(isset($_POST['clearLogFile'])) {
                viewLog();
            }
            ?>

    </div>
    <form method="post" style="margin:10px;">
        <input type="submit" name="check" value="Pārbaudīt pirms sūtīšanas" class="greenBtn">
    </form>
    <div id="header">
        <h2>Pārbaudes paziņojums</h2>
    </div>
<?php
echo "<div id='info'>";
$isFirst=true;
if(isset($_POST['check'])) {
    echo "<p>(-) Norādītā rēķinu direktorija: <i>" . $dirPath . "</i></p>";
    foreach($data as $row) {
        if ($isFirst) {
            $isFirst = false;
        } else {
            static $count=0;
            static $ready=0;
            $name = $row[1];
            $email = $row[2];
            $id = $row[3];
            $bool = $row[4];
            if ($bool === 1 && $name != null && $email != null && $id != null) {
                $count++;
                $path = "$dirPath". '/' ."$id". '*.pdf';
                $file = glob($path);
                if (count($file) > 1) {
                    echo "<span style='color:red;'>- Klientam <b>" .$name."</b> tika atrasti vairāk kā viens rēķins! </span>";
                    foreach($file as $item) {
                        echo "(" . basename($item) . "); ";
                    }
                    echo "<br>";
                    $ready++;
                } else if ($file != null) {
                    $file = basename($file[0]);
                    echo "- Klientam <b>" .$name."</b> tiks nosūtīts rēķins ar nosaukumu <b>" .$file."</b> uz adresi: <b>".$email." </b><br>";
                } else {
                    echo "<span style='color:red;'>- Klientam <b>" .$name."</b> nav atrasts rēķins, ko nosūtīt!</span><br>";
                    $ready++;
                }
            }
        }
    }
    echo "<p style='color:#b14f29;'>(-) Atzīmēti/-s ".$count." klienti, kuriem nepieciešams nosūtīt rēķinus.</p>";
    echo "</div>";

    if ($ready == 0) {
    echo "<form method='post' style='margin:10px;'>";
    echo "<input type='submit' name='submit' value='Nosūtīt rēķinus' class='greenBtn'>";
    echo "</form>";
    } else {
        echo "<div id='danger'>";
        echo "<p>(!) Nevar izpildīt, jo ".$ready." adresātam/-tiem nav atrasts, vai ir atrasti vairāki rēķini, ko nosūtīt!</style>";
        echo "</div>";
    }
}

if(isset($_POST['submit'])) {
    require_once 'mail.php';
}
?>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

</body>
</html>