import { createRoot } from "react-dom/client"
import Navbar from "./components/Navbar"
import Footer from "./components/Footer"
import ContactForm from "./components/ContactForm"
import CookieConsent from "./components/CookieConsent"
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

const consentEl = document.getElementById("rba-cookie-consent")
if (consentEl) {
  const privacy = (data.legal || []).find(l => /privacy/i.test(l.label))
  createRoot(consentEl).render(<CookieConsent privacyUrl={privacy?.url} />)
}

// Formulario de contacto: sus props vienen en data-props (page-contact-us.php).
const contactEl = document.getElementById("rba-contact-form")
if (contactEl) {
  let props = {}
  try {
    props = JSON.parse(contactEl.dataset.props || "{}")
  } catch (e) {}
  createRoot(contactEl).render(<ContactForm props={props} />)
}

initAnimations()
initHeroSlideshows()
initCarousels()
