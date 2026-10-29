# KTC — Site web de la SCI familiale

Site Joomla 6 de la SCI KTC (maison de vacances familiale, ~60 associés).
Déployé par git sur l'hébergement OVH (procédure « Associer Git » de l'espace client).

## Cycle de travail
- une branche par jalon (`feature/mXX-…`), pull request vers `main`, fusion par Paul
- déploiement : espace client OVH → Mes sites → ⁝ → Déployer Git (toujours cocher « forcer »)
- le répertoire du site ne se modifie jamais en dehors de git

## Règles d'or
- `configuration.php` (identifiants BDD) et les caches sont hors dépôt (dépôt public)
- la base de données (contenus, menus, utilisateurs) vit côté OVH : sauvegardes régulières
- aucune donnée personnelle dans ce dépôt

## Documentation
- `docs/guides/` — guides pas-à-pas (arrive avec la première pull request de M01)
