"""
Generate system-design diagrams for the Servo final report.

Outputs PNGs into docs/diagrams/:
  use_case.png        — actors and their use cases (Guest, Client, Provider, Admin)
  class_diagram.png   — key domain classes and relationships (from app/models/)
  er_diagram.png      — entity-relationship diagram (from database.sql)
  activity_bidding.png, activity_booking.png, activity_payment.png,
  activity_provider_registration.png
"""

from pathlib import Path
import matplotlib.pyplot as plt
import matplotlib.patches as mpatches

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / "docs" / "diagrams"
OUT.mkdir(parents=True, exist_ok=True)


# ----------------------------- helpers ---------------------------------

def box(ax, x, y, w, h, text, facecolor="#f3f4f6", edgecolor="#111827",
        fontsize=9, bold=False):
    p = mpatches.FancyBboxPatch(
        (x, y), w, h,
        boxstyle="round,pad=0.02,rounding_size=0.08",
        linewidth=1.1, facecolor=facecolor, edgecolor=edgecolor,
    )
    ax.add_patch(p)
    ax.text(x + w / 2, y + h / 2, text,
            ha="center", va="center", fontsize=fontsize,
            fontweight=("bold" if bold else "normal"))


def oval(ax, x, y, w, h, text, facecolor="#dbeafe", fontsize=8):
    e = mpatches.FancyBboxPatch(
        (x, y), w, h,
        boxstyle="round,pad=0.01,rounding_size=0.25",
        linewidth=1.0, facecolor=facecolor, edgecolor="#1e3a8a",
    )
    ax.add_patch(e)
    ax.text(x + w / 2, y + h / 2, text, ha="center", va="center",
            fontsize=fontsize)


def arrow(ax, x1, y1, x2, y2, color="#111827", style="-|>", lw=1.0):
    ax.annotate("",
                xy=(x2, y2), xycoords="data",
                xytext=(x1, y1), textcoords="data",
                arrowprops=dict(arrowstyle=style, color=color, lw=lw))


def line(ax, x1, y1, x2, y2, color="#4b5563", lw=0.8):
    ax.plot([x1, x2], [y1, y2], color=color, lw=lw)


def stickman(ax, x, y, label, scale=0.4):
    # Head
    ax.add_patch(mpatches.Circle((x, y + scale * 1.1), scale * 0.18,
                                 fill=False, lw=1.2))
    # Body
    line(ax, x, y + scale * 0.92, x, y + scale * 0.25, lw=1.2)
    # Arms
    line(ax, x - scale * 0.35, y + scale * 0.7, x + scale * 0.35, y + scale * 0.7, lw=1.2)
    # Legs
    line(ax, x, y + scale * 0.25, x - scale * 0.28, y - scale * 0.25, lw=1.2)
    line(ax, x, y + scale * 0.25, x + scale * 0.28, y - scale * 0.25, lw=1.2)
    ax.text(x, y - scale * 0.45, label, ha="center", va="top",
            fontsize=9, fontweight="bold")


def save(fig, name):
    path = OUT / name
    fig.savefig(path, dpi=170, bbox_inches="tight", facecolor="white")
    plt.close(fig)
    print(f"  wrote {path.relative_to(ROOT)}")


# ----------------------------- use case --------------------------------

