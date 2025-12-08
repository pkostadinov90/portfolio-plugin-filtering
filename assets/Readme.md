# Assets (Webpack)

This folder contains the frontend source assets and a minimal Webpack setup used to compile SCSS into CSS for this project.

---

## Install & run

These commands assume your package.json is in the project root and references the config in assets/.

1) Install dependencies:

```bash
npm install
```

2) Development (watch):

```bash
npm run dev:styles
```

3) Production build:

```bash
npm run build:styles
```

---

## Install dependencies (Node + npm)

This setup is intentionally simple and uses raw npm.

### Required Node version

Use:
- Node 22.x (recommended)

### Install Node via nvm

1) Make sure nvm is installed.
2) From the project root:

```bash
nvm install
nvm use
```

This will read .nvmrc and install/use the correct Node version.

### Then install npm packages

```bash
npm install
```

---

## Folder structure

```text
assets/
  styles/                # SCSS sources
  scripts/               # (Reserved for future JS sources)
  dist/
    styles/              # Compiled CSS output
    scripts/             # Webpack output JS stubs (required by webpack)
  webpack.config.js      # Single-file webpack config
```

---

## What this setup does

- Scans all .scss files inside assets/styles/ (including subfolders).
- Builds each file into a matching CSS file under:
  - assets/dist/styles/...
- Also emits minimal JS files under:
  - assets/dist/scripts/...
  (Webpack requires an output target; these are not used unless you choose to.)

---

## Naming / output mapping

Example inputs:
- assets/styles/main.scss
- assets/styles/admin/editor.scss

Outputs:
- assets/dist/styles/main.css
- assets/dist/styles/admin/editor.css

---

## Notes

- This config is SCSS-focused by design.
- If you later add JS bundling, extend entry and module rules.
- If you want to avoid emitting JS stubs entirely, the config can be adjusted.
