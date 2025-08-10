<?php

//// funzioni

// Definisce la root path del progetto per percorsi di file portabili.
define('ROOT_PATH', dirname(__DIR__));

function show404() {
	header("HTTP/1.0 404 Not Found");
	echo '404';
	exit();
}



//// variabili globali

// lista dei numeri 899
$numeri = array(
	'generico' => '899.89.82.88',
	"bassocosto" => '899.89.82.88',
	'mature' => '899.37.00.32',
	'gay' => '899.89.82.60',
	'trans' => '899.89.82.45',
	'padrone' => '899.89.82.88',
	'lesbiche' => '899.37.00.36',
	'anziane' => '899.37.00.32',
	'carta' => '06.890.838.67',
	'svizzera' => '0906.906.901',
	'germania' => '0900.586.931.385'
);

// elenco delle pagine caricate da file JSON
$json_pagine = file_get_contents(ROOT_PATH . '/pagine.json');
$listaPagine = json_decode($json_pagine, true);


// variabile che conterrà il menu per la sidebar
$menu_sidebar = array();
foreach($listaPagine as $lp) { if($lp["url"] != '/cookie-policy/' && $lp["url"] != '/privacy-policy/') { 
	if(isset($lp["parent"]) && $lp["parent"]) {
		$menu_sidebar[$lp["parent"]]["figlie"][] = $lp;
	} else {
		$menu_sidebar[$lp["url"]]["genitore"] = $lp;
	}
 }} 
 ksort($menu_sidebar);

 // variabile con l'orario fake a fondo pagina
setlocale(LC_ALL, 'it_IT');
  $update1 = date('Y-m-d');
;
  $update2 = date('d/m/Y');

  $update3 = date('H:i:s');
  $orario = 'Ultimo aggiornamento il <time datetime="'.$update1.'">'.$update2.'</time> alle <time datetime="'.$update3.'">'.$update3.'</time>';


