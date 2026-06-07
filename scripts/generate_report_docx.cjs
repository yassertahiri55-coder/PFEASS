const { Document, Packer, Paragraph, TextRun, HeadingLevel, AlignmentType, BorderStyle } = require("docx");
const fs = require("fs");
const path = require("path");

// Définition des styles et couleurs
const colors = {
  primary: "4F46E5",   // Indigo
  secondary: "06B6D4", // Cyan
  darkText: "0F172A",  // Slate 900
  lightText: "475569"  // Slate 600
};

// Helper pour créer un titre principal H1 avec style
function createHeading1(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_1,
    spacing: { before: 240, after: 120 },
    children: [
      new TextRun({
        text: text,
        bold: true,
        size: 28,
        color: colors.primary,
        font: "Helvetica"
      })
    ]
  });
}

// Helper pour créer un sous-titre H2
function createHeading2(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_2,
    spacing: { before: 180, after: 80 },
    children: [
      new TextRun({
        text: text,
        bold: true,
        size: 22,
        color: colors.darkText,
        font: "Helvetica"
      })
    ]
  });
}

// Helper pour créer un paragraphe classique
function createParagraph(text) {
  return new Paragraph({
    spacing: { before: 60, after: 120, line: 360 }, // line spacing 1.5
    children: [
      new TextRun({
        text: text,
        size: 22, // 11pt
        color: colors.lightText,
        font: "Helvetica"
      })
    ]
  });
}

// Helper pour créer un point de liste à puces
function createBulletPoint(boldText, normalText) {
  return new Paragraph({
    bullet: {
      level: 0
    },
    spacing: { before: 40, after: 60, line: 280 },
    children: [
      new TextRun({
        text: boldText + " ",
        bold: true,
        size: 22,
        color: colors.darkText,
        font: "Helvetica"
      }),
      new TextRun({
        text: normalText,
        size: 22,
        color: colors.lightText,
        font: "Helvetica"
      })
    ]
  });
}

// Helper pour un encadré / Callout
function createCallout(title, text) {
  return new Paragraph({
    spacing: { before: 180, after: 180 },
    border: {
      left: {
        color: colors.primary,
        space: 12,
        style: BorderStyle.SINGLE,
        size: 24
      }
    },
    children: [
      new TextRun({
        text: ` [${title}] `,
        bold: true,
        size: 22,
        color: colors.primary,
        font: "Helvetica"
      }),
      new TextRun({
        text: text,
        italics: true,
        size: 22,
        color: colors.lightText,
        font: "Helvetica"
      })
    ]
  });
}

