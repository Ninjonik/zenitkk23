<?php 

    require_once("includes.php");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900" rel="stylesheet">

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
              },
              screens: {
                'sm': '640px',
                'xxl': {'min': '10px', 'max': '100px'},
              },
            }
          }
        }
      </script>
      <style>
        .bg-main {
            background-image: url('images/head01.jpg')
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Roboto';
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Patua One';
        }
      </style>
</head>

<?php

    $user = "";
    $mail = "";

    if(isset($_POST["submit_newsletter"])){

        $user = $_POST["newsletter_user"];
        $mail = $_POST["newsletter_email"];

        if(empty($user) || empty($mail)){
            header("Location: index.php?action=fillAllInputs");
        }

        $uniqueSqlCheck = "SELECT id FROM newsletter WHERE name='".$user."'";
        $stmt = mysqli_execute_query($conn,$uniqueSqlCheck);
        $result = $stmt->fetch_all(MYSQLI_ASSOC);

        if($result){
            header("Location: index.php?action=alreadyUsedName");
        }

        $insertSql = "INSERT INTO `newsletter` (`name`, `mail`, `date`) VALUES ('".$user."', '".$mail."', NOW())";
        $stmt = mysqli_execute_query($conn, $insertSql);

        header("Location: index.php?action=successfulySignedNewsletter");

    }

?>

