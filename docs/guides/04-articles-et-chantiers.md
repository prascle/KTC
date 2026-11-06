# Guide 4 — Articles et chantiers

Ce guide accompagne la livraison « Articles & chantiers » du template ktc.
Il décrit les réglages d'administration (une dizaine de minutes), puis les
vérifications. Comme toujours : rien ne s'installe sur le serveur, tout le
code est livré par le dépôt.

## 1. Dates et auteur sur les articles

**Contenu → Articles → bouton Options** (onglet **Articles**) :

- *Afficher la date de création* = **Oui** ;
- *Afficher la date de modification* = **Oui** ;
- *Afficher l'auteur* = **Oui** ;
- *Position du bloc d'infos* = la valeur plaçant les informations **en
  haut**, sous le titre (si l'intitulé exact diffère, capture d'écran dans la
  conversation plutôt qu'improvisation).

Le template fait le reste, sans réglage : la date de modification
n'apparaît **que si l'article a été repris après sa création** (décision du
06/11). Un article jamais retouché n'affiche que sa date de création.

## 2. Fiche chantier : affichage automatique

Tout article de la catégorie **Travaux** affiche désormais sa fiche chantier
en tête : badge de statut coloré (gris « À l'étude / À programmer », ambre
« En cours », vert « Terminé »), barre d'avancement avec pourcentage, budget
estimé, coût constaté, date de décision. **Le rédacteur n'écrit aucun HTML** :
il remplit l'onglet « Fiche chantier » de l'article, l'affichage est assuré
par le template.

Un seul réglage à faire, une fois — pour les **cinq champs** du groupe
« Fiche chantier » (*Statut du chantier*, *Date de décision*, *Budget
estimé*, *Coût constaté*, *Avancement*) :

**Contenu → Champs** → ouvrir chaque champ → onglet **Options** →
*Affichage automatique* = **Ne pas afficher automatiquement**.

Sans ce réglage, les informations apparaîtraient deux fois : la fiche en
tête, et le rendu automatique standard en bas d'article.

Le champ *Pièce jointe officielle* (groupe « Document officiel ») reste sur
*Après le titre* : le lien de téléchargement des documents officiels
(statuts, comptes rendus d'AG) continue d'être assuré par lui, sans
développement spécifique (décision du 06/11).

Comportements attendus : champ vide = ligne absente (un chantier sans coût
constaté n'affiche pas la ligne) ; *Avancement* à 0 s'affiche (c'est une
valeur légitime d'un chantier à l'étude).

## 3. Sommaire automatique des articles longs

Rien à régler : dès qu'un article contient **au moins 3 sous-titres**
(niveaux 2 à 4), un sommaire cliquable apparaît en tête de texte, avec
ancrage fluide vers chaque section.

Conseil à transmettre aux rédacteurs : structurer avec les styles **Titre 2 /
Titre 3** de TinyMCE (menu « Formats » ou liste déroulante des paragraphes),
pas avec du texte en gras — le sommaire ne se construit que sur les vrais
titres.

## 4. Encadrés « À savoir »

Pour une règle ou un conseil, dans l'éditeur : basculer en mode **Code
source** (`</>`) et encadrer le(s) paragraphe(s) :

```html
<div class="ktc-astuce"><p>Le texte de la règle ou du conseil.</p></div>
```

Puis revenir au mode visuel pour saisir le texte. Plusieurs paragraphes
peuvent vivre dans le même encadré : `<div class="ktc-astuce">` … `</div>`
autour de l'ensemble.

## 5. Vérification

1. **Fiche chantier** — ouvrir « Chantier : refaites la toiture du côté
   nord » : badge ambre « En cours », barre à 40 %, budget estimé visible ;
   puis « Chantier : remplacement du piano » : badge gris « À l'étude ».
   Vérifier qu'aucun bloc de champs ne s'affiche **en bas** d'article (si
   oui, revoir le réglage *Affichage automatique*, §2).
2. **Dates** — un article jamais modifié n'affiche que « Créé le … » ;
   l'éditer, enregistrer, recharger : « Dernière modification … » apparaît.
3. **Sommaire** — créer ou éditer un article comportant au moins 3
   sous-titres : le sommaire se construit seul, les liens défilent en
   douceur (saut direct si la réduction d'animations est activée).
4. **Encadré** — coller un encadré « À savoir » : fond bleu clair, liseré
   bleu à gauche.
5. Vérifier sur PC **et** smartphone.

En cas de souci : noter la page et l'appareil, en faire part dans la
conversation — correctif par pull request, comme d'habitude.
