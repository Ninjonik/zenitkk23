<?php 

    require_once('includes.php');

    
    if(isset($_POST["submit"])){

        $username = $_POST["username"];
        $password = $_POST["password"];

        if($username == 'admin' && $password == 'heslo123'){
            $_SESSION["loggedIn"] = true;
            header("Location: admin.php?action=loggedIn");
        } else {
            header("Location: admin.php?action=incorrectCredentials");
        }

    }

    $query = mysqli_execute_query($conn, 'SELECT r.*, l.title FROM reservation as r INNER JOIN location as l ON r.locationId=l.id ORDER BY r.skipas');
    $data = $query->fetch_all(MYSQLI_ASSOC);

    $locationQuery = mysqli_execute_query($conn, 'SELECT * FROM location ORDER BY title');
    $locationData = $locationQuery->fetch_all(MYSQLI_ASSOC);

    $newsletterQuery = mysqli_execute_query($conn, 'SELECT * FROM newsletter ORDER BY date DESC');
    $newsletterData = $newsletterQuery->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">

    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                bgcol: '#E4E2E0',
                textcol: '#525B64',
                hoverEffectIcons: '#617391',
                textCBG: '#FFFFFF',
                textCBGMore: '#DDDDDD',
                textCBGHover: '#111111',
                textCBGMoreHover: '#999999',
                textDark: '#4d565f',
                textDarker: "#617391",
              }
            }
          }
        }
      </script>
</head>
<body>
    
    <?php 

        switch($_GET["action"]){
            case "incorrectCredentials":
                echo "Nesprávne používateľské meno/heslo!";
                break;
            case "loggedIn":
                echo "Úspešne prihlásený!";
                break;
            case "notLoggedIn":
                echo "Musíte sa najprv prihlásiť!";
                break; 
            case "locationRemoved":
                echo "Lokácia bola úspešne odstránená!";
                break;
            default:
                echo "";
                break;
        }

        if(!$_SESSION["loggedIn"]){
            
            echo '

                <form method="POST">
                    <input type="text" name="username" placeholder="Používateľské meno">
                    <input type="password" name="password" placeholder="Používateľské heslo">
                    <button type="submit" name="submit">Odoslať</button>
                </form>

            ';
            return;

        }

    ?>

    <main class='flex flex-col items-center justify-center w-screen px-12 py-12 gap-8'>

        <section class='w-full flex flex-col gap-4'>
            <h1 class='text-4xl font-bold'>Rezervácie</h1>
            <table class='table-auto'>
                <thead class='bg-bgcol h-16 text-2xl'>
                    <tr>
                        <td>Celé meno</td>
                        <td>Mail</td>
                        <td>Telefón</td>
                        <td>Lokalita</td>
                        <td>Typ skipasu</td>
                        <td>Počet dní</td>
                        <td>Počet osôb</td>
                        <td>Termín</td>
                        <td>Cena</td>
                    </tr>
                </thead>
                <tbody class='gap-2'>

                    <?php 

                        $i = 0;

                        foreach($data as $row){

                            $class = $i % 2 == 1 ? 'bg-bgcol' : 'bg-bg-white';

                            $people = unserialize($row['people']);
                            $date = new DateTime($row['date']);

                            echo "      
                                <tr class='$class py-2 h-8'>
                                    <td>".$row['name']."</td>
                                    <td>".$row['mail']."</td>
                                    <td>".$row['phone']."</td>
                                    <td>".$row['title']."</td>
                                    <td>".$row['skipas']."</td>
                                    <td>".$row['days']."</td>
                                    <td class='flex flex-col text-center justify-center items-center'>
                                        <span>".$people['children']." detí</span>
                                        <span>".$people['juniors']." juniorov</span>
                                        <span>".$people['adults']." dospelých</span>
                                        <span>".$people['seniors']." seniorov</span>
                                    </td>
                                    <td>".$date->format('d.m.Y')."</td>
                                    <td>".$row['sum']."</td>
                                </tr>
                            ";

                            $i++;

                        }

                    ?>

                </tbody>
            </table>
        </section>

        <section class='w-full flex flex-col gap-4'>
            <h1 class='text-4xl font-bold'>Lokality</h1>
            <table class='table-auto'>
                <thead class='bg-bgcol h-16 text-2xl'>
                    <tr>
                        <td>ID</td>
                        <td>Názov</td>
                        <td>Obrázok</td>
                        <td>Odstrániť</td>
                    </tr>
                </thead>
                <tbody class='gap-2'>

                    <?php 

                        $iL = 0;

                        foreach($locationData as $row){

                            $class = $iL % 2 == 1 ? 'bg-bgcol' : 'bg-bg-white';

                            echo "      
                                <tr class='$class py-2 h-8'>
                                    <td>".$row['id']."</td>
                                    <td>".$row['title']."</td>
                                    <td>".$row['image']."</td>
                                    <td><a href='remove_location.php?id=".$row['id']."'>Odstrániť</a></td>
                                </tr>
                            ";

                            $i++;

                        }

                    ?>

                </tbody>
            </table>
        </section>

        <section class='w-full flex flex-col gap-4'>
            <h1 class='text-4xl font-bold'>Newsletter</h1>
            <table class='table-auto'>
                <thead class='bg-bgcol h-16 text-2xl'>
                    <tr>
                        <td>ID</td>
                        <td>Meno</td>
                        <td>Mail</td>
                        <td>Dátum a čas</td>
                    </tr>
                </thead>
                <tbody class='gap-2'>

                    <?php 

                        $iN = 0;

                        foreach($newsletterData as $row){

                            $class = $iN % 2 == 1 ? 'bg-bgcol' : 'bg-bg-white';

                            echo "      
                                <tr class='$class py-2 h-8'>
                                    <td>".$row['id']."</td>
                                    <td>".$row['name']."</td>
                                    <td>".$row['mail']."</td>
                                    <td>".$row['date']."</td>
                                </tr>
                            ";

                            $i++;

                        }

                    ?>

                </tbody>
            </table>
        </section>

    </main>

</body>
</html>