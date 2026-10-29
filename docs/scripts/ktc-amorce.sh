#!/usr/bin/env bash
#
# KTC — Amorce du dépôt prascle/KTC (jalon M01)
# À exécuter UNE SEULE FOIS, sur votre machine Ubuntu.
#
# Prérequis :
#   1. Dépôt GitHub créé et VIDE sur https://github.com/new
#      - Nom : KTC (en majuscules)   - Visibilité : Public
#      - NE PAS cocher « Add a README file » ni .gitignore ni licence
#      (s'il existe déjà avec un README, le script le gère aussi)
#   2. Logiciels nécessaires :  sudo apt install git curl unzip
#   3. Pousser sur GitHub doit fonctionner depuis cette machine :
#      - soit HTTPS avec un PAT / gestionnaire d'identifiants,
#      - soit SSH ; dans ce cas remplacer REPO_URL ci-dessous par
#        git@github.com:prascle/KTC.git
#
set -euo pipefail

REPO_URL="https://github.com/prascle/KTC.git"
WORKDIR="ktc"
FALLBACK_VERSION="6.2.0"   # dernière stable vérifiée le 28/10/2026 (si l'API ne répond pas)

say() { printf '\n\033[1;34m=== %s\033[0m\n' "$*"; }
die() { printf '\033[1;31mERREUR : %s\033[0m\n' "$*" >&2; exit 1; }

# --- 0. Vérifications -------------------------------------------------------
for cmd in git curl unzip; do
  command -v "$cmd" >/dev/null 2>&1 || die "« $cmd » est absent — installez-le : sudo apt install $cmd"
done
git config user.name  >/dev/null 2>&1 || die "git non configuré — lancez : git config --global user.name \"Votre Nom\" et git config --global user.email \"vous@exemple.fr\""

# --- 1. Dépôt ---------------------------------------------------------------
say "1/6 Récupération du dépôt $REPO_URL"
[ -d "$WORKDIR" ] && die "le dossier ./$WORKDIR existe déjà — supprimez-le ou lancez le script ailleurs."
if git clone "$REPO_URL" "$WORKDIR" 2>/dev/null; then
  echo "Dépôt cloné."
else
  echo "Clone impossible (dépôt vide ?) — initialisation locale."
  mkdir "$WORKDIR"
fi
cd "$WORKDIR"
if ! git rev-parse --git-dir >/dev/null 2>&1; then
  git init -b main
  git remote add origin "$REPO_URL"
fi
git rev-parse --verify HEAD >/dev/null 2>&1 || git symbolic-ref HEAD refs/heads/main

# --- 2. Dernière version stable de Joomla 6 ---------------------------------
say "2/6 Recherche de la dernière version stable de Joomla 6"
VERSION="$FALLBACK_VERSION"
if API=$(curl -fsSL --max-time 20 "https://downloads.joomla.org/api/v1/latest/cms"); then
  V=$(printf '%s' "$API" | sed 's/},{/}\n{/g' | grep '"branch":"Joomla! 6"' | sed 's/.*"version":"\([0-9.]*\)".*/\1/')
  [ -n "$V" ] && VERSION="$V"
fi
echo "Version retenue : Joomla $VERSION"

# --- 3. Téléchargement ------------------------------------------------------
VERURL=$(printf '%s' "$VERSION" | tr '.' '-')            # 6.2.0 -> 6-2-0
ZIP="Joomla_${VERSION}-Stable-Full_Package.zip"
URL="https://downloads.joomla.org/cms/joomla6/${VERURL}/${ZIP}"
say "3/6 Téléchargement : $URL"
curl -fSL --retry 3 -o "$ZIP" "$URL"
[ "$(stat -c%s "$ZIP")" -gt 20000000 ] || die "téléchargement incomplet — relancez le script."
unzip -tq "$ZIP" >/dev/null 2>&1 || die "archive corrompue — relancez le script."

# --- 4. Extraction ----------------------------------------------------------
say "4/6 Extraction de Joomla"
unzip -q "$ZIP"
rm -f "$ZIP"
[ -f index.php ] || die "extraction incomplète : index.php manquant."

# --- 5. Fichiers de gestion (.gitignore, .htaccess, README) ------------------
say "5/6 .gitignore, .htaccess et README"
[ -f htaccess.txt ] && cp -n htaccess.txt .htaccess

cat > .gitignore <<'EOF'
# KTC — secrets et fichiers générés (jamais dans le dépôt)
/configuration.php
/administrator/cache/
/cache/
/logs/
/tmp/
EOF

if [ ! -f README.md ]; then
cat > README.md <<'EOF'
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
EOF
fi

# --- 6. Commit initial et push ----------------------------------------------
say "6/6 Commit initial et push"
git add -A
git commit -m "Amorce M01 : Joomla ${VERSION}-Stable (package complet)

- Package complet Joomla ${VERSION} téléchargé depuis downloads.joomla.org
- .gitignore : configuration.php, caches, tmp, logs hors dépôt
- .htaccess (copie de htaccess.txt)
- README : cycle de travail et règles d'or

Jalon M01 du site KTC."
git push -u origin main || die "push refusé : authentification GitHub à configurer (PAT en HTTPS, ou SSH — voir commentaires en tête de script)."

printf '\n'
say "Amorce terminée :)"
echo "Le dépôt contient Joomla $VERSION sur la branche main."
echo "Prochaine étape : Guide 1 — associer Git dans l'espace client OVH."
