"""
Generate Servo Final Report (Phase 1).

Produces docs/Final_Report.docx. Headings match Final_Report_Criteria.pdf
word-for-word. Phase-2 items are laid out with placeholder notes so the
structure is complete and ready for team input.
"""

from pathlib import Path
from datetime import date
from docx import Document
from docx.shared import Pt, Inches
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml.ns import qn
from docx.oxml import OxmlElement


ROOT = Path(__file__).resolve().parent.parent


def _pick_output_path():
    """Prefer Final_Report.docx; if it's locked (Word open) fall back to
    Final_Report_v2.docx, v3, etc. so regeneration never silently fails."""
    base = ROOT / "docs" / "Final_Report.docx"
    if not base.exists():
        return base
    try:
        with open(base, "a"):
            pass
        return base
    except PermissionError:
        i = 2
        while True:
            alt = ROOT / "docs" / f"Final_Report_v{i}.docx"
            if not alt.exists():
                return alt
            try:
                with open(alt, "a"):
                    pass
                return alt
            except PermissionError:
                i += 1


OUT = _pick_output_path()


def add_h1(doc, text):
    doc.add_heading(text, level=1)


def add_h2(doc, text):
    doc.add_heading(text, level=2)


def add_h3(doc, text):
    doc.add_heading(text, level=3)


def add_p(doc, text):
    doc.add_paragraph(text)


def add_bullets(doc, items):
    for item in items:
        doc.add_paragraph(item, style="List Bullet")


def add_placeholder(doc, note):
    p = doc.add_paragraph()
    run = p.add_run(note)
    run.italic = True


def _append_field(paragraph, instr):
    """Append a Word field (e.g. TOC, PAGE) to a paragraph."""
    run = paragraph.add_run()
    fld_begin = OxmlElement("w:fldChar")
    fld_begin.set(qn("w:fldCharType"), "begin")
    instr_text = OxmlElement("w:instrText")
    instr_text.set(qn("xml:space"), "preserve")
    instr_text.text = instr
    fld_sep = OxmlElement("w:fldChar")
    fld_sep.set(qn("w:fldCharType"), "separate")
    fld_end = OxmlElement("w:fldChar")
    fld_end.set(qn("w:fldCharType"), "end")
    run._r.append(fld_begin)
    run._r.append(instr_text)
    run._r.append(fld_sep)
    run._r.append(fld_end)


def add_toc(doc):
    p = doc.add_paragraph()
    _append_field(p, 'TOC \\o "1-3" \\h \\z \\u')
    note = doc.add_paragraph()
    run = note.add_run(
        "(If the table of contents is blank, right-click it in Word and "
        "choose \"Update Field\" → \"Update entire table\".)"
    )
    run.italic = True
    run.font.size = Pt(9)


def add_page_numbers(doc):
    section = doc.sections[0]
    footer = section.footer
    p = footer.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.add_run("Page ")
    _append_field(p, "PAGE")
    p.add_run(" of ")
    _append_field(p, "NUMPAGES")


