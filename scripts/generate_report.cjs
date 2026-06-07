const PDFDocument = require('pdfkit');
const fs = require('fs');
const path = require('path');

// Initialiser le document PDF (A4 avec marges de 50pt)
const doc = new PDFDocument({ 
  size: 'A4', 
  margins: { top: 60, bottom: 60, left: 50, right: 50 },
  bufferPages: true 
});

const outputPath = path.join(__dirname, '..', 'Rapport_Projet_PFEASS.pdf');
const stream = fs.createWriteStream(outputPath);
doc.pipe(stream);

// Couleurs du Design System (Cyber Dark & Corporate)
const colors = {
  primary: '#4f46e5',   // Indigo
  secondary: '#06b6d4', // Cyan
  darkText: '#0f172a',  // Slate 900
  lightText: '#475569', // Slate 600
  divider: '#e2e8f0',   // Slate 200
  accentBg: '#f8fafc'   // Slate 50
};

// ==========================================
// 1. PAGE DE GARDE (COVER PAGE)
// ==========================================
doc.rect(0, 0, doc.page.width, 25).fill(colors.primary); // Barre colorée supérieure

doc.moveDown(5);

// Titre du Rapport
doc.font('Helvetica-Bold')
   .fontSize(28)
   .fillColor(colors.primary)
   .text("RAPPORT DE PROJET DE FIN D'ÉTUDES", { align: 'center' });

doc.moveDown(0.5);

// Sous-titre
doc.font('Helvetica')
   .fontSize(14)
   .fillColor(colors.lightText)
   .text("Plateforme Digitale de Gestion de Sinistres et Dossiers d'Assurance", { align: 'center' });

doc.moveDown(2);

// Ligne de séparation décorative
doc.strokeColor(colors.secondary)
   .lineWidth(3)
   .moveTo(150, doc.y)
   .lineTo(doc.page.width - 150, doc.y)
   .stroke();

doc.moveDown(4);

// Nom de l'application
doc.font('Helvetica-Bold')
   .fontSize(36)
   .fillColor(colors.darkText)
   .text("PFEASS Assur", { align: 'center' });

doc.moveDown(5);

// Détails du projet / Auteurs
doc.font('Helvetica-Bold')
   .fontSize(12)
   .fillColor(colors.darkText)
   .text("Présenté par :", 100, doc.y)
   .font('Helvetica')
   .fontSize(12)
   .fillColor(colors.lightText)
   .text("Équipe de Développement PFEASS", 100, doc.y + 15)
   
   .font('Helvetica-Bold')
   .fontSize(12)
   .fillColor(colors.darkText)
   .text("Technologies Utilisées :", 350, doc.y - 15)
   .font('Helvetica')
   .fontSize(11)
   .fillColor(colors.lightText)
   .text("• Backend : Laravel v13.7 (PHP)\n• Frontend : React SPA & Redux\n• Base de données : MySQL\n• Styles : Cyber Dark System CSS", 350, doc.y);

// Pied de page de la page de garde
doc.font('Helvetica-Bold')
   .fontSize(11)
   .fillColor(colors.primary)
   .text("Année Universitaire 2025 - 2026", 50, doc.page.height - 80, { align: 'center' });

doc.addPage();

// ==========================================
// 2. INTRODUCTION & CONTEXTE
// ==========================================
renderHeader("1. Introduction & Contexte du Projet");

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("1.1 Contexte Général", 50, doc.y + 10);
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText).lineGap(4)
   .text("Le secteur des assurances fait face aujourd'hui à un besoin pressant de modernisation et de digitalisation. Les procédures traditionnelles de déclaration de sinistres, d'évaluation des dossiers et d'échanges d'informations impliquent souvent des processus papier lourds, des délais de traitement importants et un manque de transparence pour l'assuré.", { align: 'justify' });

doc.moveDown(1);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("1.2 Problématique et Solution PFEASS");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Comment optimiser la communication entre l'assuré, l'agent d'assurance et l'expert pour réduire les délais de traitement des sinistres tout en renforçant la confiance ?\n\nNotre application, PFEASS, répond à cette problématique en proposant une plateforme web collaborative structurée en deux environnements distincts :", { align: 'justify' });

