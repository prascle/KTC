# KTC — Guide 1 : OVH, déploiement git et installation Joomla

*À suivre dans l'ordre, une seule fois. En cas d'écart avec un écran, me le signaler avec une capture plutôt que d'improviser.*

## Prérequis (à cocher avant de commencer)

- [ ] Base MySQL créée dans l'espace client OVH : **Hébergements → Bases de données → Créer** — une base + un utilisateur (même datacenter que l'hébergement), mot de passe noté **hors du dépôt**. Vérifier la version proposée (Joomla 6 exige MySQL ≥ 8.0.13 ou MariaDB ≥ 10.4).
- [ ] PHP de l'hébergement vérifié : **8.3 ou plus**.
- [ ] Dépôt GitHub `prascle/KTC` créé (vide, public) et amorcé avec le script `ktc-amorce.sh` (script conservé dans `docs/scripts/ktc-amorce.sh`).
- [ ] Le répertoire du sous-domaine de test sur OVH est **vide**.

## Étape 1 — Associer Git dans l'espace client OVH

1. Espace client → **Web Cloud → Hébergements** → sélectionner l'hébergement.
2. Onglet **« Mes sites »**.
3. Bouton **⁝** à droite de la ligne du sous-domaine de test → **« Associer Git »**.
4. Dans le formulaire :
   - **Dépôt** : `https://github.com/prascle/KTC.git` (dépôt public → pas de clé SSH demandée) ;
   - **Branche** : `main` ;
   - **Webhook** : laisser désactivé (nous déployons volontairement, à la main).
5. **Appliquer la configuration**. Le statut passe à « Activé » dans la colonne Git après quelques minutes.

## Étape 2 — Premier déploiement

1. Onglet **Mes sites** → bouton **⁝** sur la ligne du sous-domaine → **« Déployer Git »**.
2. **Cocher la case « forcer les modifications distantes »** — réflexe systématique : le dépôt GitHub est toujours la seule source de vérité, jamais le serveur.
3. Confirmer. Après déploiement, ouvrir le sous-domaine dans le navigateur : l'assistant d'installation Joomla doit s'afficher (drapeaux + choix de langue).

## Étape 3 — Assistant d'installation Joomla

| Page | Que saisir |
|---|---|
| Langue | Français |
| Base de données | Type : **MySQLi (PDO)** · Hôte : le **serveur** affiché dans l'espace client OVH à côté de votre base (forme `xxxx.mysql.db`) · Utilisateur : le nom d'utilisateur OVH · Mot de passe : celui de la base · Base de données : le nom de la base · Préfixe des tables : garder celui proposé |
| Configuration du site | Nom du site : **KTC** · Description : une phrase (ex. « La maison de famille des associés de la SCI KTC ») · Email de l'administrateur : votre adresse |
| Super administrateur | Identifiant **non devinable** (pas « admin ») + mot de passe fort (à conserver précieusement) |
| Données d'exemple | **Aucune** (si la question est posée) — les contenus réels et de démonstration arriveront via la configuration (Guide 2) |

Puis **Terminer l'installation**.

## Étape 4 — Supprimer le dossier « installation »

L'écran final propose **« Supprimer le dossier installation »** (nettoyage) : **cliquer ce bouton**. Joomla refuse de servir le site tant que ce dossier existe.

⚠️ **Important — puis me le signaler dans la conversation** : ce dossier étant suivi par git, le serveur est momentanément « en avance » sur le dépôt. Je pousserai un commit de suppression sur une branche (PR pour vous), et vous redéployerez avec « forcer » : dépôt et serveur resynchronisés. **Ne déployez pas de nouveau avant cela.**

## Étape 5 — HTTPS et vérifications post-installation

1. **Certificat SSL** : espace client → Hébergements → **Multisites** → ligne du sous-domaine → certificat SSL **Let's Encrypt** (gratuit) → une fois émis, activer **Forcer HTTPS**.
2. Dans l'administration Joomla : **Système → Configuration globale → Serveur** : *Forcer HTTPS = Tout le site*.
3. **Système → Informations système → Information PHP** : vérifier `memory_limit` ≥ 256M. Si inférieur, me le signaler (réglage `.ovhconfig` ou version PHP).
4. **Mise à jour automatique de Joomla : vérifier qu'elle est DÉSACTIVÉE** — Système → Mise à jour → Joomla → Options (paramètres) → « Mises à jour automatiques » : désactivées. Elle est active par défaut sur les nouvelles installations Joomla 6 et écrirait des fichiers directement sur le serveur, hors du dépôt git (incompatible avec notre déploiement). Toutes les mises à jour passent par le dépôt : Vibe fournit chaque version en pull request (D16). Ne jamais utiliser le bouton « Installer la mise à jour » ni un transfert FTP (FileZilla) : le serveur ne se modifie jamais hors git.
5. Activer la **double authentification** du super administrateur : Utilisateurs → votre profil → authentification à deux facteurs.
6. **Courriels** : Joomla a besoin d'un SMTP fonctionnel (réinitialisations de mot de passe…). Si vous avez une adresse mail OVH, me le dire : je vous donnerai le réglage SMTP exact (sinon, remettre ce point à plus tard).

## En cas d'erreur d'association ou de déploiement

La colonne Git affiche « Erreur » → bouton **⁝ → Informations du dernier déploiement**. Causes typiques :

| Message | Cause | Remède |
|---|---|---|
| Répertoire d'installation non vide | des fichiers existent sur le sous-domaine | les lister (FTP) et me signaler avant toute suppression |
| Adresse du dépôt erronée | faute de frappe dans l'URL | refaire l'association avec la bonne URL |
| Branche inexistante | la branche `main` n'existe pas | vérifier que le script d'amorçage a bien été poussé |

Après correction : ⁝ → **Associer Git** à nouveau.

## Suivi

Une fois l'installation terminée et le point 4 exécuté, passez-moi le relais dans la conversation (install faite) : je pousse la suppression du dossier `installation/` + les guides dans `docs/guides/`, vous fusionnez et redéployez. Ensuite seulement, ouvrez le **Guide 2 — configuration du site**.