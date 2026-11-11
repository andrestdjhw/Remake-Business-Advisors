import { useEffect, useId, useRef, useState } from "react"
import Icon from "./Icon"

/**
 * Aviso de cookies con preferencias por categoría.
 *
 * - "Accept all" y "Reject non-essential" tienen el mismo peso visual (sin patrones oscuros).
 * - Guarda la elección en localStorage + cookie "rba_consent" (180 días) y la versión de la política.
 * - Actualiza Google Consent Mode v2 (los valores "denied" por defecto se fijan en el <head>, functions.php).
 * - Emite window "rba:consent" con la elección; se reabre con el evento "rba:open-consent" (link del footer).
 */

export const CONSENT_KEY = "rba-consent"
export const CONSENT_VERSION = 1
const MAX_AGE_DAYS = 180

const CATEGORIES = [
  {
    key: "necessary",
    title: "Necessary",
    text: "Required for the website to work, such as security and remembering your cookie choices. They cannot be turned off.",
    locked: true,
  },
  {
    key: "analytics",
    title: "Analytics",
    text: "Help us understand how visitors use the website so we can improve it. Information is collected in aggregate.",
  },
  {
    key: "marketing",
    title: "Marketing",
    text: "Used to measure our campaigns and show relevant content on other platforms.",
  },
]

export function readConsent() {
  try {
    const saved = JSON.parse(localStorage.getItem(CONSENT_KEY) || "null")
    return saved && saved.v === CONSENT_VERSION ? saved : null
  } catch (e) {
    return null
  }
}

function saveConsent(choice) {
  const record = { v: CONSENT_VERSION, necessary: true, analytics: !!choice.analytics, marketing: !!choice.marketing, ts: new Date().toISOString() }
  try {
    localStorage.setItem(CONSENT_KEY, JSON.stringify(record))
  } catch (e) {}
  const value = `v${CONSENT_VERSION}|analytics:${record.analytics ? 1 : 0}|marketing:${record.marketing ? 1 : 0}`
  document.cookie = `rba_consent=${encodeURIComponent(value)}; Max-Age=${MAX_AGE_DAYS * 86400}; Path=/; SameSite=Lax${location.protocol === "https:" ? "; Secure" : ""}`

  if (typeof window.gtag === "function") {
    window.gtag("consent", "update", {
      analytics_storage: record.analytics ? "granted" : "denied",
      ad_storage: record.marketing ? "granted" : "denied",
      ad_user_data: record.marketing ? "granted" : "denied",
      ad_personalization: record.marketing ? "granted" : "denied",
    })
  }
  if (Array.isArray(window.dataLayer)) window.dataLayer.push({ event: "consent_update", consent: record })
  window.dispatchEvent(new CustomEvent("rba:consent", { detail: record }))
  return record
}

function Toggle({ id, checked, disabled, onChange, label }) {
  return (
    <button
      id={id}
      type="button"
      role="switch"
      aria-checked={checked}
      aria-label={label}
      disabled={disabled}
      onClick={() => onChange(!checked)}
      className={`relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-700 ${
        checked ? "bg-brand-700" : "bg-gray-300"
      } ${disabled ? "cursor-not-allowed opacity-60" : "cursor-pointer"}`}
    >
      <span className={`inline-block h-5 w-5 rounded-full bg-white shadow transition-transform ${checked ? "translate-x-5.5" : "translate-x-0.5"}`} />
    </button>
  )
}

