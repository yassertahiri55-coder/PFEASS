import { useSelector } from 'react-redux';
import { Link } from 'react-router-dom';

export default function Accueil() {
  const user = useSelector(state => state.user.user);

  return (
    <div className="landing-page animate-fade-in">
      {/* Hero Section */}
      <header className="hero-section">
        <div className="hero-overlay"></div>
        <div className="hero-content">
          <div className="hero-badge">
            <span>✨ Plateforme Digitale d'Assurance</span>
          </div>
          <h1 className="hero-title">
            Simplifiez la gestion de vos <span className="text-gradient">Assurances</span> avec PFEASS
          </h1>
          <p className="hero-description">
            Une interface moderne, sécurisée et instantanée pour déclarer vos sinistres, suivre vos dossiers et planifier vos rendez-vous avec nos conseillers en temps réel.
          </p>
          <div className="hero-actions">
            {!user ? (
              <>
                <Link to="/login" className="btn btn-primary">
                  Accéder à mon espace
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" className="w-5 h-5 inline-block ml-1">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                  </svg>
                </Link>
                <Link to="/register" className="btn btn-secondary">
                  Créer un compte
                </Link>
              </>
            ) : (
              <Link to={user.role === 'client' ? "/dashboard-client" : "/dashboard-agent"} className="btn btn-primary">
                Aller au Tableau de bord
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" className="w-5 h-5 inline-block ml-1">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
              </Link>
            )}
          </div>
        </div>
      </header>

      {/* Main Content Sections */}
      <main className="landing-container">
        
        {/* Section 1: Qu'est-ce que l'Assurance & Son Rôle */}
        <section className="info-section alternate-layout">
          <div className="info-text">
            <div className="section-pretitle">Concept Fondamental</div>
            <h2 className="section-title">Qu'est-ce que l'assurance et son rôle ?</h2>
            <p className="section-paragraph">
              L'assurance est un mécanisme financier et juridique qui permet d'associer la solidarité et la prévoyance. C'est un contrat (la police d'assurance) par lequel un individu ou une entreprise bénéficie d'une protection financière et d'une prise en charge des sinistres de la part d'une compagnie d'assurance, en contrepartie du paiement d'une cotisation (la prime).
            </p>
            <p className="section-paragraph">
              Son rôle principal est de **stabiliser et sécuriser** le quotidien face aux aléas de la vie :
            </p>
            <ul className="info-list">
              <li>
                <div className="list-icon">🛡️</div>
                <div>
                  <strong>Sécurité & Protection financière :</strong> Couverture des frais lors d'accidents, de vols, d'incendies ou de problèmes de santé.
                </div>
              </li>
              <li>
                <div className="list-icon">🧘</div>
                <div>
                  <strong>Sérénité d'esprit :</strong> Réduction du stress lié aux risques du quotidien pour les familles et les professionnels.
                </div>
              </li>
              <li>
                <div className="list-icon">🤝</div>
                <div>
                  <strong>Mutualisation des risques :</strong> Les cotisations payées par tous servent à indemniser ceux qui subissent des dommages.
                </div>
              </li>
            </ul>
          </div>
          <div className="info-image-container">
            <img src="/insurance_protection.png" alt="Concept d'Assurance et Protection" className="info-image" />
            <div className="image-glow indigo-glow"></div>
          </div>
        </section>

        {/* Section 2: Notre Application PFEASS */}
        <section className="info-section">
          <div className="info-image-container">
            <img src="/app_mockup.png" alt="Tableau de bord de l'application" className="info-image" />
            <div className="image-glow cyan-glow"></div>
          </div>
          <div className="info-text">
            <div className="section-pretitle">Innovation Digitale</div>
            <h2 className="section-title">Notre Application PFEASS</h2>
            <p className="section-paragraph">
              PFEASS réinvente l'interaction entre les assurés, les agents et les experts d'assurance. Notre but est de numériser entièrement les processus papier, de réduire les temps de traitement et d'offrir une transparence totale à chaque étape.
            </p>
            
            <div className="features-grid">
              <div className="feature-card">
                <div className="feature-icon">📝</div>
                <h3 className="feature-card-title">Déclaration en 2 minutes</h3>
                <p className="feature-card-desc">Saisissez les détails de vos sinistres en ligne et soumettez instantanément les pièces jointes.</p>
              </div>

              <div className="feature-card">
                <div className="feature-icon">🔍</div>
                <h3 className="feature-card-title">Suivi Interactif</h3>
                <p className="feature-card-desc">Visualisez l'avancement de vos dossiers et les rapports rédigés par l'expert en temps réel.</p>
              </div>

              <div className="feature-card">
                <div className="feature-icon">📅</div>
                <h3 className="feature-card-title">Rendez-vous Facilités</h3>
                <p className="feature-card-desc">Planifiez des rendez-vous avec des agents qualifiés directement depuis votre espace personnel.</p>
              </div>

              <div className="feature-card">
                <div className="feature-icon">💬</div>
                <h3 className="feature-card-title">Messagerie Directe</h3>
                <p className="feature-card-desc">Ajoutez des commentaires pour échanger directement avec l'expert en charge de votre dossier.</p>
              </div>
            </div>
          </div>
        </section>

        {/* CTA Banner */}
        <section className="cta-banner">
          <div className="cta-glow"></div>
          <h2 className="cta-title">Prenez le contrôle de vos assurances</h2>
          <p className="cta-description">
            Inscrivez-vous dès maintenant et découvrez la simplicité de la gestion numérique des sinistres.
          </p>
          <div className="cta-buttons">
            {!user ? (
              <>
                <Link to="/register" className="btn btn-primary">Créer un compte</Link>
                <Link to="/login" className="btn btn-outline">Se connecter</Link>
              </>
            ) : (
              <Link to={user.role === 'client' ? "/dashboard-client" : "/dashboard-agent"} className="btn btn-primary">
                Ouvrir mon Tableau de bord
              </Link>
            )}
          </div>
        </section>
      </main>

      {/* Footer */}
      <footer className="landing-footer">
        <p>© 2026 PFEASS Assur. Tous droits réservés. Conçu pour simplifier votre quotidien.</p>
      </footer>
    </div>
  );
}
