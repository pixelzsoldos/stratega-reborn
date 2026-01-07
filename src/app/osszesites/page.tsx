"use client"

import React from 'react'
import { useTheme } from '@/components/theme/ThemeProvider'
import SectionCard from '@/components/ui/SectionCard'

export default function OsszesitesPage() {
  const { palette } = useTheme()

  const tableStyle: React.CSSProperties = {
    width: '100%',
    borderCollapse: 'collapse'
  }
  const thStyle: React.CSSProperties = {
    textAlign: 'left',
    padding: '8px 6px',
    borderBottom: `1px solid ${palette.border}`,
    color: palette.lightText
  }
  const tdStyle: React.CSSProperties = {
    padding: '8px 6px',
    borderBottom: `1px solid ${palette.border}`
  }

  return (
    <div style={{
      minHeight: '100vh',
      backgroundColor: palette.background,
      color: palette.text,
      fontFamily: 'Verdana, Arial, Helvetica, sans-serif',
      fontSize: '12px'
    }}>
      {/* Full-width header */}
      <div style={{ width: '100%', padding: '1.5rem 1rem', borderBottom: `1px solid ${palette.border}` }}>
        <h1 style={{ color: palette.link, textAlign: 'center', margin: 0 }}>🎮 Játék felület – Főoldal</h1>
      </div>

      {/* Main area with sticky left menu and scrollable content */}
      <div style={{ display: 'flex', alignItems: 'flex-start' }}>
        {/* Left sidebar (sticky) */}
        <aside style={{
          position: 'sticky',
          top: 0,
          alignSelf: 'flex-start',
          width: 260,
          minHeight: 'calc(100vh - 0px)',
          borderRight: `1px solid ${palette.border}`,
          padding: '1rem',
          boxSizing: 'border-box'
        }}>
          <SectionCard title="📂 Oldalsó menü">
            <div style={{ display: 'grid', gap: 8 }}>
              <ul style={{ margin: 0, paddingLeft: '1rem' }}>
                <li>🏰 Trónterem</li>
                <li>🏗️ Építkezés</li>
                <li>📜 Tekercsek</li>
                <li>✨ Varázslatelfogadás</li>
                <li>⚔️ Kiképzés</li>
                <li>🗡️ Támadás</li>
                <li>🏪 Piactér</li>
                <li>🍺 Kocsma</li>
              </ul>
              <hr style={{ borderColor: palette.border, opacity: 0.4 }} />
              <ul style={{ margin: 0, paddingLeft: '1rem' }}>
                <li>🧠 Tanácsterem</li>
                <li>🎯 Prioritás</li>
                <li>🌍 Országlista</li>
                <li>⚙️ Beállítások</li>
                <li>👤 Intéző</li>
              </ul>
              <hr style={{ borderColor: palette.border, opacity: 0.4 }} />
              <ul style={{ margin: 0, paddingLeft: '1rem' }}>
                <li>💳 Kredit</li>
                <li>📨 Futár</li>
                <li>📖 Krónika</li>
                <li>📊 Statisztikák</li>
                <li>📝 Jegyzet</li>
                <li>🎁 Bónusz</li>
                <li>🏆 Hírnévtábla</li>
                <li>📰 Hírek</li>
                <li>❓ Súgó / GYIK</li>
                <li>🔒 Adatvédelem / ÁSZF / FMSZ</li>
                <li>📜 Játékszabályzat</li>
              </ul>
              <hr style={{ borderColor: palette.border, opacity: 0.4 }} />
              <div>🚪 Kilépés</div>
            </div>
          </SectionCard>
        </aside>

        {/* Right content area */}
        <main style={{ flex: 1, padding: '1rem', boxSizing: 'border-box' }}>
          {/* Felső státuszsor */}
          <SectionCard title="🔝 Felső státuszsor">
          <div style={{ display: 'grid', gap: 8 }}>
            <div><b>Felhasználó:</b> van 5 szabad percem (ID: 147006, #691)</div>
            <div><b>Faj:</b> Törpe</div>
          </div>

          <div style={{ height: 12 }} />

          <div style={{ overflowX: 'auto' }}>
            <table style={tableStyle}>
              <thead>
                <tr>
                  {['Arany','Élelem','Fa','Kő','Fém','Mana','Föld','XP','Hűség','Szerencse','Körök','Idő'].map(h => (
                    <th key={h} style={thStyle}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                <tr>
                  {['1 215 615','6324','2347','3660','2663','690','297','4767','100','***','65 398','~⏱'].map((v, i) => (
                    <td key={i} style={{ ...tdStyle, textAlign: i === 10 ? 'right' : (i === 8 ? 'right' : 'left') }}>{v}</td>
                  ))}
                </tr>
              </tbody>
            </table>
          </div>
        </SectionCard>

        <div style={{ height: 16 }} />

        {/* Bal tartalom – Ország állapota */}
        <SectionCard title="🧱 Bal tartalom – Ország állapota">
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>🏘️ Épületek (297 / 297)</h3>
          <table style={tableStyle}>
            <thead>
              <tr>
                <th style={thStyle}>Épület</th>
                <th style={{ ...thStyle, textAlign: 'right' }}>Darab</th>
              </tr>
            </thead>
            <tbody>
              {[
                ['Raktár','4'],
                ['Ércbánya','16'],
                ['Fatelep','12'],
                ['Kőbánya','14'],
                ['Tanya','60'],
                ['Sziklafalvak','81'],
                ['Barakk','60'],
                ['Erőd','20'],
                ['Manaforrás','14'],
                ['Varázslótorony','14'],
                ['Kincstár','1'],
                ['Könyvtár','1'],
              ].map(([name, count]) => (
                <tr key={name}>
                  <td style={tdStyle}>{name}</td>
                  <td style={{ ...tdStyle, textAlign: 'right' }}>{count}</td>
                </tr>
              ))}
            </tbody>
          </table>

          <div style={{ height: 16 }} />
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>🪖 Egységek (6000 TE, 6000 / 7000)</h3>
          <table style={tableStyle}>
            <thead>
              <tr>
                <th style={thStyle}>Egység</th>
                <th style={{ ...thStyle, textAlign: 'right' }}>Db</th>
                <th style={{ ...thStyle, textAlign: 'center' }}>Morál</th>
                <th style={{ ...thStyle, textAlign: 'center' }}>T / V / ÉP / K</th>
              </tr>
            </thead>
            <tbody>
              {[
                ['Tárnametsző','3000','***','5 / 1 / 10 / 2'],
                ['Számszeríjász','3000','***','1 / 3 / 10 / 2'],
              ].map(([name, count, moral, stats]) => (
                <tr key={name}>
                  <td style={tdStyle}>{name}</td>
                  <td style={{ ...tdStyle, textAlign: 'right' }}>{count}</td>
                  <td style={{ ...tdStyle, textAlign: 'center' }}>{moral}</td>
                  <td style={{ ...tdStyle, textAlign: 'center' }}>{stats}</td>
                </tr>
              ))}
            </tbody>
          </table>

          <div style={{ height: 16 }} />
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>⚔️ Háborúban</h3>
          <div style={{ color: palette.hover }}>Nincs aktív sereg</div>

          <div style={{ height: 12 }} />
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>🏃 Hazafele jövet</h3>
          <div style={{ color: palette.hover }}>Üres</div>

          <div style={{ height: 12 }} />
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>🤝 Támogató seregek</h3>
          <div style={{ color: palette.hover }}>Üres</div>
        </SectionCard>

        <div style={{ height: 16 }} />

        {/* Jobb tartalom – Krónika és varázslatok */}
        <SectionCard title="📜 Jobb tartalom – Krónika és varázslatok">
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>📖 Krónika</h3>
          <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
            {[
              ['2026.01.07. 20:18', 'Életerő varázslatot kaptál'],
              ['2026.01.07. 20:00', 'Karaván érkezett'],
              ['2026.01.07. 15:40', 'Karaván érkezett'],
              ['2026.01.07. 15:00', 'Fegyelmezetlen katonák'],
              ['2026.01.07. 14:40', 'Szélvihar'],
              ['…', '']
            ].map(([date, text], idx) => (
              <li key={idx} style={{ padding: '6px 0', borderBottom: `1px solid ${palette.border}` }}>
                <span>⏰ <b>{date}</b></span>
                {text && <span style={{ marginLeft: 8 }}>– {text}</span>}
              </li>
            ))}
          </ul>

          <div style={{ height: 16 }} />
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>✨ Aktív varázslatok</h3>
          <div style={{ color: palette.hover }}>Nincs aktív varázslat</div>

          <div style={{ height: 16 }} />
          <h3 style={{ margin: '0 0 8px 0', color: palette.link }}>📜 Tekercsek (73 db)</h3>
          <ul style={{ margin: 0, paddingLeft: '1rem' }}>
            {[
              'Adomány (5)',
              'Agresszivitás (9)',
              'Áldás (12)',
              'Alkímia (1)',
              'Bányászat (7)',
              'Égi abrak (4)',
              'Életerő (10)',
              'Szélvész (9)',
              'Szorgalom (5)',
              'Termékenység (4)',
              'Varázsgömb (7)'
            ].map((t, i) => (
              <li key={i}>{t}</li>
            ))}
          </ul>
        </SectionCard>
        </main>
      </div>
    </div>
  )
}
