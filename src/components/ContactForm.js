import { useEffect, useRef, useState } from "react"
import Icon from "./Icon"

/**
 * Formulario de contacto progresivo (3 pasos) → POST /wp-json/rba/v1/contact.
 * - Validación por paso con mensajes accesibles (aria-invalid / aria-describedby) y foco al primer error.
 * - Borrador en sessionStorage para no perder datos al recargar (sin el consentimiento).
 * - Eventos form_start / form_success en window.dataLayer si existe (GA4 / GTM, brief §08).
 */

const DRAFT_KEY = "rba-contact-draft"

const STEPS = [
  { title: "Your contact information", fields: ["first_name", "last_name", "email", "phone"] },
  { title: "About your business", fields: ["objective", "decision", "timeline"] },
  { title: "A few more details", fields: ["consent"] },
]

const EMPTY = {
  first_name: "",
  last_name: "",
  email: "",
  phone: "",
  city: "",
  service: "",
  objective: "",
  decision: "",
  timeline: "",
  sales: "",
  age: "",
  message: "",
  consent: false,
  website: "",
}

function readDraft() {
  try {
    return JSON.parse(sessionStorage.getItem(DRAFT_KEY) || "{}")
  } catch (e) {
    return {}
  }
}

function track(event) {
  if (Array.isArray(window.dataLayer)) window.dataLayer.push({ event })
}

function validate(step, v) {
  const e = {}
  if (step === 0) {
    if (!v.first_name.trim()) e.first_name = "Please enter your first name."
    if (!v.last_name.trim()) e.last_name = "Please enter your last name."
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.email.trim())) e.email = "Please enter a valid email address."
    if (v.phone.replace(/\D/g, "").length < 7) e.phone = "Please enter a valid phone number."
  }
  if (step === 1) {
    if (!v.objective) e.objective = "Please choose your main objective."
    if (!v.decision) e.decision = "Please choose an option."
    if (!v.timeline) e.timeline = "Please choose an option."
  }
  if (step === 2 && !v.consent) e.consent = "Please accept the Privacy Policy and Terms & Conditions."
  return e
}

const inputClass = invalid =>
  `mt-2 block w-full rounded-xl border bg-white px-4 py-3 text-base text-ink transition placeholder:text-ink/40 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 focus:outline-none ${
    invalid ? "border-red-600" : "border-gray-300"
  }`

function FieldError({ id, message }) {
  if (!message) return null
  return (
    <p id={id} className="mt-2 text-sm font-medium text-red-700">
      {message}
    </p>
  )
}

function TextField({ name, label, type = "text", required, autoComplete, values, errors, onChange }) {
  const errId = `${name}-error`
  return (
    <div>
      <label htmlFor={`cf-${name}`} className="font-heading text-sm font-semibold text-navy-900">
        {label} {required ? <span className="text-brand-700">*</span> : <span className="font-normal text-ink/50">(optional)</span>}
      </label>
      <input
        id={`cf-${name}`}
        name={name}
        type={type}
        autoComplete={autoComplete}
        value={values[name]}
        onChange={e => onChange(name, e.target.value)}
        aria-invalid={errors[name] ? "true" : undefined}
        aria-describedby={errors[name] ? errId : undefined}
        aria-required={required ? "true" : undefined}
        className={inputClass(errors[name])}
      />
      <FieldError id={errId} message={errors[name]} />
    </div>
  )
}

function ChoiceGroup({ name, legend, options, values, errors, onChange, columns = "sm:grid-cols-2" }) {
  const errId = `${name}-error`
  return (
    <fieldset aria-describedby={errors[name] ? errId : undefined} data-field={name}>
      <legend className="font-heading text-sm font-semibold text-navy-900">
        {legend} <span className="text-brand-700">*</span>
      </legend>
      <div className={`mt-3 grid gap-3 ${columns}`}>
        {options.map(option => {
          const checked = values[name] === option
          return (
            <label
              key={option}
              className={`flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 text-sm transition focus-within:ring-4 focus-within:ring-brand-100 ${
                checked ? "border-brand-500 bg-brand-50 text-navy-900" : "border-gray-300 text-ink/80 hover:border-brand-300"
              }`}
            >
              <input
                type="radio"
                name={name}
                value={option}
                checked={checked}
                onChange={() => onChange(name, option)}
                className="h-4 w-4 accent-brand-700"
                aria-invalid={errors[name] ? "true" : undefined}
              />
              {option}
            </label>
          )
        })}
      </div>
      <FieldError id={errId} message={errors[name]} />
    </fieldset>
  )
}

