const { Document, Packer, Paragraph, TextRun, HeadingLevel, AlignmentType, BorderStyle, Table, TableRow, TableCell, WidthType } = require("docx");
const fs = require("fs");
const path = require("path");

// Couleurs du design system
const colors = {
  primary: "4F46E5",   // Indigo
  secondary: "06B6D4", // Cyan
  darkText: "0F172A",  // Slate 900
  lightText: "475569", // Slate 600
  lightBg: "F8FAFC",   // Slate 50
  border: "CBD5E1"     // Slate 300
};

// Helpers de mise en page
function createHeading1(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_1,
    spacing: { before: 300, after: 150 },
    children: [
      new TextRun({
        text: text,
        bold: true,
        size: 32,
        color: colors.primary,
        font: "Helvetica"
      })
    ]
  });
}

function createHeading2(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_2,
    spacing: { before: 240, after: 100 },
    children: [
      new TextRun({
        text: text,
        bold: true,
        size: 24,
        color: colors.darkText,
        font: "Helvetica"
      })
    ]
  });
}

function createHeading3(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_3,
    spacing: { before: 180, after: 80 },
    children: [
      new TextRun({
        text: text,
        bold: true,
        size: 20,
        color: colors.secondary,
        font: "Helvetica"
      })
    ]
  });
}

function createParagraph(text) {
  return new Paragraph({
    spacing: { before: 80, after: 120, line: 360 }, // Spécification interligne 1.5
    children: [
      new TextRun({
        text: text,
        size: 22,
        color: colors.lightText,
        font: "Helvetica"
      })
    ]
  });
}

