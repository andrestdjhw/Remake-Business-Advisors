import Icon from "./Icon"

function FooterLinks({ title, links }) {
  return (
    <div>
      <h2 className="font-heading text-sm font-semibold text-white">{title}</h2>
      <ul className="mt-4 space-y-2.5">
        {links.map(link => (
          <li key={link.url}>
            <a
              href={link.url}
              className="inline-flex items-center gap-1.5 text-sm text-white/70 transition-colors hover:text-brand-300"
              {...(link.external ? { target: "_blank", rel: "noopener noreferrer" } : {})}
            >
              {link.label}
              {link.external && (
                <>
                  <Icon name="external" className="h-3.5 w-3.5" />
                  <span className="sr-only"> (opens in a new tab)</span>
                </>
              )}
            </a>
          </li>
        ))}
      </ul>
    </div>
  )
}

export default function Footer({ data }) {
  const { homeUrl, logoUrl, siteName, nav = [], services = [], cta, socials = [], legal = [] } = data
  const quickLinks = nav.map(({ label, url, external }) => ({ label, url, external }))
  const year = new Date().getFullYear()

  return (
    <footer className="relative overflow-hidden bg-navy-950 text-white">
      <div className="pointer-events-none absolute -bottom-40 -left-24 h-80 w-80 rounded-full border-[2rem] border-brand-500/15" aria-hidden="true" />

      <div className="relative mx-auto grid max-w-site gap-10 px-4 py-16 sm:grid-cols-2 sm:px-6 lg:grid-cols-[1.4fr_1fr_1.3fr_1.2fr] lg:px-10">
        <div>
          <a href={homeUrl} className="inline-block rounded-lg bg-white px-4 py-3">
            {logoUrl ? <img src={logoUrl} alt={siteName} className="h-10 w-auto" width="120" height="40" loading="lazy" /> : siteName}
          </a>
          <p className="mt-5 max-w-xs text-sm leading-relaxed text-white/70">
            Business consulting and education to help entrepreneurs diagnose, structure, and grow their companies.
          </p>
        </div>

        <FooterLinks title="Quick Links" links={quickLinks} />
        <FooterLinks title="Our Services" links={services} />

        <div>
          <h2 className="font-heading text-sm font-semibold text-white">Let&rsquo;s Connect</h2>
          <p className="mt-4 text-sm leading-relaxed text-white/70">Ready to take the next step? Tell us about your business.</p>
          {cta && (
            <a href={cta.url} className="btn btn-primary mt-5">
              {cta.label}
              <Icon name="arrowRight" className="h-4 w-4" />
            </a>
          )}
          {socials.length > 0 && (
            <ul className="mt-6 flex gap-3">
              {socials.map(social => (
                <li key={social.network}>
                  <a
                    href={social.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white transition-colors hover:bg-brand-500"
                  >
                    <Icon name={social.network} className="h-5 w-5" />
                    <span className="sr-only">{`${social.label} (opens in a new tab)`}</span>
                  </a>
                </li>
              ))}
            </ul>
          )}
        </div>
      </div>

      <div className="relative border-t border-white/10">
        <div className="mx-auto flex max-w-site flex-col gap-3 px-4 py-6 text-xs text-white/60 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-10">
          <p>{`© ${year} ${siteName}. All rights reserved.`}</p>
          {legal.length > 0 && (
            <ul className="flex gap-5">
              {legal.map(link => (
                <li key={link.url}>
                  <a href={link.url} className="hover:text-white">
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          )}
        </div>
      </div>
    </footer>
  )
}
