"use client"

import React from 'react'
import { useTheme } from '../theme/ThemeProvider'
import { Language } from '@/types/translations'

export default function LanguageSwitcher({
  lang,
  setLang,
  labels
}: {
  lang: Language
  setLang: (l: Language) => void
  labels: { hu: string; en: string }
}) {
  const { palette } = useTheme()

  const buttonStyle = (isActive: boolean): React.CSSProperties => ({
    margin: '0 5px',
    padding: '8px 20px',
    backgroundColor: isActive ? palette.accent : '#333',
    color: '#d3d3d3',
    border: '1px solid',
    borderColor: `${palette.accent} #d3d3d3 #d3d3d3 ${palette.accent}`,
    fontWeight: 'bold',
    fontSize: '12px',
    cursor: 'pointer',
    fontFamily: 'Verdana, Arial, Helvetica, sans-serif',
    transition: 'all 0.2s',
    minWidth: '100px'
  })

  const handleButtonMouseOver = (e: React.MouseEvent<HTMLButtonElement>, isActive: boolean) => {
    if (!isActive) e.currentTarget.style.backgroundColor = '#444'
  }
  const handleButtonMouseOut = (e: React.MouseEvent<HTMLButtonElement>, isActive: boolean) => {
    if (!isActive) e.currentTarget.style.backgroundColor = '#333'
  }

  return (
    <div style={{
      textAlign: 'center',
      margin: '1em 0 2em 0',
      width: '100%',
      padding: '0 1em',
      boxSizing: 'border-box'
    }}>
      <button
        onClick={() => setLang('hu')}
        style={buttonStyle(lang === 'hu')}
        onMouseOver={(e) => handleButtonMouseOver(e, lang === 'hu')}
        onMouseOut={(e) => handleButtonMouseOut(e, lang === 'hu')}
      >
        {labels.hu}
      </button>
      <button
        onClick={() => setLang('en')}
        style={buttonStyle(lang === 'en')}
        onMouseOver={(e) => handleButtonMouseOver(e, lang === 'en')}
        onMouseOut={(e) => handleButtonMouseOut(e, lang === 'en')}
      >
        {labels.en}
      </button>
    </div>
  )
}
