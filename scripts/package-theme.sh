#!/bin/bash
# Genera dist/remake_business_advisors.zip listo para subir (Apariencia > Temas > Añadir > Subir, o por FTP).
# Incluye solo archivos de producción: excluye todo lo listado en .distignore (node_modules, src, package.json, etc.).
set -euo pipefail

THEME_DIR="$(cd "$(dirname "$0")/.." && pwd)"
THEME_SLUG="$(basename "$THEME_DIR")"
OUT_DIR="$THEME_DIR/dist"
ZIP="$OUT_DIR/$THEME_SLUG.zip"

mkdir -p "$OUT_DIR"
rm -f "$ZIP"

EXCLUDES=()
while IFS= read -r line; do
  [[ -z "$line" || "$line" == \#* ]] && continue
  EXCLUDES+=("-x" "$THEME_SLUG/$line" "-x" "$THEME_SLUG/$line/*")
done < "$THEME_DIR/.distignore"

cd "$THEME_DIR/.."
zip -rq "$ZIP" "$THEME_SLUG" "${EXCLUDES[@]}"

echo "Paquete: $ZIP ($(du -h "$ZIP" | cut -f1))"
unzip -l "$ZIP" | awk 'NR>3 {print $4}' | grep -E '(^|/)(node_modules|src|package(-lock)?\.json|\.git)(/|$)' && { echo "ERROR: el paquete contiene archivos de desarrollo"; exit 1; } || echo "Verificado: sin node_modules, src ni package.json."
