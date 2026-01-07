import React from 'react'

export default function ContentGrid({ children }: { children: React.ReactNode }) {
  return (
    <div className="content-grid" style={{
      display: 'grid',
      gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
      gap: '2em',
      marginBottom: '3em'
    }}>
      {children}
      <style jsx>{`
        @media (max-width: 600px) {
          .content-grid { grid-template-columns: 1fr !important; gap: 1.5em !important; }
        }
      `}</style>
    </div>
  )
}
