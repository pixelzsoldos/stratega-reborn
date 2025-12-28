export interface Translation {
  // Navigation
  account: string
  information: string
  
  // Links
  login: string
  register: string
  legend: string
  guide: string
  rules: string
  
  // Footer
  copyright: string
  terms: string
  privacy: string
  
  // Language selector
  langHu: string
  langEn: string
}

export type Language = 'hu' | 'en'

export type Translations = Record<Language, Translation>
