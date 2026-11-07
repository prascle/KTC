<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.ktc
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/*
 * Template enfant « ktc » — dérivé de templates/cassiopeia/index.php (Joomla 6.2).
 * Six écarts seulement par rapport au parent :
 *   1. chargement de la feuille de style enfant (palette P3, composants KTC) ;
 *   2. barre de navigation permanente (sticky) forcée ;
 *   3. menu mobile « burger » avec panneau à grandes entrées (arbitrage M1) ;
 *   4. scripts inline : carrousel de l'accueil, sommaire automatique ;
 *   5. bouton et panneau « Confort » de l'en-tête (arbitrage D24 : mode
 *      auto/clair/sombre + taille du texte) et entrée « Réglages » du burger ;
 *   6. barre basse mobile « type app » (arbitrage BB) construite par script
 *      depuis le menu réel (aucune adresse en dur).
 * À chaque mise à jour du cœur Joomla (via PR, décision D16), vérifier si
 * templates/cassiopeia/index.php évolue et reporter les changements ici.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Detecting Active Variables
$option   = $input->getCmd('option', '');
$view     = $input->getCmd('view', '');
$layout   = $input->getCmd('layout', '');
$task     = $input->getCmd('task', '');
$itemid   = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName  = 'theme.' . $paramsColorName;

// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles       = '';

if ($paramsFontScheme) {
    if (stripos($paramsFontScheme, 'https://') === 0) {
        $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['rel' => 'lazy-stylesheet', 'crossorigin' => 'anonymous']);

        if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
            $fontStyles = '--cassiopeia-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-family-headings: "' . str_replace('+', ' ', $matches[1][1] ?? $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-weight-normal: 400;
			--cassiopeia-font-weight-headings: 700;';
        }
    } elseif ($paramsFontScheme === 'system') {
        $fontStylesBody    = $this->params->get('systemFontBody', '');
        $fontStylesHeading = $this->params->get('systemFontHeading', '');

        if ($fontStylesBody) {
            $fontStyles = '--cassiopeia-font-family-body: ' . $fontStylesBody . ';
            --cassiopeia-font-weight-normal: 400;';
        }
        if ($fontStylesHeading) {
            $fontStyles .= '--cassiopeia-font-family-headings: ' . $fontStylesHeading . ';
    		--cassiopeia-font-weight-headings: 700;';
        }
    } else {
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['rel' => 'lazy-stylesheet']);
        $this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
    }
}

// Enable assets
$wa->usePreset('template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->registerAndUseStyle($assetColorName, 'global/' . $paramsColorName . '.css')
    ->useStyle('template.user')
    ->useScript('template.user')
    ->addInlineStyle(":root {
		--hue: 214;
		--template-bg-light: #f0f4fb;
		--template-text-dark: #495057;
		--template-text-light: #ffffff;
		--template-link-color: var(--link-color);
		--template-special-color: #001B4C;
		$fontStyles
	}");

// KTC : feuille de style du template enfant (palette P3, boutons, badges, encadrés) —
// chargée via l'API document (chemin déterministe, rendue après les assets du template).
// Version = date du fichier : l'URL change à chaque déploiement git, sinon les
// navigateurs gardent l'ancienne feuille en cache (la version « média » de Joomla
// ne change pas lors d'un déploiement par git — retour du test du 05/11).
$ktcCssFile = JPATH_PUBLIC . '/templates/ktc/css/template.css';
$this->addStyleSheet(Uri::root(true) . '/templates/ktc/css/template.css', ['version' => is_file($ktcCssFile) ? date('Ymd-His', (int) filemtime($ktcCssFile)) : 'auto']);

// KTC : bascule du menu mobile (burger — arbitrage M1, bouton visible < 992 px).
$wa->addInlineScript('
 document.addEventListener("DOMContentLoaded", function () {
  const b = document.querySelector(".ktc-burger");
  const m = document.getElementById("ktc-menu-list");
  if (!b || !m) { return; }
  b.addEventListener("click", function () {
    const open = b.getAttribute("aria-expanded") === "true";
    b.setAttribute("aria-expanded", open ? "false" : "true");
    m.classList.toggle("ktc-open", !open);
  });
 });
');

// KTC : carrousel de l'accueil (arbitrage D21) — le script crée les points
// de navigation, branche les flèches (en butée, le carrousel tourne en
// boucle — retour du peaufinage du 07/11) et suit le défilement de la piste.
// Sans JavaScript, la piste reste défilable au doigt ou à la molette
// (scroll natif) ; le défilement animé respecte prefers-reduced-motion.
$wa->addInlineScript('
 document.addEventListener("DOMContentLoaded", function () {
  var fluide = !window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  document.querySelectorAll(".ktc-carrousel").forEach(function (carrousel) {
    var piste = carrousel.querySelector(".ktc-carrousel-piste");
    var points = carrousel.querySelector(".ktc-carrousel-points");
    var nb = piste ? piste.querySelectorAll("figure").length : 0;
    if (!piste || nb === 0) { return; }
    var defile = function (gauche) {
      piste.scrollBy({ left: gauche, behavior: fluide ? "smooth" : "auto" });
    };
    if (points) {
      var i;
      for (i = 0; i < nb; i++) {
        (function (indice) {
          var point = document.createElement("button");
          point.type = "button";
          point.setAttribute("aria-label", "Aller à la photo " + (indice + 1));
          point.addEventListener("click", function () {
            piste.scrollTo({ left: indice * piste.clientWidth, behavior: fluide ? "smooth" : "auto" });
          });
          points.appendChild(point);
        })(i);
      }
      piste.addEventListener("scroll", function () {
        var actif = Math.min(nb - 1, Math.max(0, Math.round(piste.scrollLeft / piste.clientWidth)));
        Array.prototype.forEach.call(points.children, function (point, n) {
          point.classList.toggle("actif", n === actif);
        });
      });
    }
    var precedente = carrousel.querySelector(".ktc-carrousel-fleche.ktc-precedente");
    var suivante = carrousel.querySelector(".ktc-carrousel-fleche.ktc-suivante");
    var fleche = function (versLaFin) {
      var butee = piste.scrollWidth - piste.clientWidth;
      if (versLaFin ? piste.scrollLeft >= butee - 4 : piste.scrollLeft <= 4) {
        piste.scrollTo({ left: versLaFin ? 0 : piste.scrollWidth, behavior: fluide ? "smooth" : "auto" });
      } else {
        defile(versLaFin ? piste.clientWidth : -piste.clientWidth);
      }
    };
    if (precedente) { precedente.addEventListener("click", function () { fleche(false); }); }
    if (suivante) { suivante.addEventListener("click", function () { fleche(true); }); }
  });
 });
');

// KTC : sommaire automatique des articles longs (arbitrage D21) — dès qu'un
// article contient au moins 3 sous-titres (niveaux 2 à 4), un sommaire
// cliquable est inséré en tête de texte. Les ancres suivent le défilement
// fluide du CSS (section 6), sauf si la réduction d'animations est activée.
$wa->addInlineScript('
 document.addEventListener("DOMContentLoaded", function () {
  var corps = document.querySelector(".com-content-article__body");
  if (!corps) { return; }
  var titres = corps.querySelectorAll("h2, h3, h4");
  if (titres.length < 3) { return; }
  var nav = document.createElement("nav");
  nav.className = "ktc-sommaire";
  nav.setAttribute("aria-label", "Sommaire");
  var entete = document.createElement("p");
  entete.className = "ktc-sommaire-titre";
  entete.textContent = "Sommaire";
  nav.appendChild(entete);
  var liste = document.createElement("ul");
  Array.prototype.forEach.call(titres, function (titre, i) {
    if (!titre.id) { titre.id = "ktc-s-" + (i + 1); }
    var item = document.createElement("li");
    item.className = "ktc-niveau-" + titre.tagName.toLowerCase();
    var lien = document.createElement("a");
    lien.href = "#" + titre.id;
    lien.textContent = titre.textContent;
    item.appendChild(lien);
    liste.appendChild(item);
  });
  nav.appendChild(liste);
  corps.insertBefore(nav, corps.firstChild);
 });
');

// KTC : réglages de confort (arbitrages D24 + S) — mode d'affichage
// auto/clair/sombre et taille du texte, persistés par navigateur
// (localStorage). Robustesse (retour du test du 06/11) : le branchement de
// l'interface est enregistré AVANT toute application du thème et tout
// appel sensible est gardé par try/catch — sur un navigateur où l'écoute
// matchMedia n'existe pas (iOS < 14, vieux Chrome), le script s'interrompait
// avant de brancher le bouton et l'entrée de menu : le panneau s'ouvre
// désormais quoi qu'il arrive. L'écoute de la préférence de l'appareil a un
// repli addListener pour ces navigateurs. Enfin, le détecteur de « clic
// extérieur » ignore les clics venant de l'entrée « Réglages » du burger,
// qui refermait le panneau qu'elle venait d'ouvrir.
$wa->addInlineScript('
 (function () {
  var racine = document.documentElement;
  var lire = function (cle, defaut) {
    try { return window.localStorage.getItem(cle) || defaut; } catch (e) { return defaut; }
  };
  var ecrire = function (cle, valeur) {
    try { window.localStorage.setItem(cle, valeur); } catch (e) {}
  };
  var systeme = null;
  try { systeme = window.matchMedia("(prefers-color-scheme: dark)"); } catch (e) {}
  var appliquerTheme = function () {
    var choix = lire("ktc-theme", "auto");
    var nuit = choix === "sombre" || (choix === "auto" && systeme && systeme.matches);
    racine.setAttribute("data-ktc-theme", nuit ? "sombre" : "clair");
  };
  var appliquerTaille = function () {
    var taille = lire("ktc-taille", "moyenne");
    racine.classList.remove("ktc-taille-petite", "ktc-taille-grande");
    if (taille === "petite") { racine.classList.add("ktc-taille-petite"); }
    if (taille === "grande") { racine.classList.add("ktc-taille-grande"); }
  };
  document.addEventListener("DOMContentLoaded", function () {
    var panneau = document.getElementById("ktc-confort");
    var bouton = document.querySelector(".ktc-confort-bouton");
    var burger = document.querySelector(".ktc-burger");
    var liste = document.getElementById("ktc-menu-list");
    if (!panneau) { return; }
    var marquer = function () {
      var theme = lire("ktc-theme", "auto");
      var taille = lire("ktc-taille", "moyenne");
      panneau.querySelectorAll("[data-theme-choix]").forEach(function (b) {
        b.classList.toggle("actif", b.getAttribute("data-theme-choix") === theme);
      });
      panneau.querySelectorAll("[data-taille-choix]").forEach(function (b) {
        b.classList.toggle("actif", b.getAttribute("data-taille-choix") === taille);
      });
    };
    var basculer = function (ouvrir) {
      panneau.classList.toggle("ktc-open", ouvrir);
      if (bouton) { bouton.setAttribute("aria-expanded", ouvrir ? "true" : "false"); }
    };
    panneau.querySelectorAll("[data-theme-choix]").forEach(function (b) {
      b.addEventListener("click", function () {
        ecrire("ktc-theme", b.getAttribute("data-theme-choix"));
        appliquerTheme();
        marquer();
      });
    });
    panneau.querySelectorAll("[data-taille-choix]").forEach(function (b) {
      b.addEventListener("click", function () {
        ecrire("ktc-taille", b.getAttribute("data-taille-choix"));
        appliquerTaille();
        marquer();
      });
    });
    if (bouton) {
      bouton.addEventListener("click", function () {
        basculer(!panneau.classList.contains("ktc-open"));
      });
    }
    document.addEventListener("click", function (e) {
      var cible = e.target;
      var depuisEntree = (cible && cible.closest) ? cible.closest(".ktc-li-reglages") : null;
      if (panneau.classList.contains("ktc-open")
          && !panneau.contains(cible)
          && !(bouton && bouton.contains(cible))
          && !depuisEntree) {
        basculer(false);
      }
    });
    var ul = liste ? liste.querySelector(".mod-menu") : null;
    if (ul && burger) {
      var li = document.createElement("li");
      li.className = "ktc-li-reglages";
      var entree = document.createElement("button");
      entree.type = "button";
      entree.textContent = "Réglages";
      entree.addEventListener("click", function () {
        burger.setAttribute("aria-expanded", "false");
        liste.classList.remove("ktc-open");
        basculer(true);
      });
      li.appendChild(entree);
      ul.appendChild(li);
    }
    marquer();
  });
  try { appliquerTheme(); } catch (e) {}
  try { appliquerTaille(); } catch (e) {}
  try {
    if (systeme) {
      if (systeme.addEventListener) {
        systeme.addEventListener("change", appliquerTheme);
      } else if (systeme.addListener) {
        systeme.addListener(function () { appliquerTheme(); });
      }
    }
  } catch (e) {}
 })();
');

// KTC : barre basse mobile « type app » (arbitrage BB du laboratoire,
// entrées confirmées par Paul le 06/11 : Accueil, la rubrique des règles
// [La maison], la SCI, Travaux, et le bouton Menu). Construite depuis le
// menu réel : liens toujours corrects, aucune adresse en dur ; les
// raccourcis sont la 1re, 2e, 3e et 5e entrée du menu principal (ordre
// D20 — ajuster ici si le menu évolue). Visible < 992 px uniquement.
$wa->addInlineScript('
 document.addEventListener("DOMContentLoaded", function () {
  var ul = document.querySelector("#ktc-menu-list .mod-menu");
  if (!ul) { return; }
  var entrees = ul.children;
  var positions = [0, 1, 2, 4];
  var icones = ["&#127968;", "&#127969;", "&#127963;&#65039;", "&#128296;"];
  var nav = document.createElement("nav");
  nav.className = "ktc-bb";
  nav.setAttribute("aria-label", "Accès rapides");
  var courante = window.location.pathname + window.location.search;
  positions.forEach(function (position, i) {
    var li = entrees[position];
    var lien = li ? li.querySelector("a") : null;
    if (!lien) { return; }
    var item = document.createElement("a");
    item.href = lien.href;
    item.className = "ktc-bb-item";
    var icone = document.createElement("span");
    icone.className = "ktc-bb-icone";
    icone.setAttribute("aria-hidden", "true");
    icone.innerHTML = icones[i];
    var libelle = document.createElement("span");
    libelle.textContent = lien.textContent.trim();
    item.appendChild(icone);
    item.appendChild(libelle);
    try {
      var u = new URL(lien.href);
      if (u.pathname + u.search === courante) { item.classList.add("actif"); }
    } catch (e) {}
    nav.appendChild(item);
  });
  var boutonMenu = document.createElement("button");
  boutonMenu.type = "button";
  boutonMenu.className = "ktc-bb-item ktc-bb-menu";
  var iconeMenu = document.createElement("span");
  iconeMenu.className = "ktc-bb-icone";
  iconeMenu.setAttribute("aria-hidden", "true");
  iconeMenu.innerHTML = "&#9776;";
  var libelleMenu = document.createElement("span");
  libelleMenu.textContent = "Menu";
  boutonMenu.appendChild(iconeMenu);
  boutonMenu.appendChild(libelleMenu);
  boutonMenu.addEventListener("click", function () {
    var b = document.querySelector(".ktc-burger");
    var m = document.getElementById("ktc-menu-list");
    if (!b || !m) { return; }
    var ouvrir = !(b.getAttribute("aria-expanded") === "true");
    b.setAttribute("aria-expanded", ouvrir ? "true" : "false");
    m.classList.toggle("ktc-open", ouvrir);
  });
  nav.appendChild(boutonMenu);
  document.body.appendChild(nav);
 });
');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
if ($this->params->get('logoFile')) {
    $logo = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($this->params->get('siteTitle')) {
    $logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
    $logo = HTMLHelper::_('image', 'logo.svg', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
}

$hasClass = '';

if ($this->countModules('sidebar-left', true)) {
    $hasClass .= ' has-sidebar-left';
}

if ($this->countModules('sidebar-right', true)) {
    $hasClass .= ' has-sidebar-right';
}

// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

// KTC : barre de navigation permanente pendant le scroll (acquis M02)
$stickyHeader = 'position-sticky sticky-top';

// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>

<body id="top" class="site <?php echo $option
    . ' ' . $wrapper
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($pageclass ? ' ' . $pageclass : '')
    . $hasClass
    . ($this->direction == 'rtl' ? ' rtl' : '');
?>">
    <header class="header container-header full-width<?php echo $stickyHeader ? ' ' . $stickyHeader : ''; ?>">

        <?php if ($this->countModules('topbar')) : ?>
            <div class="container-topbar">
                <jdoc:include type="modules" name="topbar" style="none" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('below-top')) : ?>
            <div class="grid-child container-below-top">
                <jdoc:include type="modules" name="below-top" style="none" />
            </div>
        <?php endif; ?>

        <?php if ($this->params->get('brand', 1)) : ?>
            <div class="grid-child">
                <div class="navbar-brand">
                    <a class="brand-logo" href="<?php echo $this->baseurl; ?>/">
                        <?php echo $logo; ?>
                    </a>
                    <?php if ($this->params->get('siteDescription')) : ?>
                        <div class="site-description"><?php echo htmlspecialchars($this->params->get('siteDescription')); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('menu', true) || $this->countModules('search', true)) : ?>
            <div class="grid-child container-nav">
                <?php if ($this->countModules('menu', true)) : ?>
                    <button class="ktc-burger" type="button" aria-expanded="false" aria-controls="ktc-menu-list">
                        <span class="ktc-burger-lines" aria-hidden="true"></span>Menu
                    </button>
                    <div id="ktc-menu-list" class="ktc-menu-list">
                        <jdoc:include type="modules" name="menu" style="none" />
                    </div>
                <?php endif; ?>
                <?php if ($this->countModules('search', true)) : ?>
                    <div class="container-search">
                        <jdoc:include type="modules" name="search" style="none" />
                    </div>
                <?php endif; ?>
                <button class="ktc-confort-bouton" type="button" aria-expanded="false" aria-controls="ktc-confort">
                    <span aria-hidden="true">&#9881;&#65039;</span><span class="visually-hidden">Réglages de confort</span>
                </button>
                <div id="ktc-confort" class="ktc-confort">
                    <p class="ktc-confort-titre">Mode d'affichage</p>
                    <div class="ktc-confort-choix" role="group" aria-label="Mode d'affichage">
                        <button type="button" data-theme-choix="auto">Auto</button>
                        <button type="button" data-theme-choix="clair">Clair</button>
                        <button type="button" data-theme-choix="sombre">Sombre</button>
                    </div>
                    <p class="ktc-confort-titre">Taille du texte</p>
                    <div class="ktc-confort-choix" role="group" aria-label="Taille du texte">
                        <button type="button" data-taille-choix="petite">Petite</button>
                        <button type="button" data-taille-choix="moyenne">Moyenne</button>
                        <button type="button" data-taille-choix="grande">Grande</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <div class="site-grid">
        <?php if ($this->countModules('banner', true)) : ?>
            <div class="container-banner full-width">
                <jdoc:include type="modules" name="banner" style="none" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-a', true)) : ?>
            <div class="grid-child container-top-a">
                <jdoc:include type="modules" name="top-a" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-b', true)) : ?>
            <div class="grid-child container-top-b">
                <jdoc:include type="modules" name="top-b" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('sidebar-left', true)) : ?>
            <div class="grid-child container-sidebar-left">
                <jdoc:include type="modules" name="sidebar-left" style="card" />
            </div>
        <?php endif; ?>

        <div class="grid-child container-component">
            <jdoc:include type="modules" name="breadcrumbs" style="none" />
            <jdoc:include type="modules" name="main-top" style="card" />
            <jdoc:include type="message" />
            <main>
                <jdoc:include type="component" />
            </main>
            <jdoc:include type="modules" name="main-bottom" style="card" />
        </div>

        <?php if ($this->countModules('sidebar-right', true)) : ?>
            <div class="grid-child container-sidebar-right">
                <jdoc:include type="modules" name="sidebar-right" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('bottom-a', true)) : ?>
            <div class="grid-child container-bottom-a">
                <jdoc:include type="modules" name="bottom-a" style="card" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('bottom-b', true)) : ?>
            <div class="grid-child container-bottom-b">
                <jdoc:include type="modules" name="bottom-b" style="card" />
            </div>
        <?php endif; ?>
    </div>

    <?php if ($this->countModules('footer', true)) : ?>
        <footer class="container-footer footer full-width">
            <div class="grid-child">
                <jdoc:include type="modules" name="footer" style="none" />
            </div>
        </footer>
    <?php endif; ?>

    <?php if ($this->params->get('backTop') == 1) : ?>
        <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_CASSIOPEIA_BACKTOTOP'); ?>">
            <span class="icon-arrow-up icon-fw" aria-hidden="true"></span>
        </a>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />
</body>

</html>
