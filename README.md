# Stratega Reborn Next.js + TypeScript Projekt

Stratega Reborn - Középkori stratégiai böngészős játék újjászületése Next.js és TypeScript stackkel.

## Tech Stack

- **Next.js 14** - React framework App Router-rel
- **React 18** - UI library
- **TypeScript 5** - Type safety
- **CSS-in-JS** - Inline styles + styled-jsx

## Telepítés

A projekt már fel van telepítve! Most már csak a függőségeket kell telepíteni:

```bash
npm install
```

## Fejlesztés

Indítsd el a fejlesztői szervert:

```bash
npm run dev
```

Nyisd meg a böngészőben: [http://localhost:3000](http://localhost:3000)

## Scriptek

```bash
npm run dev          # Fejlesztői szerver indítása
npm run build        # Production build
npm start            # Production szerver
npm run lint         # ESLint futtatása
npm run type-check   # TypeScript típusellenőrzés
```

## Többnyelvűség hozzáadása

1. Nyisd meg: `src/locales/translations.ts`
2. Az új nyelv már be van építve (angol), csak engedélyezd a nyelvválasztót
3. Engedélyezd a nyelvválasztót a `src/components/Homepage.tsx`-ben
   - Keresd meg a sort: `display: 'none'`
   - Töröld ki vagy változtasd `display: 'flex'`-re

## Projektstruktúra

```
stratega-reborn/
├── public/              # Statikus fájlok
├── src/
│   ├── app/            # Next.js App Router
│   │   ├── layout.tsx  # Root layout
│   │   ├── page.tsx    # Főoldal
│   │   └── globals.css # Globális stílusok
│   ├── components/     # React komponensek
│   │   └── Homepage.tsx # Főoldal komponens
│   ├── locales/        # Fordítások
│   │   └── translations.ts
│   └── types/          # TypeScript típusok
│       └── translations.ts
├── package.json        # NPM konfiguráció
├── tsconfig.json       # TypeScript konfiguráció
├── next.config.js      # Next.js konfiguráció
└── README.md          # Ez a fájl
```

## TypeScript

A projekt teljes mértékben típusos:
- Minden komponens `.tsx` fájl
- Típusdefiníciók a `src/types/` mappában
- Strict mode enabled
- Futtatás előtt type-check: `npm run type-check`

## Jellemzők

- ✅ Teljesen responsív design
- ✅ Mobil-optimalizált (touch-friendly)
- ✅ Sötét téma (#111 háttér)
- ✅ Eredeti Stratega Reborn stílus (#366, #5f9ea0 színek)
- ✅ Többnyelvűség támogatás (HU/EN)
- ✅ Modern Next.js 14 App Router
- ✅ **TypeScript - Type Safety**
- ✅ ESLint + Type checking

## Fejlesztés

A projekt a következő színsémát használja:
- Háttér: `#111` (nagyon sötét szürke)
- Linkek: `#5f9ea0` (cadet blue)
- Hover: `#696969` (dim gray)
- Kiemelések: `#366` (sötét kék-zöld)
- Fény szöveg: `#dcdcdc` (gainsboro)

## Licenc

Copyright © Stratega Reborn Team - 2025. Minden jog fenntartva.
