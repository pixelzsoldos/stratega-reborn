'use client'

import React, { useState, CSSProperties } from 'react'
import { translations } from '@/locales/translations'
import { Language } from '@/types/translations'

export default function Homepage() {
  const [lang, setLang] = useState<Language>('hu')
  const t = translations[lang]

  const linkStyle: CSSProperties = {
    color: '#5f9ea0',
    textDecoration: 'none',
    fontSize: '12px',
    fontWeight: 'bold',
    display: 'block',
    padding: '0.5em 1em',
    transition: 'color 0.2s'
  }

  const buttonStyle = (isActive: boolean): CSSProperties => ({
    margin: '0 5px',
    padding: '8px 20px',
    backgroundColor: isActive ? '#366' : '#333',
    color: '#d3d3d3',
    border: '1px solid',
    borderColor: '#366 #d3d3d3 #d3d3d3 #366',
    fontWeight: 'bold',
    fontSize: '12px',
    cursor: 'pointer',
    fontFamily: 'Verdana, Arial, Helvetica, sans-serif',
    transition: 'all 0.2s',
    minWidth: '100px'
  })

  const handleLinkMouseOver = (e: React.MouseEvent<HTMLAnchorElement>) => {
    e.currentTarget.style.color = '#696969'
    e.currentTarget.style.backgroundColor = '#222'
  }

  const handleLinkMouseOut = (e: React.MouseEvent<HTMLAnchorElement>) => {
    e.currentTarget.style.color = '#5f9ea0'
    e.currentTarget.style.backgroundColor = 'transparent'
  }

  const handleButtonMouseOver = (e: React.MouseEvent<HTMLButtonElement>, isActive: boolean) => {
    if (!isActive) {
      e.currentTarget.style.backgroundColor = '#444'
    }
  }

  const handleButtonMouseOut = (e: React.MouseEvent<HTMLButtonElement>, isActive: boolean) => {
    if (!isActive) {
      e.currentTarget.style.backgroundColor = '#333'
    }
  }

  return (
    <>
      <div style={{
        minHeight: '100vh',
        backgroundColor: '#111',
        color: '#a9a9a9',
        fontFamily: 'Verdana, Arial, Helvetica, sans-serif',
        fontSize: '9px',
        margin: 0,
        padding: 0,
        display: 'flex',
        flexDirection: 'column'
      }}>
        <div style={{
          padding: '1em',
          flex: 1,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          width: '100%',
          boxSizing: 'border-box'
        }}>
          
          {/* Logo Section */}
          <div style={{
            textAlign: 'center',
            margin: '2em auto',
            width: '100%',
            maxWidth: '600px',
            padding: '0 1em',
            boxSizing: 'border-box'
          }}>
            <div style={{
              border: '3px solid #366',
              padding: '1.5em 1em',
              backgroundColor: '#1a1a1a',
              width: '100%',
              boxSizing: 'border-box'
            }}>
              <h1 style={{
                fontSize: 'clamp(28px, 8vw, 48px)',
                fontWeight: 'bold',
                color: '#5f9ea0',
                margin: '0.3em 0',
                letterSpacing: '0.2em',
                textTransform: 'uppercase',
                wordBreak: 'break-word'
              }}>
                STRATEGA
              </h1>
            </div>
          </div>

          {/* Language Selector - Jelenleg elrejtve */}
          <div style={{
            textAlign: 'center',
            margin: '1em 0 2em 0',
            width: '100%',
            padding: '0 1em',
            boxSizing: 'border-box',
            display: 'none'
          }}>
            <button
              onClick={() => setLang('hu')}
              style={buttonStyle(lang === 'hu')}
              onMouseOver={(e) => handleButtonMouseOver(e, lang === 'hu')}
              onMouseOut={(e) => handleButtonMouseOut(e, lang === 'hu')}
            >
              {t.langHu}
            </button>
            <button
              onClick={() => setLang('en')}
              style={buttonStyle(lang === 'en')}
              onMouseOver={(e) => handleButtonMouseOver(e, lang === 'en')}
              onMouseOut={(e) => handleButtonMouseOut(e, lang === 'en')}
            >
              {t.langEn}
            </button>
          </div>

          {/* Main Content */}
          <div style={{
            width: '100%',
            maxWidth: '700px',
            margin: '0 auto',
            padding: '0 1em',
            boxSizing: 'border-box'
          }}>
            
            <div className="content-grid" style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
              gap: '2em',
              marginBottom: '3em'
            }}>
              
              {/* Left Column - Account */}
              <div style={{
                textAlign: 'center',
                padding: '1em',
                backgroundColor: '#1a1a1a',
                border: '1px solid #333',
                borderRadius: '4px'
              }}>
                <h2 style={{
                  fontSize: '14px',
                  fontWeight: 'bold',
                  color: '#5f9ea0',
                  marginBottom: '1em',
                  borderBottom: '1px solid #366',
                  paddingBottom: '0.5em'
                }}>
                  {t.account}
                </h2>
                <a 
                  href="/login.html"
                  style={linkStyle}
                  onMouseOver={handleLinkMouseOver}
                  onMouseOut={handleLinkMouseOut}
                >
                  {t.login}
                </a>
                <a 
                  href="/register.html"
                  style={linkStyle}
                  onMouseOver={handleLinkMouseOver}
                  onMouseOut={handleLinkMouseOut}
                >
                  {t.register}
                </a>
              </div>

              {/* Right Column - Information */}
              <div style={{
                textAlign: 'center',
                padding: '1em',
                backgroundColor: '#1a1a1a',
                border: '1px solid #333',
                borderRadius: '4px'
              }}>
                <h2 style={{
                  fontSize: '14px',
                  fontWeight: 'bold',
                  color: '#5f9ea0',
                  marginBottom: '1em',
                  borderBottom: '1px solid #366',
                  paddingBottom: '0.5em'
                }}>
                  {t.information}
                </h2>
                <a 
                  href="/story.html"
                  style={linkStyle}
                  onMouseOver={handleLinkMouseOver}
                  onMouseOut={handleLinkMouseOut}
                >
                  {t.legend}
                </a>
                <a 
                  href="/sugo.html"
                  style={linkStyle}
                  onMouseOver={handleLinkMouseOver}
                  onMouseOut={handleLinkMouseOut}
                >
                  {t.guide}
                </a>
                <a 
                  href="/jatekszabaly.html"
                  style={linkStyle}
                  onMouseOver={handleLinkMouseOver}
                  onMouseOut={handleLinkMouseOut}
                >
                  {t.rules}
                </a>
              </div>
            </div>

          </div>

          {/* Spacer */}
          <div style={{ flex: 1, minHeight: '2em' }}></div>

          {/* Footer */}
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
              borderTop: '1px solid #696969',
              paddingTop: '1em'
            }}>
              <p style={{
                fontSize: 'clamp(9px, 2.5vw, 11px)',
                color: '#dcdcdc',
                margin: '0.5em 0',
                lineHeight: '1.6'
              }}>
                {t.copyright}
              </p>
              <p style={{
                fontSize: 'clamp(9px, 2.5vw, 11px)',
                margin: '1em 0',
                lineHeight: '1.6'
              }}>
                <a 
                  href="/aszf.html"
                  style={{
                    color: '#5f9ea0',
                    textDecoration: 'none',
                    padding: '0.5em',
                    display: 'inline-block'
                  }}
                  onMouseOver={(e) => {
                    e.currentTarget.style.color = '#696969'
                  }}
                  onMouseOut={(e) => {
                    e.currentTarget.style.color = '#5f9ea0'
                  }}
                >
                  {t.terms}
                </a>
                <span style={{ margin: '0 0.5em', color: '#696969' }}>|</span>
                <a 
                  href="/adatvedelem.html"
                  style={{
                    color: '#5f9ea0',
                    textDecoration: 'none',
                    padding: '0.5em',
                    display: 'inline-block'
                  }}
                  onMouseOver={(e) => {
                    e.currentTarget.style.color = '#696969'
                  }}
                  onMouseOut={(e) => {
                    e.currentTarget.style.color = '#5f9ea0'
                  }}
                >
                  {t.privacy}
                </a>
              </p>
            </div>
          </div>

        </div>
      </div>

      {/* Responsive Styles */}
      <style jsx>{`
        @media (max-width: 600px) {
          .content-grid {
            grid-template-columns: 1fr !important;
            gap: 1.5em !important;
          }
        }

        @media (max-width: 400px) {
          button {
            margin: 0.3em !important;
            display: block !important;
            width: 90% !important;
            margin-left: auto !important;
            margin-right: auto !important;
          }
        }

        @media (hover: none) and (pointer: coarse) {
          a {
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
          }
          
          button {
            min-height: 44px;
          }
        }
      `}</style>
    </>
  )
}