def build():
    doc = Document()

    style = doc.styles["Normal"]
    style.font.name = "Calibri"
    style.font.size = Pt(11)

    # ---------- Cover ----------
    for _ in range(5):
        doc.add_paragraph()

    title = doc.add_paragraph()
    title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = title.add_run("Servo")
    run.bold = True
    run.font.size = Pt(36)

    subtitle = doc.add_paragraph()
    subtitle.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = subtitle.add_run("The All-in-One Services Marketplace")
    run.bold = True
    run.font.size = Pt(18)

    doc.add_paragraph()
    sub = doc.add_paragraph()
    sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = sub.add_run("Final Project Report")
    run.italic = True
    run.font.size = Pt(14)

    for _ in range(3):
        doc.add_paragraph()

    group = doc.add_paragraph()
    group.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = group.add_run("CS Group 54")
    run.bold = True
    run.font.size = Pt(13)

    year = doc.add_paragraph()
    year.alignment = WD_ALIGN_PARAGRAPH.CENTER
    year.add_run("Second-Year Group Project").font.size = Pt(11)

    doc.add_paragraph()
    members_cover = doc.add_paragraph()
    members_cover.alignment = WD_ALIGN_PARAGRAPH.CENTER
    members_cover.add_run(
        "K. H. H. A. Kalatuwawa  ·  W. P. P. G. Samaraweera  ·  "
        "P. G. C. Bandara  ·  A. P. Abeysundara  ·  B. S. Kalutharage"
    ).font.size = Pt(10)

    for _ in range(5):
        doc.add_paragraph()

    d = doc.add_paragraph()
    d.alignment = WD_ALIGN_PARAGRAPH.CENTER
    d.add_run(date.today().strftime("%d %B %Y")).font.size = Pt(10)

    doc.add_page_break()

    # ---------- Table of Contents ----------
    toc_heading = doc.add_heading("Table of Contents", level=1)
    add_toc(doc)
    doc.add_page_break()

    # ==================================================================
    # 1. INTRODUCTION
    # ==================================================================
    add_h1(doc, "1. Introduction")

    # --- Domain description ---
    add_h2(doc, "Domain description")
    add_p(doc,
        "Servo operates in the online services-marketplace domain: a two-sided "
        "platform that connects clients who need work done with service providers "
        "who offer that work. Providers span both digital categories (such as "
        "design, development, writing, and consulting) and physical categories "
        "(such as repairs, tutoring, home services, and event-related work). The "
        "platform's target market is Sri Lanka, where a unified, locally focused "
        "services marketplace is currently absent."
    )
    add_p(doc,
        "Within this domain a transaction typically involves discovery (the client "
        "finds a suitable provider or posts a request), negotiation (price, scope, "
        "timeline), delivery (the work is performed, possibly across multiple "
        "milestones), settlement (payment and rating), and aftercare (reviews, "
        "dispute handling). Servo is built to cover this full lifecycle inside a "
        "single web application."
    )

    # --- Current system and limitations ---
    add_h2(doc, "Current system and limitations")
    add_p(doc,
        "At present, clients and service providers in Sri Lanka rely on a "
        "fragmented mix of channels to transact: word of mouth, social-media "
        "groups, classified-ad sites, and international freelance platforms that "
        "are not tailored to the local market. This leads to the following "
        "limitations:"
    )
    add_bullets(doc, [
        "No dedicated, widely recognised local platform that serves both digital and physical service categories in one place.",
        "Existing international platforms charge high fees, focus on digital work, and do not reflect Sri-Lanka-specific pricing, currency, or trust signals.",
        "Discovery is ad-hoc: clients cannot easily compare providers on ratings, portfolios, price, or location.",
        "Communication happens off-platform (messaging apps, phone calls), making it hard to keep a traceable project history or resolve disputes.",
        "There is no structured workflow for tracking project status, deliverables, revisions, or payments, which increases the risk of scams and miscommunication.",
        "Ratings and reviews, where they exist, are informal and not verifiable against real transactions.",
    ])

    # --- Goal & objectives ---
    add_h2(doc, "Goal & objectives")
    add_p(doc,
        "The goal of the project is to design, develop, and deploy a web-based "
        "services marketplace that lets any person in Sri Lanka either offer "
        "services to others or hire someone for upcoming work, through a single "
        "unified portal that covers discovery, communication, project workflow, "
        "payment tracking, and feedback."
    )
    add_p(doc, "The supporting objectives are:")
    add_bullets(doc, [
        "Provide a platform that serves both digital and physical service providers under one system, with category- and location-aware filtering.",
        "Give clients two complementary ways to engage providers: a direct booking flow from a provider's profile, and a request-and-bid flow where providers compete on a client post.",
        "Deliver a responsive, accessible user interface with dedicated dashboards for clients, providers, and administrators.",
        "Track every project with structured status updates, requirement lists, work logs, and deliverables, so both parties have a shared view of progress.",
        "Support trust and safety through verified provider onboarding, bidirectional ratings, review moderation, and an admin panel with visibility over platform activity.",
        "Record payments and commission for every completed project so earnings and platform revenue are auditable.",
        "Keep the architecture scalable and maintainable so that new categories, templates, and features can be added without rewriting core modules.",
    ])

    # --- Process re-engineered ---
    add_h2(doc, "Process re-engineered")
    add_p(doc,
        "Servo replaces the informal, channel-hopping workflow described above "
        "with a single end-to-end process performed entirely inside the platform. "
        "The re-engineered flow differs from the current practice in the "
        "following ways:"
    )
    add_bullets(doc, [
        "Unified discovery: clients browse categorised providers or post a request once, instead of searching across multiple channels.",
        "Two explicit engagement paths: (i) Direct request — the client finds a provider and sends a direct booking; (ii) Indirect request — the client posts a service request, providers submit bids, and the client selects one.",
        "Structured project lifecycle: every accepted engagement becomes a tracked project with requirements, update logs, status transitions (Pending / Completed / Cancelled), and a final review step.",
        "In-platform communication: clients and providers chat through a real-time messaging module with reply threading and online-status indicators, removing the need for external messaging.",
        "Transparent payments: every project produces a payment record with a commission entry, replacing off-the-books cash transactions.",
        "Verifiable trust signals: provider profiles are approved by an administrator before going live, and ratings are tied to real completed projects rather than arbitrary endorsements.",
    ])

    # --- Assumptions ---
    add_h2(doc, "Assumptions, if any")
    add_p(doc, "The project is built under the following assumptions:")
    add_bullets(doc, [
        "Users have a reasonable level of digital literacy and a stable internet connection.",
        "Users register using real personal information; identity documents submitted during provider onboarding are genuine.",
        "The platform's primary market is Sri Lanka; currency, districts, and locations are modelled accordingly.",
        "Revenue is earned through commissions taken on completed transactions.",
        "Clients and providers comply with platform policies; disputes that cannot be resolved between the two parties are escalated to administrators.",
        "There is sufficient demand and supply across both digital and physical categories for the platform to be useful at launch.",
    ])

    # ==================================================================
    # 2. FEASIBILITY STUDY
    # ==================================================================
    add_h1(doc, "2. Feasibility Study")

    add_h2(doc, "Technical feasibility")
    add_bullets(doc, [
        "The required stack — HTML, CSS, JavaScript, PHP, and MySQL — is mature, well documented, and available on all common hosting environments.",
        "The team already has experience building MVC-style PHP/MySQL applications and the real-time chat layer is implemented using the established Ratchet WebSocket library, so no unknown technology is introduced.",
        "Standard shared or VPS hosting is sufficient for the initial deployment; the architecture does not require specialised infrastructure.",
        "Security concerns associated with PHP applications (SQL injection, XSS, CSRF, file-upload abuse) are mitigated using prepared statements, input validation, role-based access control in the base controller, and server-side session management.",
    ])

    add_h2(doc, "Operational feasibility")
    add_bullets(doc, [
        "Survey results indicate that local users are unsatisfied with existing platforms due to high fees, scams, and lack of local focus, confirming that a trustworthy, locally scoped marketplace has real demand.",
        "Administrators manage content, user reports, support, and platform security through a centralised admin dashboard.",
        "The client- and provider-facing UI is designed to be simple, responsive, and intuitive so that users with limited technical background can still transact comfortably.",
    ])

    add_h2(doc, "Schedule feasibility")
    add_bullets(doc, [
        "The total estimated duration of the project is 10-11 months, ending with a deployed first version.",
        "Requirements gathering and planning: 1-2 months.",
        "System and UI design: 3-4 weeks.",
        "Development: 5-6 months.",
        "Testing: 1-2 months.",
        "Deployment and feedback: 3-4 weeks.",
        "With five team members working approximately four hours per day across the active period, the team has a time budget of around 960 hours, which provides comfortable slack for schedule risks.",
    ])

    add_h2(doc, "Resource feasibility")
    add_bullets(doc, [
        "Human resources: the five-member team covers development, UI design, and testing, matching the skill profile the project requires.",
        "Financial resources: the main recurring cost is hosting and a domain name; these are covered by the team and sized for a low-traffic launch.",
    ])

    add_h2(doc, "Legal feasibility")
    add_bullets(doc, [
        "The system follows data-protection principles aligned with Sri Lanka's Personal Data Protection Act and the general guidance of the GDPR.",
        "User-facing terms of service, a privacy policy, and a dispute process are to be published alongside the deployed platform.",
        "Provider identity documents (NIC front, NIC back, resume) are stored only for verification and are not exposed publicly.",
    ])

    add_h2(doc, "Financial feasibility")
    add_bullets(doc, [
        "One-off and recurring infrastructure costs (domain, hosting, payment integration fees) are estimated in the range of LKR 10,000-20,000 during the launch window.",
        "Ongoing operational costs include server maintenance, support, and light marketing.",
        "Revenue sources are: a commission on every completed transaction, optional service add-ons such as profile boosting, and advertising slots for verified providers.",
    ])

    add_h2(doc, "Social feasibility")
    add_p(doc,
        "A Google-Forms survey was conducted with a wide cross-section of "
        "respondents. The headline findings are:"
    )
    add_bullets(doc, [
        "45% of respondents said they had never found a local platform that serves both digital and physical service providers, and a further 30% were unsure — confirming that the target need is not met today.",
        "43.8% said they would use such a platform only as a client, 12.5% only as a provider, and 43.8% as both — suggesting healthy two-sided engagement at launch.",
        "45% of respondents said they would definitely use a locally focused marketplace if it existed, and 50% would consider it once they knew more — leaving only 5% uninterested.",
    ])

    # ==================================================================
    # 3. REQUIREMENTS
    # ==================================================================
    add_h1(doc, "3. Requirements")

    # --- Stakeholders / Actors ---
    add_h2(doc, "Stakeholders / Actors")
    add_p(doc,
        "Four actor roles interact with the system. Role enforcement is applied "
        "centrally by the base controller, which gates every protected action "
        "behind a session check and a role match."
    )
    add_bullets(doc, [
        "Guest — an unauthenticated visitor who can browse the landing page, search providers and services, view public provider profiles, and register or log in.",
        "Client — an authenticated user who posts service requests, books providers directly, manages projects, chats with providers, makes payments, and leaves reviews.",
        "Service Provider — an authenticated, admin-verified user who manages a profile and portfolio, offers services within chosen categories, bids on client requests, handles direct requests, tracks and updates project status, chats with clients, and rates clients in return.",
        "Administrator — a privileged user who approves or rejects provider registrations, manages service categories, monitors payments and commission, moderates user activity, and oversees platform health through the admin dashboard.",
    ])

    # --- Functional and Non-Requirements ---
    add_h2(doc, "Functional and Non-Requirements")

    add_h3(doc, "Functional requirements")

    add_p(doc, "Guest")
    add_bullets(doc, [
        "Shall be able to register a new account or log in using an existing email and password.",
        "Shall be able to search and filter for service providers and services from the public landing page.",
        "Shall be able to view public provider profiles before creating an account.",
    ])

    add_p(doc, "Client")
    add_bullets(doc, [
        "Shall be able to manage and customise the client profile, including profile picture, bio, and contact details.",
        "Shall be able to search, filter, and browse providers by category, skills, and location.",
        "Shall be able to create, edit, publish, and delete service request posts with title, description, price, price type, level, and estimated date.",
        "Shall be able to send a direct request to a specific provider from the provider's profile.",
        "Shall be able to review bids received on a posted request and accept or reject them.",
        "Shall be able to approve or reject provider responses to direct requests.",
        "Shall be able to track active projects, view status updates and work logs, and cancel a project where applicable.",
        "Shall be able to chat with providers in real time, including reply threading and viewing online status.",
        "Shall be able to record payments against completed projects and view invoice history.",
        "Shall be able to leave ratings and written reviews for providers after a project completes.",
        "Shall be able to receive in-platform notifications for new messages, bid activity, and project updates.",
    ])

    add_p(doc, "Service Provider")
    add_bullets(doc, [
        "Shall be able to complete a multi-step registration including personal information, profile, password, identity documents (NIC front, NIC back, resume), and service selection.",
        "Shall be able to manage a provider profile, portfolio, social links, and offered services by category, with per-category pricing, skills, and locations served.",
        "Shall be able to view a feed of client service requests filtered by the provider's categories and skills.",
        "Shall be able to place, edit, and withdraw bids on client requests, including amount, duration, and a bid comment.",
        "Shall be able to accept or decline direct requests sent by clients.",
        "Shall be able to update the status of active projects and add dated work-log entries with descriptions and worked hours.",
        "Shall be able to chat with clients in real time with reply threading and emoji support.",
        "Shall be able to view an earnings dashboard summarising completed projects and associated payments.",
        "Shall be able to rate the clients the provider has worked with after a project completes.",
        "Shall be able to receive in-platform notifications for new messages, bids, and project events.",
    ])

    add_p(doc, "Administrator")
    add_bullets(doc, [
        "Shall be able to sign in to a separate admin portal.",
        "Shall be able to review pending provider registrations, approve them, or reject them with a reason.",
        "Shall be able to monitor the overall platform from the admin dashboard.",
        "Shall be able to view payments and the associated commission entries.",
    ])

    add_h3(doc, "Non-functional requirements")

    add_p(doc, "Performance")
    add_bullets(doc, [
        "The system shall serve typical page requests without noticeable delay under expected concurrent-user load.",
        "Real-time chat shall deliver messages to online recipients within a perceptible-latency threshold.",
    ])
    add_p(doc, "Scalability")
    add_bullets(doc, [
        "The architecture shall support growth in users and categories without rewriting core modules.",
        "Adding new service categories, skills, or locations shall be a data change rather than a code change.",
    ])
    add_p(doc, "Security")
    add_bullets(doc, [
        "User passwords shall be stored using a modern one-way hash.",
        "All protected actions shall be behind a session-based authentication and role check at the base-controller level.",
        "User-submitted content shall be validated and escaped to prevent injection and cross-site scripting.",
        "Sensitive documents uploaded during provider registration (NIC front, NIC back, resume) shall not be exposed through public URLs.",
        "Production traffic shall be served over TLS.",
    ])
    add_p(doc, "Usability")
    add_bullets(doc, [
        "The user interface shall be intuitive and consistent across client, provider, and admin views.",
        "The platform shall be responsive on both desktop and mobile-width screens.",
    ])
    add_p(doc, "Availability")
    add_bullets(doc, [
        "The platform shall aim for minimal downtime, with planned maintenance windows communicated in advance.",
    ])
    add_p(doc, "Reliability")
    add_bullets(doc, [
        "The system shall maintain data integrity across projects, payments, and messages.",
        "Transactional operations shall either complete in full or roll back cleanly on failure.",
    ])
    add_p(doc, "Maintainability")
    add_bullets(doc, [
        "The codebase shall follow a clear MVC separation so each feature can be located, modified, and tested in isolation.",
        "Database configuration and secrets shall be loaded from environment variables rather than hard-coded values.",
    ])
    add_p(doc, "Auditability")
    add_bullets(doc, [
        "Major user actions (registration, login, project events) shall be recorded in the user activity log with timestamps.",
        "Payment records shall retain creation and status-change timestamps for later auditing.",
    ])

    # --- In-scope and out-scope ---
    add_h2(doc, "In-scope and out-scope")

    add_h3(doc, "In-scope")
    add_bullets(doc, [
        "User registration and profile management for clients and providers, including multi-step provider onboarding with identity-document upload.",
        "Search, filter, and navigation of providers and services, with category, skill, location, and price filters.",
        "Client service-request creation and bidding, with provider bids that include amount, duration, and comment, plus bid editing and withdrawal.",
        "Direct booking flow from a provider profile, distinct from the request-and-bid flow.",
        "Provider portfolio and category management, including per-category pricing, skills, and locations served.",
        "Project lifecycle tracking with status, requirements, update logs, and cancellation.",
        "Real-time in-platform messaging between clients and providers over a WebSocket server.",
        "Payment records and commission tracking against completed projects, plus a provider earnings dashboard.",
        "Bidirectional ratings and written reviews attached to completed projects.",
        "Admin dashboard for provider verification and platform oversight.",
        "In-platform notification centre for messages, bids, and project events.",
    ])

    add_h3(doc, "Out-scope")
    add_bullets(doc, [
        "Native mobile applications for Android and iOS (considered for future enhancement).",
        "Social-login integration (Google, Facebook, and similar) for registration and sign-in.",
        "Personalised recommendation engine based on browsing or hiring history.",
        "Gamification such as provider levels, experience points, and achievement badges.",
        "Dual-role company accounts where a provider profile represents an organisation with sub-members.",
        "Escrow-style held payments where funds are released on project completion.",
        "Live payment-gateway integration (the system records payments but does not currently execute online transactions through a gateway in production).",
        "Automated email and push-notification delivery for all events (foundation exists but full delivery across all event types is deferred).",
    ])

    # --- Constraints and limitations ---
    add_h2(doc, "Constraints and limitations, if any")
    add_bullets(doc, [
        "The project must be delivered to a fixed academic deadline.",
        "Use of heavy external web frameworks is avoided; a custom MVC layer is used instead, which limits access to ecosystem tooling such as built-in ORMs or routers.",
        "The budget is limited and is used mainly for server and domain costs.",
        "The system must follow Sri Lanka's Personal Data Protection Act.",
        "Exact real-time location tracking of providers is not implemented; location filtering is at the city/district granularity.",
        "The full system is delivered by a five-person team, which constrains how much can be built in parallel.",
    ])

    # ==================================================================
    # 4. SYSTEM'S ARCHITECTURE
    # ==================================================================
    add_h1(doc, "4. System's Architecture")

    add_h2(doc, "Components and their functionalities")
    add_p(doc,
        "Servo follows a custom three-tier MVC architecture implemented directly "
        "in PHP without an external framework. The major components are:"
    )
    add_bullets(doc, [
        "Front controller (public/index.php): the single HTTP entry point. A regex-based router inspects the url query parameter and dispatches the request to the appropriate controller method.",
        "Core layer (app/core): the Database wrapper around MySQLi that exposes query, fetch, and prepared-statement helpers; the BaseController class that enforces session-based authentication, role checks (client / provider / admin), and shared setup such as loading unread notification counts; and a helpers module for common utilities.",
        "Controllers (app/controllers): feature-oriented classes that handle routed requests — LoginController, RegisterController, HomeController, DashboardController, ProfileController, PostController, ProjectController, ProviderController, MessageController, NotificationController, PaymentController, EarningsController, FeedController, BidController, FileController, NotFoundController, and an admin sub-module (AdminLoginController, AdminDashboardController, AdminProviderController).",
        "Models (app/models): domain classes that encapsulate persistence and business rules — ClientModel, ProviderModel, ProviderCategoriesModel, ProviderSocialModel, PostModel, PostSkillsModel, ProjectModel, BidModel, PaymentModel, MessageModel, NotificationModel, ReviewModel, CategoryModel, SkillsModel, LocationModel, FeedModel, and EarningsModel.",
        "Views (app/views): server-rendered PHP templates grouped by role — client, provider, admin, guest/landing, login, register, plus shared components (SearchHeader, FilterModal) and layout includes (navbar, footer).",
        "Real-time chat server (websocket/ChatServer.php): a long-running PHP process built on the Ratchet library (cboden/ratchet) that implements MessageComponentInterface to manage open connections and broadcast messages between clients and providers.",
        "Static assets (public/assets): custom CSS and JavaScript organised per feature, plus fonts and imagery.",
        "File storage (uploads/): server-side storage for user-submitted content such as profile pictures, NIC documents, and portfolio files.",
        "Configuration (config.php + .env): environment-driven configuration that exposes database credentials, base URL, application secret, and the WebSocket URL.",
        "Relational database (MySQL): a twenty-eight-table schema that persists users, providers, clients, posts, projects, bids, payments, messages, conversations, reviews, categories, skills, locations, districts, notifications, activity logs, and supporting lookup tables.",
    ])

    add_h2(doc, "Component interactions")
    add_p(doc,
        "A typical request flows through the system as follows. The user's "
        "browser issues an HTTP request; the web server rewrites the URL to "
        "public/index.php. The front controller parses the url parameter, "
        "instantiates the matching controller, and invokes the target method. "
        "The controller extends BaseController, which enforces authentication and "
        "role authorisation before the method runs. The controller then calls one "
        "or more models, which use the Database wrapper to issue prepared SQL "
        "statements against MySQL. The controller assembles the result and "
        "renders a view template, which returns HTML to the browser."
    )
    add_p(doc,
        "Real-time messaging runs on a parallel channel. When a user opens a "
        "conversation, the browser opens a WebSocket connection to the Ratchet "
        "chat server. The server keeps the authenticated connection open, "
        "persists incoming messages through MessageModel, and pushes delivery to "
        "the recipient's connection if it is online. Unread-count state for the "
        "notification centre is computed server-side on each HTTP request and "
        "injected into views by BaseController, so the UI stays consistent with "
        "the database even when a user reopens the site."
    )
    add_p(doc,
        "The client-provider engagement loop ties these pieces together. A "
        "client posts a request, which PostController writes through PostModel; "
        "matching providers see it in the feed produced by FeedController and "
        "FeedModel; a provider submits a bid via BidController; the client "
        "accepts one, which creates a project row via ProjectModel; from that "
        "point forward status, requirements, update logs, and messages are "
        "attached to the project until it reaches a completed or cancelled "
        "state, at which point payments are recorded through PaymentController "
        "and reviews through ReviewModel. Administrators interact with the same "
        "data through the admin sub-module: provider registrations land in a "
        "pending state and AdminProviderController transitions them to approved "
        "or rejected."
    )

    # ==================================================================
    # 5. SYSTEM DESIGN DIAGRAMS
    # ==================================================================
    add_h1(doc, "5. System Design Diagrams")

    diagrams_dir = ROOT / "docs" / "diagrams"

    def embed(figure_file, caption):
        path = diagrams_dir / figure_file
        if path.exists():
            doc.add_picture(str(path), width=Inches(6.3))
            cap = doc.add_paragraph()
            cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
            run = cap.add_run(caption)
            run.italic = True
            run.font.size = Pt(9)
        else:
            add_placeholder(doc, f"[missing diagram: {figure_file}]")

    add_h2(doc, "Use case diagram")
    add_p(doc,
        "Figure 5.1 shows the four actors — Guest, Client, Service Provider, "
        "and Administrator — and the use cases each can initiate within the "
        "Servo system boundary. Use cases are grouped by role: Guests read "
        "and register; Clients post requests, review bids, accept or reject, "
        "and book directly; Providers onboard, manage offerings, bid, accept "
        "direct bookings, track projects, and exchange messages; "
        "Administrators verify providers and monitor payments and commission. "
        "All authenticated actors can manage their profile, receive "
        "notifications, and participate in the review system."
    )
    embed("use_case.png", "Figure 5.1 — Use Case Diagram")

    add_h2(doc, "Class diagram")
    add_p(doc,
        "Figure 5.2 captures the domain classes derived from the models in "
        "app/models/. Client, Provider, and Admin are the actor classes. "
        "ProviderCategory links a Provider to a Category (with default price, "
        "price type, and negotiability) and is further associated with Skills "
        "and Locations through many-to-many relationships. A Client's Post is "
        "linked to a Category; Providers place Bids against Posts; an accepted "
        "Bid (or a direct request) gives rise to a Project, which carries "
        "ProjectUpdateLog entries, a Payment, and Reviews. A Conversation "
        "pairs a Client and Provider and holds Messages; Notifications are "
        "emitted to any user role."
    )
    embed("class_diagram.png", "Figure 5.2 — Class Diagram (domain model)")

    add_h2(doc, "ER diagram")
    add_p(doc,
        "Figure 5.3 renders the relational schema from database.sql. Entities "
        "are drawn with their primary key and key foreign-key columns, and "
        "lines indicate the foreign-key constraints declared in the schema. "
        "The core backbone runs client → post → bids → project → payment / "
        "reviews, with provider participating in post, bids, and "
        "provider_categories. Catalog data (category, skills, districts, "
        "location) is normalised through the join tables "
        "provider_categories_has_skills and "
        "provider_categories_has_location. The messaging sub-domain is "
        "rooted at conversation (client × provider) with messages as its "
        "children. The notification_center table stores per-user in-platform "
        "notifications."
    )
    embed("er_diagram.png", "Figure 5.3 — Entity-Relationship Diagram")

    add_h2(doc, "Activity diagrams")
    add_p(doc,
        "Four activity diagrams cover the principal workflows:"
    )
    add_bullets(doc, [
        "Figure 5.4a — Indirect request-and-bid flow: the client posts a "
        "request, matching providers are notified, providers bid, the client "
        "reviews and either accepts (creating a Project and opening a "
        "conversation thread) or rejects.",
        "Figure 5.4b — Direct booking flow: the client discovers a provider "
        "and submits a direct request; the provider accepts or declines, "
        "which either creates a Project or closes the request with a reason.",
        "Figure 5.4c — Payment lifecycle: a Pending payment row is created "
        "when a project begins, transitions to Paid (with Paid_At) on "
        "completion or to Refunded if a dispute is raised; commission is "
        "computed and provider earnings are updated on successful payment.",
        "Figure 5.4d — Provider registration and verification: a five-step "
        "onboarding flow followed by OTP email verification and administrator "
        "approval or rejection with a reason.",
    ])
    embed("activity_bidding.png",
          "Figure 5.4a — Activity: Indirect Request & Bidding")
    embed("activity_booking.png",
          "Figure 5.4b — Activity: Direct Booking")
    embed("activity_payment.png",
          "Figure 5.4c — Activity: Payment Lifecycle")
    embed("activity_provider_registration.png",
          "Figure 5.4d — Activity: Provider Registration & Verification")

    # ==================================================================
    # 6. COMPLETENESS OF THE PROJECT
    # ==================================================================
    add_h1(doc, "6. Completeness of the Project")

    add_h2(doc, "Functionalities completed")
    add_p(doc, "The following functionalities are implemented and wired end-to-end in the current codebase:")
    add_bullets(doc, [
        "Authentication — client and provider sign-in, sign-out, and session management.",
        "Client registration — email-based account creation.",
        "Provider registration — five-step onboarding covering personal information, profile, password, identity documents (NIC front, NIC back, resume), and service selection, with OTP verification.",
        "Admin authentication and admin dashboard landing.",
        "Admin provider verification — reviewing pending provider registrations and transitioning them to approved or rejected, with a rejection-reason field.",
        "Client profile management — profile picture, bio, contact information, and account settings.",
        "Provider profile and portfolio management — bio, profile picture, social links, per-category offerings (title, description, default price, price type, portfolio link, negotiability, skills, and locations served).",
        "Provider and service discovery — categorised browsing, keyword search, and filter UI for clients and guests.",
        "Client service request posts — create, edit, publish, list, view, and delete, with category, skills, price, price type, level, and estimated date.",
        "Bidding system — providers place bids with amount, duration, and comment; bids can be edited and withdrawn; clients review and accept or reject them.",
        "Direct booking — clients send a direct request to a chosen provider; the provider can accept or decline.",
        "Project lifecycle — accepted requests become projects with status tracking (Pending / Completed / Cancelled), a requirements list, and dated update-log entries including worked hours.",
        "Real-time messaging — conversations between clients and providers backed by a Ratchet WebSocket server, with reply threading, emoji support, delivered/read status, starring, and archiving.",
        "Payment records — per-project payment rows with status (Paid / Pending / Refunded), hold and paid timestamps, and a commission field.",
        "Provider earnings dashboard — database-driven view of completed project value and earnings.",
        "Ratings and reviews — bidirectional reviews attached to completed projects, with title, description, rating, and edit history.",
        "In-platform notification centre — per-user notification records with title, section, and read state, shown through a dedicated view and surfaced as unread counts in navigation.",
        "Admin payment and commission visibility — commission per transaction is recorded on the payment row and is visible to administrators.",
    ])

    add_h2(doc, "Functionalities yet to complete")
    add_p(doc,
        "The following items from the original scope are either partially "
        "implemented or deferred. The list below is the Phase-1 best estimate "
        "based on code inspection; it will be finalised in Phase 2 after the "
        "team confirms the current sprint state."
    )
    add_bullets(doc, [
        "Automated email and push-notification delivery across all event types (messages, bids, project status changes). A mail helper exists but end-to-end delivery for every event is not yet wired.",
        "Live payment-gateway integration. Payments are recorded and commission is tracked, but online settlement through a third-party gateway (card or bank) is not yet live in production.",
        "Admin user moderation tools — banning, timing out, and flagging users — as dedicated UI flows.",
        "Admin content moderation for reviews and service requests as first-class admin screens.",
        "Admin report generation (analytics and platform reports with filters and time ranges).",
        "Admin service-category management UI (add, edit, remove categories).",
        "Request boosting and other paid visibility add-ons.",
    ])
    add_p(doc,
        "The items above are drawn from two sources: features explicitly "
        "listed in the proposal that have no or partial implementation in "
        "the repository, and functionality areas where admin or client-side "
        "UI stubs exist without full end-to-end wiring. None of these affect "
        "the core two-sided marketplace flow (post → bid → accept → project "
        "→ payment → review), which is complete and functional."
    )

    add_h2(doc, "Individual contribution of the team members")
    add_p(doc,
        "The feature ownership below is derived from git authorship — for "
        "each controller, model, and view folder the primary committer was "
        "identified from the repository history, and that committer is "
        "credited as the owner of the corresponding feature. The percentage "
        "figures are the share of total authored commits in the main branches "
        "as of the reporting date and should be regarded as a starting point; "
        "the team should adjust them to reflect non-code contributions "
        "(design, testing, documentation, diagrams, presentations) before "
        "final submission."
    )

    members = [
        {
            "name": "K. H. H. A. Kalatuwawa",
            "commits_alias": "himath-adithya / Himath Adithya / K.H.H.A. Kalatuwawa",
            "features": [
                "Authentication base and role enforcement (BaseController).",
                "Bidding system end-to-end — BidController and related views; "
                "bid placement, editing, withdrawal, client review and "
                "accept/reject.",
                "Client and provider dashboards (DashboardController).",
                "Provider earnings view (EarningsController).",
                "Feed / discovery surface (FeedController, FeedModel).",
                "In-platform notification centre (NotificationController, "
                "NotificationModel).",
                "Payment record lifecycle (PaymentController, PaymentModel) — "
                "status, hold/paid timestamps, commission.",
                "Project lifecycle — ProjectController, ProjectModel, update "
                "log, requirements list.",
            ],
            "tests": [
                "Client and provider can sign in and reach their dashboard.",
                "Provider can place, edit, and withdraw a bid on a client post.",
                "Client can accept a bid and a Project record is created with "
                "Pending status.",
                "Project completion marks the payment row as Paid with a "
                "Paid_At timestamp and updates provider earnings.",
                "Notifications are recorded for each event and the unread "
                "counter in the navigation updates correctly.",
            ],
        },
        {
            "name": "W. P. P. G. Samaraweera",
            "commits_alias": "Pasindu Gihan",
            "features": [
                "Application bootstrap and regex routing (public/index.php).",
                "Home / landing surface (HomeController).",
                "Client registration and provider five-step onboarding with "
                "OTP verification (RegisterController).",
                "Sign-in and session management (LoginController).",
                "File upload and serving for NIC, resume, portfolio, and "
                "profile images (FileController).",
                "Real-time messaging — MessageController, MessageModel, and "
                "the Ratchet WebSocket server (websocket/ChatServer.php).",
                "Admin authentication, dashboard, and provider-verification "
                "screens (app/controllers/admin/).",
                "Catalog reference data — CategoryModel, SkillsModel, "
                "LocationModel, AdminUserModel.",
            ],
            "tests": [
                "New client can register and receive OTP; provider can "
                "complete all five onboarding steps and reach the pending "
                "state.",
                "Authenticated sessions persist across protected routes; "
                "unauthenticated access is redirected to sign-in.",
                "Two browsers exchange real-time messages through the "
                "WebSocket server with delivered and read indicators.",
                "Admin can sign in, view pending providers, and approve or "
                "reject with a reason, which updates provider.Status.",
                "Uploaded NIC and resume files are stored under the uploads "
                "folder and served only to authorised users.",
            ],
        },
        {
            "name": "P. G. C. Bandara",
            "commits_alias": "Chethiya Bandara",
            "features": [
                "Client service-request post lifecycle — PostController, "
                "PostModel, PostSkillsModel; create, edit, publish, list, "
                "view, delete with category, skills, price, price type, "
                "level, estimated date.",
                "Client and provider profile management — ProfileController, "
                "ClientModel, ProviderModel, ProviderSocialModel.",
                "Provider discovery and directory — ProviderController, "
                "ProviderCategoriesModel; categorised browsing, search, "
                "filters.",
                "Ratings and reviews — ReviewModel; bidirectional reviews "
                "on completed projects with edit history.",
                "Bids persistence layer — BidModel.",
            ],
            "tests": [
                "Client can create, edit, and delete a service-request post "
                "and select required skills.",
                "Client and provider can update profile picture, bio, and "
                "social links and the changes are visible on the public "
                "profile.",
                "Browsing by category and searching by keyword returns the "
                "expected providers and services.",
                "Once a project is marked completed, both client and "
                "provider can leave a review with a rating and description.",
                "Reviews can be edited and the edit history flag is "
                "recorded.",
            ],
        },
        {
            "name": "A. P. Abeysundara",
            "commits_alias": "A. P. ABEYSUNDARA / AkilaPrabhashwara",
            "features": [
                "Provider earnings data layer (EarningsModel).",
                "Payment UI for clients including invoice view "
                "(app/views/client/Payments/, invoice.php).",
                "Recent integration work on PaymentController and "
                "PaymentModel for invoice generation and payment listing.",
            ],
            "tests": [
                "Earnings dashboard aggregates only completed projects and "
                "matches the sum of Paid payment rows for the provider.",
                "Client payments index lists the correct rows with the right "
                "status for the signed-in client.",
                "Invoice view renders with the correct project, client, "
                "provider, amount, and commission fields.",
            ],
        },
        {
            "name": "B. S. Kalutharage",
            "commits_alias": "Bashitha Sandeepa Kalutharage / Bashitha123-gif",
            "features": [
                "Supporting work across the shared views and schema "
                "(database.sql) during integration.",
                "Focused contributions to be confirmed by the team; items "
                "are tracked through the commit history but are smaller in "
                "volume than the other members.",
            ],
            "tests": [
                "Schema migrations apply cleanly against a fresh MySQL "
                "database and all seeded reference data loads.",
                "Shared navigation, footer, and layout partials render "
                "consistently across client, provider, and admin areas.",
            ],
        },
    ]

    commits_share = {
        "K. H. H. A. Kalatuwawa": 154,
        "W. P. P. G. Samaraweera": 147,
        "P. G. C. Bandara": 66,
        "B. S. Kalutharage": 11,
        "A. P. Abeysundara": 8,
    }
    total = sum(commits_share.values())

    for m in members:
        add_h3(doc, m["name"])
        add_p(doc, f"Git alias(es): {m['commits_alias']}.")
        p = doc.add_paragraph()
        p.add_run("Functionalities completed: ").bold = True
        add_bullets(doc, m["features"])
        p = doc.add_paragraph()
        p.add_run("Test cases: ").bold = True
        add_bullets(doc, m["tests"])
        share = commits_share[m["name"]] / total * 100
        p = doc.add_paragraph()
        p.add_run("Individual contribution (commit-share baseline): ").bold = True
        p.add_run(f"{share:.1f}% ")
        run = p.add_run(
            "(evidence-based baseline from git commit counts; "
            "team to adjust for non-code contributions before submission)."
        )
        run.italic = True

    add_page_numbers(doc)

    OUT.parent.mkdir(parents=True, exist_ok=True)
    doc.save(OUT)
    print(f"Wrote {OUT}")


if __name__ == "__main__":
    build()
