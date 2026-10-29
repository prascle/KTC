# KTC — Site web de la SCI familiale

Site Joomla 6 de la SCI KTC (maison de vacances familiale, ~60 associés).
Déployé par git sur l'hébergement OVH (procédure « Associer Git » de l'espace client).

## Cycle de travail
- une branche par jalon (`feature/mXX-…`), pull request vers `main`, fusion par Paul
- déploiement : automatique (webhook OVH) à chaque fusion sur `main` — le dépôt est la source de vérité
- le répertoire du site ne se modifie jamais en dehors de git

## Règles d'or
- `configuration.php` (identifiants BDD) et les caches sont hors dépôt (dépôt public)
- la base de données (contenus, menus, utilisateurs) vit côté OVH : sauvegardes régulières
- aucune donnée personnelle dans ce dépôt
- mises à jour de Joomla : uniquement par pull request ; mise à jour automatique Joomla désactivée ; jamais d'écriture serveur (bouton de mise à jour, FTP)

## Documentation
- `docs/guides/` — guides pas-à-pas (installation OVH, configuration du site)
- `docs/scripts/` — scripts de maintenance (amorçage du dépôt)