export default function CookieConsent({ privacyUrl }) {
  const [open, setOpen] = useState(false)
  const [visible, setVisible] = useState(false) // controla la animación de entrada/salida
  const [customize, setCustomize] = useState(false)
  const [prefs, setPrefs] = useState({ analytics: false, marketing: false })
  const titleId = useId()
  const descId = useId()
  const panelRef = useRef(null)
  const returnFocus = useRef(null)

  const show = (withPanel = false) => {
    const saved = readConsent()
    setPrefs({ analytics: !!saved?.analytics, marketing: !!saved?.marketing })
    setCustomize(withPanel)
    setOpen(true)
    requestAnimationFrame(() => requestAnimationFrame(() => setVisible(true)))
  }

  const close = () => {
    setVisible(false)
    setTimeout(() => {
      setOpen(false)
      returnFocus.current?.focus?.()
      returnFocus.current = null
    }, 350)
  }

  // Primera visita: aparece tras un instante para no competir con la carga del hero.
  useEffect(() => {
    if (readConsent()) return
    const t = setTimeout(() => show(false), 900)
    return () => clearTimeout(t)
  }, [])

  // Reabrir desde "Cookie Settings" del footer: abre directo en preferencias y enfoca el panel.
  useEffect(() => {
    const onOpen = () => {
      returnFocus.current = document.activeElement
      show(true)
      setTimeout(() => panelRef.current?.focus(), 60)
    }
    window.addEventListener("rba:open-consent", onOpen)
    return () => window.removeEventListener("rba:open-consent", onOpen)
  }, [])

  const decide = choice => {
    saveConsent(choice)
    close()
  }

  if (!open) return null

  return (
    <div
      ref={panelRef}
      role="dialog"
      aria-modal="false"
      aria-labelledby={titleId}
      aria-describedby={descId}
      tabIndex={-1}
      className={`fixed right-3 bottom-3 left-3 z-[60] max-h-[calc(100dvh-1.5rem)] overflow-y-auto rounded-2xl bg-white/95 p-5 shadow-2xl ring-1 ring-black/10 backdrop-blur-xl transition-all duration-300 ease-out focus:outline-none sm:right-auto sm:bottom-6 sm:left-6 sm:max-w-[30rem] sm:p-6 motion-reduce:transition-none print:hidden ${
        visible ? "translate-y-0 opacity-100" : "translate-y-6 opacity-0"
      }`}
    >
      <div className="flex items-start gap-4">
        <span className="hidden h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-500 sm:flex" aria-hidden="true">
          <svg className="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" strokeLinecap="round" strokeLinejoin="round">
            <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5" />
            <path d="M8.5 8.5v.01M16 15.5v.01M12 12v.01M11 17v.01M7 14v.01" />
          </svg>
        </span>
        <div className="min-w-0">
          <h2 id={titleId} className="font-heading text-lg font-semibold text-navy-900">
            We value your privacy
          </h2>
          <p id={descId} className="mt-1.5 text-sm leading-relaxed text-ink/75">
            We use necessary cookies to make this website work. With your permission, we also use analytics and marketing cookies to improve your
            experience. You can change your choice at any time.{" "}
            {privacyUrl && (
              <a href={privacyUrl} className="font-semibold text-brand-700 underline underline-offset-2 hover:text-brand-800">
                Privacy Policy
              </a>
            )}
          </p>
        </div>
      </div>

      {customize && (
        <ul className="mt-5 space-y-3" aria-label="Cookie categories">
          {CATEGORIES.map(cat => {
            const id = `consent-${cat.key}`
            const checked = cat.locked ? true : prefs[cat.key]
            return (
              <li key={cat.key} className="flex items-start justify-between gap-4 rounded-xl bg-cream p-4 ring-1 ring-brand-100">
                <div>
                  <label htmlFor={id} className="font-heading text-sm font-semibold text-navy-900">
                    {cat.title} {cat.locked && <span className="font-normal text-ink/50">(always on)</span>}
                  </label>
                  <p className="mt-1 text-xs leading-relaxed text-ink/70">{cat.text}</p>
                </div>
                <Toggle
                  id={id}
                  label={`${cat.title} cookies`}
                  checked={checked}
                  disabled={cat.locked}
                  onChange={value => setPrefs(p => ({ ...p, [cat.key]: value }))}
                />
              </li>
            )
          })}
        </ul>
      )}

      <div className="mt-5 grid gap-2 sm:grid-cols-2">
        {customize ? (
          <>
            <button type="button" className="btn btn-outline px-4" onClick={() => decide({ analytics: false, marketing: false })}>
              Reject non-essential
            </button>
            <button type="button" className="btn btn-primary px-4" onClick={() => decide(prefs)}>
              Save preferences
            </button>
          </>
        ) : (
          <>
            <button type="button" className="btn btn-outline px-4" onClick={() => decide({ analytics: false, marketing: false })}>
              Reject non-essential
            </button>
            <button type="button" className="btn btn-primary px-4" onClick={() => decide({ analytics: true, marketing: true })}>
              Accept all
            </button>
          </>
        )}
      </div>

      <div className="mt-3 flex justify-center">
        {customize ? (
          <button type="button" className="text-sm font-semibold text-brand-700 hover:text-brand-800" onClick={() => decide({ analytics: true, marketing: true })}>
            Accept all
          </button>
        ) : (
          <button type="button" className="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-900 hover:text-brand-700" onClick={() => setCustomize(true)} aria-expanded={customize}>
            Customize
            <Icon name="chevronDown" className="h-4 w-4" />
          </button>
        )}
      </div>
    </div>
  )
}
