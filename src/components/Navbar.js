import { useEffect, useId, useRef, useState } from "react"
import Icon from "./Icon"

function toPath(url) {
  try {
    const path = new URL(url, window.location.origin).pathname
    return path.endsWith("/") ? path : `${path}/`
  } catch (e) {
    return url
  }
}

function isActive(item, current) {
  if (item.external) return false
  return toPath(item.url) === current || (item.children || []).some(child => toPath(child.url) === current)
}

function externalProps(item) {
  return item.external ? { target: "_blank", rel: "noopener noreferrer" } : {}
}

function ExternalHint({ item }) {
  if (!item.external) return null
  return (
    <>
      <Icon name="external" className="h-3.5 w-3.5 opacity-60" />
      <span className="sr-only"> (opens in a new tab)</span>
    </>
  )
}

const desktopLink = active =>
  `relative inline-flex items-center gap-1 py-2 whitespace-nowrap font-heading text-base font-medium transition-colors hover:text-brand-700 focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-700 ${
    active
      ? "text-navy-900 after:absolute after:inset-x-0 after:-bottom-0.5 after:h-0.5 after:rounded-full after:bg-brand-500"
      : "text-ink/80"
  }`

/*
 * Mega menú de "Our Services": panel a todo el ancho bajo el navbar con los servicios agrupados
 * (consultoría / formación) y una tarjeta para quien no sabe por dónde empezar.
 * Se abre al pasar el mouse o con el botón de la flecha; se cierra con Escape, clic fuera o al salir el foco.
 */
const GROUPS = [
  { key: "consulting", title: "Consulting Services", cols: "grid-cols-2" },
  { key: "training", title: "Training & Education", cols: "grid-cols-1" },
]

