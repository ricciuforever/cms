<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">
      <img src="/img/logo.png" alt="Logo raccontierotici24.it" width="135" height="40" style="height: 40px; width: auto;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php foreach($menu_sidebar as $ms) { 
            // Escludiamo le pagine di policy dal menu di navigazione
            if($ms["genitore"]["url"] != '/cookie-policy' && $ms["genitore"]["url"] != '/privacy-policy') {
                
                // Controlliamo se ci sono pagine figlie per creare un dropdown
                if(isset($ms["figlie"]) && $ms["figlie"] && is_array($ms["figlie"])) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink-<?= md5($ms["genitore"]["url"]) ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?=$ms["genitore"]["title"]?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink-<?= md5($ms["genitore"]["url"]) ?>">
                            <?php foreach($ms["figlie"] as $msf) { ?>
                                <li><a class="dropdown-item" href="<?=$msf["url"]?>"><?=$msf["title"]?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$ms["genitore"]["url"]?>"><?=$ms["genitore"]["title"]?></a>
                    </li>
                <?php }
            }
        } ?>
      </ul>
    </div>
  </div>
</nav>