doc.moveDown(0.5);
renderBulletPoint("Une interface client (React SPA) :", "permettant de soumettre des sinistres en ligne, de téléverser les justificatifs nécessaires, de consulter ses dossiers en temps réel et de réserver des rendez-vous.");
renderBulletPoint("Un espace de gestion (Laravel / React) :", "dédié aux agents d'assurance et aux experts pour analyser les pièces, rédiger des rapports d'expertise, programmer des visites de sinistres et interagir directement avec l'assuré.");

doc.moveDown(2);

// Encadré Info
renderCallout("Objectif Majeur", "Automatiser les flux de travail et offrir un canal de communication instantané et sécurisé pour toutes les parties prenantes du contrat d'assurance.");

doc.addPage();

// ==========================================
// 3. ARCHITECTURE TECHNIQUE
// ==========================================
renderHeader("2. Architecture Technique & Choix Technologiques");

doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("L'application PFEASS repose sur un modèle d'architecture découplée moderne garantissant robustesse, flexibilité et extensibilité.", { align: 'justify' });

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("2.1 Le Backend : Laravel v13");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Laravel a été choisi pour concevoir le moteur applicatif et les APIs sécurisées du système. Ses atouts clés sont :\n" +
         "• Authentification sécurisée par jetons d'API (Laravel Sanctum).\n" +
         "• Architecture MVC robuste avec des API Resources prêtes pour le JSON.\n" +
         "• Système de migration de base de données fluide (MySQL).\n" +
         "• Traitement en arrière-plan optimisé (écouteurs de files d'attente pour notifications).", { align: 'justify' });

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("2.2 Le Frontend : React SPA");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Pour fournir une expérience utilisateur fluide digne des standards modernes, la partie utilisateur de PFEASS est entièrement développée en React.js :\n" +
         "• Routage dynamique côté client grâce à React Router DOM.\n" +
         "• Gestion d'état global centralisée avec Redux Toolkit (Slices pour l'utilisateur).\n" +
         "• Appels d'API asynchrones gérés par Axios pour communiquer avec le backend Laravel.\n" +
         "• Design System personnalisé reposant sur le concept du verre poli (Glassmorphism) et du style 'Cyber Dark'.", { align: 'justify' });

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("2.3 Modèle Conceptuel des Données (Base de données)");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("La structure SQL s'articule autour de plusieurs tables interconnectées :\n" +
         "• Users : Gestion des profils (Clients, Agents, Experts, Administrateurs).\n" +
         "• Sinistres : Enregistrement des incidents déclarés par les assurés.\n" +
         "• Dossiers : Regroupement et validation des pièces justificatives.\n" +
         "• Documents : Fichiers téléversés (cartes grises, constats, devis).\n" +
         "• Rendez-vous : Sessions planifiées entre agents et clients.\n" +
         "• Commentaires : Journal d'échanges d'informations.", { align: 'justify' });

doc.addPage();

// ==========================================
// 4. FONCTIONNALITÉS DU SYSTÈME
// ==========================================
renderHeader("3. Fonctionnalités Détaillées du Système");

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("3.1 L'Espace Client (Interface React)", 50, doc.y + 10);
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("L'espace assuré est conçu pour maximiser l'autonomie du client dans la gestion de ses sinistres :", { align: 'justify' });

renderBulletPoint("Déclaration Simplifiée :", "Formulaire intuitif pour décrire l'incident (type, date, description) et attacher instantanément des photos et fichiers justificatifs.");
renderBulletPoint("Consultation de Dossier :", "Une vue synthétique montrant le statut du dossier (en attente, validé, refusé) avec les commentaires de l'expert.");
renderBulletPoint("Prise de Rendez-vous :", "Calendrier interactif permettant de choisir un créneau horaire pour rencontrer un agent.");
renderBulletPoint("Centre de Notifications :", "Système d'alertes en direct informant l'utilisateur de toute mise à jour ou demande de document.");

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("3.2 L'Espace Agent et Expert (Laravel & React)");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Les professionnels de l'assurance disposent d'un tableau de bord avancé pour traiter efficacement les demandes :", { align: 'justify' });