// Construction du document
const doc = new Document({
  sections: [
    {
      // --- PAGE 1: PAGE DE GARDE ---
      properties: {},
      children: [
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { before: 1000, after: 200 },
          children: [
            new TextRun({
              text: "RAPPORT DE PROJET DE FIN D'ÉTUDES",
              bold: true,
              size: 48, // 24pt
              color: colors.primary,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { before: 100, after: 800 },
          children: [
            new TextRun({
              text: "PFEASS - Plateforme Digitale de Gestion de Sinistres et Dossiers d'Assurance",
              size: 24, // 12pt
              color: colors.lightText,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { before: 500, after: 1200 },
          children: [
            new TextRun({
              text: "PFEASS Assur",
              bold: true,
              size: 64, // 32pt
              color: colors.darkText,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          spacing: { before: 800, after: 100 },
          children: [
            new TextRun({
              text: "Présenté par :",
              bold: true,
              size: 24,
              color: colors.darkText,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          spacing: { before: 40, after: 400 },
          children: [
            new TextRun({
              text: "Équipe de Développement PFEASS",
              size: 22,
              color: colors.lightText,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          spacing: { before: 100, after: 100 },
          children: [
            new TextRun({
              text: "Technologies Utilisées :",
              bold: true,
              size: 24,
              color: colors.darkText,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          spacing: { before: 40, after: 1200 },
          children: [
            new TextRun({
              text: "• Backend : Laravel v13.7 (PHP v8.3/8.5)\n" +
                    "• Frontend : React SPA & Redux Toolkit\n" +
                    "• Base de données : MySQL\n" +
                    "• Styles : Cyber Dark System Custom CSS",
              size: 22,
              color: colors.lightText,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { before: 800 },
          children: [
            new TextRun({
              text: "Année Universitaire 2025 - 2026",
              bold: true,
              size: 22,
              color: colors.primary,
              font: "Helvetica"
            })
          ]
        }),
        
        // --- PAGE 2: INTRODUCTION & CONTEXTE ---
        new Paragraph({ text: "", pageBreakBefore: true }), // Saut de page
        createHeading1("1. Introduction & Contexte du Projet"),
        
        createHeading2("1.1 Contexte Général"),
        createParagraph(
          "Le secteur des assurances fait face aujourd'hui à un besoin pressant de modernisation et de digitalisation. Les procédures traditionnelles de déclaration de sinistres, d'évaluation des dossiers et d'échanges d'informations impliquent souvent des processus papier lourds, des délais de traitement importants et un manque de transparence pour l'assuré."
        ),
        
        createHeading2("1.2 Problématique et Solution PFEASS"),
        createParagraph(
          "Comment optimiser la communication entre l'assuré, l'agent d'assurance et l'expert pour réduire les délais de traitement des sinistres tout en renforçant la confiance ?\n\n" +
          "Notre application, PFEASS, répond à cette problématique en proposant une plateforme web collaborative structurée en deux environnements distincts :"
        ),
        createBulletPoint(
          "Une interface client (React SPA) :", 
          "permettant de soumettre des sinistres en ligne, de téléverser les justificatifs nécessaires, de consulter ses dossiers en temps réel et de réserver des rendez-vous."
        ),
        createBulletPoint(
          "Un espace de gestion (Laravel / React) :", 
          "dédié aux agents d'assurance et aux experts pour analyser les pièces, rédiger des rapports d'expertise, programmer des visites de sinistres et interagir directement avec l'assuré."
        ),
        
        createCallout(
          "Objectif Majeur", 
          "Automatiser les flux de travail et offrir un canal de communication instantané et sécurisé pour toutes les parties prenantes du contrat d'assurance."
        ),
        
        // --- PAGE 3: ARCHITECTURE TECHNIQUE ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("2. Architecture Technique & Choix Technologiques"),
        
        createParagraph(
          "L'application PFEASS repose sur un modèle d'architecture découplée moderne garantissant robustesse, flexibilité et extensibilité."
        ),
        
        createHeading2("2.1 Le Backend : Laravel v13"),
        createParagraph(
          "Laravel a été choisi pour concevoir le moteur applicatif et les APIs sécurisées du système. Ses atouts clés sont :\n" +
          "• Authentification sécurisée par jetons d'API (Laravel Sanctum).\n" +
          "• Architecture MVC robuste avec des API Resources prêtes pour le JSON.\n" +
          "• Système de migration de base de données fluide (MySQL).\n" +
          "• Traitement en arrière-plan optimisé (écouteurs de files d'attente pour notifications)."
        ),
        
        createHeading2("2.2 Le Frontend : React SPA"),
        createParagraph(
          "Pour fournir une expérience utilisateur fluide digne des standards modernes, la partie utilisateur de PFEASS est entièrement développée en React.js :\n" +
          "• Routage dynamique côté client grâce à React Router DOM.\n" +
          "• Gestion d'état global centralisée avec Redux Toolkit (Slices pour l'utilisateur).\n" +
          "• Appels d'API asynchrones gérés par Axios pour communiquer avec le backend Laravel.\n" +
          "• Design System personnalisé reposant sur le concept du verre poli (Glassmorphism) et du style 'Cyber Dark'."
        ),
        
        createHeading2("2.3 Modèle Conceptuel des Données"),
        createParagraph(
          "La structure SQL s'articule autour de plusieurs tables interconnectées :\n" +
          "• Users : Gestion des profils (Clients, Agents, Experts, Administrateurs).\n" +
          "• Sinistres : Enregistrement des incidents déclarés par les assurés.\n" +
          "• Dossiers : Regroupement et validation des pièces justificatives.\n" +
          "• Documents : Fichiers téléversés (cartes grises, constats, devis).\n" +
          "• Rendez-vous : Sessions planifiées entre agents et clients.\n" +
          "• Commentaires : Journal d'échanges d'informations."
        ),
        
        // --- PAGE 4: FONCTIONNALITÉS ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("3. Fonctionnalités Détaillées du Système"),
        
        createHeading2("3.1 L'Espace Client (Interface React)"),
        createParagraph(
          "L'espace assuré est conçu pour maximiser l'autonomie du client dans la gestion de ses sinistres :"
        ),
        createBulletPoint("Déclaration Simplifiée :", "Formulaire intuitif pour décrire l'incident (type, date, description) et attacher instantanément des photos et fichiers justificatifs."),
        createBulletPoint("Consultation de Dossier :", "Une vue synthétique montrant le statut du dossier (en attente, validé, refusé) avec les commentaires de l'expert."),
        createBulletPoint("Prise de Rendez-vous :", "Calendrier interactif permettant de choisir un créneau horaire pour rencontrer un agent."),
        createBulletPoint("Centre de Notifications :", "Système d'alertes en direct informant l'utilisateur de toute mise à jour ou demande de document."),
        
        createHeading2("3.2 L'Espace Agent et Expert (Laravel & React)"),
        createParagraph(
          "Les professionnels de l'assurance disposent d'un tableau de bord avancé pour traiter efficacement les demandes :"
        ),
        createBulletPoint("Gestion des Sinistres :", "Liste ordonnée de tous les incidents nécessitant une action, triés par priorité et statut."),
        createBulletPoint("Validation de Pièces :", "Module de prévisualisation des documents envoyés par le client, avec options de validation ou de rejet motivé."),
        createBulletPoint("Transmission à l'Expert :", "Action permettant d'envoyer l'ensemble d'un dossier technique à un expert externe en un seul clic."),
        createBulletPoint("Messagerie Collaborative :", "Possibilité de rédiger des commentaires sous le dossier pour guider l'assuré ou demander des précisions."),
        
        createHeading2("3.3 L'Espace Administrateur"),
        createParagraph(
          "Il permet de configurer l'application, de valider les comptes des nouveaux agents et d'accéder aux statistiques globales d'activité."
        ),
        
        // --- PAGE 5: AMÉLIORATIONS DESIGN ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("4. Améliorations de l'Expérience Utilisateur & Design"),
        
        createParagraph(
          "Afin de rendre la plateforme PFEASS plus attractive, intuitive et moderne, d'importants travaux de refonte visuelle et d'ergonomie ont été menés sur le frontend React."
        ),
        
        createHeading2("4.1 Refonte de l'Ergonomie de Navigation (Navbar)"),
        createParagraph(
          "L'analyse du parcours utilisateur a révélé que le lien textuel 'Accueil' dans le menu de navigation était redondant pour les utilisateurs, car le logo de la marque remplit déjà cette fonction de retour.\n\n" +
          "Modifications apportées :\n" +
          "• Suppression du bouton 'Accueil' traditionnel de la barre de navigation.\n" +
          "• Intégration d'un bouton contextuel 'Connexion' directement au centre de la Navbar pour les utilisateurs non connectés, augmentant le taux de conversion.\n" +
          "• Substitution par le bouton 'Tableau de bord' dynamique dès qu'un utilisateur se connecte, l'orientant immédiatement vers son espace de travail."
        ),
        
        createHeading2("4.2 Design Visuel Premium (Cyber Dark)"),
        createParagraph(
          "La page d'accueil de l'application a été entièrement réinventée avec un design haut de gamme :\n" +
          "• Palette de couleurs harmonieuse : Fond sombre spatial (#030712) enrichi de halos lumineux colorés indigo et cyan.\n" +
          "• Composants Glassmorphism : Cartes translucides floutées en arrière-plan avec bordures subtiles pour faire ressortir l'information.\n" +
          "• Ajout d'illustrations haute définition : Intégration d'une image symbolisant la protection d'assurance (bouclier technologique protégeant les biens) et d'un mockup réaliste du tableau de bord applicatif.\n" +
          "• Section didactique complète : Explication claire de ce qu'est l'assurance, son rôle sociétal fondamental et les avantages concrets de la numérisation des processus."
        ),
        
        // --- PAGE 6: CONCLUSION ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("5. Conclusion et Perspectives"),
        
        createHeading2("5.1 Bilan du Projet"),
        createParagraph(
          "Le développement de la plateforme PFEASS démontre l'efficacité d'un couplage entre Laravel et React pour la création d'applications web d'entreprise réactives. Les objectifs de centralisation des documents, de suivi en direct et de fluidification des rendez-vous ont été pleinement atteints sans altérer la logique métier existante de l'application."
        ),
        
        createHeading2("5.2 Perspectives d'Évolution Future"),
        createParagraph(
          "Pour aller plus loin, plusieurs axes d'évolution peuvent être envisagés :\n" +
          "• Module d'analyse automatique par IA : Analyse automatique des pièces justificatives (OCR pour lire les constats et factures) lors du téléversement pour assister l'expert.\n" +
          "• Signature Électronique : Intégration d'une API de signature de documents pour signer les rapports d'expertise en ligne de manière juridiquement contraignante.\n" +
          "• Application Mobile Native : Portage de l'interface client en React Native pour permettre aux assurés de déclarer directement un sinistre sur le lieu de l'accident avec leur appareil photo."
        ),
        
        createCallout(
          "Remerciements", 
          "Nous remercions l'ensemble des parties prenantes, tuteurs et testeurs qui ont contribué à l'évaluation, au design et à la réussite de ce projet applicatif."
        )
      ]
    }
  ]
});

// Écriture du fichier DOCX
const docxOutputPath = path.join(__dirname, '..', 'Rapport_Projet_PFEASS.docx');
Packer.toBuffer(doc).then((buffer) => {
  fs.writeFileSync(docxOutputPath, buffer);
  console.log("Rapport Word (.docx) généré avec succès dans la racine du projet.");
}).catch((err) => {
  console.error("Erreur lors de la génération du rapport Word :", err);
});
