# Stratega Reborn

A **Stratega Reborn** egy klasszikus, böngészőalapú, körökre osztott (turn-based) fantasy stratégiai játék újragondolt, modern technológiai alapokra helyezett változata. A projekt célja egy komplex gazdasági–katonai–mágikus rendszerrel rendelkező online játék létrehozása, amely hosszú távú stratégiai gondolkodást, tervezést és taktikai döntéshozatalt igényel.

A játék szellemiségében a régi iskolás browser MMO-kat idézi, miközben mai fejlesztői eszközökkel, skálázható backenddel és modern frontenddel készül.

---

## 🎮 A játékról röviden

* **Műfaj:** Körökre osztott online stratégiai játék
* **Köridő:** Jellemzően 20 perc (epizód- és fordulófüggő)
* **Fő elemek:**

  * Országépítés és gazdaság
  * Egységek képzése és hadműveletek
  * Varázslatok és mágikus rendszerek
  * Piac és kereskedelem
  * Automatizmusok és prioritáskezelés
  * Epizód alapú rangsorok és resetek

A játékban minden esemény szerveroldalon, körváltásokhoz kötve történik. A játékos döntései hosszú távon hatnak az ország fejlődésére.

---

## 🧠 Alap koncepció

A játékos egy **országot** irányít egy fantasy világban. A cél:

* stabil gazdaság kiépítése,
* erős hadsereg fenntartása,
* varázslatok és mágikus tárgyak használata,
* más országokkal való háborúzás vagy együttműködés,
* epizódon belül minél jobb pozíció elérése.

A játék egyik kulcseleme a **prioritásrendszer**: nem minden fér bele egyszerre, az erőforrásokat okosan kell elosztani.

---

## 🏗 Projekt felépítése

A repository monorepo jellegű:

```
/stratega-reborn
├── client/        # Frontend (Next.js / React)
├── server/        # Backend (Node.js / NestJS)
├── docs/          # Dokumentáció
│   └── help.md    # Részletes játékleírás és szabályrendszer
└── README.md
```

---

## 📚 Dokumentáció

A projekt részletes leírása és a játék teljes szabályrendszere külön dokumentumokban található, hogy a README könnyen áttekinthető maradjon.

Elérhető dokumentációk:

* 📖 **Játéksúgó és részletes mechanikák:** [`docs/help.md`](docs/help.md)

  * gazdaság, prioritások, egységek, varázslatok, hadműveletek
  * amulettek, automatizmusok, piac, véletlen események

* 🌍 **Történet és világ (Story):** [`docs/story.md`](docs/story.md)

  * a Stratega világának háttere
  * fajok, világkép, hangulat és lore

* 📜 **Játékszabályok:** `docs/rules.md`

  * alapvető játékszabályok
  * fair play irányelvek
  * technikai és gameplay korlátozások

Ez a README szándékosan csak **áttekintést** ad; a részletes leírások a `docs/` mappában találhatók.

---

## 🗺 Roadmap

### 1. Alapok (Core MVP)

* [ ] Felhasználókezelés (regisztráció, belépés)
* [ ] Ország entitás és alapadatok
* [ ] Körkezelő (turn engine)
* [ ] Gazdasági termelés és fenntartás
* [ ] Prioritásrendszer implementálása

### 2. Katonai rendszer

* [ ] Egységek és képzés
* [ ] Dezertálás logika
* [ ] Hadműveletek indítása
* [ ] Alap csatarendszer

### 3. Mágia és varázslatok

* [ ] Varázslatok típusai
* [ ] Mana- és toronykezelés
* [ ] Információs varázslatok

### 4. Piac és automatizmusok

* [ ] Nyersanyag- és egységpiac
* [ ] Cserebere piac
* [ ] Intéző (automata vásárlás, képzés)

### 5. Haladó rendszerek

* [ ] Amulettek és hősök
* [ ] Véletlen események
* [ ] Klánok
* [ ] Epizódok, ranglisták, resetek

---

## ⚙️ Technológiai irány (tervezett)

* **Frontend:** Next.js, React, TypeScript
* **Backend:** Node.js, NestJS
* **Adatbázis:** PostgreSQL + Prisma
* **Kommunikáció:** REST / WebSocket (körfrissítések)
* **Infra:** Docker, később VPS / Proxmox

---

## 🚧 Projekt státusz

A projekt **aktív fejlesztés alatt áll**, elsősorban hobbi / tanulási / kísérleti céllal. A fókusz a stabil játékmenet és a tiszta, jól bővíthető architektúra kialakításán van.

---

## 🤝 Közreműködés

Jelenleg a projekt **zárt fejlesztésű**, de a kód és a dokumentáció szabadon böngészhető. Később nyitottabb fejlesztési modell is elképzelhető.

---

## 📜 Licenc

Licencelés később kerül meghatározásra.

---

Ha érdekel a játék működése, mindenképp kezdd a **`docs/help.md`** fájllal – ott van a Stratega Reborn lelke.
