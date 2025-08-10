<?php 
header('Content-type: text/html; charset=UTF-8');
require_once('/var/www/vhosts/raccontierotici24.it/httpdocs/include/funzioni.php');
?><!doctype html>
<html lang="it" dir="ltr">
<head>
  <title><?php if(isset($titleCustom) && $titleCustom) {
    echo $titleCustom;
  } 
  else {
    echo $title.' - '. $numero.' - Racconti Erotici 24';
  }?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?=$description?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="robots" content="<?=$robots?>">
  <meta name="keywords" content="<?=$keywords?>">
  <link rel="canonical" href="https://www.raccontierotici24.it<?=$path?>">
  <link rel="shortcut icon" href="/img/favicon.ico">
  <link rel="dns-prefetch" href="//raccontierotici24.it">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></noscript>
  <link rel="stylesheet" href="/style.css">


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TGNQMT8');</script>
<!-- End Google Tag Manager -->
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TGNQMT8"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<?php require_once('/var/www/vhosts/raccontierotici24.it/httpdocs/include/nav.php'); ?>

<!-- End Google Tag Manager (noscript) -->
  <div class="container mt-4">
    <div class="row">
        <?php 
        // Se siamo in homepage, il contenuto occupa tutta la larghezza (12 colonne)
        // Altrimenti, occupa 8 colonne per fare spazio alla sidebar
        $main_content_class = ($path == '/') ? 'col-lg-12' : 'col-lg-8';
        ?>

        <div class="<?= $main_content_class ?>">
            <main>
                <?=$barretta?>
                <?=$barrettasecondaria?>

                <div id="testo" class="mt-4">
                    <h1><?=$title?></h1>
                    <?=$content?>
                </div>

                <?php
                // ########## BLOCCO DINAMICO CENTRALE ##########

                // --- Logica per la HOMEPAGE ---
                if ($path == '/') {
                    // (Il codice per la homepage rimane qui, non lo riscrivo per brevità)
                    // --- Sezione Dinamica per le CATEGORIE ---
                    echo '<div class="container"><div class="row text-center">';
                    foreach ($listaPagine as $pagina) {
                        if (empty($pagina['parent']) && $pagina['url'] !== '/' && $pagina['url'] !== '/cookie-policy' && $pagina['url'] !== '/privacy-policy') {
                            echo '
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">' . htmlspecialchars($pagina['title']) . '</h5>
                                        <p class="card-text">' . substr(htmlspecialchars($pagina['description']), 0, 150) . '...</p>
                                        <a href="' . htmlspecialchars($pagina['url']) . '" class="btn btn-dark mt-auto">Scopri di più</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    }
                    echo '</div></div>';


                    // --- Sezione Dinamica per gli ULTIMI RACCONTI ---
                    echo '
                    <div class="container mt-5">
                        <h2 class="text-center mb-4">Ultimi Racconti Aggiunti</h2>
                        <div class="row">';
                    
                    $racconti = array_filter($listaPagine, function($pagina_racconto) {
                        return !empty($pagina_racconto['parent']);
                    });

                    // **LOGICA DI ORDINAMENTO DEFINITIVA CON FALLBACK**
                    usort($racconti, function($a, $b) {
                        // Se 'created_at' non esiste, usa la data di modifica del file come fallback
                        $time_a = $a['created_at'] ?? filemtime($_SERVER['DOCUMENT_ROOT'] . $a['content']);
                        $time_b = $b['created_at'] ?? filemtime($_SERVER['DOCUMENT_ROOT'] . $b['content']);
                        
                        // Ordina dal più recente (timestamp più alto) al più vecchio
                        return $time_b <=> $time_a;
                    });

                    $ultimi_tre = array_slice($racconti, 0, 3);

                    foreach ($ultimi_tre as $racconto) {
                        echo '
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">';
                        
                        if (!empty($racconto['image'])) {
                            echo '<img src="' . htmlspecialchars($racconto['image']) . '" class="card-img-top" alt="' . htmlspecialchars($racconto['title']) . '" style="height: 200px; object-fit: cover;">';
                        }
                        
                        echo '
                                <div class="card-body d-flex flex-column"> 
                                    <h5 class="card-title">' . htmlspecialchars($racconto['title']) . '</h5>
                                    <p class="card-text">' . substr(htmlspecialchars($racconto['description']), 0, 100) . '...</p>
                                    <a href="' . htmlspecialchars($racconto['url']) . '" class="btn btn-danger mt-auto">Leggi il racconto</a>
                                </div>
                            </div>
                        </div>';
                    }
                    
                    echo '
                        </div>
                    </div>';
                }

                // --- NUOVA Logica per le PAGINE DI CATEGORIA ---
                $is_category_page = (empty($pageData['parent']) && $path !== '/' && $path !== '/cookie-policy' && $path !== '/privacy-policy');

                if ($is_category_page) {
                    $current_category_url = $pageData['url'];
                    $racconti_in_categoria = [];
                    foreach ($listaPagine as $pagina) {
                        if (isset($pagina['parent']) && $pagina['parent'] === $current_category_url) {
                            $racconti_in_categoria[] = $pagina;
                        }
                    }

                    if (!empty($racconti_in_categoria)) {
                        echo '<div class="row mt-4">';
                        foreach ($racconti_in_categoria as $racconto) {
                            echo '
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">';
                            
                            if (!empty($racconto['image'])) {
                                echo '<a href="' . htmlspecialchars($racconto['url']) . '"><img src="' . htmlspecialchars($racconto['image']) . '" class="card-img-top" alt="' . htmlspecialchars($racconto['title']) . '" style="height: 200px; object-fit: cover;"></a>';
                            }
                            
                            echo '
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">' . htmlspecialchars($racconto['title']) . '</h5>
                                        <p class="card-text flex-grow-1">' . substr(htmlspecialchars($racconto['description']), 0, 100) . '...</p>
                                        <a href="' . htmlspecialchars($racconto['url']) . '" class="btn btn-danger mt-auto">Leggi il racconto</a>
                                    </div>
                                </div>
                            </div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="mt-4">Non ci sono ancora racconti in questa categoria. Torna a trovarci presto!</p>';
                    }
                }
                // ########## FINE BLOCCO DINAMICO ##########
                ?>

                <?=$barretta?>
                <p id="orariofondo"><?=$orario?></p>
            </main>
        </div>

        <?php 
        // Mostra la sidebar solo se NON siamo in homepage
        if ($path != '/'): 
        ?>
            <aside class="col-lg-4">
                <?php require_once('/var/www/vhosts/raccontierotici24.it/httpdocs/include/sidebar.php'); ?>
            </aside>
        <?php endif; ?>
    </div>

    <?php require_once('/var/www/vhosts/raccontierotici24.it/httpdocs/include/footer.php'); ?>
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
  </body>
  </html>
