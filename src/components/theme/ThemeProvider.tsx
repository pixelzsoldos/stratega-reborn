"use client"

import React, { createContext, useContext, useMemo, useState } from 'react'

export type Palette = {
  name: string
  background: string
  text: string
  link: string
  hover: string
  accent: string
  lightText: string
  cardBg: string
  border: string
}

const palettes: Record<string, Palette> = {
  darkSea: {
    name: 'darkSea',
    background: '#111',
    text: '#a9a9a9',
    link: '#5f9ea0',
    hover: '#696969',
    accent: '#366',
    lightText: '#dcdcdc',
    cardBg: '#1a1a1a',
    border: '#333'
  },
  darkGray: {
    name: 'darkGray',
    background: '#0e0e0e',
    text: '#b0b0b0',
    link: '#76b7c4',
    hover: '#808080',
    accent: '#3a6',
    lightText: '#e5e5e5',
    cardBg: '#171717',
    border: '#2a2a2a'
  }
}

export type ThemeContextType = {
  palette: Palette
  setPaletteName: (name: keyof typeof palettes) => void
  paletteName: keyof typeof palettes
  palettes: typeof palettes
}

const ThemeContext = createContext<ThemeContextType | null>(null)

export const useTheme = () => {
  const ctx = useContext(ThemeContext)
  if (!ctx) throw new Error('useTheme must be used within ThemeProvider')
  return ctx
}

export default function ThemeProvider({ children }: { children: React.ReactNode }) {
  const [paletteName, setPaletteName] = useState<keyof typeof palettes>('darkSea')
  const value = useMemo(() => ({
    palette: palettes[paletteName],
    paletteName,
    setPaletteName,
    palettes
  }), [paletteName])

  return (
    <ThemeContext.Provider value={value}>
      <div
        style={{
          // CSS variables to be used in globals.css
          // applied via inline style on a wrapper
          ['--bg' as any]: palettes[paletteName].background,
          ['--text' as any]: palettes[paletteName].text,
          ['--link' as any]: palettes[paletteName].link,
          ['--hover' as any]: palettes[paletteName].hover,
          ['--accent' as any]: palettes[paletteName].accent,
          ['--lightText' as any]: palettes[paletteName].lightText,
          ['--cardBg' as any]: palettes[paletteName].cardBg,
          ['--border' as any]: palettes[paletteName].border,
        } as React.CSSProperties}
      >
        {children}
      </div>
    </ThemeContext.Provider>
  )
}
