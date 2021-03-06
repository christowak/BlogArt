<?php 
include "verifText.php";
include "connection.php";

$Lib1Lang = "";
$Lib2Lang = "";
$numPays = "";

echo $conn == NULL ? "oui" : "non";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "test 1";
    $Submit = isset($_POST['Submit']) ? $_POST['Submit'] : '';
    
    if(isset($_POST['id']) AND $_POST['id'] == 0) {
        echo "test 2";
        echo print_r($_POST);
        if( ( isset($_POST['Lib1Langs'])) AND 
            ( isset($_POST['Lib2Langs'])) AND
            ( isset($_POST['TypPays']))
        ) {
            echo "test 3";
            $Lib1Lang = ctrlSaisies($_POST["Lib1Langs"]);
            $Lib2Lang = ctrlSaisies($_POST["Lib2Langs"]);
            $numPays = ctrlSaisies($_POST["TypPays"]);

            echo $Lib1Lang . ' ' . $Lib2Lang . ' ' . $numPays;

            $error = false;

            $numPaysSelect = $numPays;
            $parmNumLang = $numPaysSelect . '%';
            $requete = "SELECT MAX(NumLang) AS NumLang FROM LANGUE WHERE NumLang LIKE '$parmNumLang;'";

            $result = $conn->query($requete);
            
            if($result) {
                $tuple = $result->fetch();
                $NumLang = $tuple["NumLang"];
                $numSeqLang = 0;
                $StrLang = "";
                if(is_null($NumLang)) {
                    $NumLang = 0;
                    $StrLang = $numPaysSelect;
                }else{
                    $SrtLang = substr($numLang, 0, 4);
                    $numSeqLang = (int)substr($NumLang, 4);
                }

                $numSeqLang++;
                $NumLang = $StrLang . ($numSeqLang < 10 ? '0' : '') . $numSeqLang;

                try {
                    $stmt = $conn->prepare("INSERT INTO LANGUE (NumLang, Lib1Lang, Lib2Lang, NumPays) VALUES (:NumLang, :Lib1Lang, :Lib2Lang, :NumPays)");
                    $stmt->bindParam(':NumLang', $numSeqLang);
                    $stmt->bindParam(':Lib1Lang', $Lib1Lang);
                    $stmt->bindParam(':Lib2Lang', $Lib2Lang);
                    $stmt->bindParam(':NumPays', $numPays);

                    $stmt->execute();
                } catch (\Throwable $th) {
                    //throw $th;
                }
                
            }


        }
    }

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogArt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1>Ajoutez une langue</h1>
        <form method="post" action="index.php">
            <div class="form-group">
                <label for="Lib1Lang">Libellé court</label>
                <input type="text" class="form-control" id="Lib1Langs" name="Lib1Langs" maxlength="25" placeholder="Libellé court" autofocus="autofocus" 
                value="<?php 
                        if(isset($_GET["id"])) {
                            echo $_POSt["LibLang1"];
                        }
                        ?>">
            </div>
            <div class="form-group">
                <label for="Lib2Lang">Libellé long</label>
                <input type="text" class="form-control" id="Lib2Langs" name="Lib2Langs" maxlength="25" placeholder="Libellé long">
            </div>
            <div class="form-group">
                <label for="TypPays">Libellé long</label>
                <input type="text" class="form-control" name="TypPays" id="TypPays" maxlength="25" placeholder="Pays">
            </div>
            <button name="id" type="submit" name="Submit" class="btn btn-primary">Valider</button>
        </form>
    </div>
</body>
</html>