// barretta orizzontale con la lista degli 899 sparsa in giro
/*$barretta = '<div style="clear:both;"></div>
<div class="elenconumeri">
	<div class="elenconumero"><p>Ragazze</p>
	<p><a rel="nofollow" title="chiama ora" class="num" href="tel:'.$numeri["generico"].'">'.$numeri["generico"].'</a></p>
	</div>
	<div class="elenconumero"><p>Mature</p>
	<p><a rel="nofollow" title=" mature" class="num" href="tel:'.$numeri["mature"].'">'.$numeri["mature"].'</a></p>
	</div>
	<div class="elenconumero"><p>Trans</p>
	<p><a rel="nofollow" title=" trans" class="num" href="tel:'.$numeri["trans"].'">'.$numeri["trans"].'</a></p>
	</div>
	<div class="elenconumero"><p>Gay</p>
	<p><a rel="nofollow" title=" gay" class="num" href="tel:'.$numeri["gay"].'">'.$numeri["gay"].'</a></p>
	</div>
	<div class="elenconumero"><p>Padrona</p>
	<p><a rel="nofollow" title=" padrona" class="num" href="tel:'.$numeri["padrone"].'">'.$numeri["padrone"].'</a></p>
	</div>
	<div class="elenconumero"><p>Lesbica</p>
	<p><a rel="nofollow" title=" lesbica" class="num" href="tel:'.$numeri["lesbiche"].'">'.$numeri["lesbiche"].'</a></p>
	</div>
</div>
	<p class="tariffesottobarra">Tariffe (numeri - operatore - € minuto / € scatto): <strong>899.00.33.65</strong> Fisso 0,31/0,13 - Vodafone/Tim 0,95/0,16 - Wind 0,98/0,16 - H3G 0,98/,0,15. <strong>899.37.00.32 - 899.37.00.36</strong> Fisso 1,22/0,13 - Vodafone 1,47/0,25 - Tim 1,58/0,16 - Wind 1,59/0,16 - H3G 1,30/0,15. <strong>899.89.82.45 - 899.89.82.60 - 899.89.82.88</strong> Fisso 1,22/0,13 - Vodafone 1,47/0,25 - Tim 1,58/0,16 - Wind 1,59/0,16 - H3G 1,59/0,15.</p>
<div style="clear:both;"></div>';


// barretta secondaria

  $barrettasecondaria = '<div style="clear:both;"></div>
<div class="elenconumeri">
  <div class="elenconumerosecondario"><p><img style="height:24px; padding:2px 0; width:auto" src="/img/icona-svizzera.png"><br />SVIZZERA<br /><span class="tariffina">0,99 CHF/MIN</span></p>
  <p><a rel="nofollow" title="svizzera" class="num" href="tel:'.$numeri["svizzera"].'">'.$numeri["svizzera"].'</a></p>
  </div>
  <div class="elenconumerosecondario"><p><img style="height:24px; padding:2px 0; width:auto" src="/img/icona-germania.png"><br />GERMANIA<br /><span class="tariffina">€ 2,00 /MIN IVA INC. DA FISSO</span></p>
  <p><a rel="nofollow" title="germania" class="num" href="tel:'.$numeri["germania"].'">'.$numeri["germania"].'</a></p>
  </div>
  <div class="elenconumerosecondario"><p><img src="/img/icona-carta.png"><br />CARTA DI CREDITO<br /><span class="tariffina">€ 0,69 AL MIN - RISPARMIA!</span></p>
  <p><a rel="nofollow" title="carta di credito" class="num" href="tel:'.$numeri["carta"].'">'.$numeri["carta"].'</a></p>
  </div>
</div>';
$barrettasecondaria .= '<div style="clear:both;"></div>';
*/
//// controlli 
// Funzione per evidenziare il numero corrente sulla barretta
function evidenziaNumero($barretta, $tipo) {
    $map = [
        'generico' => 'Ragazze',
        'mature' => 'Mature',
        'anziane' => 'Mature',
        'trans' => 'Trans',
        'gay' => 'Gay',
        'padrone' => 'Padrona',
        'lesbiche' => 'Lesbica'
    ];
    if (isset($map[$tipo])) {
        $barretta = str_replace('class="elenconumero"><p>' . $map[$tipo], 'class="elenconumero evidenzia"><p>' . $map[$tipo], $barretta);
    }
    return $barretta;
}

// Funzione per ottenere immagini randomizzate
function getRandomImages($basePath, $tipo) {
    $path = $basePath . "/imgcontent/$tipo/";
    if (file_exists($path)) {
        $images = array_diff(scandir($path), ['..', '.']);
        if (!empty($images)) {
            shuffle($images);
            return $images;
        }
    }
    return [];
}

// Funzione per generare HTML per un'immagine
function generateImageHtml($base, $tipo, $numero, $title, $image) {
    $url_image = "/imgcontent/$tipo/$image";
    list($width, $height) = @getimagesize($base . $url_image);
    return '<p class="imgcontent"><a href="tel:' . $numero . '" rel="nofollow"><img src="' . $url_image . '" alt="' . $title . '" width="' . $width . '" height="' . $height . '"></a><br /><a href="tel:' . $numero . '" class="imgcontent_link" rel="nofollow">' . $numero . '</a></p>';
}

// Funzione per sostituire gli shortcode
function replaceShortcodes($content, $shortcodes) {
    return str_replace(array_keys($shortcodes), array_values($shortcodes), $content);
}

// Base path
$base = ROOT_PATH;

// Sanificazione della richiesta
$path = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL);

