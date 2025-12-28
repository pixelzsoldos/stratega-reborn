import type { Metadata } from 'next'
import './globals.css'

export const metadata: Metadata = {
  title: 'Stratega Reborn - Középkori Stratégiai Játék',
  description: 'Stratega Reborn - Középkori stratégiai böngészős játék újjászületése',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="hu">
      <body>{children}</body>
    </html>
  )
}