renderBulletPoint("Gestion des Sinistres :", "Liste ordonnée de tous les incidents nécessitant une action, triés par priorité et statut.");
renderBulletPoint("Validation de Pièces :", "Module de prévisualisation des documents envoyés par le client, avec options de validation ou de rejet motivé.");
renderBulletPoint("Transmission à l'Expert :", "Action permettant d'envoyer l'ensemble d'un dossier technique à un expert externe en un seul clic.");
renderBulletPoint("Messagerie Collaborative :", "Possibilité de rédiger des commentaires sous le dossier pour guider l'assuré ou demander des précisions.");

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("3.3 L'Espace Administrateur");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Il permet de configurer l'application, de valider les comptes des nouveaux agents et d'accéder aux statistiques globales d'activité.", { align: 'justify' });

doc.addPage();

// ==========================================
// 5. AMÉLIORATIONS UX & DESIGN EFFECTUÉES
// ==========================================
renderHeader("4. Améliorations de l'Expérience Utilisateur & Design");

doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Afin de rendre la plateforme PFEASS plus attractive, intuitive et moderne, d'importants travaux de refonte visuelle et d'ergonomie ont été menés sur le frontend React.", { align: 'justify' });

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("4.1 Refonte de l'Ergonomie de Navigation (Navbar)");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("L'analyse du parcours utilisateur a révélé que le lien textuel 'Accueil' dans le menu de navigation était redondant pour les utilisateurs, car le logo de la marque remplit déjà cette fonction de retour.\n\n" +
         "**Modifications apportées :**\n" +
         "• **Suppression** du bouton 'Accueil' traditionnel de la barre de navigation.\n" +
         "• **Intégration d'un bouton contextuel 'Connexion'** directement au centre de la Navbar pour les utilisateurs non connectés, augmentant le taux de conversion.\n" +
         "• **Substitution par le bouton 'Tableau de bord'** dynamique dès qu'un utilisateur se connecte, l'orientant immédiatement vers son espace de travail.", { align: 'justify' });

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("4.2 Design Visuel Premium (Cyber Dark)");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("La page d'accueil de l'application a été entièrement réinventée avec un design haut de gamme :\n" +
         "• **Palette de couleurs harmonieuse** : Fond sombre spatial (#030712) enrichi de halos lumineux colorés indigo et cyan.\n" +
         "• **Composants Glassmorphism** : Cartes translucides floutées en arrière-plan avec bordures subtiles pour faire ressortir l'information.\n" +
         "• **Ajout d'illustrations haute définition** : Intégration d'une image symbolisant la protection d'assurance (bouclier technologique protégeant les biens) et d'un mockup réaliste du tableau de bord applicatif.\n" +
         "• **Section didactique complète** : Explication claire de ce qu'est l'assurance, son rôle sociétal fondamental et les avantages concrets de la numérisation des processus.", { align: 'justify' });

doc.addPage();

// ==========================================
// 6. CONCLUSION
// ==========================================
renderHeader("5. Conclusion et Perspectives");

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("5.1 Bilan du Projet", 50, doc.y + 10);
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Le développement de la plateforme PFEASS démontre l'efficacité d'un couplage entre Laravel et React pour la création d'applications web d'entreprise réactives. Les objectifs de centralisation des documents, de suivi en direct et de fluidification des rendez-vous ont été pleinement atteints sans altérer la logique métier existante de l'application.", { align: 'justify' });

doc.moveDown(1.5);