if (!empty($path)) {
    // Controllo se il permalink richiesto esiste
    if ((isset($listaPagine[$path]) && $listaPagine[$path]) || $path == '/sitemap.xml') {
        $pageData = $listaPagine[$path];
        $tipo = $pageData["tipo"];
        $description = $pageData["description"];
        $robots = $pageData["robots"];
        $keywords = $pageData["keywords"];
        $content = '';
        $numero = $numeri["generico"];
        $title = $pageData["title"];
        $titleCustom = $pageData["titleCustom"] ?? '';

        // Caricamento del contenuto della pagina direttamente dal JSON
        $content = $pageData['html_content'] ?? '';

        // Assegna il numero corretto in base al tipo
        if (isset($numeri[$tipo]) && $numeri[$tipo]) {
            $numero = $numeri[$tipo];
        }

        // Evidenziazione del numero corrente sulla barretta
        $barretta = evidenziaNumero($barretta, $tipo);

        // Prelevo immagini randomizzate per il content
        $imgcontent = getRandomImages($base, $tipo);

        // Generazione HTML per immagini specifiche
        if (!empty($imgcontent)) {
            $html_immagine_1 = generateImageHtml($base, $tipo, $numero, $title, $imgcontent[0]);
            $content = str_replace('%%IMMAGINE_1%%', $html_immagine_1, $content);

            if (isset($imgcontent[1])) {
                $html_immagine_2 = generateImageHtml($base, $tipo, $numero, $title, $imgcontent[1]);
                $content = str_replace('%%IMMAGINE_2%%', $html_immagine_2, $content);
            }
        }

        // Prelevo immagini randomizzate per viste multinumero
        $types = ['generico', 'mature', 'gay', 'trans', 'padrone', 'lesbiche', 'anziane'];
        foreach ($types as $type) {
            $images = getRandomImages($base, $type);
            if (!empty($images)) {
                $html_image = generateImageHtml($base, $type, $numeri[$type], '', $images[0]);
                $content = str_replace('%%IMMAGINE_' . strtoupper($type) . '%%', $html_image, $content);
            }
        }

        // Sostituzioni degli shortcode
        $array_from_to = [
            '%%BARRETTA%%' => $barretta,
            '%%NUMERO_GENERICO%%' => $numeri["generico"],
            '%%NUMERO_MATURE%%' => $numeri["mature"],
            '%%NUMERO_GAY%%' => $numeri["gay"],
            '%%NUMERO_TRANS%%' => $numeri["trans"],
            '%%NUMERO_PADRONE%%' => $numeri["padrone"],
            '%%NUMERO_LESBICHE%%' => $numeri["lesbiche"],
            '%%NUMERO_ANZIANE%%' => $numeri["anziane"],
            '%%NUMERO_CARTA%%' => $numeri["carta"],
            '%%NUMERO_SVIZZERA%%' => $numeri["svizzera"],
            '%%NUMERO_GENERICO_LINK%%' => '<a href="tel:' . $numeri["generico"] . '" rel="nofollow">' . $numeri["generico"] . '</a>',
            '%%NUMERO_MATURE_LINK%%' => '<a href="tel:' . $numeri["mature"] . '" rel="nofollow">' . $numeri["mature"] . '</a>',
            '%%NUMERO_GAY_LINK%%' => '<a href="tel:' . $numeri["gay"] . '" rel="nofollow">' . $numeri["gay"] . '</a>',
            '%%NUMERO_TRANS_LINK%%' => '<a href="tel:' . $numeri["trans"] . '" rel="nofollow">' . $numeri["trans"] . '</a>',
            '%%NUMERO_PADRONE_LINK%%' => '<a href="tel:' . $numeri["padrone"] . '" rel="nofollow">' . $numeri["padrone"] . '</a>',
            '%%NUMERO_LESBICHE_LINK%%' => '<a href="tel:' . $numeri["lesbiche"] . '" rel="nofollow">' . $numeri["lesbiche"] . '</a>',
            '%%NUMERO_ANZIANE_LINK%%' => '<a href="tel:' . $numeri["anziane"] . '" rel="nofollow">' . $numeri["anziane"] . '</a>',
            '%%NUMERO_CARTA_LINK%%' => '<a href="tel:' . $numeri["carta"] . '" rel="nofollow">' . $numeri["carta"] . '</a>',
            '%%NUMERO_SVIZZERA_LINK%%' => '<a href="tel:' . $numeri["svizzera"] . '" rel="nofollow">' . $numeri["svizzera"] . '</a>'
        ];
        $content = replaceShortcodes($content, $array_from_to);
        $description = replaceShortcodes($description, $array_from_to);
    } else {
        // Mostra una pagina 404 se il permalink non esiste
        show404();
    }
}


