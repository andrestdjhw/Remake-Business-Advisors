/**
 * Carrusel horizontal para [data-carousel] (scroll nativo con snap).
 *
 * - Botones anterior/siguiente: avanzan una "página" de tarjetas visibles y se deshabilitan en los extremos.
 * - Barra de progreso [data-carousel-progress] según la posición del scroll.
 * - Flechas del teclado cuando el carril tiene el foco.
 * - Arrastre con el mouse en escritorio (en táctil y trackpad se usa el scroll nativo).
 * Los botones se buscan en la misma <section>, porque viven en el encabezado.
 */
export default function initCarousels() {
  document.querySelectorAll("[data-carousel]").forEach(setup)
}

function setup(root) {
  const track = root.querySelector("[data-carousel-track]")
  if (!track) return
  const scope = root.closest("section") || root
  const prev = scope.querySelector("[data-carousel-prev]")
  const next = scope.querySelector("[data-carousel-next]")
  const progress = root.querySelector("[data-carousel-progress]")
  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches

  const step = () => {
    const item = track.querySelector("li")
    if (!item) return track.clientWidth
    const gap = parseFloat(getComputedStyle(track).columnGap) || 0
    const perPage = Math.max(1, Math.floor((track.clientWidth + gap) / (item.offsetWidth + gap)) - 1 || 1)
    return perPage * (item.offsetWidth + gap)
  }

  const scrollByPage = dir => track.scrollBy({ left: dir * step(), behavior: reduced ? "auto" : "smooth" })

  const update = () => {
    const max = track.scrollWidth - track.clientWidth
    if (prev) prev.disabled = track.scrollLeft <= 2
    if (next) next.disabled = track.scrollLeft >= max - 2
    if (progress) {
      const visible = track.clientWidth / track.scrollWidth
      const ratio = max > 0 ? track.scrollLeft / max : 1
      progress.style.transform = `scaleX(${visible + (1 - visible) * ratio})`
    }
  }

  prev?.addEventListener("click", () => scrollByPage(-1))
  next?.addEventListener("click", () => scrollByPage(1))
  track.addEventListener("scroll", update, { passive: true })
  window.addEventListener("resize", update)

  track.addEventListener("keydown", e => {
    if (e.target !== track) return
    if (e.key === "ArrowRight") {
      e.preventDefault()
      scrollByPage(1)
    } else if (e.key === "ArrowLeft") {
      e.preventDefault()
      scrollByPage(-1)
    }
  })

  // Arrastre con mouse: si se movió más de 5px, se cancela el clic en el enlace de la tarjeta.
  let startX = 0
  let startScroll = 0
  let dragging = false
  let moved = false

  track.addEventListener("pointerdown", e => {
    if (e.pointerType !== "mouse" || e.button !== 0) return
    dragging = true
    moved = false
    startX = e.clientX
    startScroll = track.scrollLeft
  })
  window.addEventListener("pointermove", e => {
    if (!dragging) return
    const dx = e.clientX - startX
    if (!moved && Math.abs(dx) > 5) {
      moved = true
      track.classList.add("is-dragging")
    }
    if (moved) track.scrollLeft = startScroll - dx
  })
  const endDrag = () => {
    if (!dragging) return
    dragging = false
    if (!moved) return
    track.classList.remove("is-dragging")
    // Al soltar, vuelve a encajar en la tarjeta más cercana.
    const item = track.querySelector("li")
    const gap = parseFloat(getComputedStyle(track).columnGap) || 0
    const size = item ? item.offsetWidth + gap : 1
    track.scrollTo({ left: Math.round(track.scrollLeft / size) * size, behavior: reduced ? "auto" : "smooth" })
  }
  window.addEventListener("pointerup", endDrag)
  window.addEventListener("pointercancel", endDrag)
  track.addEventListener(
    "click",
    e => {
      if (moved) {
        e.preventDefault()
        e.stopPropagation()
        moved = false
      }
    },
    true
  )

  update()
  window.addEventListener("load", update)
}
