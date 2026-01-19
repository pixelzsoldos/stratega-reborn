import React from 'react'
import { useTheme } from '../theme/ThemeProvider'

export default function SectionCard({ title, children }: { title?: string, children: React.ReactNode }) {
  const { palette } = useTheme()
  const showTitle = (title ?? '').trim().length > 0
  return (
    <div style={{
      textAlign: 'center',
      padding: '1em',
      backgroundColor: palette.cardBg,
      border: `1px solid ${palette.border}`,
      borderRadius: '4px'
    }}>
      {showTitle && (
        <h2 style={{
          fontSize: '14px',
          fontWeight: 'bold',
          color: palette.link,
          marginBottom: '1em',
          borderBottom: `1px solid ${palette.accent}`,
          paddingBottom: '0.5em'
        }}>
          {title}
        </h2>
      )}
      {children}
    </div>
  )
}
