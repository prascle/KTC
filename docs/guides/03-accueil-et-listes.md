# Guide 3 — Accueil (carrousel + mot d'accueil) et listes en cartes

Ce guide accompagne la Livraison 2 du template ktc. Il décrit la mise en
place, entièrement depuis l'administration :

1. les photos du carrousel d'accueil ;
2. le module « Carrousel d'accueil » ;
3. le module « Mot d'accueil » (brouillon de texte à ajuster) ;
4. les listes d'articles en cartes (automatiques).

Rappel de la convention (décision D16) : aucun fichier n'est modifié sur le
serveur ; le code (CSS, script) est déjà livré par le dépôt. Ici, tout se
règle par les médias et les modules.

## 1. Photos

Contenu → Médias → créer le dossier `accueil` dans `images/`, puis y
téléverser les cinq photos ci-dessous (format JPG ; environ 1600 px de
large suffisent, la qualité 80 % est un bon compromis) :

| Fichier               | Photo d'origine | Sujet                                             |
|-----------------------|-----------------|---------------------------------------------------|
| `facade.jpg`          | IMG_1090        | La maison et son jardin                           |
| `maison-enceinte.jpg` | img022          | La maison et le mur d'enceinte                    |
| `maison-1996.jpg`     | 1996_07_134     | La maison et le jardin fleuri (1996)              |
| `cote-mer.jpg`        | IMG_0992        | La côte et les bateaux à marée basse              |
| `jardin-soir.jpg`     | IMG_3882        | La maison et le jardin en fin de journée          |

> ⚠️ RGPD : ne pas téléverser `110-1092` dans ce dossier public (personnes
> visibles — point n°7 de la reflexion RGPD en cours).

## 2. Module « Carrousel d'accueil »

Contenu → Modules → Nouveau → **Contenu personnalisé** :

- **Titre** : `Carrousel d'accueil` ;
- **Position** : `banner` (Bannière) ;
- onglet **Affectation** : « Sur les pages sélectionnées uniquement »
  → cocher uniquement **Accueil**.

Dans l'éditeur TinyMCE, basculer en mode code source (bouton **Code
source**, `</>`) et coller :

```html
<div class="ktc-carrousel">
  <div class="ktc-carrousel-piste">
    <figure><img src="images/accueil/facade.jpg" alt="Ker Tad-Coz : la maison et son jardin" loading="eager"></figure>
    <figure><img src="images/accueil/maison-enceinte.jpg" alt="La maison et le mur d'enceinte" loading="lazy"></figure>
    <figure><img src="images/accueil/maison-1996.jpg" alt="La maison et le jardin fleuri en 1996" loading="lazy"></figure>
    <figure><img src="images/accueil/cote-mer.jpg" alt="La côte et les bateaux à marée basse" loading="lazy"></figure>
    <figure><img src="images/accueil/jardin-soir.jpg" alt="La maison et le jardin en fin de journée" loading="lazy"></figure>
  </div>
  <button class="ktc-carrousel-fleche ktc-precedente" type="button" aria-label="Photo précédente">&#8249;</button>
  <button class="ktc-carrousel-fleche ktc-suivante" type="button" aria-label="Photo suivante">&#8250;</button>
  <div class="ktc-carrousel-points"></div>
</div>
```

Les points de navigation sont créés automatiquement par le script du
template ; les textes `alt` peuvent être ajustés librement. Enregistrer.

## 3. Module « Mot d'accueil »

Même méthode : nouveau module **Contenu personnalisé**, position `banner`,
affecté à la seule page **Accueil**. Dans la liste des modules (trie par
position), le placer **sous** le carrousel. En mode code source, coller ce
brouillon (texte provisoire, à affiner — le texte définitif sera validé
avec le conseil d'administration) :

```html
<div class="ktc-bienvenue">
  <h2>Bienvenue à Ker Tad-Coz</h2>
  <p>Ker Tad-Coz, « la maison du coin » en breton, est la maison de famille
  de la SCI depuis 1909. Elle accueille les associés et leurs proches
  d'avril à octobre. Ce site présente la maison, la vie de la SCI,
  l'avancement des travaux et les comptes rendus des assemblées.</p>
  <p>Associés : <a href="index.php?option=com_users&amp;view=login">connectez-vous</a>
  pour accéder à l'espace réservé (assemblées, vie familiale, contributions).</p>
</div>
```

> Le lien vers l'espace associés : le plus robuste est de le recréer avec le
> bouton **Lien** de l'éditeur en choisissant l'élément de menu
> « Connexion / Espace associés » (il suivra ainsi le menu si son adresse
> change).

## 4. Listes d'articles en cartes

Rien à installer : depuis cette livraison, les pages « Blog d'une
catégorie » affichent chaque article en carte (fond blanc, image au format
16/9, ombre au survol).

- **Image d'une carte** : dans l'article, onglet **Images et liens** →
  champ **Image d'introduction** → choisir une photo dans le sélecteur de
  médias. Prévoir idéalement une image proche du 16/9 (par exemple
  1200 × 675 px) ; sans image, la carte reste correcte (texte seul).
- **Deux colonnes** : dans chaque élément de menu de rubrique, onglet
  **Blog** → **# Colonnes** = `2`.

## 5. Vérification

Un rechargement normal suffit (la feuille de style est versionnée par la
date du fichier, donc le cache se met à jour tout seul à chaque
déploiement). Vérifier :

- l'accueil : carrousel pleine largeur, cinq photos, flèches ‹ ›, points de
  navigation, défilement au doigt sur smartphone ;
- le mot d'accueil centré sous le carrousel ;
- une rubrique ayant au moins un article avec image d'introduction :
  cartes blanches à deux colonnes, image 16/9, ombre au survol ;
- la même page sur smartphone : cartes empilées, carrousel au doigt.

En cas de souci : noter la page et l'appareil, et en faire part dans la
conversation — correctif par pull request, comme d'habitude.
