"use client";

import React, { useState } from "react";
import { useTheme } from "@/components/theme/ThemeProvider";
import SectionCard from "@/components/ui/SectionCard";
import LanguageSwitcher from "@/components/ui/LanguageSwitcher";
import { useTranslation } from "react-i18next";
import i18n from "@/i18n";
import Logo from "@/components/ui/Logo";

export default function LoginPage() {
  const { palette } = useTheme();
  const { t } = useTranslation();

  const [loginForm, setLoginForm] = useState({ alogin: "", password: "" });
  const [forgotForm, setForgotForm] = useState({ needmail: "" });
  const [submittingLogin, setSubmittingLogin] = useState(false);
  const [submittingForgot, setSubmittingForgot] = useState(false);

  const updateLogin = (k: keyof typeof loginForm, v: string) =>
    setLoginForm((s) => ({ ...s, [k]: v }));
  const updateForgot = (k: keyof typeof forgotForm, v: string) =>
    setForgotForm((s) => ({ ...s, [k]: v }));

  const onLoginSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submittingLogin) return;

    if (!loginForm.alogin.trim() || !loginForm.password) {
      alert("Belépési név és jelszó kötelező");
      return;
    }

    setSubmittingLogin(true);
    try {
      // Replikáljuk az eredeti logikát: a jelszót egy "jelszo43" mezőbe küldjük (demo)
      const payload = {
        CCHK: 1,
        alogin: loginForm.alogin,
        jelszo43: loginForm.password,
      };
      console.log("Login submit (demo):", payload);
      if (typeof window !== "undefined") {
        localStorage.setItem("sr_logged_in", "1");
        window.location.href = "/game";
      }
    } finally {
      setSubmittingLogin(false);
    }
  };

  const onForgotSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submittingForgot) return;

    if (!forgotForm.needmail.trim()) {
      alert("E-mail megadása kötelező");
      return;
    }

    setSubmittingForgot(true);
    try {
      const payload = { CCHK: 1, needmail: forgotForm.needmail };
      console.log("Forgot password submit (demo):", payload);
      alert("Elfelejtett jelszó (demo)");
    } finally {
      setSubmittingForgot(false);
    }
  };

  return (
    <div
      style={{
        minHeight: "100vh",
        backgroundColor: palette.background,
        color: palette.text,
        fontFamily: "Verdana, Arial, Helvetica, sans-serif",
      }}
    >
      <div style={{ maxWidth: 600, margin: "0 auto", padding: "2rem 1rem" }}>
        <Logo />
        <LanguageSwitcher
          lang={i18n.language}
          setLang={i18n.changeLanguage}
          labels={{ hu: t("langHu"), en: t("langEn") }}
        />

        <SectionCard>
          <form onSubmit={onLoginSubmit} noValidate>
            <div style={{ display: "grid", gap: 12 }}>
              <label>
                Belépési név:
                <input
                  type="text"
                  value={loginForm.alogin}
                  onChange={(e) => updateLogin("alogin", e.target.value)}
                  style={{
                    width: "100%",
                    padding: "8px",
                    background: palette.cardBg,
                    border: `1px solid ${palette.border}`,
                    color: palette.lightText,
                  }}
                />
              </label>
              <label>
                Jelszó:
                <input
                  type="password"
                  maxLength={20}
                  value={loginForm.password}
                  onChange={(e) => updateLogin("password", e.target.value)}
                  style={{
                    width: "100%",
                    padding: "8px",
                    background: palette.cardBg,
                    border: `1px solid ${palette.border}`,
                    color: palette.lightText,
                  }}
                />
              </label>
              <div
                style={{ display: "flex", gap: 12, justifyContent: "flex-end" }}
              >
                <button
                  type="submit"
                  disabled={submittingLogin}
                  style={{
                    padding: "8px 16px",
                    background: palette.accent,
                    color: palette.lightText,
                    border: `1px solid ${palette.border}`,
                    cursor: "pointer",
                  }}
                >
                  Belépés
                </button>
              </div>
            </div>
          </form>
        </SectionCard>

        <div style={{ height: 16 }} />

        <SectionCard>
          <form onSubmit={onForgotSubmit} noValidate>
            <div style={{ display: "grid", gap: 12 }}>
              <div>Ha elfelejtetted a jelszavadat, add meg az E-mail-edet:</div>
              <label>
                E-mail:
                <input
                  type="email"
                  value={forgotForm.needmail}
                  onChange={(e) => updateForgot("needmail", e.target.value)}
                  style={{
                    width: "100%",
                    padding: "8px",
                    background: palette.cardBg,
                    border: `1px solid ${palette.border}`,
                    color: palette.lightText,
                  }}
                />
              </label>
              <div
                style={{ display: "flex", gap: 12, justifyContent: "flex-end" }}
              >
                <button
                  type="submit"
                  disabled={submittingForgot}
                  style={{
                    padding: "8px 16px",
                    background: palette.accent,
                    color: palette.lightText,
                    border: `1px solid ${palette.border}`,
                    cursor: "pointer",
                  }}
                >
                  Mehet!
                </button>
              </div>
            </div>
          </form>
        </SectionCard>
      </div>
    </div>
  );
}
