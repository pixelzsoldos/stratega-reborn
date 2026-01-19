"use client";

import React, { useMemo, useState, useEffect } from "react";
import { useTheme } from "@/components/theme/ThemeProvider";
import Logo from "@/components/ui/Logo";
import LanguageSwitcher from "@/components/ui/LanguageSwitcher";
import { useTranslation } from "react-i18next";
import i18n from "@/i18n";
import { translations } from "@/locales/translations";
import { Language } from "@/types/translations";

const races = [
  { value: "", label: "" },
  { value: "1", label: "Elf" },
  { value: "2", label: "Élőholt" },
  { value: "3", label: "Törpe" },
  { value: "4", label: "Ember" },
  { value: "5", label: "Sötételf" },
  { value: "6", label: "Ork" },
];

const countries = [
  { value: "HUN", label: "Hungary" },
  { value: "DEU", label: "Germany" },
  { value: "USA", label: "United States" },
  { value: "GBR", label: "United Kingdom" },
  { value: "FRA", label: "France" },
  { value: "ESP", label: "Spain" },
  { value: "ITA", label: "Italy" },
];

function range(from: number, to: number) {
  const arr: number[] = [];
  for (let i = from; i >= to; i--) arr.push(i);
  return arr;
}

export default function RegisterPage() {
  const { palette } = useTheme();

  // Language from localStorage (follows homepage selection)
  const [lang, setLang] = useState<Language>("hu");
  useEffect(() => {
    try {
      const saved =
        typeof window !== "undefined" ? localStorage.getItem("sr_lang") : null;
      if (saved === "en" || saved === "hu") setLang(saved);
    } catch {}
  }, []);
  const t = translations[lang];

  const [submitting, setSubmitting] = useState(false);
  const [form, setForm] = useState({
    email: "",
    countryName: "",
    login: "",
    pwd: "",
    pwdre: "",
    race: "",
    lastName: "",
    firstName: "",
    birthYear: "",
    birthMonth: "1",
    birthDay: "1",
    country: "HUN",
    city: "",
    address: "",
    zip: "",
    mobile: "",
    agreed: false,
  });

  const years = useMemo(() => range(new Date().getFullYear(), 1922), []);
  const months = useMemo(() => range(12, 1).reverse(), []);
  const days = useMemo(() => range(31, 1).reverse(), []);

  const update = (k: keyof typeof form, v: any) =>
    setForm((s) => ({ ...s, [k]: v }));

  const onSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submitting) return;
    // Minimal validáció
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
      alert(
        lang === "hu" ? "Érvénytelen e-mail cím" : "Invalid e-mail address",
      );
      return;
    }
    if (form.countryName.trim().length < 3) {
      alert(
        lang === "hu"
          ? "Az ország név minimum 3 karakter legyen"
          : "Country name must be at least 3 characters",
      );
      return;
    }
    if (form.login.trim().length === 0) {
      alert(lang === "hu" ? "Belépési név kötelező" : "Login name is required");
      return;
    }
    if (
      form.pwd.length < 6 ||
      !/[a-zA-Z]/.test(form.pwd) ||
      !/\d/.test(form.pwd)
    ) {
      alert(
        lang === "hu"
          ? "A jelszó legalább 6 karakter, betűt és számot is tartalmazzon"
          : "Password must be at least 6 characters and contain letters and numbers",
      );
      return;
    }
    if (form.pwd !== form.pwdre) {
      alert(
        lang === "hu" ? "A két jelszó nem egyezik" : "Passwords do not match",
      );
      return;
    }
    if (!form.race) {
      alert(lang === "hu" ? "Válassz fajt" : "Select a race");
      return;
    }
    if (!form.lastName.trim() || !form.firstName.trim()) {
      alert(
        lang === "hu"
          ? "Vezetéknév és keresztnév kötelező"
          : "First and last name are required",
      );
      return;
    }
    if (!form.agreed) {
      alert(
        lang === "hu"
          ? "El kell fogadnod a feltételeket"
          : "You must accept the terms",
      );
      return;
    }

    setSubmitting(true);
    try {
      // Mentés localStorage-be a /game headerhez
      if (typeof window !== "undefined") {
        localStorage.setItem("sr_countryName", form.countryName);
        const raceLabel =
          (
            [
              "",
              "Elf",
              "Élőholt",
              "Törpe",
              "Ember",
              "Sötételf",
              "Ork",
            ] as string[]
          )[Number(form.race) || 0] || "";
        localStorage.setItem("sr_race", String(form.race));
        localStorage.setItem("sr_raceLabel", raceLabel);
        localStorage.setItem("sr_logged_in", "1");
      }

      alert(
        lang === "hu"
          ? "Sikeres regisztráció (demo)"
          : "Registration successful (demo)",
      );
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="dmain">
      <div className="dtop"></div>

      <table className="table1" cellPadding={0} cellSpacing={0} width="100%">
        <tbody>
          <tr valign="top">
            <td width="100%">
              <table className="tekercs" align="center">
                <tbody>
                  <tr>
                    <td height={5} id="tr5"></td>
                  </tr>
                  <tr valign="top">
                    <td width="4%"></td>
                    <td width="92%">
                      <table
                        className="tabla"
                        align="center"
                        cellPadding={1}
                        cellSpacing={0}
                      >
                        <tbody>
                          <tr>
                            <td style={{ textAlign: "center" }}>
                              <Logo />
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <br />
                              <form
                                onSubmit={onSubmit}
                                noValidate
                                id="Register"
                              >
                                <table
                                  className="tabla"
                                  width={600}
                                  cellPadding={0}
                                  cellSpacing={0}
                                >
                                  <tbody>
                                    <tr>
                                      <td align="center"></td>
                                    </tr>
                                    <tr>
                                      <td className="fejlec">
                                        {t.registerTitle}
                                      </td>
                                    </tr>
                                    <tr>
                                      <td
                                        className="szoveg"
                                        align="center"
                                        valign="middle"
                                      >
                                        <center>
                                          <div
                                            className="DarkRow"
                                            style={{ lineHeight: 1.6 }}
                                          >
                                            <br />
                                            {lang === "hu" ? (
                                              <>
                                                Ha a STRATEGA játékban részt
                                                szeretnél venni akkor,
                                                <br />
                                                az alábbi kérdések
                                                megválaszolásával regisztráld
                                                magad.
                                                <br />
                                                Figyelem, saját neved később nem
                                                módosítható!
                                                <br />
                                                Kérjük, a regisztrációs lapon
                                                feltüntetett adatokat saját
                                                érdekedben pontosan töltsd ki!
                                                <br />
                                                Olvasd el jogi és titoktartási
                                                nyilatkozatunkat.
                                                <br />A *-al jelölt mezőket
                                                kötelező kitölteni!
                                                <br />
                                              </>
                                            ) : (
                                              <>
                                                If you wish to participate in
                                                STRATEGA, please register by
                                                answering the questions below.
                                                <br />
                                                Note: Your name cannot be
                                                changed later!
                                                <br />
                                                Please fill out the registration
                                                form accurately for your own
                                                benefit!
                                                <br />
                                                Read our legal and privacy
                                                statements.
                                                <br />
                                                Fields marked with * are
                                                required!
                                                <br />
                                              </>
                                            )}
                                            <br />

                                            <table
                                              className="tabla1"
                                              cellPadding={0}
                                              cellSpacing={3}
                                            >
                                              <tbody>
                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.email}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="email"
                                                      value={form.email}
                                                      onChange={(e) =>
                                                        update(
                                                          "email",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td colSpan={2}>
                                                    <span>
                                                      {lang === "hu"
                                                        ? "Ezen a címeden kapod meg az aktiváláshoz szükséges kódot, és erre a címedre küldjük meg az általad választott értesítéseket is. *** Jelenleg elsősorban a Yahoo, másodsorban a Gmail levelezési szolgáltatók az elfogadottak. ***"
                                                        : "We will send the activation code and your selected notifications to this address. *** Currently Yahoo and Gmail are preferred. ***"}
                                                    </span>
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.countryName}</span>
                                                    <br />(
                                                    {lang === "hu"
                                                      ? "Min. 3 karakter"
                                                      : "Min. 3 characters"}
                                                    )
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="country"
                                                      value={form.countryName}
                                                      onChange={(e) =>
                                                        update(
                                                          "countryName",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td colSpan={2}>
                                                    {lang === "hu"
                                                      ? "A hatályos törvényi szabályozás alapján a jó erkölcs követelményére és a jó hírnév védelmére tekintettel az országnevek tetszőleges megválasztását korlátozzuk. Ennek megfelelően azokat a neveket, amelyek másokra nézve sértőek vagy egyéb módon veszélyeztetik a stratega.hu weboldal rendeltetésszerű használatát, illetve hátrányosan befolyásolják üzleti érdekeit, előzetes figyelmeztetés nélkül módosítjuk, vagy töröljük."
                                                      : "Due to legal regulations and good reputation requirements, country names may be moderated."}
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.loginName}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="login"
                                                      maxLength={20}
                                                      value={form.login}
                                                      onChange={(e) =>
                                                        update(
                                                          "login",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.password}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="password"
                                                      name="pwd"
                                                      maxLength={20}
                                                      value={form.pwd}
                                                      onChange={(e) =>
                                                        update(
                                                          "pwd",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td width="50%">
                                                    <span>
                                                      {t.passwordAgain}
                                                    </span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="password"
                                                      name="pwdre"
                                                      maxLength={20}
                                                      value={form.pwdre}
                                                      onChange={(e) =>
                                                        update(
                                                          "pwdre",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <p>
                                                      <span>{t.race}</span>
                                                    </p>
                                                  </td>
                                                  <td width="50%">
                                                    <select
                                                      name="Race"
                                                      value={form.race}
                                                      onChange={(e) =>
                                                        update(
                                                          "race",
                                                          e.target.value,
                                                        )
                                                      }
                                                    >
                                                      {races.map((r) => (
                                                        <option
                                                          key={r.value}
                                                          value={r.value}
                                                        >
                                                          {r.label}
                                                        </option>
                                                      ))}
                                                    </select>
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.lastName}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="vezeteknev"
                                                      maxLength={80}
                                                      value={form.lastName}
                                                      onChange={(e) =>
                                                        update(
                                                          "lastName",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.firstName}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="keresztnev"
                                                      maxLength={80}
                                                      value={form.firstName}
                                                      onChange={(e) =>
                                                        update(
                                                          "firstName",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.birthDate}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <select
                                                      value={form.birthYear}
                                                      onChange={(e) =>
                                                        update(
                                                          "birthYear",
                                                          e.target.value,
                                                        )
                                                      }
                                                    >
                                                      <option value="">
                                                        {lang === "hu"
                                                          ? "Év"
                                                          : "Year"}
                                                      </option>
                                                      {years.map((y) => (
                                                        <option
                                                          key={y}
                                                          value={String(y)}
                                                        >
                                                          {y}
                                                        </option>
                                                      ))}
                                                    </select>
                                                    <select
                                                      value={form.birthMonth}
                                                      onChange={(e) =>
                                                        update(
                                                          "birthMonth",
                                                          e.target.value,
                                                        )
                                                      }
                                                    >
                                                      {months.map((m) => (
                                                        <option
                                                          key={m}
                                                          value={String(m)}
                                                        >
                                                          {m}
                                                        </option>
                                                      ))}
                                                    </select>
                                                    <select
                                                      value={form.birthDay}
                                                      onChange={(e) =>
                                                        update(
                                                          "birthDay",
                                                          e.target.value,
                                                        )
                                                      }
                                                    >
                                                      {days.map((d) => (
                                                        <option
                                                          key={d}
                                                          value={String(d)}
                                                        >
                                                          {d}
                                                        </option>
                                                      ))}
                                                    </select>
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.country}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <select
                                                      value={form.country}
                                                      onChange={(e) =>
                                                        update(
                                                          "country",
                                                          e.target.value,
                                                        )
                                                      }
                                                    >
                                                      {countries.map((c) => (
                                                        <option
                                                          key={c.value}
                                                          value={c.value}
                                                        >
                                                          {c.label}
                                                        </option>
                                                      ))}
                                                    </select>
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.city}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="varos"
                                                      maxLength={255}
                                                      value={form.city}
                                                      onChange={(e) =>
                                                        update(
                                                          "city",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td width="50%">
                                                    <span>
                                                      {t.billingAddress}
                                                    </span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="cim"
                                                      maxLength={255}
                                                      value={form.address}
                                                      onChange={(e) =>
                                                        update(
                                                          "address",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.zip}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="irsz"
                                                      maxLength={6}
                                                      value={form.zip}
                                                      onChange={(e) =>
                                                        update(
                                                          "zip",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td width="50%">
                                                    <span>{t.mobile}</span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="text"
                                                      name="mobile"
                                                      maxLength={13}
                                                      value={form.mobile}
                                                      onChange={(e) =>
                                                        update(
                                                          "mobile",
                                                          e.target.value,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>

                                                <tr>
                                                  <td width="50%">
                                                    <span>
                                                      {lang === "hu" ? (
                                                        <>
                                                          A{" "}
                                                          <a
                                                            href="/jatekszabaly"
                                                            className="sublink"
                                                            target="_blank"
                                                          >
                                                            játék szabályait
                                                          </a>
                                                          , az{" "}
                                                          <a
                                                            href="/aszf"
                                                            className="sublink"
                                                            target="_blank"
                                                          >
                                                            ÁSZF
                                                          </a>
                                                          -t és az{" "}
                                                          <a
                                                            href="/adatvedelem"
                                                            className="sublink"
                                                            target="_blank"
                                                          >
                                                            adatvédelemi
                                                          </a>{" "}
                                                          nyilatkozatokat
                                                          elolvastam, és
                                                          elfogadom.
                                                        </>
                                                      ) : (
                                                        <>
                                                          I have read and accept
                                                          the{" "}
                                                          <a
                                                            href="/jatekszabaly"
                                                            className="sublink"
                                                            target="_blank"
                                                          >
                                                            game rules
                                                          </a>
                                                          , the{" "}
                                                          <a
                                                            href="/aszf"
                                                            className="sublink"
                                                            target="_blank"
                                                          >
                                                            TOS
                                                          </a>{" "}
                                                          and the{" "}
                                                          <a
                                                            href="/adatvedelem"
                                                            className="sublink"
                                                            target="_blank"
                                                          >
                                                            Privacy Policy
                                                          </a>
                                                          .
                                                        </>
                                                      )}
                                                    </span>
                                                  </td>
                                                  <td width="50%">
                                                    <input
                                                      type="checkbox"
                                                      className="checkbox"
                                                      checked={form.agreed}
                                                      onChange={(e) =>
                                                        update(
                                                          "agreed",
                                                          e.target.checked,
                                                        )
                                                      }
                                                    />
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>

                                            <table align="center">
                                              <tbody>
                                                <tr>
                                                  <td
                                                    className="szoveg"
                                                    style={{ fontSize: 16 }}
                                                  >
                                                    <button
                                                      type="submit"
                                                      className="imgbutton"
                                                      style={{
                                                        padding: "8px 16px",
                                                        background:
                                                          palette.accent,
                                                        color:
                                                          palette.lightText,
                                                        border: `1px solid ${palette.border}`,
                                                        cursor: "pointer",
                                                      }}
                                                    >
                                                      {t.submit}
                                                    </button>
                                                  </td>
                                                  <td></td>
                                                  <td
                                                    className="szoveg"
                                                    style={{ fontSize: 16 }}
                                                  >
                                                    <button
                                                      type="reset"
                                                      className="imgbutton"
                                                      onClick={() =>
                                                        setForm({
                                                          ...form,
                                                          email: "",
                                                          countryName: "",
                                                          login: "",
                                                          pwd: "",
                                                          pwdre: "",
                                                          race: "",
                                                          lastName: "",
                                                          firstName: "",
                                                          birthYear: "",
                                                          birthMonth: "1",
                                                          birthDay: "1",
                                                          country: "HUN",
                                                          city: "",
                                                          address: "",
                                                          zip: "",
                                                          mobile: "",
                                                          agreed: false,
                                                        })
                                                      }
                                                      style={{
                                                        padding: "8px 16px",
                                                        background: "#333",
                                                        color:
                                                          palette.lightText,
                                                        border: `1px solid ${palette.border}`,
                                                        cursor: "pointer",
                                                      }}
                                                    >
                                                      {t.cancel}
                                                    </button>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </center>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </form>
                            </td>
                          </tr>
                          <tr>
                            <td></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                    <td width="4%"></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  );
}