def use_case_diagram():
    fig, ax = plt.subplots(figsize=(12, 9))
    ax.set_xlim(0, 12); ax.set_ylim(0, 10); ax.axis("off")

    # System boundary
    sys_box = mpatches.Rectangle((2.3, 0.6), 7.4, 8.8, linewidth=1.2,
                                 edgecolor="#111827", facecolor="none",
                                 linestyle="-")
    ax.add_patch(sys_box)
    ax.text(6, 9.15, "Servo Platform", fontsize=11, fontweight="bold",
            ha="center")

    # Actors
    stickman(ax, 0.9, 7.6, "Guest")
    stickman(ax, 0.9, 5.2, "Client")
    stickman(ax, 11.1, 6.4, "Service Provider")
    stickman(ax, 11.1, 2.8, "Administrator")

    # Use cases (left column — client/guest)
    uc = [
        # (x, y, w, h, label, actors)
        (3.0, 7.8, 2.3, 0.55, "Browse services",             ["Guest", "Client"]),
        (3.0, 7.1, 2.3, 0.55, "Search & filter providers",   ["Guest", "Client"]),
        (3.0, 6.4, 2.3, 0.55, "View provider profile",       ["Guest", "Client"]),
        (3.0, 5.7, 2.3, 0.55, "Register account",            ["Guest"]),
        (3.0, 5.0, 2.3, 0.55, "Authenticate",                ["Client", "Provider", "Admin"]),
        (3.0, 4.3, 2.3, 0.55, "Manage profile",              ["Client", "Provider"]),
        (3.0, 3.6, 2.3, 0.55, "Post service request",        ["Client"]),
        (3.0, 2.9, 2.3, 0.55, "Review received bids",        ["Client"]),
        (3.0, 2.2, 2.3, 0.55, "Accept / reject bid",         ["Client"]),
        (3.0, 1.5, 2.3, 0.55, "Direct-book provider",        ["Client"]),
        # right column — provider / admin
        (6.6, 8.1, 2.9, 0.55, "Complete 5-step onboarding",  ["Provider"]),
        (6.6, 7.4, 2.9, 0.55, "Manage portfolio / services", ["Provider"]),
        (6.6, 6.7, 2.9, 0.55, "Place / edit / withdraw bid", ["Provider"]),
        (6.6, 6.0, 2.9, 0.55, "Accept / decline direct booking", ["Provider"]),
        (6.6, 5.3, 2.9, 0.55, "Track project & update log",  ["Client", "Provider"]),
        (6.6, 4.6, 2.9, 0.55, "Exchange real-time messages", ["Client", "Provider"]),
        (6.6, 3.9, 2.9, 0.55, "Record / view payment",       ["Client", "Provider"]),
        (6.6, 3.2, 2.9, 0.55, "Leave / edit review",         ["Client", "Provider"]),
        (6.6, 2.5, 2.9, 0.55, "Receive notifications",       ["Client", "Provider", "Admin"]),
        (6.6, 1.8, 2.9, 0.55, "Verify providers",            ["Admin"]),
        (6.6, 1.1, 2.9, 0.55, "Monitor payments & commission", ["Admin"]),
    ]

    actor_xy = {
        "Guest":    (0.9, 7.6),
        "Client":   (0.9, 5.2),
        "Provider": (11.1, 6.4),
        "Admin":    (11.1, 2.8),
    }

    for x, y, w, h, label, actors in uc:
        oval(ax, x, y, w, h, label)
        cx, cy = x + w / 2, y + h / 2
        for a in actors:
            ax_x, ax_y = actor_xy[a]
            side = "right" if ax_x < 6 else "left"
            edge_x = x if side == "right" else x + w
            line(ax, ax_x + (0.45 if side == "right" else -0.45),
                 ax_y + 0.3, edge_x, cy, lw=0.6)

    ax.set_title("Figure 5.1 — Use Case Diagram", fontsize=12,
                 fontweight="bold", pad=10)
    save(fig, "use_case.png")


# ----------------------------- class diagram ----------------------------

