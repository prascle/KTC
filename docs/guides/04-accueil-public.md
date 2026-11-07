# Guide 4 — Accueil public pour les visiteurs non connectés

Ce guide accompagne la décision D26 (peaufinage post-M02) : les visiteurs
non connectés ne sont plus redirigés vers la page Connexion ; ils voient une
page d'accueil succincte les invitant à se connecter. Tout se règle depuis
l'administration : aucun fichier n'est modifié sur le serveur (convention
D16).

Principe : il n'y a qu'une seule page d'accueil, mais chacun n'en voit que
sa part. Joomla gère la page d'accueil par défaut sans distinction de
niveau d'accès ; on joue donc sur les accès des modules et des articles :

- l'élément de menu « Accueil » (articles en vedette) devient **Public** :
  plus de redirection vers la connexion ;
- les **modules** (carrousel, mot d'accueil) et les **articles** restent
  d'accès Enregistré : le visiteur n'en voit aucun ;
- un nouveau module « Espace privé », d'accès **Invité**, visible des seuls
  visiteurs, affiche le message et le bouton de connexion.

## 1. Ouvrir la page d'accueil aux visiteurs

Menus → Menu principal → élément **Accueil** (Articles en vedette) :

- champ **Accès** : `Enregistré` → `Public` ;
- Enregistrer.

La racine du site s'affiche désormais pour tout le monde. La liste des
articles en vedette reste vide pour les visiteurs : chaque article est
d'accès Enregistré (à conserver pour les futurs articles de la page
d'accueil).

## 2. Réserver le carrousel et le mot d'accueil aux connectés

Contenu → Modules :

- **Carrousel d'accueil** → champ **Accès** : `Enregistré` ;
- **Mot d'accueil** → champ **Accès** : `Enregistré`.

## 3. Créer le module « Espace privé »

Contenu → Modules → Nouveau → **Contenu personnalisé** :

- **Titre** : `Espace privé` ;
- **Position** : `banner` (Bannière) ;
- **Accès** : `Invité` ;
- onglet **Affectation** : « Sur les pages sélectionnées uniquement »
  → cocher uniquement **Accueil**.

Dans l'éditeur TinyMCE, basculer en mode code source (bouton **Code
source**, `</>`) et coller :

```html
<div class="ktc-bienvenue">
  <h2>Ker Tad-Coz</h2>
  <p>Espace privé de la famille : connectez-vous avec les identifiants
  que vous avez reçus.</p>
  <p><a class="btn btn-primary" href="index.php/connexion">Connexion</a></p>
</div>
```

Le texte est un brouillon, librement ajustable. Pour le lien du bouton, le
plus robuste est de le recréer avec le bouton **Lien** de l'éditeur en
choisissant l'élément de menu « Connexion » (il suivra le menu si son
adresse change). La classe `ktc-bienvenue` centre le bloc comme le mot
d'accueil ; le bouton porte la couleur granit rose du site.

## 4. Vérification

Un rechargement suffit. Vérifier :

- en navigation privée (visiteur) : la racine du site affiche « Ker
  Tad-Coz », le message et le bouton Connexion ; pas de carrousel, pas
  d'articles, pas de redirection ;
- clic sur le bouton Connexion → page de connexion → saisie des
  identifiants → retour sur l'accueil complet (carrousel, mot d'accueil,
  cartes) ;
- déconnexion → retour sur l'accueil public ;
- sur smartphone : le burger du visiteur propose « Accueil » et
  « Connexion ».

Remarque : pour le visiteur, l'icône du burger sur « Connexion » est
actuellement une maison (les icônes suivent la position dans le menu, pas
le libellé) ; si cela gêne, un petit correctif CSS la changera en clé.

En cas de souci : noter la page et l'appareil, et en faire part dans la
conversation — correctif par pull request, comme d'habitude.

## 5. Compléments après les premiers retours (08/11/2026)

### 5.1 Après déconnexion, revenir à l'accueil

Retour de test : après une déconnexion, on atterrissait sur la page
Connexion. Le lien de menu « Déconnexion » possède une option native de
redirection :

Menus → Menu principal → élément **Déconnexion** (Utilisateurs →
Déconnexion) :

- onglet **Paramètres** → champ **Redirection après déconnexion** :
  choisir l'élément **Accueil** ;
- Enregistrer.

Le champ contient probablement « Connexion » aujourd'hui, ce qui explique
le comportement constaté. Vérifier : se déconnecter → retour sur l'accueil
public (message + bouton Connexion), et non sur la page de connexion.

### 5.2 Bandeau et menus réservés aux connectés

Deuxième retour : réserver le bandeau (logo + description), le menu, le
burger mobile et la barre basse des smartphones aux utilisateurs
connectés. Un visiteur ne voit désormais que le bloc « Espace privé » (à
l'accueil) et le formulaire de connexion.

Rien à faire dans l'administration : le bandeau est rendu par le template
et non par un module, le champ Accès d'un module ne peut donc pas le
masquer. Le réglage est livré par le template lui-même (pull request,
convention D16) et fonctionne dès que les sections 1 et 3 ci-dessus sont
en place. Vérifier en navigation privée :

- à l'accueil : ni bandeau, ni menu, ni burger — le bloc « Espace privé »
  et le pied de page seulement ;
- sur la page Connexion : le formulaire, sans le bandeau ;
- sur smartphone : pas de barre basse ;
- connecté : rien ne change (bandeau, menus, barre basse, panneau
  Confort, retour en haut).

Remarque : le fil d'Ariane et le pied de page restent visibles des
visiteurs ; le mode sombre et la taille de texte mémorisés par un
visiteur restent appliqués sur ces pages.