function SelectField({ name, label, options, placeholder, values, onChange }) {
  return (
    <div>
      <label htmlFor={`cf-${name}`} className="font-heading text-sm font-semibold text-navy-900">
        {label} <span className="font-normal text-ink/50">(optional)</span>
      </label>
      <select id={`cf-${name}`} name={name} value={values[name]} onChange={e => onChange(name, e.target.value)} className={inputClass(false)}>
        <option value="">{placeholder}</option>
        {options.map(o => (typeof o === "string" ? <option key={o}>{o}</option> : <option key={o.value} value={o.value}>{o.label}</option>))}
      </select>
    </div>
  )
}

export default function ContactForm({ props }) {
  const { endpoint, nonce, options, service, privacyUrl, termsUrl, phone, phoneHref, homeUrl } = props
  const [step, setStep] = useState(0)
  const [values, setValues] = useState(() => {
    const draft = readDraft()
    return { ...EMPTY, ...draft, consent: false, website: "", service: service || draft.service || "" }
  })
  const [errors, setErrors] = useState({})
  const [status, setStatus] = useState("idle") // idle | sending | success | error
  const [message, setMessage] = useState("")
  const headingRef = useRef(null)
  const formRef = useRef(null)
  const started = useRef(false)
  const startedAt = useRef(Date.now())
  const firstRender = useRef(true)

  useEffect(() => {
    if (status === "success") return
    try {
      const { consent, website, ...draft } = values
      sessionStorage.setItem(DRAFT_KEY, JSON.stringify(draft))
    } catch (e) {}
  }, [values, status])

  // Al cambiar de paso, el foco va al título del paso (lectores de pantalla y teclado).
  useEffect(() => {
    if (firstRender.current) {
      firstRender.current = false
      return
    }
    headingRef.current?.focus()
  }, [step, status])

  const onChange = (name, value) => {
    if (!started.current) {
      started.current = true
      track("form_start")
    }
    setValues(v => ({ ...v, [name]: value }))
    if (errors[name]) setErrors(e => ({ ...e, [name]: undefined }))
  }

  const focusFirstError = errs => {
    const first = Object.keys(errs)[0]
    if (!first) return
    requestAnimationFrame(() => {
      const el = formRef.current?.querySelector(`#cf-${first}, [data-field="${first}"] input, input[name="${first}"]`)
      el?.focus()
    })
  }

  const next = () => {
    const errs = validate(step, values)
    setErrors(errs)
    if (Object.keys(errs).length) return focusFirstError(errs)
    setStep(s => s + 1)
  }

  const submit = async e => {
    e.preventDefault()
    if (step < STEPS.length - 1) return next()
    const errs = validate(step, values)
    setErrors(errs)
    if (Object.keys(errs).length) return focusFirstError(errs)

    setStatus("sending")
    setMessage("")
    try {
      const res = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...values, _nonce: nonce, elapsed: Date.now() - startedAt.current }),
      })
      const data = await res.json().catch(() => ({}))
      if (res.ok && data.ok) {
        setStatus("success")
        track("form_success")
        try {
          sessionStorage.removeItem(DRAFT_KEY)
        } catch (err) {}
        return
      }
      if (data.fields && Object.keys(data.fields).length) {
        setErrors(data.fields)
        const stepWithError = STEPS.findIndex(s => s.fields.some(f => data.fields[f]))
        if (stepWithError >= 0) setStep(stepWithError)
      }
      setStatus("error")
      setMessage(data.message || `Something went wrong. Please call us at ${phone}.`)
    } catch (err) {
      setStatus("error")
      setMessage(`We could not send your request. Please check your connection or call us at ${phone}.`)
    }
  }

  if (status === "success") {
    return (
      <div className="py-8 text-center" role="status">
        <span className="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-50 text-brand-700">
          <Icon name="check" className="h-8 w-8" />
        </span>
        <h2 ref={headingRef} tabIndex={-1} className="mt-6 font-heading text-2xl font-bold text-navy-900 focus:outline-none">
          {`Thank you, ${values.first_name.trim()}!`}
        </h2>
        <p className="mx-auto mt-3 max-w-md text-ink/75">
          We received your information. A member of our team will contact you soon to find the right next step for your business.
        </p>
        <a href={homeUrl} className="btn btn-outline mt-8">
          Back to Home
        </a>
      </div>
    )
  }

  const fieldProps = { values, errors, onChange }

  return (
    <form ref={formRef} onSubmit={submit} noValidate>
      {/* Progreso */}
      <ol className="flex items-center gap-2" aria-label="Form progress">
        {STEPS.map((s, i) => (
          <li key={s.title} className="flex flex-1 items-center gap-2" aria-current={i === step ? "step" : undefined}>
            <span
              className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-heading text-sm font-semibold transition-colors ${
                i < step ? "bg-brand-700 text-white" : i === step ? "bg-navy-900 text-white" : "bg-gray-100 text-ink/50"
              }`}
            >
              {i < step ? <Icon name="check" className="h-4 w-4" /> : i + 1}
              <span className="sr-only">{`${s.title}${i < step ? " (completed)" : ""}`}</span>
            </span>
            {i < STEPS.length - 1 && <span className={`h-0.5 flex-1 rounded-full transition-colors ${i < step ? "bg-brand-500" : "bg-gray-200"}`} aria-hidden="true" />}
          </li>
        ))}
      </ol>

      <p className="mt-6 text-sm font-medium text-ink/60">{`Step ${step + 1} of ${STEPS.length}`}</p>
      <h2 ref={headingRef} tabIndex={-1} className="mt-1 font-heading text-2xl font-bold text-navy-900 focus:outline-none">
        {STEPS[step].title}
      </h2>

      <div className="mt-8 space-y-6">
        {step === 0 && (
          <>
            <div className="grid gap-6 sm:grid-cols-2">
              <TextField name="first_name" label="First name" required autoComplete="given-name" {...fieldProps} />
              <TextField name="last_name" label="Last name" required autoComplete="family-name" {...fieldProps} />
              <TextField name="email" label="Email" type="email" required autoComplete="email" {...fieldProps} />
              <TextField name="phone" label="Phone number" type="tel" required autoComplete="tel" {...fieldProps} />
            </div>
            <TextField name="city" label="City" autoComplete="address-level2" {...fieldProps} />
          </>
        )}

        {step === 1 && (
          <>
            <SelectField name="service" label="Service of interest" placeholder="I'm not sure yet" options={options.services} {...fieldProps} />
            <ChoiceGroup name="objective" legend="What is the main objective of your business right now?" options={options.objectives} {...fieldProps} />
            <ChoiceGroup name="decision" legend="Do you make the decisions for your company?" options={options.decision} columns="sm:grid-cols-3" {...fieldProps} />
            <ChoiceGroup name="timeline" legend="When would you like to address these challenges?" options={options.timeline} {...fieldProps} />
          </>
        )}

        {step === 2 && (
          <>
            <div className="grid gap-6 sm:grid-cols-2">
              <SelectField name="sales" label={options.salesLabel} placeholder="Select a range" options={options.sales} {...fieldProps} />
              <SelectField name="age" label="How long has your company been in operation?" placeholder="Select an option" options={options.age} {...fieldProps} />
            </div>
            <div>
              <label htmlFor="cf-message" className="font-heading text-sm font-semibold text-navy-900">
                Anything else we should know? <span className="font-normal text-ink/50">(optional)</span>
              </label>
              <textarea
                id="cf-message"
                rows="4"
                value={values.message}
                onChange={e => onChange("message", e.target.value)}
                className={inputClass(false)}
                placeholder="Tell us briefly about your business and what you need help with. Please do not include tax IDs or bank details."
              />
            </div>

            {/* Honeypot: invisible para personas */}
            <div className="hidden" aria-hidden="true">
              <label htmlFor="cf-website">Website</label>
              <input id="cf-website" type="text" tabIndex={-1} autoComplete="off" value={values.website} onChange={e => onChange("website", e.target.value)} />
            </div>

            <div>
              <label className="flex cursor-pointer items-start gap-3 text-sm leading-relaxed text-ink/80">
                <input
                  id="cf-consent"
                  type="checkbox"
                  checked={values.consent}
                  onChange={e => onChange("consent", e.target.checked)}
                  className="mt-1 h-4 w-4 shrink-0 accent-brand-700"
                  aria-invalid={errors.consent ? "true" : undefined}
                  aria-describedby={errors.consent ? "consent-error" : undefined}
                />
                <span>
                  I agree to the website&rsquo;s{" "}
                  <a href={privacyUrl} target="_blank" rel="noopener noreferrer" className="font-semibold text-brand-700 underline underline-offset-2">
                    Privacy Policy
                  </a>{" "}
                  and{" "}
                  <a href={termsUrl} target="_blank" rel="noopener noreferrer" className="font-semibold text-brand-700 underline underline-offset-2">
                    Terms &amp; Conditions
                  </a>
                  . <span className="text-brand-700">*</span>
                </span>
              </label>
              <FieldError id="consent-error" message={errors.consent} />
            </div>
          </>
        )}
      </div>

      {status === "error" && message && (
        <p className="mt-6 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-800" role="alert">
          {message}{" "}
          <a href={phoneHref} className="underline">
            {phone}
          </a>
        </p>
      )}

      <div className="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
        {step > 0 ? (
          <button type="button" onClick={() => setStep(s => s - 1)} className="btn btn-outline">
            <Icon name="arrowRight" className="h-4 w-4 rotate-180" />
            Previous
          </button>
        ) : (
          <span />
        )}
        <button type="submit" className="btn btn-primary" disabled={status === "sending"}>
          {step < STEPS.length - 1 ? "Next Step" : status === "sending" ? "Sending…" : "Submit Request"}
          <Icon name="arrowRight" className="h-4 w-4" />
        </button>
      </div>
    </form>
  )
}
