# KTC — Guide 2 : configuration du site Joomla

*À exécuter dans l'administration Joomla (`/administrator`) après le Guide 1. Les intitulés exacts des écrans peuvent varier légèrement : en cas de doute, capture d'écran dans la conversation plutôt qu'improvisation. Durée estimée : 1 à 2 heures.*

## Principe : les 5 rôles traduits en ACL Joomla

| Rôle KTC | Groupe Joomla | Niveau d'accès | Voit |
|---|---|---|---|
| Visiteur non enregistré | (Public) | — | uniquement la page de connexion |
| Invité non associé | **Invités KTC** | Accès invités | la maison, infos pratiques, règles d'utilisation |
| Associé | **Associés** | Accès associés | tout ce qui précède + SCI, assemblées, travaux, vie familiale |
| Membre du CA | **Conseil d'administration** (sous-groupe d'Associés) | Accès conseil | tout ce qui précède + rubriques réservées au CA |
| Administrateur du site | Super Users | — | tout |

Mécanisme Joomla : un membre d'un sous-groupe appartient implicitement à tous ses groupes parents (jamais l'inverse : le membre d'un groupe parent n'appartient pas à ses sous-groupes). Les niveaux d'accès suivent ce sens : un niveau qui liste « Invités KTC » est visible par les invités **et par tous leurs descendants**. D'où l'arborescence **Invités KTC → Associés → Conseil d'administration** : les associés descendent des invités (ils voient tout ce que voient les invités), le CA descend des associés — sans doublon de réglage.

## 1. Groupes d'utilisateurs

**Utilisateurs → Groupes d'utilisateurs → Ajouter** (trois créations) :

1. **Invités KTC** — parent : *Enregistré* — description : « Invités non associés, compte temporaire créé par un associé » ;
2. **Associés** — parent : **Invités KTC** (ainsi les associés héritent de l'accès invités : ils voient tout ce que voient les invités) ;
3. **Conseil d'administration** — parent : *Associés*.

## 2. Niveaux d'accès

**Utilisateurs → Niveaux d'accès → Ajouter** (trois créations) :

1. **Accès invités** — groupes cochés : Invités KTC ;
2. **Accès associés** — groupes cochés : Associés ;
3. **Accès conseil** — groupes cochés : Conseil d'administration.

## 3. Site fermé aux visiteurs

1. **Système → Configuration globale → onglet Utilisateurs** : *Autoriser l'enregistrement des utilisateurs* = **Désactivé** (rôle « visiteur : aucun accès, même pour demander un compte »).
2. Le menu (ci-dessous §6) ne contiendra **aucun** élément d'accès « Public » sauf la page de connexion.

## 4. Catégories de contenus

**Contenu → Catégories → Ajouter** (l'accès se règle dans chaque fiche, onglet « Catégorie » → champ *Accès*) :

| Catégorie | Accès | Sous-catégories (accès hérité) |
|---|---|---|
| La maison | Accès invités | Histoire · Informations pratiques · Règles d'utilisation |
| La SCI | Accès associés | Statuts · Associés · Gouvernance (qui fait quoi) · Comptes |
| Assemblées | Accès associés | (à plat : comptes rendus d'AG, relevés de décisions, convocations) |
| Travaux | Accès associés | (à plat : une fiche-article par chantier) |
| Vie familiale | Accès associés | Photos · Souvenirs · Événements |

## 5. Champs personnalisés

**Contenu → Groupes de champs** : créer le groupe **« Fiche chantier »**, affecté à la catégorie *Travaux*.
**Contenu → Champs** (chaque champ : onglet Publication → *Afficher sur le site* = « Après le contenu ») :

| Champ | Type | Réglages |
|---|---|---|
| Statut du chantier | Liste | À l'étude · À programmer · En cours · Terminé |
| Date de décision | Calendrier | — |
| Budget estimé | Texte | — |
| Coût constaté | Texte | masqué tant que vide |
| Avancement | Entier | « % » dans le libellé : « Avancement (%) » |

Toujours dans **Contenu → Groupes de champs** : créer le groupe **« Document officiel »** affecté à la catégorie *La SCI* → un champ type **Document** (natif Joomla 6) nommé « Pièce jointe officielle » : il s'affichera comme lien de téléchargement propre en tête de fiche (statuts PDF, CR d'AG).

## 6. Menus et modules

**Menus → Menu principal** — créer les éléments :

| Élément de menu | Type | Accès |
|---|---|---|
| Accueil | Articles → Articles en vedette | Enregistré |
| La maison | Articles → Blog de catégorie « La maison » | Accès invités |
| Règles d'utilisation | Articles → Article unique | Accès invités |
| La SCI | Articles → Blog de catégorie « La SCI » | Accès associés |
| Assemblées | Articles → Blog de catégorie « Assemblées » | Accès associés |
| Travaux | Articles → Blog de catégorie « Travaux » | Accès associés |
| Vie familiale | Articles → Blog de catégorie « Vie familiale » | Accès associés |
| Mon compte | Utilisateur → Modifier le profil | Enregistré |
| Connexion | Utilisateur → Connexion | Public |

**Modules** (**Modules → Modules du site**) :
- *Menu principal* : position **menu** (Cassiopeia), afficher le titre : non ;
- *Connexion* : position **topbar**, toutes les pages, accès Public.

La recherche et l'ergonomie mobile fine arrivent avec M02 (template) — ne rien ajouter pour l'instant.

## 7. Articles de démonstration (à créer par vous)

Quelques articles illustratifs avec photos réelles de la maison (via **Contenu → Médias**) :

1. *Histoire de la maison* (La maison/Histoire) — quelques paragraphes + 2 photos ;
2. *Les règles d'utilisation en bref* (Règles d'utilisation) — liste à puces, style « à savoir » ;
3. *Statuts de la SCI* (La SCI/Statuts) — résumé + **PDF joint** via le champ « Pièce jointe officielle » ;
4. *Compte rendu d'AG — exemple* (Assemblées) — trame vide à remplacer ;
5. *Chantier : refaites la toiture du côté nord* (Travaux) — champs remplis : Statut = En cours, Avancement = 40, Budget estimé ;
6. *Chantier : remplacement du piano* (Travaux) — Statut = À l'étude ;
7. *L'été à KTC 2026* (Vie familiale/Photos) — galerie de 4–6 photos.

Mettre 3–4 articles « en vedette » (option de chaque article) pour peupler la page d'accueil.

## 8. Utilisateurs de test

**Utilisateurs → Ajouter** — en plus de votre super administrateur :

| Utilisateur fictif | Groupe | Sert à tester |
|---|---|---|
| Camille Associé | Associés | l'espace associé complet |
| Claude CA | Conseil d'administration | idem + futures rubriques CA |
| Invitation Test | Invités KTC | la vision « invité » (la maison + règles seulement) |

Tester chaque compte **dans une fenêtre privée du navigateur** : un invité ne doit voir que « La maison », « Règles d'utilisation » et « Accueil » ; un visiteur non connecté ne doit voir que la connexion.

## 9. Invités temporaires — version M01

Le rôle « Invités KTC » existe (§1), mais Joomla n'a pas **d'expiration native** des comptes. En M01, la temporaryté est gérée à la main : à la fin d'un séjour, l'associé référent demande au CA de **désactiver** le compte (Utilisateurs → fiche → bloqué). L'automatisation (création par l'associé + expiration automatique) est notée comme chantier d'extension pour un jalon futur.