def class_diagram():
    fig, ax = plt.subplots(figsize=(13, 9.5))
    ax.set_xlim(0, 13); ax.set_ylim(0, 10); ax.axis("off")

    def cls(x, y, name, attrs):
        w = 2.5
        h = 0.4 + 0.25 * len(attrs)
        # header
        box(ax, x, y + h - 0.4, w, 0.4, name, facecolor="#1e40af",
            edgecolor="#1e40af", bold=True)
        ax.texts[-1].set_color("white")
        # body
        body = mpatches.Rectangle((x, y), w, h - 0.4, linewidth=1.0,
                                  edgecolor="#1e40af", facecolor="#eff6ff")
        ax.add_patch(body)
        for i, a in enumerate(attrs):
            ax.text(x + 0.08, y + h - 0.55 - i * 0.25, a, fontsize=7.5,
                    va="center", ha="left")
        return (x, y, w, h)

    # Top row — actors
    client = cls(0.3, 7.5, "Client",
                 ["- Client_ID", "- Email", "- Password",
                  "- Name", "- Profile_Pic"])
    provider = cls(5.1, 7.5, "Provider",
                   ["- Provider_ID", "- Email", "- NIC_Front/Back",
                    "- Resume", "- Status", "- Bio"])
    admin = cls(10.0, 7.5, "Admin",
                ["- User_ID", "- Email", "- Password",
                 "- Role"])

    # Middle row — catalog + offerings
    category = cls(5.1, 5.3, "Category",
                   ["- Category_ID", "- Name"])
    pcat = cls(8.5, 5.3, "ProviderCategory",
               ["- ID", "- Title", "- Description",
                "- Default_Price", "- Price_Type",
                "- Negotiable"])
    skills = cls(11.2, 5.3, "Skill",
                 ["- Skill_ID", "- Name"])
    location = cls(8.5, 3.1, "Location",
                   ["- Location_ID", "- Address",
                    "- District_ID"])

    # Post + bid + project
    post = cls(0.3, 5.3, "Post",
               ["- Post_ID", "- Client_ID",
                "- Category_ID", "- Title",
                "- Description", "- Price",
                "- Price_Type", "- Level",
                "- Estimated_Date"])
    bid = cls(3.1, 5.3, "Bid",
              ["- Bid_ID", "- Post_ID",
               "- Provider_ID", "- Amount",
               "- Duration", "- Comment",
               "- Status"])
    project = cls(0.3, 2.8, "Project",
                  ["- Project_ID", "- Post_ID",
                   "- Status", "- Start_Date",
                   "- End_Date"])
    plog = cls(3.1, 2.8, "ProjectUpdateLog",
               ["- Log_ID", "- Project_ID",
                "- Date", "- Hours",
                "- Note"])
    payment = cls(0.3, 0.6, "Payment",
                  ["- Payment_ID", "- Project_ID",
                   "- Amount", "- Status",
                   "- Commission",
                   "- Hold_At", "- Paid_At"])
    review = cls(3.1, 0.6, "Review",
                 ["- Review_ID", "- Project_ID",
                  "- Author", "- Title",
                  "- Description", "- Rating"])

    # Messaging
    conv = cls(6.0, 2.8, "Conversation",
               ["- ID", "- Client_ID",
                "- Provider_ID"])
    msg = cls(6.0, 0.6, "Message",
              ["- ID", "- Conversation_ID",
               "- Sender", "- Body",
               "- Status", "- Starred"])
    notif = cls(10.0, 2.8, "Notification",
                ["- ID", "- User_ID",
                 "- Title", "- Section",
                 "- Read"])

    # Relations (simple lines)
    def rel(a, b, label=""):
        (x1, y1, w1, h1) = a
        (x2, y2, w2, h2) = b
        cx1 = x1 + w1 / 2; cy1 = y1 + h1 / 2
        cx2 = x2 + w2 / 2; cy2 = y2 + h2 / 2
        line(ax, cx1, cy1, cx2, cy2, color="#4b5563", lw=0.9)
        if label:
            ax.text((cx1 + cx2) / 2, (cy1 + cy2) / 2, label,
                    fontsize=7, color="#374151",
                    bbox=dict(facecolor="white", edgecolor="none", pad=1))

    rel(client, post, "1..*")
    rel(post, bid, "1..*")
    rel(provider, bid, "1..*")
    rel(post, project, "1..1")
    rel(project, payment, "1..1")
    rel(project, review, "1..*")
    rel(project, plog, "1..*")
    rel(client, conv)
    rel(provider, conv)
    rel(conv, msg, "1..*")
    rel(provider, pcat, "1..*")
    rel(category, pcat)
    rel(pcat, skills, "*..*")
    rel(pcat, location, "*..*")
    rel(post, category)
    rel(admin, notif)
    rel(client, notif)
    rel(provider, notif)

    ax.set_title("Figure 5.2 — Class Diagram (domain model)",
                 fontsize=12, fontweight="bold", pad=10)
    save(fig, "class_diagram.png")


# ----------------------------- ER diagram -------------------------------

