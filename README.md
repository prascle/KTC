# KTC — Site web de la SCI familiale

Site Joomla 6 de la SCI KTC (maison de vacances familiale, ~60 associés).
Déployé par git sur l'hébergement OVH (procédure « Associer Git » de l'espace client).

## État — jalon M01 (terminé le 02/11/2026)

Site d'information opérationnel, testé par Paul sur ordinateur et smartphone.

Ce qui est en place :

- Joomla 6.2.0 sur OVH (MySQL 8.4, HTTPS forcé), paquet de langue fr-FR 6.2.0.1
- ACL : 3 groupes KTC (Invités KTC → Associés → Conseil d'administration) ; les groupes natifs Author/Editor/Publisher ne sont jamais attribués ; enregistrement public désactivé
- Catégories : La maison (invités) ; SCI, Assemblées, Travaux, Vie familiale (associés) ; champs personnalisés « Fiche chantier » (statut, dates, budget, avancement)
- Contributions des associés : rédaction type Auteur dans les catégories autorisées, insertion de photos et PDF via « Contenu CMS → Médias » (dossier `images/contributions`), publication réservée au CA (workflow de modération), versions d'articles activées pour l'audit (décision D17)
- Déploiement automatique par webhook OVH à chaque fusion sur `main`
- Guides pas-à-pas (1 : installation OVH ; 2 : configuration §1–11) et scripts (`ktc-amorce.sh`, `ktc-langue-fr.sh`) versionnés dans `docs/`

Leçons des incidents de déploiement :

- le « forcer » OVH n'écrase pas les fichiers hors git : pour déployer des fichiers installés côté admin, les supprimer d'abord sur le serveur puis redéployer (incident paquet fr-FR)
- le clone OVH n'avance que par fast-forward : un push forcé sur une branche déployée casse le déploiement (incident 02/11, réparé par le commit de raccordement `3f432a0d`)

Prochain jalon : M02 — template KTC (child template de Cassiopeia), puis phase 2 (planning, équité, paiement simulé).

## État — jalon M02 (terminé le 06/11/2026)

Template KTC co-conçu avec Paul, testé sur ordinateur et smartphone.

Ce qui est en place :

- child template `ktc` de Cassiopeia (Joomla 6.2.0), sans dépendance tierce ; palette agapanthe / granit rose, logo G1 (3 variantes SVG dans `images/logo/`), mode sombre complet avec sa variante de logo
- en-tête : logo, barre de navigation sticky ; bouton burger sous 992 px ouvrant un panneau mobile avec icônes
- accueil : carrousel, sommaire, cartes succinctes (texte tronqué à 3 lignes) ; articles et fiches chantiers avec badge de statut et budget en tête
- barre de navigation basse BB sur smartphone (4 raccourcis + Menu, page courante surlignée, contenu jamais masqué) ; absente sur grands écrans
- panneau Confort : mode d'affichage (Auto / Clair / Sombre) et taille du texte, ouverts par l'icône ⚙️ de l'en-tête ou l'entrée « Réglages » du burger ; préférences persistées, sans flash blanc au rechargement

Leçons des incidents du jalon :

- une IIFE se termine par `})();` : une fonction déclarée mais jamais invoquée ne produit **aucune erreur console** (panneau Confort muet des PR #16 à #18)
- quand les symptômes contredisent le code, examiner la **page réellement servie** (curl), pas le fichier poussé
- la vérification octet par octet valide la conformité à l'intention, pas l'intention elle-même

Prochain jalon : M03 — planning administratif, puis phase 2 (planning associés, équité, paiement simulé).

## Cycle de travail
- une branche par jalon (`feature/mXX-…`), pull request vers `main`, fusion par Paul
- déploiement : automatique (webhook OVH) à chaque fusion sur `main` — le dépôt est la source de vérité
- le répertoire du site ne se modifie jamais en dehors de git
- vérifier l'état des branches (head de `main`, PR toujours ouverte) avant chaque push

## Règles d'or
- `configuration.php` (identifiants BDD) et les caches sont hors dépôt (dépôt public)
- la base de données (contenus, menus, utilisateurs) vit côté OVH : sauvegardes régulières
- aucune donnée personnelle dans ce dépôt
- mises à jour de Joomla : uniquement par pull request ; mise à jour automatique Joomla désactivée ; jamais d'écriture serveur (bouton de mise à jour, FTP)
- jamais de push forcé sur `main` ni sur une branche déjà fusionnée : le déploiement OVH n'avance que par fast-forward

## Documentation
- `docs/guides/` — guides pas-à-pas (installation OVH, configuration du site)
- `docs/scripts/` — scripts de maintenance (amorçage du dépôt)