## 10. Contributions des associés

**Objectif** : les associés peuvent proposer des articles (souvenirs, photos, suites de séjours…), sans les publier eux-mêmes — le CA relit et publie. Les groupes prédéfinis *Author / Editor / Publisher* restent **vides** : on réutilise leur sémantique (Associés = type Auteur, CA = type Publisher), mais les comptes restent dans les 3 groupes KTC (§1) et ce sont les **permissions** qui portent les droits.

### Accès à l'administration

En M01, les rédacteurs passent par l'administration Joomla :

1. **Système → Configuration globale → onglet Permissions** → groupe **Associés** → *Connexion à l'administration* = **Autorisé**. L'associé ne verra ensuite que ce que les permissions lui ouvrent (ici : écrire dans Vie familiale, rien d'autre).

### Permissions « type Auteur » — uniquement sur Vie familiale

1. **Système → Configuration globale → onglet Permissions** → groupe **Conseil d'administration** → réglages d'*Articles* : *Créer*, *Modifier*, *Modifier l'état* = **Autorisé** (rôle type Publisher : le CA peut publier partout).
2. Pour les **Associés**, on n'accorde **rien** globalement (*Hérité* = refusé). On n'ouvre que la rubrique des contributions : **Contenu → Catégories** → ouvrir **Vie familiale** → onglet **Permissions** → groupe **Associés** → réglages d'*Articles* : *Créer* = **Autorisé** et *Modifier les siens* = **Autorisé** ; *Modifier l'état* laissé sur *Hérité* (pas d'auto-publication). Les sous-catégories (Photos, Souvenirs, Événements) héritent de ce réglage : un associé peut créer et reprendre ses brouillons dans Vie familiale — et rien d'autre.

### Workflow de publication (modération par le CA)

1. **Contenu → Articles → Options → onglet Intégration** : *Activer les workflows* = **Oui** (si rien ne réagit, vérifier dans **Système → Plugins** que les plugins du groupe *Workflow* sont activés).
2. **Contenu → Workflows** : le workflow par défaut enchaîne déjà « brouillon → relecture → publié » (éditeur visuel de workflow depuis Joomla 6.1). Ouvrir la transition qui mène à l'état **Publié** et ne laisser en groupes autorisés que **Conseil d'administration** + Super Users ; les transitions « enregistrer en brouillon » et « soumettre en relecture » restent accessibles aux Associés.

### Test (avec les comptes du §8)

1. Connecté comme **Camille Associé** (fenêtre privée) : créer un article dans Vie familiale → il s'enregistre **non publié**, sans bouton de publication ;
2. Connecté comme **Claude CA** : retrouver l'article → transition → **Publié** → il apparaît sur le site.

*Note M01* : l'édition directement depuis le site public (sans passer par l'administration) sera évaluée plus tard (M02+) ; M01 valide d'abord le mécanisme de modération.

## 11. Sauvegardes

La base ne vit pas dans git : après chaque séance de configuration lourde, exporter la base (espace client OVH → Bases de données → phpMyAdmin → Exporter, format SQL, gzip) et conserver l'export localement. Rythme visé : une sauvegarde par séance de travail au début, puis mensuelle.

## Hors périmètre M01 (noté, rien à faire)

Recherche du site, questionnaire template, look « vendeur » (**M02**) ; plugins planning, paiement, vote électronique (phase 2) ; expiration automatique des invités (extension future) ; SMTP à régler si vous me confirmez une adresse mail OVH (Guide 1, étape 5).