def er_diagram():
    """
    Hand-positioned ER diagram grouped by sub-domain.
    Positions chosen so related tables sit near each other; FK edges
    read against database.sql.
    """
    fig, ax = plt.subplots(figsize=(14, 10))
    ax.set_xlim(0, 14); ax.set_ylim(0, 10); ax.axis("off")

    # (name, x, y, w, h, key fields)
    tables = {
        "client":        (0.3, 8.3, 2.2, 1.2, ["PK Client_ID", "Email", "Password", "Name"]),
        "provider":      (5.7, 8.3, 2.4, 1.5, ["PK Provider_ID", "Email", "Status",
                                               "NIC_Front", "NIC_Back"]),
        "admin":         (11.3, 8.3, 2.2, 1.2, ["PK User_ID", "Email", "Role"]),
        "category":      (5.8, 6.0, 2.2, 1.0, ["PK Category_ID", "Name"]),
        "skills":        (11.4, 6.0, 2.1, 0.9, ["PK Skill_ID", "Name"]),
        "provider_categories": (5.8, 4.3, 2.4, 1.4,
                                ["PK ID", "FK Provider_ID",
                                 "FK Category_ID", "Title",
                                 "Default_Price"]),
        "districts":     (11.4, 4.3, 2.1, 0.9, ["PK ID", "Name"]),
        "location":      (11.4, 2.9, 2.1, 1.0,
                          ["PK Location_ID", "FK District_ID", "Address"]),
        "provider_categories_has_location":
                          (8.5, 3.0, 2.5, 1.4,
                           ["FK Provider_Categories_ID",
                            "FK Location_ID", "FK District_ID"]),
        "provider_categories_has_skills":
                          (8.5, 4.7, 2.5, 1.2,
                           ["FK Provider_Categories_ID",
                            "FK Skills_Skill_ID"]),
        "post":          (0.3, 6.0, 2.6, 1.6,
                          ["PK Post_ID", "FK Client_ID",
                           "FK Provider_ID",
                           "FK Category_ID",
                           "FK Provider_Categories_ID",
                           "Title", "Price", "Level"]),
        "post_need_skills": (3.2, 6.0, 2.3, 1.0,
                             ["FK Post_ID", "FK Skill_ID"]),
        "bids":          (0.3, 4.2, 2.6, 1.3,
                          ["PK Bid_ID", "FK Post_ID",
                           "FK Provider_ID",
                           "Amount", "Status"]),
        "project":       (0.3, 2.4, 2.6, 1.3,
                          ["PK Project_ID", "FK Post_ID",
                           "Status", "Start/End"]),
        "project_requirements": (3.2, 2.4, 2.4, 1.0,
                                  ["FK Project_ID", "Requirement"]),
        "project_update_log": (3.2, 0.9, 2.4, 1.1,
                                ["PK Log_ID", "FK Project_ID",
                                 "Date", "Hours", "Note"]),
        "payment":       (0.3, 0.7, 2.6, 1.3,
                          ["PK Payment_ID", "FK Project_ID",
                           "Amount", "Status", "Commission"]),
        "reviews":       (5.9, 0.7, 2.5, 1.3,
                          ["PK Review_ID", "FK Project_ID",
                           "Rating", "Title"]),
        "conversation":  (8.6, 6.2, 2.5, 1.2,
                          ["PK ID", "FK Client_ID",
                           "FK Provider_ID"]),
        "messages":      (8.6, 0.7, 2.5, 1.3,
                          ["PK ID", "FK Conversation_ID",
                           "Sender", "Body", "Status"]),
        "provider_social": (3.2, 8.3, 2.3, 1.1,
                            ["PK ID", "FK Provider_ID",
                             "Link"]),
        "notification_center": (11.3, 0.7, 2.3, 1.3,
                                 ["PK ID", "User_ID", "Title",
                                  "Section", "Read"]),
    }

    def tbl(name):
        x, y, w, h, attrs = tables[name]
        # header
        hb = mpatches.Rectangle((x, y + h - 0.35), w, 0.35,
                                facecolor="#1e40af", edgecolor="#1e40af")
        ax.add_patch(hb)
        ax.text(x + w / 2, y + h - 0.175, name, color="white",
                fontsize=8, fontweight="bold", ha="center", va="center")
        # body
        body = mpatches.Rectangle((x, y), w, h - 0.35,
                                  facecolor="#eff6ff", edgecolor="#1e40af")
        ax.add_patch(body)
        for i, a in enumerate(attrs):
            ax.text(x + 0.05, y + h - 0.52 - i * 0.2, a,
                    fontsize=6.5, va="center", ha="left")

    for name in tables:
        tbl(name)

    # Foreign-key edges: (from, to)
    fks = [
        ("bids", "post"), ("bids", "provider"),
        ("conversation", "client"), ("conversation", "provider"),
        ("messages", "conversation"),
        ("payment", "project"),
        ("post", "category"), ("post", "client"),
        ("post", "provider"), ("post", "provider_categories"),
        ("post_need_skills", "post"), ("post_need_skills", "skills"),
        ("project", "post"),
        ("project_requirements", "project"),
        ("project_update_log", "project"),
        ("provider_categories", "category"),
        ("provider_categories", "provider"),
        ("provider_categories_has_location", "location"),
        ("provider_categories_has_location", "provider_categories"),
        ("provider_categories_has_location", "districts"),
        ("provider_categories_has_skills", "provider_categories"),
        ("provider_categories_has_skills", "skills"),
        ("provider_social", "provider"),
        ("reviews", "project"),
        ("location", "districts"),
    ]

    def center(t):
        x, y, w, h, _ = tables[t]
        return (x + w / 2, y + h / 2)

    for a, b in fks:
        x1, y1 = center(a); x2, y2 = center(b)
        line(ax, x1, y1, x2, y2, color="#6b7280", lw=0.5)

    ax.set_title("Figure 5.3 — Entity-Relationship Diagram",
                 fontsize=12, fontweight="bold", pad=10)
    save(fig, "er_diagram.png")


