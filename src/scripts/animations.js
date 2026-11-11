import gsap from "gsap"
import { ScrollTrigger } from "gsap/ScrollTrigger"
import Lenis from "lenis"

/**
 * Transiciones del sitio (GSAP + ScrollTrigger + Lenis).
 *
 * Atributos disponibles en las plantillas:
 *  - .js-split           Título que aparece palabra por palabra (data-split="hero" = entrada inicial del hero).
 *  - [data-reveal]       Aparece desde abajo con fade al entrar en pantalla. data-reveal="pop" = escala suave.
 *  - [data-hero-in]      Elementos del hero que entran después del título.
 *  - .js-img-reveal      Contenedor de imagen que se revela con efecto cortina.
 *  - [data-parallax="N"] Desplazamiento vertical (yPercent) ligado al scroll de su sección.
 *  - [data-line]         Línea que se dibuja de izquierda a derecha.
 *  - [data-inner-parallax] Imagen que se desplaza dentro de su marco al hacer scroll.
 *  - .js-stack-card      Tarjeta sticky apilada (ver .rba-stack-card en index.css).
 *
 * Las animaciones se reinician al salir de pantalla y se repiten al volver a cada sección.
 *
 * Solo corre si <html> tiene la clase "rba-anim" (la agrega functions.php cuando el usuario
 * no pidió movimiento reducido). Sin JS o con movimiento reducido, todo queda visible.
 */

function splitWords(el) {
  const label = el.textContent.trim().replace(/\s+/g, " ")

  const walk = node => {
    Array.from(node.childNodes).forEach(child => {
      if (child.nodeType === Node.TEXT_NODE) {
        const frag = document.createDocumentFragment()
        child.textContent.split(/(\s+)/).forEach(part => {
          if (!part) return
          if (/^\s+$/.test(part)) {
            frag.appendChild(document.createTextNode(" "))
            return
          }
          const outer = document.createElement("span")
          outer.className = "rba-w"
          const inner = document.createElement("span")
          inner.className = "rba-w__i"
          inner.textContent = part
          outer.appendChild(inner)
          frag.appendChild(outer)
        })
        child.replaceWith(frag)
      } else if (child.nodeType === Node.ELEMENT_NODE && !child.matches("svg, br, img")) {
        walk(child)
      }
    })
  }

  walk(el)
  // Los lectores de pantalla leen el título completo, no palabra por palabra.
  el.setAttribute("aria-label", label)
  el.querySelectorAll(".rba-w").forEach(w => w.setAttribute("aria-hidden", "true"))
  return el.querySelectorAll(".rba-w__i")
}

function initSmoothScroll() {
  const lenis = new Lenis({ lerp: 0.1, smoothWheel: true })
  window.rbaLenis = lenis
  lenis.on("scroll", ScrollTrigger.update)
  gsap.ticker.add(time => lenis.raf(time * 1000))
  gsap.ticker.lagSmoothing(0)
}

/*
 * Repetición al revisitar: cada animación se reinicia (oculta) solo cuando su elemento salió
 * por completo de la pantalla, y se vuelve a reproducir al entrar otra vez, en ambas direcciones.
 * Así nunca se "resetea" algo que el usuario está viendo.
 */
const PLAY_START = "top 94%"
const PLAY_END = "bottom 12%"

/*
 * ScrollTrigger calcula posiciones con el layout estático y no sabe que un elemento sticky sigue
 * en pantalla. Para el contenido dentro de un contenedor sticky usamos su columna (el padre del sticky)
 * como referencia: se reinicia solo cuando toda la columna sale, y al volver desde abajo se reproduce
 * cuando asoma el borde inferior de la columna (ahí es donde está el contenido pegado).
 */
function stickyScope(el) {
  for (let n = el.parentElement; n && n !== document.body; n = n.parentElement) {
    if (getComputedStyle(n).position === "sticky") return n.parentElement
  }
  return null
}

function replayOnView(trigger, tl, { start = PLAY_START, end = PLAY_END, onPlay, onReset } = {}) {
  let hidden = true
  const play = () => {
    if (!hidden) return
    hidden = false
    tl.play(0)
    onPlay?.()
  }
  const reset = () => {
    hidden = true
    tl.pause(0)
    onReset?.()
  }

  const scope = stickyScope(trigger)
  if (scope) {
    ScrollTrigger.create({ trigger, start, onEnter: play })
    ScrollTrigger.create({ trigger: scope, start: "top bottom", end: "bottom 15%", onEnterBack: play })
    ScrollTrigger.create({ trigger: scope, start: "top bottom", end: "bottom top", onLeave: reset, onLeaveBack: reset })
    return
  }

  ScrollTrigger.create({ trigger, start, end, onEnter: play, onEnterBack: play })
  ScrollTrigger.create({ trigger, start: "top bottom", end: "bottom top", onLeave: reset, onLeaveBack: reset })
}