function MegaMenu({ item, active, current, cta, servicesUrl }) {
  const [open, setOpen] = useState(false)
  const ref = useRef(null)
  const buttonRef = useRef(null)
  const closeTimer = useRef(null)
  const panelId = useId()

  const show = () => {
    clearTimeout(closeTimer.current)
    setOpen(true)
  }
  // Pequeña demora al salir: evita que se cierre al cruzar el espacio entre el link y el panel.
  const hideSoon = () => {
    clearTimeout(closeTimer.current)
    closeTimer.current = setTimeout(() => setOpen(false), 140)
  }

  useEffect(() => () => clearTimeout(closeTimer.current), [])

  useEffect(() => {
    if (!open) return
    const onKey = e => {
      if (e.key === "Escape") {
        setOpen(false)
        buttonRef.current?.focus()
      }
    }
    const onPointer = e => {
      if (ref.current && !ref.current.contains(e.target)) setOpen(false)
    }
    document.addEventListener("keydown", onKey)
    document.addEventListener("pointerdown", onPointer)
    return () => {
      document.removeEventListener("keydown", onKey)
      document.removeEventListener("pointerdown", onPointer)
    }
  }, [open])

  return (
    <li
      ref={ref}
      onMouseEnter={show}
      onMouseLeave={hideSoon}
      onBlur={e => {
        if (!ref.current.contains(e.relatedTarget)) setOpen(false)
      }}
    >
      <div className="flex items-center">
        <a href={item.url} className={desktopLink(active)} aria-current={toPath(item.url) === current ? "page" : undefined}>
          {item.label}
        </a>
        <button
          ref={buttonRef}
          type="button"
          className="ml-0.5 rounded p-1 text-ink/70 transition-colors hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-brand-700"
          aria-expanded={open}
          aria-controls={panelId}
          onClick={() => setOpen(o => !o)}
        >
          <span className="sr-only">{`${open ? "Hide" : "Show"} ${item.label} menu`}</span>
          <Icon name="chevronDown" className={`h-4 w-4 transition-transform duration-300 ${open ? "rotate-180" : ""}`} />
        </button>
      </div>

      {/* Fondo atenuado bajo el panel (solo visual) */}
      <div
        className={`pointer-events-none absolute inset-x-0 top-full h-screen bg-navy-950/25 transition-opacity duration-300 ${open ? "opacity-100" : "opacity-0"}`}
        aria-hidden="true"
      />

      <div
        id={panelId}
        className={`absolute inset-x-0 top-full transition-all duration-300 ease-out before:absolute before:inset-x-0 before:-top-6 before:h-6 ${
          open ? "visible translate-y-0 opacity-100" : "invisible -translate-y-2 opacity-0"
        }`}
      >
        <div className="border-t border-gray-100 bg-white shadow-2xl">
          <div className="mx-auto grid max-w-site grid-cols-12 gap-8 px-10 py-8">
            {GROUPS.map(group => {
              const services = item.children.filter(child => child.group === group.key)
              if (!services.length) return null
              return (
                <div key={group.key} className={group.key === "consulting" ? "col-span-6" : "col-span-3"}>
                  <p className="eyebrow px-3">{group.title}</p>
                  <ul className={`mt-4 grid gap-1 ${group.cols}`}>
                    {services.map(child => {
                      const isCurrent = toPath(child.url) === current
                      return (
                        <li key={child.url}>
                          <a
                            href={child.url}
                            aria-current={isCurrent ? "page" : undefined}
                            className={`group flex gap-3 rounded-xl p-3 transition-colors hover:bg-brand-50 focus-visible:bg-brand-50 focus-visible:outline-2 focus-visible:outline-brand-700 ${
                              isCurrent ? "bg-brand-50" : ""
                            }`}
                          >
                            <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-500 transition-colors group-hover:bg-brand-500 group-hover:text-white">
                              <Icon name={child.icon} className="h-5 w-5" />
                            </span>
                            <span>
                              <span className="block font-heading text-[15px] leading-snug font-semibold text-navy-900 group-hover:text-brand-800">
                                {child.label}
                              </span>
                              <span className="mt-0.5 block text-sm leading-snug text-ink/65">{child.tagline}</span>
                            </span>
                          </a>
                        </li>
                      )
                    })}
                  </ul>
                </div>
              )
            })}

            <div className="relative col-span-3 isolate flex flex-col justify-between overflow-hidden rounded-2xl bg-navy-900 p-6 text-white">
              <div className="rba-grid-pattern" aria-hidden="true" />
              <div>
                <p className="font-heading text-xs font-semibold tracking-[0.25em] text-brand-400 uppercase">Not sure where to start?</p>
                <p className="mt-3 font-heading text-lg leading-snug font-semibold">
                  Book a consultation and we&rsquo;ll recommend the right service for your business.
                </p>
              </div>
              <div className="mt-6 space-y-3">
                {cta && (
                  <a href={cta.url} className="btn btn-primary w-full">
                    <Icon name="calendar" className="h-5 w-5" />
                    {cta.label}
                  </a>
                )}
                {servicesUrl && (
                  <a href={servicesUrl} className="flex items-center justify-center gap-2 font-heading text-sm font-semibold text-white/80 transition-colors hover:text-white">
                    View all services
                    <Icon name="arrowRight" className="h-4 w-4" />
                  </a>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </li>
  )
}

function MobileItem({ item, current, onNavigate }) {
  const [open, setOpen] = useState(false)
  const subId = useId()
  const active = isActive(item, current)
  const base = `flex flex-1 items-center gap-2 rounded-lg px-3 py-3 font-heading text-lg font-medium ${
    active ? "bg-brand-50 text-brand-800" : "text-navy-900 hover:bg-gray-50"
  }`

  if (!item.children) {
    return (
      <li>
        <a href={item.url} className={base} onClick={onNavigate} aria-current={active ? "page" : undefined} {...externalProps(item)}>
          {item.label}
          <ExternalHint item={item} />
        </a>
      </li>
    )
  }

  return (
    <li>
      <div className="flex items-center gap-1">
        <a href={item.url} className={base} onClick={onNavigate}>
          {item.label}
        </a>
        <button
          type="button"
          className="rounded-lg p-3 text-navy-900 hover:bg-gray-50"
          aria-expanded={open}
          aria-controls={subId}
          onClick={() => setOpen(o => !o)}
        >
          <span className="sr-only">{`${open ? "Hide" : "Show"} ${item.label} submenu`}</span>
          <Icon name="chevronDown" className={`h-5 w-5 transition-transform ${open ? "rotate-180" : ""}`} />
        </button>
      </div>
      <ul id={subId} className={`ml-3 border-l-2 border-brand-100 pl-3 ${open ? "block" : "hidden"}`}>
        {item.children.map(child => (
          <li key={child.url}>
            <a
              href={child.url}
              onClick={onNavigate}
              className="block rounded-lg px-3 py-2 text-base text-ink/80 hover:bg-gray-50 hover:text-brand-800"
              aria-current={toPath(child.url) === current ? "page" : undefined}
            >
              {child.label}
            </a>
          </li>
        ))}
      </ul>
    </li>
  )
}

export default function Navbar({ data }) {
  const { homeUrl, logoUrl, siteName, nav = [], cta } = data
  const [menuOpen, setMenuOpen] = useState(false)
  const [scrolled, setScrolled] = useState(false)
  const toggleRef = useRef(null)
  const current = toPath(window.location.href)

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8)
    onScroll()
    window.addEventListener("scroll", onScroll, { passive: true })
    return () => window.removeEventListener("scroll", onScroll)
  }, [])

  // Cierra el menú móvil al pasar a escritorio o con Escape, y bloquea el scroll mientras está abierto.
  useEffect(() => {
    if (!menuOpen) return
    const mq = window.matchMedia("(min-width: 1280px)")
    const onChange = e => e.matches && setMenuOpen(false)
    const onKey = e => {
      if (e.key === "Escape") {
        setMenuOpen(false)
        toggleRef.current?.focus()
      }
    }
    mq.addEventListener("change", onChange)
    document.addEventListener("keydown", onKey)
    document.body.style.overflow = "hidden"
    window.rbaLenis?.stop()
    return () => {
      mq.removeEventListener("change", onChange)
      document.removeEventListener("keydown", onKey)
      document.body.style.overflow = ""
      window.rbaLenis?.start()
    }
  }, [menuOpen])

  return (
    <header className={`relative border-b bg-white transition-shadow ${scrolled ? "border-gray-200 shadow-md" : "border-gray-200"}`}>
      <div className="mx-auto flex h-20 max-w-site items-center justify-between gap-6 px-4 sm:px-6 lg:px-10">
        <a href={homeUrl} className="shrink-0 rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-700">
          {logoUrl ? (
            <img src={logoUrl} alt={siteName} className="h-[55px] w-auto" width="165" height="55" />
          ) : (
            <span className="font-heading text-xl font-bold text-navy-900">{siteName}</span>
          )}
        </a>

        <nav aria-label="Main" className="hidden xl:block">
          <ul className="flex items-center gap-7 2xl:gap-9">
            {nav.map(item =>
              item.children ? (
                <MegaMenu key={item.url} item={item} active={isActive(item, current)} current={current} cta={cta} servicesUrl={data.servicesUrl} />
              ) : (
                <li key={item.url}>
                  <a
                    href={item.url}
                    className={desktopLink(isActive(item, current))}
                    aria-current={isActive(item, current) ? "page" : undefined}
                    {...externalProps(item)}
                  >
                    {item.label}
                    <ExternalHint item={item} />
                  </a>
                </li>
              )
            )}
          </ul>
        </nav>

        <div className="flex items-center gap-2">
          {cta && (
            <a href={cta.url} className="btn btn-primary hidden px-5 text-base sm:inline-flex">
              <Icon name="calendar" className="h-5 w-5" />
              {cta.label}
            </a>
          )}
          <button
            ref={toggleRef}
            type="button"
            className="rounded-lg p-2 text-navy-900 hover:bg-gray-100 focus-visible:outline-2 focus-visible:outline-brand-700 xl:hidden"
            aria-expanded={menuOpen}
            aria-controls="rba-mobile-menu"
            onClick={() => setMenuOpen(o => !o)}
          >
            <span className="sr-only">{menuOpen ? "Close menu" : "Open menu"}</span>
            <Icon name={menuOpen ? "close" : "menu"} className="h-7 w-7" />
          </button>
        </div>
      </div>

      <div
        id="rba-mobile-menu"
        className={`absolute inset-x-0 top-full max-h-[calc(100dvh_-_7.5rem)] overflow-y-auto border-t border-gray-100 bg-white shadow-lg xl:hidden ${
          menuOpen ? "block" : "hidden"
        }`}
      >
        <nav aria-label="Mobile" className="px-4 py-4 sm:px-6">
          <ul className="space-y-1">
            {nav.map(item => (
              <MobileItem key={item.url} item={item} current={current} onNavigate={() => setMenuOpen(false)} />
            ))}
          </ul>
          {cta && (
            <a href={cta.url} className="btn btn-primary mt-4 w-full">
              <Icon name="calendar" className="h-5 w-5" />
              {cta.label}
            </a>
          )}
        </nav>
      </div>
    </header>
  )
}
