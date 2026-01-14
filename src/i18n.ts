import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import enTranslation from '@/locales/en/translation.json';
import huTranslation from '@/locales/hu/translation.json';

i18n
  .use(initReactI18next)
  .init({
    resources: {
      en: { translation: enTranslation },
      hu: { translation: huTranslation },
    },
    lng: 'hu',
    fallbackLng: 'en',
    interpolation: { escapeValue: false },
  });

export default i18n;
