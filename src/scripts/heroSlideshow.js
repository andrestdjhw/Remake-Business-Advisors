/**
 * Slideshow con crossfade para [data-slideshow].
 *
 * El tiempo lo marca la animación CSS del punto activo (.rba-dot__fill): al terminar, pasa a la
 * siguiente foto. Pausar es solo poner [data-paused], que congela esa animación y el zoom.
 *
 * Se pausa: con el botón (WCAG 2.2.2), al pasar el mouse o enfocar un control, cuando el slideshow
 * sale de pantalla y cuando la pestaña está oculta. Con movimiento reducido no avanza solo.
 */
export default function initHeroSlideshows() {
  document.querySelectorAll("[data-slideshow]").forEach(setup)
}

function setup(root) {
  const slides = [...root.querySelectorAll("[data-slide]")]
  const dots = [...root.querySelectorAll("[data-slide-dot]")]
  const toggle = root.querySelector("[data-slide-toggle]")
  const toggleLabel = root.querySelector("[data-slide-toggle-label]")
  if (slides.length < 2) return

  const FADE_MS = 1300
  const leaving = new Map()
  let index = 0
  const state = { user: false, hover: false, offscreen: false, hidden: document.hidden }

  const sync = () => {
    root.toggleAttribute("data-paused", state.user || state.hover || state.offscreen || state.hidden)
    root.toggleAttribute("data-user-paused", state.user)
    if (toggleLabel) toggleLabel.textContent = state.user ? "Play slideshow" : "Pause slideshow"
  }

  const go = next => {
    if (next === index) return
    const prev = slides[index]
    const incoming = slides[next]

    // La foto saliente conserva su zoom mientras se desvanece (evita un salto de escala).
    prev.classList.remove("is-active")
    prev.classList.add("is-leaving")
    prev.setAttribute("aria-hidden", "true")
    clearTimeout(leaving.get(prev))
    leaving.set(prev, setTimeout(() => prev.classList.remove("is-leaving"), FADE_MS))

    clearTimeout(leaving.get(incoming))
    incoming.classList.remove("is-leaving")
    incoming.classList.add("is-active")
    incoming.removeAttribute("aria-hidden")

    dots[index]?.classList.remove("is-active")
    dots[index]?.removeAttribute("aria-current")
    dots[next]?.classList.add("is-active")
    dots[next]?.setAttribute("aria-current", "true")

    index = next
  }

  dots.forEach((dot, i) => {
    dot.addEventListener("click", () => go(i))
    dot.querySelector(".rba-dot__fill")?.addEventListener("animationend", () => {
      if (dot.classList.contains("is-active")) go((i + 1) % slides.length)
    })
  })

  toggle?.addEventListener("click", () => {
    state.user = !state.user
    sync()
  })

  root.addEventListener("mouseenter", () => ((state.hover = true), sync()))
  root.addEventListener("mouseleave", () => ((state.hover = false), sync()))
  root.addEventListener("focusin", () => ((state.hover = true), sync()))
  root.addEventListener("focusout", e => {
    if (!root.contains(e.relatedTarget)) {
      state.hover = false
      sync()
    }
  })

  document.addEventListener("visibilitychange", () => ((state.hidden = document.hidden), sync()))

  if ("IntersectionObserver" in window) {
    new IntersectionObserver(([entry]) => {
      state.offscreen = !entry.isIntersecting
      sync()
    }).observe(root)
  }

  sync()
}