<body class="flex flex-col items-center w-full min-h-screen">
    <main class="w-[1140px] flex flex-col items-center" id="top">

        <?php 

        $name = "";
        $mail = "";
        $phone = "";
        $location = "";
        $skipas = "";
        $days = "";
        $date = "";
        $people = [];
        $juniors = "";
        $adults = "";
        $seniors = "";

        if(isset($_POST["submit_reservation"])){

            $base = 10;

            $name = $_POST["skipas_name"];
            $mail = $_POST["skipas_email"];
            $phone = $_POST["skipas_phone"];
            $location = intval($_POST["skipas_location"]);
            $skipas = intval($_POST["skipas_skipas"]);
            $days = $_POST["skipas_days"];
            $date = $_POST["skipas_date"];
            $people = [
                'children' => 0,
                'juniors' => 0,
                'adults' => 0,
                'seniors' => 0
            ];

            $children = $_POST["skipas_people_children"];
            $juniors = $_POST["skipas_people_juniors"];
            $adults = $_POST["skipas_people_adults"];
            $seniors = $_POST["skipas_people_seniors"];

            $people['children'] = $children;
            $people['juniors'] = $juniors;
            $people['adults'] = $adults;
            $people['seniors'] = $seniors;

            $people = serialize($people);

            if(empty($name) || empty($mail) || empty($phone) || empty($location) || empty($skipas) || empty($days) || empty($date)|| empty($children)|| empty($juniors)|| empty($adults)|| empty($seniors) ){
                header("Location: index.php?action=fillAllInputs");
            }

            $price = $base * 0.7 * $children + $base * 0.8 * $juniors + $base * 1 * $adults + $base * 0.85 * $seniors;

            $insertQuery = "INSERT INTO reservation (`locationId`, `mail`, `phone`, `name`, `people`, `skipas`, `days`, `date`, `sum`, `updated_at`) VALUES ('$location', '$mail', '$phone', '$name', '$people', '$skipas', '$days', '$date', '$price', NOW())";
            $result = mysqli_query($conn, $insertQuery);

            header("Location: index.php?action=reservationSubmitted");
        }

        ?>

        <a href="#top" class='fixed z-50 flex items-center justify-center w-16 h-16 text-3xl text-center transition-all ease-in rounded-full bg-bgcol bottom-10 right-10 text-textCBG' title='A' id='scrollToTop'>A</a>

        <script>

            const scrollToTop = document.getElementById('scrollToTop')

            window.addEventListener("scroll", () => {
                if (window.pageYOffset > 100){
                    scrollToTop.classList.remove('hidden')
                } else {
                    scrollToTop.classList.add('hidden')
                }
            })

        </script>

        <form class="w-[60dvw] bg-gray-100 flex flex-col gap-4 rounded-md p-8 fixed right-50 top-[10dvh] z-50" name="reservation" method="POST" id='modal' onSubmit='closeModal()'>
            <h2 class="text-4xl text-center">Rezervácia skipasu</h2>
            <input class="flex p-2 text-gray-200 bg-white text-end" type="text" name="skipas_name" placeholder="Celé meno" value="<?php echo($name); ?>" required>
            <input class="flex p-2 text-gray-200 bg-white text-end" type="email" name="skipas_email" placeholder="Kontaktný email" value="<?php echo($mail); ?>" required>
            <input class="flex p-2 text-gray-200 bg-white text-end" type="text" name="skipas_phone" placeholder="Kontaktné telefónne číslo" value="<?php echo($phone); ?>" required>
            <select name="skipas_location" required>
                <?php 
                
                    $locationsQuery = mysqli_execute_query($conn, "SELECT * FROM location");
                    $locations = $locationsQuery->fetch_all(MYSQLI_ASSOC);

                    foreach($locations as $location){

                        echo ("
                            
                            <option value=".$location['id'].">
                                ".$location['title']."
                            </option>

                        ");

                    }
                
                ?>
            </select>
            <select name="skipas_skipas" required>
                <option value="0">Jednodňový</option>
                <option value="1">Viacdňový</option>
                <option value="2">Sezónny</option>
            </select>
            <input class="flex p-2 text-gray-200 bg-white text-end" value="<?php echo($days); ?>" type="number" name="skipas_days" placeholder="Počet dní" min="1" value="1" required>
            <input class="flex p-2 text-gray-200 bg-white text-end" type="date" value="<?php echo($date); ?>" name="skipas_date" placeholder="Termín" required>
            <h3 class="text-2xl text-center">Počet osôb</h3>
            <input class="flex p-2 text-gray-200 bg-white text-end" value="<?php echo($children); ?>" onChange='updatePrice()' type="number" name="skipas_people_children" placeholder="Dieťa (6-11r.)" id="children" value="0" min="0" required>
            <input class="flex p-2 text-gray-200 bg-white text-end" value="<?php echo($juniors); ?>" onChange='updatePrice()' type="number" name="skipas_people_juniors" placeholder="Junior (12-17r.)" id="juniors" value="0" min="0" required>
            <input class="flex p-2 text-gray-200 bg-white text-end" value="<?php echo($adults); ?>" onChange='updatePrice()' type="number" name="skipas_people_adults" placeholder="Dospelý (18-59r.)" id="adults" value="0" min="0" required>
            <input class="flex p-2 text-gray-200 bg-white text-end" value="<?php echo($seniors); ?>" onChange='updatePrice()' type="number" name="skipas_people_seniors" placeholder="Senior (60r.+)" id="seniors" value="0" min="0" required>
            <h3 class="text-2xl text-center">Výsledná cena: <span id="price">0</span>E</h3>
            <button class="transition-all ease-in border border-white bg-none hover:bg-textDarker" type="submit" name="submit_reservation">Rezervovať</button>
            <button class="transition-all ease-in border border-white bg-none hover:bg-textDarker" type="button" onClick='closeModal()'>Zavrieť</button>
        </form>

        <header class="w-full flex flex-row justify-center items-center bg-fill bg-no-repeat bg-main h-[60dvh] text-white gap-8">
            <div class="flex flex-col w-1/2 text-center">
                <h1 class="text-[80px] text-outline">ZenSki</h1>
                <span class="text-md xxl:text-red-500">Doprajte si zimnú dovolenku na nezabudnutie. Lyžiarske stredisko ZenSki je ideálnym miestom pre lyžiarov, snowboardistov, rodiny s deťmi a milovníkov prírody.            </span>
            </div>
            <form class="flex flex-col gap-3" name="newsletter" method="POST">
                <h2 class="text-3xl">Chcem dostávať novinky</h2>
                <input class="flex p-2 text-gray-200 bg-white text-end" type="text" name="newsletter_user" placeholder="Zadajte svoje meno" min="4" max="64" value="<?php echo($user) ?>" required>
                <input class="flex p-2 text-gray-200 bg-white text-end" type="email" name="newsletter_email" placeholder="Zadajte emailovú adresu" value="<?php echo($mail) ?>" required>
                <button class="transition-all ease-in border border-white bg-none hover:bg-textDarker" type="submit" name="submit_newsletter">Odoslať</button>
            </div>
        </header>
        <article class="w-full p-16 flex flex-col md:flex-row items-center justify-center text-center bg-[#E4E2E0]">
            <div class="flex flex-col items-center justify-center w-full h-full text-center">
                <h3 class="text-[120px] text-white font-bold">01.</h3>
                <h2 class="text-textDark text-[50px]">Zjazdovky <br /> pre všetkých</h2>
                <span class="text-sm italic">Ponúkame širokú škálu zjazdoviek pre všetky úrovne zručností, vrátane náročných zjazdoviek pre pokročilých, ako aj miernych zjazdoviek pre začiatočníkov a rodiny s deťmi.            </span>
            </div>
            <div class="flex flex-col items-center justify-center w-full h-full text-center">
                <h3 class="text-[120px] text-white font-bold">02.</h3>
                <h2 class="text-textDark text-[50px]">Prekrásna <br /> príroda</h2>
                <span class="text-sm italic">Stredisko sa nachádza v krásnej horskej oblasti a ponúka nádherné výhľady na okolitú prírodu. Okrem lyžovania a snowboardingu tu môžete využiť aj množstvo ďalších aktivít.
                </span>
            </div>
            <div class="flex flex-col items-center justify-center w-full h-full text-center">
                <h3 class="text-[120px] text-white font-bold">03.</h3>
                <h2 class="text-textDark text-[50px]">Lyžiarska <br /> škola</h2>
                <span class="text-sm italic">Lyžiarska škola je skvelou možnosťou pre začiatočníkov, ktorí sa chcú naučiť lyžovať alebo snowboardovať. Lyžiarski inštruktori poskytujú individuálne alebo skupinové lekcie.</span>
            </div>
        </article>
        <article class="text-white grid-special grid grid-cols-12 grid-rows-3 w-full h-[50dvh]">
            <div class="flex flex-col items-center justify-between col-span-4 row-span-2 p-8 transition-all ease-in bg-no-repeat bg-cover hover:text-black filter grayscale hover:grayscale-0" style="background-image: url(images/res02.jpg)">
                <div class="flex flex-col gap-2 text-center">
                    <h2 class="text-4xl">Špeciálna ponuka</h2>
                    <h3 class="text-md">UŠETRITE 25%</h3>
                </div>
                <button class="w-48 font-bold transition-all ease-in border border-white text-md bg-none hover:bg-textDarker" onClick='openModal(1)'>Rezervovať skipas</button>
            </div>
            <div class="flex flex-col items-center justify-between col-span-4 row-span-2 p-8 transition-all ease-in bg-no-repeat bg-cover hover:text-black filter grayscale hover:grayscale-0" style="background-image: url(images/res02.jpg)">
                <div class="flex flex-col gap-2 text-center">
                    <h2 class="text-4xl">Festival</h2>
                    <h3 class="text-md">UŽ ZA 3 DNI</h3>
                </div>
                <button class="w-48 font-bold transition-all ease-in border border-white text-md bg-none hover:bg-textDarker" onClick='openModal(2)'>Rezervovať skipas</button>
            </div>
            <div class="flex flex-col items-center justify-between col-span-4 row-span-2 p-8 transition-all ease-in bg-no-repeat bg-cover hover:text-black filter grayscale hover:grayscale-0" style="background-image: url(images/res02.jpg)">
                <div class="flex flex-col gap-2 text-center">
                    <h2 class="text-4xl">Rodinná dovolenka</h2>
                    <h3 class="text-md">ŠPECIÁLNE PONUKY</h3>
                </div>
                <button class="w-48 font-bold transition-all ease-in border border-white text-md bg-none hover:bg-textDarker" onClick='openModal(3)'>Rezervovať skipas</button>
            </div>
            <div class='flex flex-row w-full h-full col-span-12' id='slideShow'>
            <?php 

            $locations_query = mysqli_execute_query($conn, "SELECT * FROM `location`");
            $locations = $locations_query->fetch_all(MYSQLI_ASSOC);

            foreach($locations as $location){

                echo '
                <div class="flex items-center justify-center w-1/4 p-8 transition-all ease-in bg-no-repeat bg-cover slide hover:text-black filter grayscale hover:grayscale-0" style="background-image: url('.$location["image"].')">
                    <h2 class="text-4xl">'.$location["title"].'</h2>
                </div>
                ';

            }

            ?>
            </div>
            <script>
                const slideShow = document.getElementById('slideShow')
                const slides = Array.from(document.querySelectorAll('.slide'))

                const slideLimit = 4
                const overLimit = slides.length - slideLimit

                const updateSlides = () => {

                    slideShow.append(slideShow.firstElementChild)
                    slides.push(slides.shift())

                    for(i = overLimit; i > 0; i--){
                        slides[slideLimit + i - 1].style.display = 'none'
                    }

                    if(overLimit > 0){
                        slides[slideLimit - 1].style.display = 'block'
                    }

                }

                updateSlides()

                setInterval(() => {
                        updateSlides()
                }, 2000)
            </script>

            <script>

                // const slides = Array.from(document.querySelectorAll('.slide'));
                // const slideShow = document.getElementById('slideShow');

                // const overFour = slides.length - 4;

                // const updateSlideShow = () => {
                //     slideShow.append(slideShow.firstElementChild);
                //     slides.push(slides.shift());
                //     for(i = overFour; i > 0; i--){
                //         console.info(i);
                //         slides[4 + i - 1].style.display = 'none';
                //     }
                //     if(overFour > 0){
                //         slides[3].style.display = 'block';
                //     }                    
                // }

                // updateSlideShow();

                // setInterval(() => {
                //     updateSlideShow();
                // }, 2000);

            </script>

            <?php 
            
                // $file = fopen("test.txt", "a+");
                // if($file){
                //     $content = fread($file, filesize('test.txt'));
                //     echo($content);
                // }

                // fwrite($file, "test");
                // fclose($file);
                // unlink('test.txt');

            ?>
        </article>
        <article class="flex flex-col items-center justify-center w-full gap-8 p-8 text-center bg-bgcol text-textDark">
            <h2 class="text-4xl text-textDark">Apré ski <br /> stvorené priamo pre vás</h2>
            <div class="grid grid-cols-3 grid-rows-2 gap-8">
                <div class="flex flex-col items-center justify-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="#617391" xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
                        <g>
                            <path d="M498.682,435.326L297.917,234.56L63.357,0H45.026l-3.743,9.511c-9.879,25.104-14.1,50.78-12.205,74.249    c2.16,26.752,12.323,49.913,29.392,66.982L241.58,333.852l24.152-24.152l169.285,189.293c16.84,16.84,45.825,17.84,63.665,0    C516.236,481.439,516.236,452.879,498.682,435.326z"></path>
                        </g>
                        <g>
                            <path d="M156.728,291.442L13.317,434.853c-17.552,17.552-17.552,46.113,0,63.665c16.674,16.674,45.519,18.146,63.665,0    l143.412-143.412L156.728,291.442z"></path>
                        </g>
                        <g>
                            <path d="M490.253,85.249l-81.351,81.35l-21.223-21.222l81.351-81.351l-21.222-21.222l-81.35,81.35l-21.222-21.222l81.351-81.35    L405.366,0.361L299.256,106.471c-12.981,12.981-20.732,30.217-21.828,48.535c-0.277,4.641-1.329,9.206-3.074,13.548l68.929,68.929    c4.342-1.747,8.908-2.798,13.548-3.075c18.318-1.093,35.554-8.846,48.535-21.827l106.11-106.109L490.253,85.249z"></path>
                        </g>
                    </svg>
                    <h3 class="text-4xl text-textDarker">Reštaurácie</h3>
                    <span class="text-sm">U nás nájdete širokú škálu reštaurácií a barov, ktoré ponúkajú jedlá a nápoje pre všetky chute. Dostupné v blízkosti zjazdoviek a vlekov.</span>
                </div>
                <div class="flex flex-col items-center justify-center text-center">
                    <svg width="100" height="100" fill="#617391" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" x="0px" y="0px" viewBox="0 0 20.495 20.495" xml:space="preserve">
                        <g>
                            <path d="M16.197,8.55h-0.911c-0.188,0-0.37,0.019-0.548,0.052V6.545c0-0.112-0.013-0.221-0.033-0.327    c0.599-0.443,0.991-1.148,0.991-1.948c0-1.49-1.372-2.685-2.883-2.386c-0.5-0.526-1.213-0.823-1.946-0.786    C10.399,0.42,9.62,0,8.789,0C8.114,0,7.476,0.268,7.006,0.734C6.881,0.683,6.753,0.642,6.623,0.612L6.459,0.58    C6.333,0.56,6.207,0.549,6.078,0.547c-0.189-0.015-0.371-0.014-0.55,0.001H5.479v0.004C4.405,0.66,3.5,1.296,3.145,2.183    C2.09,2.383,1.29,3.312,1.29,4.422c0,0.775,0.39,1.458,0.982,1.87C2.26,6.375,2.247,6.458,2.247,6.545v12.309    c0,0.905,0.736,1.642,1.641,1.642h9.208c0.905,0,1.642-0.736,1.642-1.642V16.34c0.178,0.033,0.36,0.053,0.548,0.053h0.911    c1.659,0,3.009-1.351,3.009-3.01v-1.822C19.206,9.901,17.856,8.55,16.197,8.55z M13.643,18.854c0,0.302-0.244,0.547-0.547,0.547    H3.888c-0.302,0-0.547-0.245-0.547-0.547V6.545c0-0.302,0.245-0.547,0.547-0.547h9.208c0.303,0,0.547,0.245,0.547,0.547V18.854z     M14.13,5.28L14.13,5.28c-0.282-0.232-0.64-0.377-1.034-0.377H3.888c-0.417,0-0.793,0.161-1.083,0.417    C2.549,5.103,2.384,4.783,2.384,4.422c0-0.651,0.529-1.182,1.181-1.184l0.448-0.002L4.1,2.797C4.202,2.283,4.735,1.74,5.552,1.645    h0.603c0.245,0.017,0.481,0.097,0.689,0.234l0.452,0.299l0.303-0.449C7.867,1.332,8.312,1.094,8.79,1.094    c0.556,0,1.05,0.315,1.292,0.823l0.184,0.387l0.42-0.086c0.571-0.115,1.154,0.124,1.478,0.597l0.239,0.352l0.4-0.147    c0.161-0.059,0.312-0.088,0.461-0.088c0.737,0,1.338,0.6,1.338,1.338C14.602,4.675,14.416,5.035,14.13,5.28z M17.565,13.383    c0,0.754-0.613,1.368-1.368,1.368h-0.911c-0.195,0-0.38-0.042-0.548-0.116v-4.326c0.168-0.074,0.353-0.116,0.548-0.116h0.911    c0.754,0,1.368,0.613,1.368,1.368V13.383z"></path>
                            <rect x="3.888" y="8.003" width="9.21" height="10.851"></rect>
                        </g>
                    </svg>
                    <h3 class="text-4xl text-textDarker">Nočný život</h3>
                    <span class="text-sm">Nočné lyžovanie, posedenie v bare, diskotéky alebo koncerty. Zúčastnite sa festivalu zábavy, ktorý začíná zo západom slnka.</span>
                </div>
                <div class="flex flex-col items-center justify-center text-center">
                    <svg width="100" height="100" fill="#617391" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" x="0px" y="0px" viewBox="0 0 437.212 437.212" xml:space="preserve">
                        <g>
                            <path d="M404.812,41.206h-300c-18,0-32.4,14.8-32.4,32.4v186.4c0,18,14.8,32.4,32.4,32.4h206.8l46.8,47.2c2,2,4.4,3.2,7.2,3.2     c5.6,0,10.4-4.4,10.4-10.4v-40h28.8c18,0,32.4-14.8,32.4-32.4v-186.4C437.212,55.606,422.412,41.206,404.812,41.206z      M150.812,201.606c-16.4,0-30-13.6-30-30c0-16.4,13.6-30,30-30c16.4,0,30,13.6,30,30     C180.812,188.006,167.612,201.606,150.812,201.606z M256.012,201.606c-16.4,0-30-13.6-30-30c0-16.4,13.6-30,30-30     c16.4,0,30,13.6,30,30C286.012,188.006,272.412,201.606,256.012,201.606z M360.812,201.606c-16.4,0-30-13.6-30-30     c0-16.4,13.6-30,30-30c16.4,0,30,13.6,30,30C390.812,188.006,377.212,201.606,360.812,201.606z"></path>
                            <path d="M54.012,260.806v-63.6h-32.8c-11.6,0-21.2,9.6-21.2,21.2v122.4c-0.4,12.4,9.2,22,21.2,22h18.8v26.4     c0,3.6,3.2,6.8,6.8,6.8c2,0,3.6-0.8,4.8-2l30.8-30.8h136c11.6,0,21.2-9.6,21.2-21.2v-30.4h-134.8     C76.812,311.606,54.012,288.806,54.012,260.806z"></path>
                        </g>
                    </svg>
                    <h3 class="text-4xl text-textDarker">Wellness</h3>
                    <span class="text-sm">Náše wellness centrum ponúka širokú škálu služieb, ktoré vám pomôžu uvoľniť sa a obnoviť si energiu po náročnom dni na svahu.</span>
                </div>
                <div class="flex flex-col items-center justify-center text-center">
                    <svg width="100" height="100" fill="#617391"  xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" x="0px" y="0px" viewBox="0 0 79.518 79.518" xml:space="preserve">
                        <g>
                            <path d="M72.799,1.569L72.799,1.569C72.799,1.569,72.573,1.569,72.799,1.569C72.127,0.448,71.006,0,69.887,0l0,0H9.631l0,0    C7.614,0,6.047,1.569,6.047,3.584l0,0v3.137v11.647v6.496v11.648v6.494v11.648v6.496v11.646v3.137l0,0    c0,0.672,0.224,1.344,0.672,2.018l0,0l0,0c0.672,0.896,1.792,1.566,2.912,1.566l0,0h60.256l0,0c2.016,0,3.584-1.566,3.584-3.584    l0,0v-3.137V61.152v-6.495v-11.65v-6.494V24.865v-6.496V6.721V3.584l0,0C73.245,2.689,73.023,2.24,72.799,1.569z M28.895,68.32    c0,0.896-0.672,1.568-1.567,1.568h-8.287c-0.896,0-1.568-0.673-1.568-1.568v-3.584c0-0.896,0.672-1.567,1.568-1.567h8.287    c0.896,0,1.567,0.672,1.567,1.567V68.32z M28.895,56.225c0,0.896-0.672,1.567-1.567,1.567h-8.287    c-0.896,0-1.568-0.672-1.568-1.567v-3.584c0-0.896,0.672-1.567,1.568-1.567h8.287c0.896,0,1.567,0.672,1.567,1.567V56.225z     M28.895,44.354c0,0.896-0.672,1.565-1.567,1.565h-8.287c-0.896,0-1.568-0.672-1.568-1.565V40.77c0-0.896,0.672-1.568,1.568-1.568    h8.287c0.896,0,1.567,0.672,1.567,1.568V44.354z M45.245,68.32c0,0.896-0.672,1.568-1.565,1.568h-8.289    c-0.896,0-1.568-0.673-1.568-1.568v-3.584c0-0.896,0.673-1.567,1.568-1.567h8.289c0.896,0,1.565,0.672,1.565,1.567V68.32z     M45.245,56.225c0,0.896-0.672,1.567-1.565,1.567h-8.289c-0.896,0-1.568-0.672-1.568-1.567v-3.584    c0-0.896,0.673-1.567,1.568-1.567h8.289c0.896,0,1.565,0.672,1.565,1.567V56.225z M45.245,44.354c0,0.896-0.672,1.565-1.565,1.565    h-8.289c-0.896,0-1.568-0.672-1.568-1.565V40.77c0-0.896,0.673-1.568,1.568-1.568h8.289c0.896,0,1.565,0.672,1.565,1.568V44.354z     M61.822,68.32c0,0.896-0.672,1.568-1.567,1.568h-8.286c-0.896,0-1.568-0.673-1.568-1.568v-3.584c0-0.896,0.672-1.567,1.568-1.567    h8.286c0.896,0,1.567,0.672,1.567,1.567V68.32z M61.822,56.225c0,0.896-0.672,1.567-1.567,1.567h-8.286    c-0.896,0-1.568-0.672-1.568-1.567v-3.584c0-0.896,0.672-1.567,1.568-1.567h8.286c0.896,0,1.567,0.672,1.567,1.567V56.225z     M61.822,44.354c0,0.896-0.672,1.565-1.567,1.565h-8.286c-0.896,0-1.568-0.672-1.568-1.565V40.77c0-0.896,0.672-1.568,1.568-1.568    h8.286c0.896,0,1.567,0.672,1.567,1.568V44.354z M62.493,31.809L62.493,31.809c0,0.896-0.673,1.566-1.567,1.566H18.143    c-0.896,0-1.567-0.672-1.567-1.566V10.977c0-0.896,0.672-1.568,1.567-1.568h42.783c0.896,0,1.567,0.672,1.567,1.568V31.809z"></path>
                            <path d="M28.447,19.712c-1.566-0.672-2.238-0.896-2.238-1.567c0-0.448,0.446-0.896,1.566-0.896c1.345,0,2.018,0.448,2.464,0.672    l0.448-2.016c-0.672-0.224-1.344-0.448-2.464-0.672v-1.568h-1.792v1.792c-1.792,0.448-2.912,1.568-2.912,3.136    c0,1.793,1.344,2.688,3.136,3.137c1.346,0.448,1.792,0.896,1.792,1.567s-0.672,1.121-1.792,1.121    c-1.119,0-2.238-0.448-2.911-0.672l-0.447,2.017c0.672,0.446,1.792,0.672,2.912,0.672v1.792h1.792v-2.018    c2.016-0.446,3.136-1.791,3.136-3.358C31.359,21.504,30.463,20.385,28.447,19.712z"></path>
                            <path d="M40.543,19.712c-1.568-0.672-2.24-0.896-2.24-1.567c0-0.448,0.448-0.896,1.567-0.896c1.345,0,2.017,0.448,2.464,0.672    l0.448-2.016c-0.673-0.224-1.345-0.448-2.464-0.672v-1.568h-1.792v1.792c-1.792,0.448-2.912,1.568-2.912,3.136    c0,1.793,1.346,2.688,3.138,3.137c1.344,0.448,1.792,0.896,1.792,1.567s-0.673,1.121-1.792,1.121c-1.12,0-2.24-0.448-2.912-0.672    l-0.448,2.017c0.672,0.446,1.792,0.672,2.912,0.672v1.792h1.792v-2.018c2.016-0.446,3.137-1.791,3.137-3.358    C43.68,21.504,42.782,20.385,40.543,19.712z"></path>
                            <path d="M52.863,19.712c-1.566-0.672-2.238-0.896-2.238-1.567c0-0.448,0.445-0.896,1.566-0.896c1.345,0,2.018,0.448,2.463,0.672    l0.449-2.016c-0.672-0.224-1.346-0.448-2.465-0.672v-1.568h-1.791v1.792c-1.793,0.448-2.912,1.568-2.912,3.136    c0,1.793,1.344,2.688,3.135,3.137c1.346,0.448,1.793,0.896,1.793,1.567s-0.672,1.121-1.793,1.121    c-1.118,0-2.239-0.448-2.911-0.672l-0.447,2.016c0.672,0.446,1.791,0.672,2.911,0.672v1.792h1.791v-2.018    c2.018-0.446,3.139-1.791,3.139-3.358C55.775,21.504,54.879,20.385,52.863,19.712z"></path>
                        </g>
                    </svg>
                    <h3 class="text-4xl text-textDarker">Obchody</h3>
                    <span class="text-sm">Ponúkame širokú škálu obchodov a služieb, ako sú lyžiarske a snowboardové predajne, požičovne, športové potreby a ďalšie.</span>
                </div>
                <div class="flex flex-col items-center justify-center text-center">
                    <svg width="100" height="100" fill="#617391" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" x="0px" y="0px" viewBox="0 0 54.391 54.391" xml:space="preserve">
                        <g>
                            <polygon points="0.285,19.392 24.181,49.057 13.342,19.392  "></polygon>
                            <polygon points="15.472,19.392 27.02,50.998 38.795,19.392  "></polygon>
                            <polygon points="29.593,49.823 54.105,19.392 40.929,19.392  "></polygon>
                            <polygon points="44.755,3.392 29.297,3.392 39.896,16.437  "></polygon>
                            <polygon points="38.094,17.392 27.195,3.979 16.297,17.392  "></polygon>
                            <polygon points="25.094,3.392 9.625,3.392 14.424,16.525  "></polygon>
                            <polygon points="7.959,4.658 0,17.392 12.611,17.392  "></polygon>
                            <polygon points="54.391,17.392 46.424,4.645 41.674,17.392  "></polygon>
                        </g>
                    </svg>
                    <h3 class="text-4xl text-textDarker">Ubytovanie</h3>
                    <span class="text-sm">Pripravili sme pre vás sieť hotelov, apartmánov a dokonca aj chatiek. Na svoje si prídu aj tí najnáročnejší.</span>
                </div>
                <div class="flex flex-col items-center justify-center text-center">
                    <svg width="100" height="100" fill="#617391" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" x="0px" y="0px" viewBox="0 0 451.514 451.514" xml:space="preserve">
                        <g>
                            <path d="M41.693,76.554c-7.168-3.584-15.872-0.512-19.968,6.656c-25.6,50.688-28.672,109.568-8.704,162.816    c3.072,8.192,11.776,11.776,19.968,9.216s12.288-12.288,9.216-19.968c-16.896-45.056-14.336-95.232,7.168-138.24    C52.957,89.354,49.373,80.138,41.693,76.554z"></path>
                        </g>
                        <g>
                            <path d="M429.789,83.21c-3.584-7.68-12.8-10.752-20.48-6.656c-7.68,3.584-10.752,12.8-6.656,20.48    c21.504,43.008,24.064,93.184,7.168,138.24c-3.072,7.68,1.024,16.896,8.704,19.968c7.68,3.072,16.896-1.024,19.968-8.704    C458.461,192.778,455.389,133.898,429.789,83.21z"></path>
                        </g>
                        <g>
                            <path d="M78.045,126.218c-8.192-2.048-16.384,3.072-18.432,11.264c-5.12,21.504-5.632,44.032-2.048,66.048    c2.048,8.192,10.24,13.312,18.432,11.776s13.312-9.216,11.776-17.408c-3.072-17.92-2.56-35.84,1.536-53.248    C91.357,136.458,86.237,128.266,78.045,126.218z"></path>
                        </g>
                        <g>
                            <path d="M392.413,136.97c-1.536-8.192-9.728-13.824-17.92-12.288c-8.192,1.536-13.824,9.728-12.288,17.92c0,0.512,0,1.024,0,1.536    c4.096,17.408,4.608,35.84,1.536,53.248c-1.536,8.192,4.096,16.384,12.288,17.92c8.192,1.536,16.384-4.096,17.92-12.288    C398.045,181.002,397.533,158.474,392.413,136.97z"></path>
                        </g>
                        <g>
                            <path d="M373.981,285.45c-22.016-23.552-31.744-47.616-31.744-77.312v-33.792c0-41.472-22.528-79.36-58.368-99.84V60.682    c0-31.744-25.6-57.344-57.344-57.344c-31.744,0-57.344,25.6-57.344,57.344V75.53c-36.352,21.504-58.368,60.928-58.368,102.912    v29.696c0,29.696-9.728,53.76-31.744,77.312c-24.064,1.536-43.008,21.504-43.008,46.08v11.776c0,25.6,20.48,46.08,46.08,46.08    h78.848c4.096,36.352,36.864,62.464,73.216,58.368c30.72-3.584,54.784-27.648,58.368-58.368h78.848    c25.6,0,46.08-20.48,46.08-46.08V331.53C416.989,306.954,398.045,286.986,373.981,285.45z"></path>
                        </g>
                    </svg>
                    <h3 class="text-4xl text-textDarker">Kurzy</h3>
                    <span class="text-sm">Kurzy sú určené pre malých aj veľkých, pre začiatočníkov aj pokročilých. Naučíme vás lyžovať aj padať.</span>
                </div>
            </div>
        </article>
        <article class="flex flex-row w-full justify-evenly">
            <div class="w-full bg-no-repeat bg-cover" style="background-image: url(images/foot01.jpg)">
                
            </div>
            <div class="flex flex-col items-center justify-center w-full gap-2 p-8 text-center bg-bgcol">
                <div class="flex flex-col items-center justify-center w-full text-center">
                    <h2 class="text-textDark">Základné informácie</h2>
                    <span>+(421) 999 000 001</span>
                </div>
                <div class="flex flex-col items-center justify-center w-full text-center">
                    <h2 class="text-textDark">Rezervácie</h2>
                    <span>+(421) 999 000 002</span>
                </div>
                <div class="flex flex-col items-center justify-center w-full text-center">
                    <h2 class="text-textDark">Centrum pomoci</h2>
                    <span>+(421) 999 000 004</span>
                </div>
            </div>
            <div class="w-full bg-no-repeat bg-cover" style="background-image: url(images/foot02.jpg)">
    
            </div>
        </article>
        <footer class="w-full flex text-center items-center justify-center bg-[#333333] text-white p-8">
            <span>ZENIT Všetky práva vyhradené 2023, <br /> Peter Zaťko, <br /> Stredná priemyselná škola informačných technológií Ignáca Gessaya Tvrdošín Medvedzie 133/1</span>
        </footer>
    </main>

    <script>

        let selectedOffer = null

        const modal = document.getElementById('modal')
        modal.style.visibility = 'hidden'

        const priceElement = document.getElementById('price')

        const updatePrice = (base = 10) => {
            const children = document.getElementById('children').value
            const juniors = document.getElementById('juniors').value
            const adults = document.getElementById('adults').value
            const seniors = document.getElementById('seniors').value

            const price = base * 0.7 * children + base * 0.8 * juniors + base * 1 * adults + base * 0.85 * seniors
            priceElement.innerHTML = price

            console.log(children, juniors, adults, seniors)
        }

        const closeModal = () => {

            modal.style.visibility = 'hidden'
            selectedOffer = null

        }

        const openModal = (id) => {

            selectedOffer = id
            modal.style.visibility = 'visible'

        }
    </script>

</body>
</html>