import React from 'react'
import { useTheme } from '../theme/ThemeProvider'
import LinkItem from './LinkItem'

export default function Footer({ terms, privacy }: { terms: string, privacy: string }) {
  const { palette } = useTheme()
  return (
    <div style={{
      marginTop: 'auto',
      paddingTop: '2em',
      paddingBottom: '2em',
      textAlign: 'center',
      width: '100%',
      maxWidth: '700px',
      padding: '2em 1em',
      boxSizing: 'border-box'
    }}>
      <div style={{
        borderTop: `1px solid ${palette.hover}`,
        paddingTop: '1em'
      }}>
        <p style={{
          fontSize: 'clamp(9px, 2.5vw, 11px)',
          color: palette.lightText,
          margin: '0.5em 0',
          lineHeight: '1.6'
        }}>
          {/* A valódi szöveg a szülő komponensből jön */}
          {/* eslint-disable-next-line react/no-unescaped-entities */}
          Copyright © Stratega Reborn Team - 2025. Minden jog fenntartva.
        </p>
        <p style={{
          fontSize: 'clamp(9px, 2.5vw, 11px)',
          margin: '1em 0',
          lineHeight: '1.6'
        }}>
          <LinkItem href="/aszf">{terms}</LinkItem>
          <span style={{ margin: '0 0.5em', color: palette.hover }}>|</span>
          <LinkItem href="/adatvedelem">{privacy}</LinkItem>
        </p>
      </div>
    </div>
  )
}
