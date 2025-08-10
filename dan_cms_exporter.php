<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('wp-blog-header.php');


// sudo chown -R web62:client1 /var/www/chiamateerotiche24.it/web/
// (nella cartella pagine)  grep -lr '\[num' /var/www/casalinghealtelefono.net/web/pagine/

/** Nome utente del database MySQL */
//define('DB_USER', 'casalingheteldb');

/** Password del database MySQL */
//define('DB_PASSWORD', 'bhhTqSNB_27');



// funzioni varie
function nl2p($string, $line_breaks = true, $xml = true) {
$string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);
// It is conceivable that people might still want single line-breaks
// without breaking into a new paragraph.
if ($line_breaks == true)
    return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'), trim($string)).'</p>';
else 
    return '<p>'.preg_replace(
    array("/([\n]{2,})/i", "/([\r\n]{3,})/i","/([^>])\n([^<])/i"),
    array("</p>\n<p>", "</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'),
    trim($string)).'</p>'; 
}


// inizia il processo
if(isset($_GET['pass']) && $_GET['pass'] == 'xxyyzz') {
	$args = array(
	    'post_type' => 'page',
	    'post_status' => 'publish',
	    'posts_per_page' => -1
	);

	$query = new WP_Query($args);
	while ( $query->have_posts() ) : $query->the_post();

		// creo il txt singolo
		$tt = get_the_title();
		$tt = str_replace('?','',$tt);
		$tt = str_replace('!','',$tt);
		$tt = str_replace('"',' ',$tt);
		$tt = str_replace("'",' ',$tt);
		$tt = str_replace("’",' ',$tt);
		$nomefile = urlencode(strtolower(trim($tt)));
		$nomefile = str_replace('+-','-',$nomefile);
		$nomefile = str_replace('-+','-',$nomefile);
		$nomefile = str_replace('+','-',$nomefile);
		$nomefile = str_replace('----','-',$nomefile);
		$nomefile = str_replace('---','-',$nomefile);
		$nomefile = str_replace('--','-',$nomefile);
		
	    $f = fopen('txt/'.$nomefile . '.php', 'w');
	    $content = nl2p(get_the_content(), FALSE);
	    $content = str_replace('899.54.55.55','%%NUMERO_GENERICO%%',$content);
	    $content = str_replace('899545555','%%NUMERO_GENERICO%%',$content);
	    $content = str_replace('899.545555','%%NUMERO_GENERICO%%',$content);
	    $content = str_replace('899.35.53.30','%%NUMERO_UOMINI%%',$content);
	    $content = str_replace('899355330','%%NUMERO_UOMINI%%',$content);
	    $content = str_replace('899.355330','%%NUMERO_UOMINI%%',$content);
	    fwrite($f, $content);
	    fclose($f);

	    // appendo al maxi function
	    $nomefile2 = 'txt/funzioni.txt';
	    $sitobase = 'http://www.cartomanzia-123.it';
	    $perma = str_replace($sitobase,'',get_the_permalink());
	    $perma_parent = '';
	    $title = get_the_title();

	    $tag = 'generico';
	    if(strpos(strtolower($title), 'svizzera') !== false) {
	    	$tag = 'svizzera';
	    } elseif(strpos(strtolower($title), ' carta ') !== false) {
	    	$tag = 'carta';
	    } elseif(strpos(strtolower($title), ' uomo') !== false) {
	    	$tag = 'uomini';
	    } elseif(strpos(strtolower($title), ' uomini') !== false) {
	    	$tag = 'uomini';
	    } 

	    $desc = '';
	    $desc = trim(strip_tags(get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true)));
	    $desc = str_replace('"',"'",$desc);
	    var_dump($desc);

	    // se nn c'è slasl alla fine, entrambi i numeri -1 e niente slash finale su perma_parent nella if ()
	    $base_explode_n = 3;
	    $testex = explode('/', $perma);
	    //var_dump($testex);

	    if(count($testex) == 4) {
	    	$perma_parent = '/' . $testex[1] . '/';
	    }

	    echo '<br /><br />';




	    $f2 = fopen($nomefile2, 'a');
	    

	    /*$content2 = PHP_EOL . "permalink: " . $perma . PHP_EOL;
	    $content2 .= "title: " . $title . PHP_EOL;
	    $content2 .= "tag: " . $tag . PHP_EOL;
	    $content2 .= "dio: " . $nomefile . '.php'. PHP_EOL;
	    $content2 .= "parent: " . $perma_parent . PHP_EOL;
	    $content2 .= PHP_EOL;*/

	    $content2 = '$listaPagine["'.$perma.'"] = array(' . PHP_EOL;
	    $content2 .= '	"title" => "'.$title.'",' . PHP_EOL;
		$content2 .= '	"tipo" => "'.$tag.'", ' . PHP_EOL;
		$content2 .= '	"url" => "'.$perma.'",' . PHP_EOL;
		$content2 .= '	"description" => "'.$desc.'",' . PHP_EOL;
		$content2 .= '	"robots" => "index,follow",' . PHP_EOL;
		$content2 .= '	"keywords" => "'.$title.'",' . PHP_EOL;
		$content2 .= '	"content" => "/pagine/'.$nomefile.'.php",' . PHP_EOL;
		$content2 .= '	"parent" => "'.$perma_parent.'"' . PHP_EOL;
		$content2 .= ');' . PHP_EOL;

	    var_dump($content2);
	    fwrite($f2,$content2);
	    fclose($f2);


	    /*
	    $listaPagine["/chiedi-ai-tarocchi"] = array(
			'title' => 'Chiedi ai tarocchi',
			'tipo' => 'tarocchi', 
			'url' => '/chiedi-ai-tarocchi',
			'description' => '',
			'robots' => 'index,follow',
			'keywords' => 'Chiedi ai tarocchi',
			'content' => '/pagine/chiedi-ai-tarocchi.php',
			'parent' => ''
		);

		$listaPagine["/casalinghe/casalinghe-arrapate"] = array(
			'title' => 'Casalinghe Arrapate',
			'tipo' => 'mature', 
			'url' => '/casalinghe/casalinghe-arrapate',
			'description' => 'Divertiti insieme a una delle nostre Casalinghe Arrapate: chiama il numero 899 in diretta',
			'robots' => 'index,follow',
			'keywords' => 'Casalinghe Arrapate',
			'content' => '/pagine/casalinghe-arrapate.php',
			'parent' => '/casalinghe'
		);

		*/




	endwhile;

}