function createBoldParagraph(boldText, normalText) {
  return new Paragraph({
    spacing: { before: 80, after: 120, line: 360 },
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

function createBulletPoint(boldText, normalText) {
  return new Paragraph({
    bullet: {
      level: 0
    },
    spacing: { before: 60, after: 60, line: 300 },
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

function createCallout(title, text) {
  return new Paragraph({
    spacing: { before: 200, after: 200 },
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

// Fonction pour créer une cellule de tableau
function createCell(text, bold = false, align = AlignmentType.LEFT, span = 1) {
  return new TableCell({
    width: { size: 100 / span, type: WidthType.PERCENTAGE },
    borders: {
      top: { style: BorderStyle.SINGLE, size: 4, color: colors.border },
      bottom: { style: BorderStyle.SINGLE, size: 4, color: colors.border },
      left: { style: BorderStyle.SINGLE, size: 4, color: colors.border },
      right: { style: BorderStyle.SINGLE, size: 4, color: colors.border }
    },
    children: [
      new Paragraph({
        alignment: align,
        spacing: { before: 60, after: 60 },
        children: [
          new TextRun({
            text: text,
            bold: bold,
            size: 20,
            color: bold ? colors.darkText : colors.lightText,
            font: "Helvetica"
          })
        ]
      })
    ]
  });
}

// ==========================================
// CONSTRUCTION DU DOCUMENT EXTENSIF
// ==========================================
const doc = new Document({
  sections: [
    {
      properties: {},
      children: [
        // --- PAGE 1: PAGE DE GARDE ---
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { before: 1200, after: 300 },
          children: [
            new TextRun({
              text: "RAPPORT DE PROJET DE FIN D'ÉTUDES",
              bold: true,
              size: 52, // 26pt
              color: colors.primary,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { before: 100, after: 600 },
          children: [
            new TextRun({
              text: "PFEASS - Conception et Développement d'une Plateforme Web de Gestion de Sinistres et Dossiers d'Assurance",
              size: 24, // 12pt
              color: colors.lightText,
              font: "Helvetica"
            })
          ]
        }),
        new Paragraph({
          alignment: AlignmentType.CENTER,
          spacing: { before: 600, after: 1200 },
          children: [
            new TextRun({
              text: "PFEASS Assur",
              bold: true,
              size: 72, // 36pt
              color: colors.darkText,
              font: "Helvetica"
            })
          ]
        }),
        
        new Paragraph({
          spacing: { before: 800, after: 100 },
          children: [
            new TextRun({
              text: "Rédigé et Présenté par :",
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
              text: "Étudiant Concepteur & Développeur",
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
              text: "Technologies Clés de l'Application :",
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
              text: "• Architecture REST découplée (API Laravel / Client React)\n" +
                    "• Framework MVC Backend : Laravel Framework v13.7\n" +
                    "• Framework Frontend : React.js SPA & Redux Toolkit\n" +
                    "• Base de données Relationnelle : MySQL v8.0\n" +
                    "• Styles : Custom Premium CSS (Cyber Dark System)",
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
              text: "Année Académique 2025 - 2026",
              bold: true,
              size: 22,
              color: colors.primary,
              font: "Helvetica"
            })
          ]
        }),
        
        // --- PAGE 2: INTRODUCTION & ANALYSE ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("1. Introduction Générale et Contexte"),
        
        createHeading2("1.1 Contexte du Projet"),
        createParagraph(
          "Le traitement des sinistres d'assurance (accidents, vols, dégâts des eaux) est historiquement l'une des procédures les plus lourdes et anxiogènes pour les assurés. Les délais de traitement, le manque de visibilité sur l'état d'avancement des dossiers, la multiplicité des intermédiaires (agents, experts, conseillers clients) et l'abondance de documents physiques ou de courriels éparpillés nuisent considérablement à la qualité de service."
        ),
        createParagraph(
          "La plateforme PFEASS a été conçue pour surmonter ces contraintes en centralisant l'intégralité du cycle de vie d'un sinistre au sein d'un outil numérique performant. En connectant en temps réel les assurés (clients), les agents généraux d'assurance et les experts techniques agréés, l'application réduit les goulots d'étranglement administratifs."
        ),
        
        createHeading2("1.2 Objectifs de la Plateforme"),
        createParagraph(
          "Les objectifs principaux fixés lors de l'étude préliminaire du projet s'articulent autour des axes suivants :"
        ),
        createBulletPoint("Dématérialisation complète :", "Suppression des formulaires papier au profit d'interfaces de déclaration web instantanées avec téléversement sécurisé de justificatifs."),
        createBulletPoint("Transparence absolue :", "Permettre aux clients de suivre l'avancement de leur dossier étape par étape (en attente, en cours de traitement, transmis à l'expert, validé ou refusé)."),
        createBulletPoint("Collaboration centralisée :", "Offrir un canal d'échange direct sous chaque dossier sous forme de commentaires interactifs pour éviter les appels et e-mails redondants."),
        createBulletPoint("Gestion d'agenda :", "Faciliter la planification des visites d'expertise et des rendez-vous d'indemnisation physique ou virtuelle."),

        createCallout(
          "Vision Stratégique",
          "Fournir un outil flexible, moderne et hautement sécurisé, capable de s'adapter aux volumes de déclarations tout en maintenant un temps de réponse minimal pour l'assuré."
        ),
        
        // --- PAGE 3: DIAGRAMME DE CAS D'UTILISATION (USE CASE) ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("2. Analyse Fonctionnelle & Cas d'Utilisation"),
        
        createParagraph(
          "L'analyse des besoins a permis d'identifier quatre principaux acteurs (rôles) interagissant avec la plateforme PFEASS. Le diagramme textuel et structurel ci-dessous spécifie les cas d'utilisation pour chaque acteur."
        ),
        
        createHeading2("2.1 Acteurs du Système"),
        createBulletPoint("L'Assuré (Client) :", "Acteur déclarant le sinistre, envoyant les documents requis et consultant l'état de son indemnisation."),
        createBulletPoint("L'Agent d'Assurance :", "Acteur intermédiaire qui valide la conformité administrative des pièces, instruit les dossiers et les transfère aux experts."),
        createBulletPoint("L'Expert Agréé :", "Acteur technique qui évalue les sinistres à distance ou planifie une visite d'expertise, rédige les rapports et rend des décisions finales."),
        createBulletPoint("L'Administrateur :", "Acteur gérant la configuration globale, validant ou radiant les comptes des professionnels."),

        createHeading2("2.2 Spécification UML du Diagramme de Cas d'Utilisation"),
        createParagraph(
          "Ci-dessous, la représentation structurée des relations entre les Acteurs et les Cas d'Utilisation (Use Cases) :"
        ),
        
        new Table({
          rows: [
            new TableRow({
              children: [
                createCell("Acteur (Actor)", true, AlignmentType.LEFT),
                createCell("Cas d'Utilisation Associés (Use Cases)", true, AlignmentType.LEFT),
                createCell("Relations / Dépendances", true, AlignmentType.LEFT)
              ]
            }),
            new TableRow({
              children: [
                createCell("CLIENT (Assuré)", false),
                createCell("• S'authentifier / S'inscrire\n• Déclarer un sinistre\n• Télécharger des justificatifs\n• Planifier/Accepter un rdv\n• Consulter les notifications\n• Ajouter un commentaire", false),
                createCell("Hérite des droits de base d'accès utilisateur.", false)
              ]
            }),
            new TableRow({
              children: [
                createCell("AGENT ASSURANCE", false),
                createCell("• Consulter la liste des sinistres affectés\n• Valider administrativement un document\n• Transférer un dossier complet à l'expert\n• Communiquer par commentaires", false),
                createCell("«include» : Authentification obligatoire.", false)
              ]
            }),
            new TableRow({
              children: [
                createCell("EXPERT AGRÉÉ", false),
                createCell("• Accéder aux dossiers en cours d'évaluation\n• Valider / Refuser un dossier technique\n• Rédiger un rapport d'expertise\n• Programmer un rdv d'expertise", false),
                createCell("«extend» : Planification de rdv uniquement après validation.", false)
              ]
            }),
            new TableRow({
              children: [
                createCell("ADMINISTRATEUR", false),
                createCell("• Approuver/Rejeter les professionnels\n• Gérer la base des utilisateurs\n• Accéder au tableau de bord statistique", false),
                createCell("Accès total aux tables du système.", false)
              ]
            })
          ]
        }),

        // --- PAGE 4: MCD (MODELE CONCEPTUEL DES DONNEES) ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("3. Modélisation Conceptuelle (MCD)"),
        
        createParagraph(
          "Le Modèle Conceptuel des Données (MCD) formalise la structure de l'information indépendamment des choix d'implémentation physique. Il s'articule autour des concepts d'Entités et d'Associations avec leurs cardinalités respectives."
        ),
        
        createHeading2("3.1 Les Entités et Leurs Propriétés"),
        createBulletPoint("USER :", "Identifie tout compte utilisateur. Attributs : id (clé primaire), name, prenom, email, telephone, pays, date_naissance, role (client, agent, expert, admin), status (active, pending, rejected)."),
        createBulletPoint("SINISTRE :", "Représente l'incident déclaré. Attributs : id (clé primaire), titre, type (accident, vol, incendie, etc.), description, date_declaration, statut (en_attente, en_cours, valide, refuse, transfere_expert)."),
        createBulletPoint("DOSSIER :", "Classeur technique du sinistre. Attributs : id (clé primaire), numero (généré), statut (en_attente, en_cours, termine, refuse), date_ouverture, date_cloture."),
        createBulletPoint("DOCUMENT :", "Pièce jointe justificative. Attributs : id (clé primaire), nom, chemin (chemin physique du fichier), type (constat, carte_grise, facture)."),
        createBulletPoint("RENDEZVOUS :", "Session planifiée. Attributs : id (clé primaire), date, lieu, description, statut (planifie, effectue, annule)."),
        createBulletPoint("COMMENTAIRE :", "Message d'échange. Attributs : id (clé primaire), contenu, created_at."),

        createHeading2("3.2 Associations et Cardinalités du MCD"),
        createBoldParagraph("1. Assoc. DECLARER (USER - SINISTRE) :", "Un User (Client) peut déclarer 0 à N Sinistres. Un Sinistre est déclaré par 1 et 1 seul User. Cardinalités : USER (0,N) --- [DECLARER] --- (1,1) SINISTRE."),
        createBoldParagraph("2. Assoc. AFFECTER (USER - SINISTRE) :", "Un User (Agent) peut être affecté à 0 à N Sinistres. Un Sinistre est supervisé par 0 à 1 Agent. Cardinalités : USER (0,N) --- [AFFECTER] --- (0,1) SINISTRE."),
        createBoldParagraph("3. Assoc. RATTACHER (SINISTRE - DOSSIER) :", "Un Sinistre est rattaché à 1 et 1 seul Dossier. Un Dossier concerne 1 et 1 seul Sinistre. Cardinalités : SINISTRE (1,1) --- [RATTACHER] --- (1,1) DOSSIER."),
        createBoldParagraph("4. Assoc. JOINDRE (SINISTRE - DOCUMENT) :", "Un Sinistre possède 0 à N Documents. Un Document est lié à 1 et 1 seul Sinistre. Cardinalités : SINISTRE (0,N) --- [JOINDRE] --- (1,1) DOCUMENT."),
        createBoldParagraph("5. Assoc. PLANIFIER (DOSSIER - RENDEZVOUS) :", "Un Dossier peut faire l'objet de 0 à N Rendez-vous. Un Rendez-vous concerne 1 et 1 seul Dossier. Cardinalités : DOSSIER (0,N) --- [PLANIFIER] --- (1,1) RENDEZVOUS."),
        createBoldParagraph("6. Assoc. RANGER (DOSSIER - COMMENTAIRE) :", "Un Dossier contient 0 à N Commentaires. Un Commentaire appartient à 1 et 1 seul Dossier. Cardinalités : DOSSIER (0,N) --- [RANGER] --- (1,1) COMMENTAIRE."),

        // --- PAGE 5: MLD (MODELE LOGIQUE DES DONNEES) ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("4. Modélisation Logique & Relationnelle (MLD)"),
        
        createParagraph(
          "Le Modèle Logique des Données (MLD) découle de la traduction systématique du MCD en tables de base de données relationnelles MySQL, en appliquant les règles d'intégrité référentielle (clés étrangères)."
        ),
        
        createHeading2("4.1 Schéma Relationnel Textuel (MLD)"),
        createParagraph(
          "Les clés primaires sont soulignées ou identifiées par [PK], et les clés étrangères sont indiquées par le suffixe _id ou [FK] :"
        ),
        
        createBoldParagraph("• USERS", "([PK] id, name, prenom, email, telephone, pays, date_naissance, role, status, password, created_at, updated_at)"),
        createBoldParagraph("• SINISTRES", "([PK] id, titre, type, description, date_declaration, statut, [FK] client_id REFERENCES USERS(id), [FK] user_id REFERENCES USERS(id), created_at, updated_at)"),
        createBoldParagraph("• DOSSIERS", "([PK] id, numero, statut, date_ouverture, date_cloture, [FK] sinistre_id REFERENCES SINISTRES(id), created_at, updated_at)"),
        createBoldParagraph("• DOCUMENTS", "([PK] id, nom, chemin, [FK] sinistre_id REFERENCES SINISTRES(id), created_at, updated_at)"),
        createBoldParagraph("• RENDEZVOUS", "([PK] id, date, lieu, description, statut, [FK] dossier_id REFERENCES DOSSIERS(id), created_at, updated_at)"),
        createBoldParagraph("• COMMENTAIRES", "([PK] id, contenu, [FK] dossier_id REFERENCES DOSSIERS(id), [FK] user_id REFERENCES USERS(id), created_at, updated_at)"),
        createBoldParagraph("• NOTIFICATIONS", "([PK] id, titre, message, lu, [FK] user_id REFERENCES USERS(id), created_at, updated_at)"),

        createHeading2("4.2 Dictionnaire Physique de Données (Schéma MySQL)"),
        new Table({
          rows: [
            new TableRow({
              children: [
                createCell("Table", true, AlignmentType.LEFT),
                createCell("Champ (Colonne)", true, AlignmentType.LEFT),
                createCell("Type de Donnée", true, AlignmentType.LEFT),
                createCell("Attributs / Contraintes", true, AlignmentType.LEFT)
              ]
            }),
            new TableRow({
              children: [
                createCell("users", false),
                createCell("id\nemail\nrole\nstatus", false),
                createCell("BIGINT UNSIGNED\nVARCHAR(255)\nVARCHAR(50)\nVARCHAR(50)", false),
                createCell("Auto-Increment, PK\nUnique, Not Null\nNot Null (client/agent...)\nDefault 'active'", false)
              ]
            }),
            new TableRow({
              children: [
                createCell("sinistres", false),
                createCell("id\ntitre\nstatut\nclient_id\nuser_id", false),
                createCell("BIGINT UNSIGNED\nVARCHAR(255)\nVARCHAR(50)\nBIGINT UNSIGNED\nBIGINT UNSIGNED", false),
                createCell("Auto-Increment, PK\nNot Null\nDefault 'en_attente'\nFK (users.id), Not Null\nFK (users.id), Nullable (Agent)", false)
              ]
            }),
            new TableRow({
              children: [
                createCell("dossiers", false),
                createCell("id\nnumero\nstatut\nsinistre_id", false),
                createCell("BIGINT UNSIGNED\nVARCHAR(100)\nVARCHAR(50)\nBIGINT UNSIGNED", false),
                createCell("Auto-Increment, PK\nUnique, Not Null\nDefault 'en_attente'\nFK (sinistres.id), Cascade Delete", false)
              ]
            }),
            new TableRow({
              children: [
                createCell("rendezvous", false),
                createCell("id\ndate\nlieu\ndossier_id", false),
                createCell("BIGINT UNSIGNED\nDATETIME\nVARCHAR(255)\nBIGINT UNSIGNED", false),
                createCell("Auto-Increment, PK\nNot Null\nNot Null\nFK (dossiers.id), Cascade Delete", false)
              ]
            })
          ]
        }),

        // --- PAGE 6: DIAGRAMME DE CLASSES ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("5. Diagramme de Classes UML"),
        
        createParagraph(
          "Le Diagramme de Classes représente l'organisation statique des objets du code de l'application (modèles Eloquent de Laravel et structures d'entités de l'API JSON)."
        ),
        
        createHeading2("5.1 Représentation Conceptuelle des Modèles"),
        createParagraph(
          "+---------------------------------------------------------------------+\n" +
          "|                               USER                                  |\n" +
          "+---------------------------------------------------------------------+\n" +
          "| - id: bigint [PK]                                                   |\n" +
          "| - name: string                                                      |\n" +
          "| - prenom: string                                                    |\n" +
          "| - email: string                                                     |\n" +
          "| - role: string (client | agent | expert | admin)                    |\n" +
          "| - status: string                                                    |\n" +
          "+---------------------------------------------------------------------+\n" +
          "| + clientSinistres(): HasMany [lié à Sinistre.client_id]             |\n" +
          "| + agentSinistres(): HasMany [lié à Sinistre.user_id]                |\n" +
          "| + commentaires(): HasMany                                           |\n" +
          "| + notifications(): HasMany                                          |\n" +
          "+---------------------------------------------------------------------+"
        ),
        
        createParagraph(
          "+---------------------------------------------------------------------+\n" +
          "|                             SINISTRE                                |\n" +
          "+---------------------------------------------------------------------+\n" +
          "| - id: bigint [PK]                                                   |\n" +
          "| - titre: string                                                     |\n" +
          "| - type: string                                                      |\n" +
          "| - description: text                                                 |\n" +
          "| - date_declaration: date                                            |\n" +
          "| - statut: string                                                    |\n" +
          "| - client_id: bigint [FK -> User.id]                                 |\n" +
          "| - user_id: bigint [FK -> User.id, nullable]                         |\n" +
          "+---------------------------------------------------------------------+\n" +
          "| + client(): BelongsTo [User]                                        |\n" +
          "| + user(): BelongsTo [User] (Agent)                                  |\n" +
          "| + dossiers(): HasMany [Dossier]                                     |\n" +
          "| + documents(): HasMany [Document]                                   |\n" +
          "+---------------------------------------------------------------------+"
        ),

        createParagraph(
          "+---------------------------------------------------------------------+\n" +
          "|                              DOSSIER                                |\n" +
          "+---------------------------------------------------------------------+\n" +
          "| - id: bigint [PK]                                                   |\n" +
          "| - numero: string                                                    |\n" +
          "| - statut: string (en_attente | en_cours | termine | refuse)         |\n" +
          "| - date_ouverture: date                                              |\n" +
          "| - date_cloture: date                                                |\n" +
          "| - sinistre_id: bigint [FK -> Sinistre.id]                           |\n" +
          "+---------------------------------------------------------------------+\n" +
          "| + sinistre(): BelongsTo [Sinistre]                                  |\n" +
          "| + commentaires(): HasMany [Commentaire]                             |\n" +
          "| + rendezvous(): HasMany [RendezVous]                                |\n" +
          "+---------------------------------------------------------------------+"
        ),

        createHeading2("5.2 Associations et Multiplicités UML"),
        createBulletPoint("User (1) ---- (0..*) Sinistre [client] :", "Un utilisateur client possède zéro ou plusieurs sinistres."),
        createBulletPoint("User (0..1) ---- (0..*) Sinistre [agent] :", "Un agent supervise zéro ou plusieurs sinistres."),
        createBulletPoint("Sinistre (1) ---- (1) Dossier :", "Relation 1-à-1 stricte : un sinistre génère exactement un dossier à l'ouverture."),
        createBulletPoint("Sinistre (1) ---- (0..*) Document :", "Un sinistre regroupe zéro ou plusieurs justificatifs."),
        createBulletPoint("Dossier (1) ---- (0..*) RendezVous :", "Un dossier technique peut nécessiter plusieurs rendez-vous."),
        createBulletPoint("Dossier (1) ---- (0..*) Commentaire :", "Un dossier sert de fil de discussion pour plusieurs commentaires."),

        // --- PAGE 7: IMPLEMENTATION DU CODE ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("6. Implémentation Logicielle et Technologies"),
        
        createHeading2("6.1 Le Backend REST (Laravel v13)"),
        createParagraph(
          "Le backend de PFEASS agit comme un fournisseur d'API sans état (stateless API). Les contrôleurs Laravel interceptent les requêtes JSON, valident les données reçues, appliquent la logique métier et retournent des réponses standardisées via les Eloquent API Resources."
        ),
        createParagraph(
          "Un Middleware d'authentification Sanctum (`auth:sanctum`) protège toutes les routes sensibles. Le processus d'inscription et de connexion délivre des jetons d'accès cryptés que le client stocke localement."
        ),
        
        createHeading2("6.2 Le Frontend SPA (React v19 & Redux Toolkit)"),
        createParagraph(
          "L'interface utilisateur a été conçue comme une Single Page Application pour offrir une réactivité maximale. La navigation s'effectue instantanément grâce au routeur virtuel côté client. Redux Toolkit centralise l'état global de l'application :\n" +
          "• `userSlice.js` : Gère l'état de l'utilisateur connecté, son rôle (client/agent) et son jeton d'authentification. Lors du démarrage de l'application, un effet recherche le token dans le stockage local (LocalStorage) et ré-authentifie l'utilisateur pour préserver sa session."
        ),
        
        createHeading2("6.3 La Refonte de Navigation et le Cyber Dark Design"),
        createParagraph(
          "Dans le cadre des dernières mises à jour, la structure de la barre de navigation et le design global ont fait l'objet d'un travail soigné :\n" +
          "• Navbar contextuelle : Suppression du bouton Accueil. Un lien central 'Connexion' apparaît lorsque l'utilisateur est déconnecté, et se transforme en 'Tableau de bord' de manière dynamique dès la connexion établie.\n" +
          "• Theme Cyber Dark : Fond ultra-sombre, bordures translucides en verre (glassmorphism), halos colorés derrière les images, animations fluides (animations de fondu d'entrée et effets de pulsation)."
        ),

        // --- PAGE 8: SECURITE ET OPTIMISATIONS ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("7. Sécurité et Optimisations Réalisées"),
        
        createHeading2("7.1 Dispositifs de Sécurité"),
        createBulletPoint("Protection contre les injections SQL :", "Utilisation systématique de l'ORM Eloquent de Laravel et du Query Builder, qui s'appuient sur les requêtes préparées PDO."),
        createBulletPoint("Protection XSS (Cross-Site Scripting) :", "Échappement automatique des caractères spéciaux par le moteur de template Blade côté Laravel, et rendu sécurisé natif des chaînes de caractères par le DOM virtuel de React."),
        createBulletPoint("Politique CORS (Cross-Origin Resource Sharing) :", "Configuration stricte des entêtes de sécurité pour autoriser uniquement les requêtes provenant du domaine frontend légitime."),
        createBulletPoint("Hashing de mots de passe :", "Hachage robuste utilisant l'algorithme Bcrypt (géré par défaut par le mécanisme d'authentification de Laravel)."),

        createHeading2("7.2 Optimisations de Performance"),
        createParagraph(
          "Afin d'assurer des temps de chargement ultra-rapides et de supporter une charge d'utilisateurs importante, les optimisations suivantes ont été appliquées :\n" +
          "• Eager Loading (Chargement précoce) : Utilisation systématique de la méthode `with()` sur les requêtes complexes de base de données (ex: `Dossier::with(['sinistre.client'])`), réduisant le problème classique de requêtes N+1 en base de données.\n" +
          "• Agrégation de requêtes : Calcul des statistiques de l'agent en une seule requête SQL regroupée par agrégateurs (`SUM`, `COUNT`) plutôt que d'exécuter de multiples requêtes individuelles.\n" +
          "• Compilation et Minification : Utilisation de Vite pour optimiser, minifier et découper en fragments (code-splitting) les fichiers CSS et Javascript finaux livrés au navigateur."
        ),

        // --- PAGE 9: GUIDE DE DEPLOIEMENT ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("8. Guide de Déploiement et d'Installation"),
        
        createHeading2("8.1 Prérequis Système"),
        createParagraph(
          "Pour faire fonctionner le projet localement, assurez-vous de disposer des éléments suivants installés :\n" +
          "• PHP >= 8.3 avec les extensions (PDO, OpenSSL, MBString, XML, BCMath).\n" +
          "• Composer (Gestionnaire de dépendances PHP).\n" +
          "• Node.js (v18+) et npm (Gestionnaire de packages Node).\n" +
          "• Serveur de Base de données MySQL."
        ),
        
        createHeading2("8.2 Procédure d'Installation"),
        createParagraph(
          "Suivez les étapes ci-dessous pour déployer et démarrer le projet en environnement de développement :\n\n" +
          "1. Cloner le dépôt et accéder à la racine du projet.\n" +
          "2. Copier le fichier de configuration : `copy .env.example .env` et renseigner les informations de connexion à la base de données MySQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).\n" +
          "3. Installer les dépendances backend : `composer install`.\n" +
          "4. Générer la clé de sécurité de l'application : `php artisan key:generate`.\n" +
          "5. Créer et alimenter la base de données : `php artisan migrate --seed`.\n" +
          "6. Installer les paquets frontend de l'application React : Accéder au dossier `pfeassu` puis exécuter `npm install`.\n" +
          "7. Démarrer le serveur d'API backend : `php artisan serve` (à la racine).\n" +
          "8. Démarrer le serveur de développement React : `npm start` (dans le dossier `pfeassu`)."
        ),

        // --- PAGE 10: CONCLUSION ---
        new Paragraph({ text: "", pageBreakBefore: true }),
        createHeading1("9. Conclusion Générale"),
        
        createHeading2("9.1 Bilan du Projet"),
        createParagraph(
          "Le projet PFEASS a permis de concevoir une plateforme complète et moderne de gestion de sinistres d'assurance. Le découplage entre un backend API robuste sous Laravel v13 et une interface frontend dynamique en React.js fournit une solution performante, évolutive et agréable pour l'utilisateur final."
        ),
        createParagraph(
          "Toutes les fonctionnalités fondamentales de déclaration, de transmission technique aux experts, de validation administrative de documents, de prise de rendez-vous et de suivi des statuts en temps réel ont été intégrées avec succès."
        ),
        
        createHeading2("9.2 Perspectives Futures"),
        createParagraph(
          "Dans le cadre des développements futurs, plusieurs pistes prometteuses peuvent être explorées :\n" +
          "• Intégration de l'Intelligence Artificielle (OCR) : Pour analyser automatiquement les constats amiables et extraire les informations clés (noms, plaques, circonstances) afin d'accélérer l'évaluation de l'expert.\n" +
          "• Module de Signature Électronique : Pour permettre à l'assuré et à l'expert de signer numériquement les accords d'indemnisation de manière juridiquement valable.\n" +
          "• Application Mobile : Développement d'une déclinaison mobile hybride (React Native) permettant à l'assuré de déclarer un sinistre et de prendre des photos directement sur le lieu de l'accident."
        ),
        
        createCallout(
          "Remerciements",
          "Nous adressons nos plus sincères remerciements aux encadrants académiques et aux professionnels du secteur des assurances pour leurs précieux conseils tout au long de la phase d'analyse et d'implémentation de cette plateforme."
        )
      ]
    }
  ]
});

// Écriture du fichier DOCX étendu
const docxOutputPath = path.join(__dirname, '..', 'Rapport_Projet_PFEASS_Extended.docx');
Packer.toBuffer(doc).then((buffer) => {
  fs.writeFileSync(docxOutputPath, buffer);
  console.log("Rapport Word Étendu (.docx) généré avec succès dans la racine du projet.");
}).catch((err) => {
  console.error("Erreur lors de la génération du rapport Word Étendu :", err);
});