# ----------------------------- activity diagrams ------------------------

def _activity(fig_name, title, nodes, edges):
    """
    nodes: list of (x, y, w, h, label, kind) where kind in
           {"start","end","action","decision","io"}.
    edges: list of (i, j, label)
    """
    fig, ax = plt.subplots(figsize=(6.5, 9))
    ax.set_xlim(0, 6.5); ax.set_ylim(0, 10); ax.axis("off")
    centers = []
    for (x, y, w, h, label, kind) in nodes:
        cx, cy = x + w / 2, y + h / 2
        centers.append((cx, cy, w, h))
        if kind == "start":
            ax.add_patch(mpatches.Circle((cx, cy), 0.18,
                                         facecolor="#111827"))
            ax.text(cx + 0.3, cy, label, fontsize=8, va="center")
        elif kind == "end":
            ax.add_patch(mpatches.Circle((cx, cy), 0.22, facecolor="white",
                                         edgecolor="#111827", lw=1.2))
            ax.add_patch(mpatches.Circle((cx, cy), 0.12,
                                         facecolor="#111827"))
            ax.text(cx + 0.3, cy, label, fontsize=8, va="center")
        elif kind == "decision":
            diamond = mpatches.Polygon(
                [(cx, cy + h / 2), (cx + w / 2, cy),
                 (cx, cy - h / 2), (cx - w / 2, cy)],
                closed=True, facecolor="#fef3c7", edgecolor="#b45309")
            ax.add_patch(diamond)
            ax.text(cx, cy, label, ha="center", va="center", fontsize=7.5)
        elif kind == "io":
            box(ax, x, y, w, h, label, facecolor="#dcfce7",
                edgecolor="#166534", fontsize=8)
        else:
            box(ax, x, y, w, h, label, facecolor="#e0e7ff",
                edgecolor="#3730a3", fontsize=8)

    for (i, j, lbl) in edges:
        x1, y1, w1, h1 = centers[i]
        x2, y2, w2, h2 = centers[j]
        # snap to nearest edge vertically or horizontally
        if abs(x1 - x2) < 0.1:
            # vertical
            y_from = y1 - h1 / 2 if y1 > y2 else y1 + h1 / 2
            y_to   = y2 + h2 / 2 if y1 > y2 else y2 - h2 / 2
            arrow(ax, x1, y_from, x2, y_to)
            if lbl:
                ax.text(x1 + 0.1, (y_from + y_to) / 2, lbl, fontsize=7)
        else:
            arrow(ax, x1, y1, x2, y2)
            if lbl:
                ax.text((x1 + x2) / 2 + 0.1, (y1 + y2) / 2, lbl, fontsize=7)

    ax.set_title(title, fontsize=11, fontweight="bold", pad=10)
    save(fig, fig_name)


