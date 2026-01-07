import React, { CSSProperties } from 'react'
import Link from 'next/link'
import { useTheme } from '../theme/ThemeProvider'

export default function LinkItem({ href, children }: { href: string, children: React.ReactNode }) {
  const { palette } = useTheme()

  const linkStyle: CSSProperties = {
    color: palette.link,
    textDecoration: 'none',
    fontSize: '12px',
    fontWeight: 'bold',
    display: 'block',
    padding: '0.5em 1em',
    transition: 'color 0.2s'
  }

  const handleLinkMouseOver = (e: React.MouseEvent<HTMLAnchorElement>) => {
    e.currentTarget.style.color = palette.hover
    e.currentTarget.style.backgroundColor = '#222'
  }

  const handleLinkMouseOut = (e: React.MouseEvent<HTMLAnchorElement>) => {
    e.currentTarget.style.color = palette.link
    e.currentTarget.style.backgroundColor = 'transparent'
  }

  return (
    <Link href={href} style={linkStyle} onMouseOver={handleLinkMouseOver} onMouseOut={handleLinkMouseOut}>
      {children}
    </Link>
  )
}
