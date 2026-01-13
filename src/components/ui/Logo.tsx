import React from 'react'
import { useTheme } from '../theme/ThemeProvider'
import Link from 'next/link'

export default function Logo() {
  const { palette } = useTheme()
  return (
    <div style={{
      textAlign: 'center',
      margin: '2em auto',
      width: '100%',
      maxWidth: '600px',
      padding: '0 1em',
      boxSizing: 'border-box'
    }}>
      <Link href="/" style={{ textDecoration: 'none' }}>
        <div style={{
          border: `3px solid ${palette.accent}`,
          padding: '1.5em 1em',
          backgroundColor: palette.cardBg,
          width: '100%',
          boxSizing: 'border-box'
        }}>
          <h1 style={{
            fontSize: 'clamp(28px, 8vw, 48px)',
            fontWeight: 'bold',
            color: palette.link,
            margin: '0.3em 0',
            letterSpacing: '0.15em',
            textTransform: 'uppercase',
            wordBreak: 'break-word'
          }}>
            STRATEGA REBORN
          </h1>
        </div>
      </Link>
    </div>
  )
}
