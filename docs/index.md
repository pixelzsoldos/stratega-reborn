# Stratega Reborn – Technikai Dokumentáció

## Bevezetés
**Stratega Reborn** a klasszikus [stratega.hu](https://stratega.hu) böngészős stratégiai játék modernizált változata. A cél:
- Modern frontend (React + Next.js)
- Robusztus backend (NestJS + Node.js)
- Köralapú logika (Turn-Based, ~20 perc/kör)
- Teljesen moduláris, skálázható rendszer

---

## Főbb komponensek

### Frontend (Client)
- **Technológiák:** React, Next.js, TypeScript, TailwindCSS
- **Fő modulok:**
  - Oldalak: Belépés, Regisztráció, Legenda, Játékismertető, Szabályok
  - UI komponensek: táblázatok egységekhez, épületekhez, varázslatokhoz
  - Valós idejű státusz kijelzés: GyP, mana, varázslatok
  - Automatizmus interfészek: Intéző, amulettek
- **Routing:** Next.js alapú, dinamikus route-ok a játék állapotához
- **Adatkommunikáció:** REST API + WebSocket a valós idejű frissítéshez
- **Állapotkezelés:** React Context / Redux opcionálisan

### Backend (Server)
- **Technológiák:** Node.js, NestJS, TypeScript
- **Fő szolgáltatások:**
  - Felhasználókezelés: regisztráció, hitelesítés, jogosultság
  - Ország logika: gazdaság, épületek, falvak, barakkok, erődök
  - Egység és varázslat kezelés: kiképzés, varázslatok aktiválása, fenntartás
  - Körvezérlés: köridő, epizódok, véletlen események
  - Győzelmi pontok (GyP) számítása, ranglisták
  - Automatizmusok: Intéző logika, piaci tranzakciók
  - Adatbázis-kezelés: PostgreSQL, Prisma ORM javasolt
- **API szerkezet:**
  - `/api/user` – felhasználói műveletek
  - `/api/country` – ország adatok, állapot
  - `/api/units` – egységek kezelése
  - `/api/magic` – varázslatok, amulettek
  - `/api/combat` – csatatörvények, támadások
  - `/api/market` – piactér logika

### Dokumentáció (Docs)
- Markdown alapú dokumentáció:
  - `rules.md` – játékszabályok
  - `help.md` – súgó, játékos segédletek
  - `faq.md` – gyakran ismételt kérdések
  - `story.md` – háttértörténet, legenda
  - `races.md` – fajok és egységek leírása
- Cél: fejlesztők és adminisztrátorok számára áttekintés

---

## Játékmechanika (Fejlesztői szempont)
- **Prioritások:** épületek, egységek, varázslatok hierarchikus fenntartása  
- **Egységek & Fajok:** minden fajhoz és egységhez GyP, T, V, ÉP, K mutatók; egyedi képességek implementálva
- **Varázslatok:** státuszos hatások (pl. +1 T/V, regeneráció, termelés növelése)
- **Védelem & Szabadság:** ideiglenes státuszok, befolyásolják a csata logikát
- **Győzelmi pontok (GyP):** csataeredmény alapján növekvő rang, szintlépés → új egységek/varázslatok

---

## Gazdaság & Épületek
- **Raktár:** alapértelmezett tárolási kapacitás (gabona/fa/kő/fém)
- **Barakk & Erőd:** határozza meg a kiképzhető egységek számát és védelmi értékét
- **Termelő épületek:** Ércbánya, Fatelep, Kőbánya, Tanya, Falu, Manaforrás, Varázslótorony
- **Fenntartási logika:** erőforrások hiányában leállhat a varázslatok, egységek fenntartása

---

## Amulettek & Varázstárgyak
- Fajfüggő és közös amulettek
- Licit és gyártás logika
- Aktív amulett: hősre helyezhető, maximális szám korlátozott
- Varázstárgy: összesen 10 db, állandó hatás az országra, elvesztés esetén hatás megszűnik

---

## Sereg & Hadműveletek
- Sereg képzése a barakkok és erődök kapacitásához kötve
- Támogató seregek logikája a rangsorolt országlista alapján
- Csata logika: támadó- és védőérték, kezdeményezés, GyP-alapú súlyozás

---

## Automatizmusok (Intéző)
- Piaci tranzakciók figyelése és automatikus vásárlás
- Egység és tekercs képzés/gyártás automatikus végrehajtása
- Beállítások: minimális darabszám, körre elegendő nyersanyag, egyszerre max. képzés/gyártás

---

## Fájlstruktúra

```

/docs
├── help.md
├── rules.md
├── faq.md
├── story.md
└── races.md
/client
├── pages/            # Next.js oldal komponensek
├── components/       # UI modulok (táblázatok, gombok, jelzők)
├── hooks/            # Egyedi React hookok
├── context/          # Állapotkezelés (Context API / Redux)
└── styles/           # Tailwind/SCSS
/server
├── src/
├── modules/      # Funkcionális modulok: user, country, units, magic, combat, market
├── services/     # Logika implementációk, szabályok
├── controllers/  # REST API végpontok
├── gateways/     # WebSocket kommunikáció
└── main.ts       # Belépési pont, NestJS bootstrap
└── prisma/           # ORM sémák és migrációk

```

---

## Fejlesztési irányelvek
- Moduláris kód: fajok, egységek, varázslatok könnyen bővíthetők
- Tipizált TypeScript mindenhol
- Körvezérlés és állapotváltozás determinisztikus tesztelése
- Frontend-backend interfészek explicit DTO-k használatával
- Valós idejű frissítés WebSocket segítségével
- Részletes dokumentáció a `/docs` mappában minden játékelemhez

