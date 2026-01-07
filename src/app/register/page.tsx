"use client"

import React, { useMemo, useState } from 'react'
import { useTheme } from '@/components/theme/ThemeProvider'
import SectionCard from '@/components/ui/SectionCard'
import Link from 'next/link'

const races = [
  { value: '', label: '' },
  { value: '1', label: 'Elf' },
  { value: '2', label: 'Élőholt' },
  { value: '3', label: 'Törpe' },
  { value: '4', label: 'Ember' },
  { value: '5', label: 'Sötételf' },
  { value: '6', label: 'Ork' },
]

const countries = [
  { value: 'HUN', label: 'Hungary' },
  { value: 'DEU', label: 'Germany' },
  { value: 'USA', label: 'United States' },
  { value: 'GBR', label: 'United Kingdom' },
  { value: 'FRA', label: 'France' },
  { value: 'ESP', label: 'Spain' },
  { value: 'ITA', label: 'Italy' },
]

function range(from: number, to: number) {
  const arr: number[] = []
  for (let i = from; i >= to; i--) arr.push(i)
  return arr
}

export default function RegisterPage() {
  const { palette } = useTheme()
  const [submitting, setSubmitting] = useState(false)
  const [form, setForm] = useState({
    email: '',
    countryName: '',
    login: '',
    pwd: '',
    pwdre: '',
    race: '',
    lastName: '',
    firstName: '',
    birthYear: '',
    birthMonth: '1',
    birthDay: '1',
    country: 'HUN',
    city: '',
    address: '',
    zip: '',
    mobile: '',
    agreed: false,
  })

  const years = useMemo(() => range(new Date().getFullYear(), 1922), [])
  const months = useMemo(() => range(12, 1).reverse(), [])
  const days = useMemo(() => range(31, 1).reverse(), [])

  const update = (k: keyof typeof form, v: any) => setForm((s) => ({ ...s, [k]: v }))

  const onSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (submitting) return
    // Minimal validáció
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
      alert('Érvénytelen e-mail cím')
      return
    }
    if (form.countryName.trim().length < 3) {
      alert('Az ország név minimum 3 karakter legyen')
      return
    }
    if (form.login.trim().length === 0) {
      alert('Belépési név kötelező')
      return
    }
    if (form.pwd.length < 6 || !/[a-zA-Z]/.test(form.pwd) || !/\d/.test(form.pwd)) {
      alert('A jelszó legalább 6 karakter, betűt és számot is tartalmazzon')
      return
    }
    if (form.pwd !== form.pwdre) {
      alert('A két jelszó nem egyezik')
      return
    }
    if (!form.race) {
      alert('Válassz fajt')
      return
    }
    if (!form.lastName.trim() || !form.firstName.trim()) {
      alert('Vezetéknév és keresztnév kötelező')
      return
    }
    if (!form.agreed) {
      alert('El kell fogadnod a feltételeket')
      return
    }

    setSubmitting(true)
    try {
      // Itt lehetne bekötni API-t (Next.js Route Handler /api/register)
      console.log('Regisztrációs adatok', form)
      alert('Sikeres regisztráció (demo)')
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div style={{
      minHeight: '100vh',
      backgroundColor: palette.background,
      color: palette.text,
      fontFamily: 'Verdana, Arial, Helvetica, sans-serif',
      fontSize: '12px'
    }}>
      <div style={{ maxWidth: 700, margin: '0 auto', padding: '2rem 1rem' }}>
        <h1 style={{ color: palette.link, textAlign: 'center', marginBottom: '1rem' }}>STRATEGA REGISZTRÁCIÓ</h1>
        <p style={{ textAlign: 'center', lineHeight: 1.6 }}>
          Ha a STRATEGA játékban részt szeretnél venni akkor, az alábbi kérdések megválaszolásával regisztráld magad. <br />
          Figyelem, saját neved később nem módosítható! Kérjük, a regisztrációs lapon feltüntetett adatokat saját érdekedben pontosan töltsd ki! <br />
          Olvasd el jogi és titoktartási nyilatkozatunkat. A *-al jelölt mezőket kötelező kitölteni!
        </p>

        <form onSubmit={onSubmit} noValidate style={{ marginTop: '1rem' }}>
          <SectionCard title="Fiók adatok">
            <div style={{ display: 'grid', gap: '0.75rem' }}>
                <label>
                  E-mail címed: *
                  <input
                    type="email"
                    value={form.email}
                    onChange={(e) => update('email', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  />
                </label>
                <small style={{ color: palette.hover }}>
                  Ezen a címeden kapod meg az aktiváláshoz szükséges kódot, és erre a címedre küldjük meg az általad választott értesítéseket is. <br />
                  *** Jelenleg elsősorban a Yahoo, másodsorban a Gmail levelezési szolgáltatók az elfogadottak. ***
                </small>
                <label>
                  Választott országnév: * (Min. 3 karakter)
                  <input
                    type="text"
                    value={form.countryName}
                    onChange={(e) => update('countryName', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  />
                </label>
                <label>
                  Belépési név: *
                  <input
                    type="text"
                    maxLength={20}
                    value={form.login}
                    onChange={(e) => update('login', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  />
                </label>
                <label>
                  Jelszó (min. 6 karakter, betű és szám): *
                  <input
                    type="password"
                    maxLength={20}
                    value={form.pwd}
                    onChange={(e) => update('pwd', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  />
                </label>
                <label>
                  Jelszó újra: *
                  <input
                    type="password"
                    maxLength={20}
                    value={form.pwdre}
                    onChange={(e) => update('pwdre', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  />
                </label>
                <label>
                  Faj: *
                  <select
                    value={form.race}
                    onChange={(e) => update('race', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  >
                    {races.map(r => <option key={r.value} value={r.value}>{r.label}</option>)}
                  </select>
                </label>
              </div>
          </SectionCard>

          <div style={{ height: 16 }} />

          <SectionCard title="Személyes adatok">
            <div style={{ display: 'grid', gap: '0.75rem' }}>
                <label>
                  Vezetékneved: *
                  <input
                    type="text"
                    maxLength={80}
                    value={form.lastName}
                    onChange={(e) => update('lastName', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  />
                </label>
                <label>
                  Keresztneved: *
                  <input
                    type="text"
                    maxLength={80}
                    value={form.firstName}
                    onChange={(e) => update('firstName', e.target.value)}
                    style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}
                  />
                </label>
                <div>
                  <div style={{ marginBottom: 6 }}>Születési idő: *</div>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 8 }}>
                    <select value={form.birthYear} onChange={(e) => update('birthYear', e.target.value)} style={{ padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}>
                      <option value="">Év</option>
                      {years.map(y => <option key={y} value={String(y)}>{y}</option>)}
                    </select>
                    <select value={form.birthMonth} onChange={(e) => update('birthMonth', e.target.value)} style={{ padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}>
                      {months.map(m => <option key={m} value={String(m)}>{m}</option>)}
                    </select>
                    <select value={form.birthDay} onChange={(e) => update('birthDay', e.target.value)} style={{ padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}>
                      {days.map(d => <option key={d} value={String(d)}>{d}</option>)}
                    </select>
                  </div>
                </div>
                <label>
                  Ország: *
                  <select value={form.country} onChange={(e) => update('country', e.target.value)} style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }}>
                    {countries.map(c => <option key={c.value} value={c.value}>{c.label}</option>)}
                  </select>
                </label>
                <label>
                  Város: *
                  <input type="text" maxLength={255} value={form.city} onChange={(e) => update('city', e.target.value)} style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }} />
                </label>
                <label>
                  Számlázási címed (nem kötelező):
                  <input type="text" maxLength={255} value={form.address} onChange={(e) => update('address', e.target.value)} style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }} />
                </label>
                <label>
                  Irányítószám: *
                  <input type="text" maxLength={6} value={form.zip} onChange={(e) => update('zip', e.target.value)} style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }} />
                </label>
                <label>
                  Mobiltelefonszámod: *
                  <input type="text" maxLength={13} value={form.mobile} onChange={(e) => update('mobile', e.target.value)} style={{ width: '100%', padding: '8px', background: palette.cardBg, border: `1px solid ${palette.border}`, color: palette.lightText }} />
                </label>
              </div>
          </SectionCard>

          <div style={{ height: 16 }} />

          <SectionCard title="Feltételek">
            <div style={{ display: 'grid', gap: 8 }}>
                <label style={{ display: 'flex', alignItems: 'flex-start', gap: 8 }}>
                  <input type="checkbox" checked={form.agreed} onChange={(e) => update('agreed', e.target.checked)} />
                  <span>
                    A <Link href="/jatekszabaly" target="_blank">játék szabályait</Link>, az <Link href="/aszf" target="_blank">ÁSZF</Link>-t és az <Link href="/adatvedelem" target="_blank">adatvédelemi</Link> nyilatkozatokat elolvastam, és elfogadom.
                  </span>
                </label>
                <div style={{ display: 'flex', gap: 12, marginTop: 8 }}>
                  <button type="submit" disabled={submitting} style={{ padding: '8px 16px', background: palette.accent, color: palette.lightText, border: `1px solid ${palette.border}`, cursor: 'pointer' }}>Mehet!</button>
                  <button type="reset" onClick={() => setForm({ ...form, email: '', countryName: '', login: '', pwd: '', pwdre: '', race: '', lastName: '', firstName: '', birthYear: '', birthMonth: '1', birthDay: '1', country: 'HUN', city: '', address: '', zip: '', mobile: '', agreed: false })} style={{ padding: '8px 16px', background: '#333', color: palette.lightText, border: `1px solid ${palette.border}`, cursor: 'pointer' }}>Mégsem</button>
                </div>
              </div>
          </SectionCard>
        </form>
      </div>
    </div>
  )
}
