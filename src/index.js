import { createRoot } from "react-dom/client"
import Navbar from "./components/Navbar"
import Footer from "./components/Footer"
import initAnimations from "./scripts/animations"
import initHeroSlideshows from "./scripts/heroSlideshow"
import initCarousels from "./scripts/carousel"

// Datos del sitio inyectados desde PHP (inc/site-data.php → rba_react_data()).
const data = window.rbaData || {}

// Monta un componente de React en el elemento con el id indicado, si existe en la página.
function mount(id, Component) {
  const el = document.getElementById(id)
  if (el) createRoot(el).render(<Component data={data} />)
}

mount("rba-navbar", Navbar)
mount("rba-footer", Footer)

initAnimations()
initHeroSlideshows()
initCarousels()