function initHero() {
  const title = document.querySelector('.js-split[data-split="hero"]')
  if (!title) return

  const words = splitWords(title)
  gsap.set(words, { yPercent: 115 })
  title.classList.add("is-split")

  const hero = title.closest("section")
  const tl = gsap
    .timeline({ defaults: { ease: "expo.out" } })
    .fromTo("[data-hero-img]", { scale: 1.15 }, { scale: 1, duration: 2.4, ease: "power3.out" }, 0)
    .fromTo("[data-hero-backdrop]", { xPercent: 10, opacity: 0 }, { xPercent: 0, opacity: 0.9, duration: 1.6 }, 0.1)
    .to(words, { yPercent: 0, duration: 1.2, stagger: 0.06 }, 0.15)
    .fromTo("[data-hero-in]", { opacity: 0, y: 26 }, { opacity: 1, y: 0, duration: 1.1, stagger: 0.12 }, 0.45)

  // El hero se reproduce al cargar; se reinicia al salir de pantalla y se repite al volver.
  ScrollTrigger.create({
    trigger: hero,
    start: "top bottom",
    end: "bottom top",
    onLeave: () => tl.pause(0),
    onEnterBack: () => tl.restart(),
  })

  const media = hero.querySelector("[data-hero-media]")
  if (!media) return

  // Leve parallax con el mouse (solo en dispositivos con puntero fino) y al hacer scroll.
  if (window.matchMedia("(pointer: fine)").matches) {
    hero.addEventListener("mousemove", e => {
      const r = hero.getBoundingClientRect()
      const px = (e.clientX - r.left) / r.width - 0.5
      const py = (e.clientY - r.top) / r.height - 0.5
      gsap.to(media, { x: px * -18, y: py * -12, duration: 1.2, ease: "power3.out" })
    })
  }
  gsap.to(media, { yPercent: 8, ease: "none", scrollTrigger: { trigger: hero, start: "top top", end: "bottom top", scrub: true } })
}

function initSplitHeadings() {
  document.querySelectorAll('.js-split:not([data-split="hero"])').forEach(el => {
    const words = splitWords(el)
    const tl = gsap.timeline({ paused: true }).fromTo(words, { yPercent: 115 }, { yPercent: 0, duration: 1.1, ease: "expo.out", stagger: 0.04 })
    el.classList.add("is-split")
    replayOnView(el, tl, { onPlay: () => el.classList.add("is-in"), onReset: () => el.classList.remove("is-in") })
  })
}

/*
 * Reveals por lotes: los elementos que entran juntos se escalonan entre sí.
 * Cada elemento guarda si está "oculto" para no repetir la animación mientras sigue visible.
 */
function batchReveal(selector, from, to) {
  const items = gsap.utils.toArray(selector)
  if (!items.length) return
  const hide = els => {
    gsap.killTweensOf(els)
    gsap.set(els, { ...from, overwrite: true })
    els.forEach(el => (el._rbaHidden = true))
  }
  const show = batch => {
    const els = batch.filter(el => el._rbaHidden)
    if (!els.length) return
    els.forEach(el => (el._rbaHidden = false))
    // Copias: GSAP modifica los objetos de configuración que recibe.
    gsap.fromTo(els, { ...from }, { ...to, stagger: to.stagger ?? 0.1, overwrite: true })
  }

  // Los que están dentro de un contenedor sticky se manejan uno a uno con replayOnView.
  const stuck = items.filter(stickyScope)
  const free = items.filter(el => !stuck.includes(el))
  stuck.forEach(el => {
    const tl = gsap.timeline({ paused: true }).fromTo(el, { ...from }, { ...to })
    replayOnView(el, tl)
  })
  if (!free.length) return

  hide(free)
  ScrollTrigger.batch(free, { start: PLAY_START, end: PLAY_END, onEnter: show, onEnterBack: show })
  ScrollTrigger.batch(free, { start: "top bottom", end: "bottom top", onLeave: hide, onLeaveBack: hide })
}

