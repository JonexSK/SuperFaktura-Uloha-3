<?php

/* 
Napíšte prosím jednoduchú knižnicu (libku, ucelený blok kódu) na načítanie údajov firiem z českého registra spoločností. Nie je potrebné vytvárať používateľské rozhranie.

Vstupom metódy pre prácu s dátami má byť IČO. Formát výstupu metódy necháme na vás.

Endpoint pre údaje je http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_bas.cgi?ico={ICO} príklad volania http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_bas.cgi?ico=45626499

Skúste prosím kód vyšperkovať úplne najlepšie, ako dokážete (PHP 7.4+, ošetrenie vstupov, error handling, dokumentácia, formátovanie kódu...).
*/


error_reporting(0);

if(isset($_REQUEST['submit']))
{
    $ICO = $_GET['ico'];
    $url = "wwwinfo.mfcr.cz/cgi-bin/ares/darv_bas.cgi?ico=" . $ICO; 


    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
    $headers = array(
        "Accept: application/xml",
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
    $resp = curl_exec($curl);
    curl_close($curl);
}

include("head.php");
?>

    <form class="ico_form" method="GET" action="">
        <label id="lbl_ico" for="ico">Zadaj IČO</label>
        <br>
        <input type="number" id="ico" name="ico" value="<?php echo $ICO; ?>" placeholder="02001423">
        <br>
        <input type="submit" id="odoslat" name="submit">
    </form>
    <?php

    function object2array($object) { return @json_decode(@json_encode($object),1); } 
    
    $xml = simplexml_load_string($resp);
    $xml_array=object2array($xml); 
    ?>

    <div class="xml_okno">
    
    <?php

    /*echo "Cela odpoveď zo servera: <br>";
    print_r($xml);

    echo "<br>XML array: <br>";
    print_r($xml_array);*/

    if(strlen($ICO) == 8)
    {
        echo "<br>    <u>odpoved datum čas:</u> <br>".$xml_array['@attributes']['odpoved_datum_cas'];
        echo "<br><br><u>odpoved pocet:</u> <br>".$xml_array['@attributes']['odpoved_pocet'];
        echo "<br><br><u>odpoved typ:</u> <br>".$xml_array['@attributes']['odpoved_typ'];
        echo "<br><br><u>vystup format:</u> <br>".$xml_array['@attributes']['vystup_format'];
        echo "<br><br><u>xslt:</u> <br>".$xml_array['@attributes']['xslt'];
        echo "<br><br><u>validation XSLT:</u> <br>".$xml_array['@attributes']['validation_XSLT'];
        echo "<br><br><u>xslt:</u> <br>".$xml_array['@attributes']['Id'];   
    ?>
        <br><br>
        <div class="curl">
            <?php
                var_dump($resp);
            ?>
        </div>

    <?php
    }

    elseif($ICO == '')
    {
        echo '';
    }

    else
    {
        echo "<script>alert('IČO musí mať 8 čísel!');</script>";
    }
?>

</div>

<?php
include("footer.php");
?>

