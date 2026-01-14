"use client";

import React, { useState } from "react";
import { translations } from "@/locales/translations";
import { Language } from "@/types/translations";
import Logo from "./ui/Logo";
import SectionCard from "./ui/SectionCard";
import LinkItem from "./ui/LinkItem";
import ContentGrid from "./ui/ContentGrid";
import Footer from "./ui/Footer";
import { useTheme } from "./theme/ThemeProvider";
import LanguageSwitcher from "./ui/LanguageSwitcher";

export default function Homepage() {
  const [lang, setLang] = useState<Language>("hu");
  const t = translations[lang];
  const { palette } = useTheme();

  return (
    <>
      <div
        style={{
          minHeight: "100vh",
          backgroundColor: palette.background,
          color: palette.text,
          fontFamily: "Verdana, Arial, Helvetica, sans-serif",
          fontSize: "9px",
          margin: 0,
          padding: 0,
          display: "flex",
          flexDirection: "column",
        }}
      >
        <div
          style={{
            padding: "1em",
            flex: 1,
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            width: "100%",
            boxSizing: "border-box",
          }}
        >
          <Logo />

          {/* Language Selector */}
          <LanguageSwitcher
            lang={lang}
            setLang={setLang}
            labels={{ hu: t.langHu, en: t.langEn }}
          />

          {/* Main Content - single container layout */}
          <div
            style={{
              width: "100%",
              maxWidth: "700px",
              margin: "0 auto",
              padding: "0 1em",
              boxSizing: "border-box",
            }}
          >
            <SectionCard>
              <LinkItem href="/login">{t.login}</LinkItem>
              <LinkItem href="/register">{t.register}</LinkItem>
            </SectionCard>

            <div style={{ height: 16 }} />

            <SectionCard>
              <LinkItem href="/story">{t.legend}</LinkItem>
              <LinkItem href="/help">{t.guide}</LinkItem>
              <LinkItem href="/rules">{t.rules}</LinkItem>
              <LinkItem href="/game">Játék</LinkItem>
            </SectionCard>
          </div>

          {/* Spacer */}
          <div style={{ flex: 1, minHeight: "2em" }}></div>

          <Footer terms={t.terms} privacy={t.privacy} />
        </div>
      </div>

      {/* Responsive Styles retained in ContentGrid; mobile button tweaks below */}
      <style jsx>{`
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
  );
}
