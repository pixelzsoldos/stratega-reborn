n# Stratega Reborn – Next.js + TypeScript alkalmazás

Középkori, böngészőben futó stratégiai játék modern, Next.js 14 + TypeScript stackre épített újragondolása.

## Fő jellemzők

- Next.js 14 App Router (src/app)
- React 18 komponensek
- Teljes TypeScript tipizálás
- Egyszerű, inline stílusok + styled-jsx (CSS-in-JS)
- Többnyelvű felület (HU/EN) – egyszerű bővíthetőség
- Téma-kezelés (ThemeProvider, több sötét paletta)
- Reszponzív, mobilbarát UI

## Követelmények

- Node.js >= 18.17
- npm

## Telepítés

1. Függőségek telepítése:
   ```bash
   npm install
   ```

## Fejlesztés

- Fejlesztői szerver indítása:
  ```bash
  npm run dev
  ```
  Megnyitás: http://localhost:3000

- Lintelés és típusellenőrzés:
  ```bash
  npm run lint
  npm run type-check
  ```

## Build és futtatás (production)

```bash
npm run build
npm start
```

## NPM scriptek

```bash
npm run dev          # Fejlesztői szerver
npm run build        # Production build
npm start            # Production szerver
npm run lint         # ESLint
npm run type-check   # TypeScript típusellenőrzés
```

## Projektstruktúra

```
stratega-reborn/
├─ public/                  # Statikus fájlok
├─ src/
│  ├─ app/                  # Next.js App Router oldalak
│  │  ├─ game/page.tsx      # (Előkészített oldal)
│  │  ├─ login/page.tsx     # Belépés oldal
│  │  ├─ register/page.tsx  # Regisztráció oldal
│  │  ├─ osszesites/page.tsx# Összesítés oldal
│  │  ├─ layout.tsx         # Gyökér layout + ThemeProvider
│  │  ├─ page.tsx           # Kezdőlap (Homepage)
│  │  └─ globals.css        # Globális stílusok
│  ├─ components/           # UI komponensek
│  │  ├─ theme/ThemeProvider.tsx  # Témakezelés (paletták)
│  │  └─ ui/                # UI elemek (Logo, LinkItem, stb.)
│  │     ├─ ContentGrid.tsx
│  │     ├─ Footer.tsx
│  │     ├─ LanguageSwitcher.tsx
│  │     ├─ LinkItem.tsx
│  │     ├─ Logo.tsx
│  │     └─ SectionCard.tsx
│  ├─ locales/translations.ts     # Fordítások (HU/EN)
│  └─ types/translations.ts       # Fordítások típusai
├─ package.json
├─ tsconfig.json
├─ next.config.js
└─ README.md
```

## Többnyelvűség (i18n)

- A fordítások a `src/locales/translations.ts` fájlban találhatók.
- A `Homepage` komponens a `LanguageSwitcher`-t használja, amely alapból engedélyezve van – nincs szükség külön megjelenítésre vagy CSS-törlésre.
- Új nyelv hozzáadásához:
  1. Add hozzá a nyelvi kulcsot a `translations` objektumba a megfelelí tipizálással.
  2. Biztosítsd, hogy a `Language` típus (src/types/translations.ts) is tartalmazza az új nyelvi kódot.

## Téma és stílus

- A téma a `src/components/theme/ThemeProvider.tsx` fájlban van definiálva.
- Elérhető paletták például: `darkSea`, `darkGray`.
- A `useTheme()` hookon keresztül érhető el a `palette` és a `setPaletteName`.
- Színek (darkSea példa):
  - background: `#111`
  - text: `#a9a9a9`
  - link: `#5f9ea0`
  - hover: `#696969`
  - accent: `#366`
  - lightText: `#dcdcdc`

## Megjegyzések az autentikációról

A projekt függőségei között megtalálható a `bcryptjs` és a `jsonwebtoken`, amelyek a későbbi (backend/API-routes alapú) autentikációhoz lesznek hasznosak. A jelenlegi állapotban ezekhez nincs még teljes funkcionalitás kiépítve az App Router alatt.

## Kódstílus és minőség

- TypeScript Strict mód
- ESLint (Next.js konfiguráció)
- Kézi és komponens-szintű styled-jsx + inline stílusok a gyors prototipizáláshoz

## Licenc

A projekt jogi státusza nem került megadásra külön licencfájlban. Amíg nincs másképp jelezve, minden jog fenntartva (© Stratega Reborn Team, 2025).