def activity_diagrams():
    # Bidding flow
    _activity(
        "activity_bidding.png",
        "Figure 5.4a — Activity: Indirect Request & Bidding",
        [
            (2.7, 9.2, 1.1, 0.5, "Start", "start"),
            (1.9, 8.2, 2.7, 0.6, "Client posts service request", "action"),
            (1.9, 7.3, 2.7, 0.6, "System notifies matching providers", "action"),
            (1.9, 6.4, 2.7, 0.6, "Provider places / edits bid", "action"),
            (1.9, 5.5, 2.7, 0.6, "Client reviews bid list", "action"),
            (2.0, 4.3, 2.5, 0.9, "Accept bid?", "decision"),
            (0.3, 3.0, 2.0, 0.6, "Reject / let expire", "action"),
            (3.8, 3.0, 2.3, 0.6, "Create Project record", "action"),
            (3.8, 1.9, 2.3, 0.6, "Open conversation thread", "action"),
            (3.8, 1.0, 2.3, 0.6, "Notify both parties", "io"),
            (2.7, 0.1, 1.1, 0.5, "End", "end"),
        ],
        [
            (0, 1, ""), (1, 2, ""), (2, 3, ""), (3, 4, ""),
            (4, 5, ""), (5, 6, "no"), (5, 7, "yes"),
            (7, 8, ""), (8, 9, ""), (9, 10, ""), (6, 10, ""),
        ],
    )

    # Direct booking
    _activity(
        "activity_booking.png",
        "Figure 5.4b — Activity: Direct Booking",
        [
            (2.7, 9.2, 1.1, 0.5, "Start", "start"),
            (1.9, 8.2, 2.7, 0.6, "Client browses providers", "action"),
            (1.9, 7.3, 2.7, 0.6, "Client opens provider profile", "action"),
            (1.9, 6.4, 2.7, 0.6, "Client submits direct request", "action"),
            (1.9, 5.5, 2.7, 0.6, "Provider reviews request", "action"),
            (2.0, 4.3, 2.5, 0.9, "Provider accepts?", "decision"),
            (0.3, 3.0, 2.0, 0.6, "Decline with reason", "action"),
            (3.8, 3.0, 2.3, 0.6, "Create Project record", "action"),
            (3.8, 1.9, 2.3, 0.6, "Notify client", "io"),
            (2.7, 0.1, 1.1, 0.5, "End", "end"),
        ],
        [
            (0, 1, ""), (1, 2, ""), (2, 3, ""), (3, 4, ""),
            (4, 5, ""), (5, 6, "no"), (5, 7, "yes"),
            (7, 8, ""), (8, 9, ""), (6, 9, ""),
        ],
    )

    # Payment
    _activity(
        "activity_payment.png",
        "Figure 5.4c — Activity: Payment Lifecycle",
        [
            (2.7, 9.2, 1.1, 0.5, "Project accepted", "start"),
            (1.9, 8.2, 2.7, 0.6, "Payment row created (Pending)", "action"),
            (1.9, 7.3, 2.7, 0.6, "Provider delivers / updates log", "action"),
            (1.9, 6.4, 2.7, 0.6, "Client marks project completed", "action"),
            (1.9, 5.5, 2.7, 0.6, "Commission computed", "action"),
            (2.0, 4.3, 2.5, 0.9, "Dispute raised?", "decision"),
            (0.3, 3.0, 2.0, 0.6, "Status: Refunded", "action"),
            (3.8, 3.0, 2.3, 0.6, "Status: Paid + Paid_At", "action"),
            (3.8, 1.9, 2.3, 0.6, "Update provider earnings", "io"),
            (3.8, 1.0, 2.3, 0.6, "Notify provider", "io"),
            (2.7, 0.1, 1.1, 0.5, "End", "end"),
        ],
        [
            (0, 1, ""), (1, 2, ""), (2, 3, ""), (3, 4, ""),
            (4, 5, ""), (5, 6, "yes"), (5, 7, "no"),
            (7, 8, ""), (8, 9, ""), (9, 10, ""), (6, 10, ""),
        ],
    )

    # Provider registration + verification
    _activity(
        "activity_provider_registration.png",
        "Figure 5.4d — Activity: Provider Registration & Verification",
        [
            (2.7, 9.2, 1.1, 0.5, "Start", "start"),
            (1.9, 8.2, 2.7, 0.6, "Step 1 — personal info", "action"),
            (1.9, 7.3, 2.7, 0.6, "Step 2 — profile / bio", "action"),
            (1.9, 6.4, 2.7, 0.6, "Step 3 — set password", "action"),
            (1.9, 5.5, 2.7, 0.6, "Step 4 — upload NIC & resume", "action"),
            (1.9, 4.6, 2.7, 0.6, "Step 5 — select services", "action"),
            (1.9, 3.7, 2.7, 0.6, "OTP email verification", "action"),
            (2.0, 2.4, 2.5, 0.9, "Admin approves?", "decision"),
            (0.3, 1.1, 2.0, 0.6, "Rejected + reason", "action"),
            (3.8, 1.1, 2.3, 0.6, "Status: Approved", "action"),
            (2.7, 0.1, 1.1, 0.5, "End", "end"),
        ],
        [
            (0, 1, ""), (1, 2, ""), (2, 3, ""), (3, 4, ""),
            (4, 5, ""), (5, 6, ""), (6, 7, ""),
            (7, 8, "no"), (7, 9, "yes"),
            (8, 10, ""), (9, 10, ""),
        ],
    )


# ----------------------------- main -------------------------------------

def main():
    print(f"Writing diagrams into {OUT}")
    use_case_diagram()
    class_diagram()
    er_diagram()
    activity_diagrams()
    print("Done.")


if __name__ == "__main__":
    main()