doc.font('Helvetica-Bold').fontSize(14).fillColor(colors.darkText).text("5.2 Perspectives d'Évolution Future");
doc.moveDown(0.5);
doc.font('Helvetica').fontSize(11).fillColor(colors.lightText)
   .text("Pour aller plus loin, plusieurs axes d'évolution peuvent être envisagés :\n" +
         "• **Module d'analyse automatique par IA :** Analyse automatique des pièces justificatives (OCR pour lire les constats et factures) lors du téléversement pour assister l'expert.\n" +
         "• **Signature Électronique :** Intégration d'une API de signature de documents pour signer les rapports d'expertise en ligne de manière juridiquement contraignante.\n" +
         "• **Application Mobile Native :** Portage de l'interface client en React Native pour permettre aux assurés de déclarer directement un sinistre sur le lieu de l'accident avec leur appareil photo.", { align: 'justify' });

doc.moveDown(2);

// Encadré Conclusion
renderCallout("Remerciements", "Nous remercions l'ensemble des parties prenantes, tuteurs et testeurs qui ont contribué à l'évaluation, au design et à la réussite de ce projet applicatif.");


// ==========================================
// PIED DE PAGE DYNAMIQUE (PAGINATION)
// ==========================================
const totalPages = doc.bufferedPageRange().count;
for (let i = 0; i < totalPages; i++) {
  doc.switchToPage(i);
  
  // Ne pas ajouter de numéro de page sur la page de garde (page 0)
  if (i > 0) {
    doc.save();
    
    // Entête de page discret
    doc.font('Helvetica-Oblique').fontSize(8).fillColor(colors.lightText)
       .text("PFEASS Assur - Rapport de Projet de Fin d'Études", 50, 30);
    doc.strokeColor(colors.divider).lineWidth(0.5).moveTo(50, 42).lineTo(doc.page.width - 50, 42).stroke();
    
    // Pied de page
    doc.strokeColor(colors.divider).lineWidth(0.5).moveTo(50, doc.page.height - 45).lineTo(doc.page.width - 50, doc.page.height - 45).stroke();
    
    doc.font('Helvetica').fontSize(8).fillColor(colors.lightText)
       .text(`Page ${i + 1} sur ${totalPages}`, doc.page.width - 150, doc.page.height - 35, { align: 'right', width: 100 });
       
    doc.font('Helvetica-Bold').fontSize(8).fillColor(colors.primary)
       .text("CONFIDENTIEL - USAGE ACADÉMIQUE", 50, doc.page.height - 35);
       
    doc.restore();
  }
}

// Finaliser l'écriture du fichier
doc.end();

console.log("Rapport PDF généré avec succès dans la racine du projet.");

// ==========================================
// FONCTIONS UTILITAIRES DE RENDU PDF
// ==========================================

function renderHeader(titleText) {
  doc.font('Helvetica-Bold')
     .fontSize(20)
     .fillColor(colors.primary)
     .text(titleText, 50, 55); // Ajusté à y=55 pour passer sous l'entête
     
  doc.moveDown(0.5);
  doc.strokeColor(colors.secondary)
     .lineWidth(1.5)
     .moveTo(50, doc.y)
     .lineTo(doc.page.width - 50, doc.y)
     .stroke();
  doc.moveDown(1.5);
}

function renderBulletPoint(boldPrefix, textValue) {
  doc.font('Helvetica-Bold')
     .fontSize(11)
     .fillColor(colors.darkText)
     .text("  • " + boldPrefix, { continued: true })
     .font('Helvetica')
     .fillColor(colors.lightText)
     .text(" " + textValue);
  doc.moveDown(0.4);
}

function renderCallout(bannerText, textValue) {
  const currentY = doc.y;
  
  // Fond gris léger de l'encadré
  doc.rect(50, currentY, doc.page.width - 100, 60)
     .fill(colors.accentBg);
     
  // Bordure gauche colorée (Indigo)
  doc.rect(50, currentY, 4, 60)
     .fill(colors.primary);
     
  // Écrire le texte à l'intérieur
  doc.font('Helvetica-Bold')
     .fontSize(11)
     .fillColor(colors.primary)
     .text(bannerText, 70, currentY + 12);
     
  doc.font('Helvetica-Oblique')
     .fontSize(10)
     .fillColor(colors.lightText)
     .text(textValue, 70, currentY + 28, { width: doc.page.width - 140 });
     
  doc.moveDown(2);
}
