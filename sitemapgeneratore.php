<?php 
// Includi le funzioni e la lista delle pagine
include('include/funzioni.php');

// Controlla se la lista delle pagine esiste ed è valida
if(isset($listaPagine) && is_array($listaPagine) && count($listaPagine) > 0) {
	
    // Imposta l'header corretto per un file XML
	header('Content-type: application/xml');
	
    // Inizia la struttura della sitemap
	echo "<?xml version='1.0' encoding='UTF-8'?>";
	echo "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";

	// Itera su ogni pagina della lista
	foreach($listaPagine as $lp) {
        
        // **LOGICA DI ORDINAMENTO CON FALLBACK**
        // Se 'created_at' esiste, usalo. Altrimenti, usa la data di modifica del file.
        $last_mod_timestamp = $lp['created_at'] ?? @filemtime($_SERVER['DOCUMENT_ROOT'] . $lp['content']);
        
        // Formatta la data nel formato YYYY-MM-DD richiesto dalla sitemap
        // Se il file non esiste o ci sono errori, usa la data di oggi come ripiego sicuro
        $last_mod_date = $last_mod_timestamp ? date("Y-m-d", $last_mod_timestamp) : date("Y-m-d");

		echo "<url>";
		echo "  <loc>https://www.raccontierotici24.it" . htmlspecialchars($lp["url"]) . "</loc>";
        echo "  <lastmod>" . $last_mod_date . "</lastmod>";
		echo "  <changefreq>weekly</changefreq>";
		echo "  <priority>0.8</priority>";
		echo "</url>";
	}

	echo "</urlset>";

} else {
	// Se non ci sono pagine, termina lo script
	exit();
}
?>