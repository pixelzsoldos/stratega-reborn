# 🏰 Stratega Reborn

A klasszikus **[stratega.hu](https://stratega.hu)** böngészős stratégiai játék modernizált változata. Egy fantasy világban játszódó köralapú (turn-based) építkezős-fejlesztős háborús stratégiai játék, ahol az elfek, törpék, emberek, élőholtak, sötételfek és orkok fajai között választhatsz, és saját birodalmat építhetsz fel.

## 🌟 Főbb jellemzők

- **Köralapú játékmenet**: ~20 perc/kör, 4 hónapos fordulók
- **6 játszható faj**: Elf, Törpe, Ember, Élőholt, Sötételf, Ork
- **Komplex gazdaság**: Épületek, nyersanyagok, arany, mana
- **Hadviselés**: Egységek kiképzése, csaták, területfoglalás
- **Mágia**: Varázslatok, tekercsek, amulettek, varázstárgyak
- **Klánrendszer**: Szövetségek, közös erőforrások
- **Győzelmi pontok (GyP)**: Szintlépés, új egységek és varázslatok feloldása
- **Modern technológiák**: React, Next.js, TypeScript, NestJS

## 🏗️ Projekt struktúra

```
stratega-reborn/
├── client/              # Frontend (Next.js + React + TypeScript)
│   ├── app/            # Next.js app router oldalak
│   ├── components/     # Újrafelhasználható UI komponensek
│   ├── locales/        # Többnyelvűség (hu, en)
│   └── types/          # TypeScript típusdefiníciók
├── server/             # Backend (NestJS + Node.js) - tervezett
├── docs/               # Dokumentáció
│   ├── help.md        # Játékos súgó
│   ├── rules.md       # Játékszabályok
│   ├── faq.md         # Gyakran ismételt kérdések
│   ├── story.md       # Háttértörténet
│   └── race/          # Fajok részletes leírása
└── README.md
```

## 🚀 Gyors kezdés

### Követelmények

- Node.js 18+ 
- npm vagy yarn

### Telepítés és indítás

```bash
# Klónozás
git clone https://github.com/yourusername/stratega-reborn.git
cd stratega-reborn

# Függőségek telepítése
cd client
npm install

# Fejlesztői szerver indítása
npm run dev
```

A játék elérhető lesz: `http://localhost:3000`

## 🎮 Játékmenet áttekintés

### Kezdés

1. **Regisztráció**: Válassz fajt, országnevet és alapadatokat
2. **Védett kezdés**: Az első 100 kör védelemmel indul
3. **Gazdaság kiépítése**: Építs épületeket, termelj nyersanyagokat
4. **Seregépítés**: Képezz egységeket a barakkokban
5. **Fejlődés**: Szerezz GyP-t csatákban, lépj szintet

### Főbb játékelemek

**Épületek:**
- Raktár, Kincstár, Könyvtár
- Falu, Tanya (népesség, étel)
- Bányák (fa, kő, fém)
- Manaforrás, Varázslótorony
- Barakk, Erőd (katonák)

**Erőforrások:**
- Arany, Gabona, Fa, Kő, Fém, Mana

**Csaták:**
- Támadás földért, aranyért, nyersanyagért
- Rablás, lopás
- Támogató seregek
- GyP szerzése

**Mágia:**
- Gazdasági varázslatok
- Harci varázslatok
- Varázsgömb (kémkedés)
- Amulettek és varázstárgyak

## 🛠️ Fejlesztés

### Frontend technológiák

- **Framework**: Next.js 14 (App Router)
- **UI**: React 18 + TypeScript
- **Styling**: CSS-in-JS (inline styles), Theme Provider
- **Többnyelvűség**: react-i18next
- **Állapotkezelés**: React Context API

### Tervezett backend

- **Framework**: NestJS
- **Database**: PostgreSQL + Prisma ORM
- **API**: RESTful + WebSocket (valós idejű frissítések)
- **Autentikáció**: JWT

### Kulcsfontosságú komponensek

```typescript
// Témarendszer
<ThemeProvider>
  // Világos/sötét témák, paletta kezelés
</ThemeProvider>

// Többnyelvűség
translations = {
  hu: { /* magyar szövegek */ },
  en: { /* english texts */ }
}

// Játék státusz
- GyP (Győzelmi Pontok)
- Erőforrások (arany, mana, nyersanyagok)
- Épületek, egységek, varázslatok
```

## 📚 Dokumentáció

Részletes játékmechanika leírás:
- [Súgó](docs/help.md) - Játékos kézikönyv
- [Szabályok](docs/rules.md) - Játékszabályok
- [GYIK](docs/faq.md) - Gyakran ismételt kérdések
- [Legenda](docs/story.md) - Háttértörténet
- [Fajok](docs/races.md) - Részletes fajleírások

Fejlesztői dokumentáció:
- [Technikai áttekintés](docs/index.md)

## 🎯 Fejlesztési roadmap

### ✅ Kész
- Frontend alap struktúra
- Belépés / Regisztráció UI
- Játék trónterem oldal (demo adatokkal)
- Többnyelvűség (magyar/angol)
- Témarendszer
- Dokumentáció

### 🚧 Folyamatban
- Backend API fejlesztés
- Adatbázis séma
- Játéklogika implementáció

### 📋 Tervezett
- Valós adatbázis integráció
- Körvezérlő rendszer
- Csatalogika
- Varázslat mechanika
- Klánrendszer
- Piactér
- Admin felület

## 🤝 Közreműködés

Közreműködők várhatóak! Ha szeretnél hozzájárulni:

1. Fork-old a repót
2. Hozz létre egy feature branch-et (`git checkout -b feature/AmazingFeature`)
3. Commit-old a változásokat (`git commit -m 'Add some AmazingFeature'`)
4. Push-old a branch-et (`git push origin feature/AmazingFeature`)
5. Nyiss egy Pull Request-et

## 📄 Licensz

Ez a projekt a klasszikus Stratega játék újragondolása oktatási és szórakoztatási célból.

## 🙏 Köszönetnyilvánítás

- Az eredeti Stratega.hu csapatának a klasszikus játékért
- A közösségnek a hosszú éveken át tartó támogatásért

## 📞 Kapcsolat

- **Projekt**: [GitHub](https://github.com/pixelzsoldos/stratega-reborn/)
- **Eredeti játék**: [stratega.hu](https://stratega.hu)

---

**Megjegyzés**: Ez egy fejlesztés alatt álló projekt. A funkciók folyamatosan bővülnek.
