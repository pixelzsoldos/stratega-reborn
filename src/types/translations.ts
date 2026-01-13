export interface Translation {
  // Navigation
  account: string
  information: string
  
  // Links / generic labels
  login: string
  register: string
  legend: string
  guide: string
  rules: string
  submit: string
  cancel: string

  // Form labels
  email: string
  countryName: string
  loginName: string
  password: string
  passwordAgain: string
  race: string
  lastName: string
  firstName: string
  birthDate: string
  country: string
  city: string
  billingAddress: string
  zip: string
  mobile: string
  termsFullText: string
  registerTitle: string
  forgotPassword: string
  forgotEmail: string

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