function initReveals() {
  batchReveal('[data-reveal]:not([data-reveal="pop"])', { opacity: 0, y: 40 }, { opacity: 1, y: 0, duration: 1.1, ease: "expo.out" })
  batchReveal('[data-reveal="pop"]', { opacity: 0, scale: 0.85 }, { opacity: 1, scale: 1, duration: 1, ease: "back.out(1.6)", stagger: 0.07 })
}

function initImageReveals() {
  document.querySelectorAll(".js-img-reveal").forEach(wrap => {
    const media = wrap.querySelector("img, .rba-placeholder")
    const tl = gsap
      .timeline({ paused: true })
      .fromTo(wrap, { clipPath: "inset(100% 0% 0% 0%)" }, { clipPath: "inset(0% 0% 0% 0%)", duration: 1.5, ease: "expo.inOut" })
      .fromTo(media, { scale: 1.3 }, { scale: media.hasAttribute("data-inner-parallax") ? 1.18 : 1, duration: 2, ease: "expo.out" }, 0.2)
    replayOnView(wrap, tl, { start: "top 82%", end: "bottom 18%" })
  })
}

function initParallax() {
  document.querySelectorAll("[data-parallax]").forEach(el => {
    gsap.to(el, {
      yPercent: Number(el.dataset.parallax) || 0,
      ease: "none",
      scrollTrigger: { trigger: el.closest("section") || el, start: "top bottom", end: "bottom top", scrub: true },
    })
  })
}

function initInnerParallax() {
  document.querySelectorAll("[data-inner-parallax]").forEach(img => {
    gsap.fromTo(img, { yPercent: -7 }, {
      yPercent: 7,
      ease: "none",
      scrollTrigger: { trigger: img.parentElement, start: "top bottom", end: "bottom top", scrub: true },
    })
  })
}

/*
 * Tarjetas apiladas (sticky): al acercarse la siguiente, la anterior se encoge, se oscurece y se desenfoca.
 * La clase is-in (dibuja el ícono) se agrega al entrar y se quita cuando la tarjeta sale por completo.
 */
function initStackCards() {
  const cards = gsap.utils.toArray(".js-stack-card")
  cards.forEach((card, i) => {
    const show = () => card.classList.add("is-in")
    const hide = () => card.classList.remove("is-in")
    ScrollTrigger.create({ trigger: card, start: "top 80%", end: "bottom top", onEnter: show, onEnterBack: show })
    ScrollTrigger.create({ trigger: card, start: "top bottom", end: "bottom top", onLeaveBack: hide })

    const next = cards[i + 1]
    if (!next) return
    // brightness en lugar de opacity: la tarjeta sigue opaca y no se transparenta el texto de las de atrás.
    gsap.fromTo(card, { scale: 1, filter: "brightness(1) blur(0px)" }, {
      scale: 0.92,
      filter: "brightness(0.72) blur(1px)",
      ease: "none",
      scrollTrigger: {
        trigger: next,
        start: "top bottom-=80",
        end: () => `top ${parseFloat(getComputedStyle(next).top) || 0}px`,
        scrub: true,
        invalidateOnRefresh: true,
      },
    })
  })
}

function initLines() {
  const lines = gsap.utils.toArray("[data-line]")
  if (!lines.length) return
  const tl = gsap.timeline({ paused: true }).fromTo(lines, { scaleX: 0 }, { scaleX: 1, duration: 0.9, ease: "power2.inOut", stagger: 0.3 })
  replayOnView(lines[0].closest("ol") || lines[0], tl, { start: "top 80%", end: "bottom 20%" })
}

export default function initAnimations() {
  if (!document.documentElement.classList.contains("rba-anim")) return
  window.rbaAnimReady = true

  gsap.registerPlugin(ScrollTrigger)
  initSmoothScroll()
  initHero()
  initSplitHeadings()
  initReveals()
  initImageReveals()
  initParallax()
  initInnerParallax()
  initStackCards()
  initLines()

  // Abrir/cerrar un acordeón cambia la altura de la página: recalcula al terminar su transición.
  let refreshTimer
  document.querySelectorAll("details").forEach(d =>
    d.addEventListener("toggle", () => {
      clearTimeout(refreshTimer)
      refreshTimer = setTimeout(() => ScrollTrigger.refresh(), 450)
    })
  )

  // Las imágenes lazy cambian alturas: recalcula los puntos de disparo cuando todo cargó.
  window.addEventListener("load", () => ScrollTrigger.refresh())
}
