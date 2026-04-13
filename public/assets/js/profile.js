// Side navigation logic
const pills = document.querySelectorAll(".profile-nav .pill");
const sections = document.querySelectorAll(".profile-section");
pills.forEach((p) => {
    if (p.classList.contains("btn-logout")) {
        return;
    }
    else {
        p.addEventListener("click", () => {
            pills.forEach((x) => x.setAttribute("aria-current", "false"));
            sections.forEach((s) => s.classList.remove("active"));
            p.setAttribute("aria-current", "true");
            document.getElementById(p.dataset.target).classList.add("active");
        });
    }
});


document.addEventListener("click", (e) => {
    const link = e.target.closest(".btn-logout");
    if (!link) return;
    e.preventDefault();
    const BASE = window.BASE_URL || location.origin;
    if (confirm("Do you want to log out?")) {
        window.location.href = `./logout`;     // server will redirect to /login
    }
});

// Toast helper
const toast = document.getElementById("toast");
function showToast(msg, type = "info") {
    document.getElementById("toastMsg").textContent = msg;
    toast.classList.remove("show", "success", "error", "info");
    toast.classList.add(type);
    // restart animation if already visible
    void toast.offsetWidth;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 3200);
}

// Personal form logic (frontend only)
const personalForm = document.getElementById("personalForm");
let personalOriginal = new FormData(personalForm);
function resetPersonal() {
    personalForm.reset();
    personalOriginal.forEach((v, k) => {
        if (personalForm.elements[k]) personalForm.elements[k].value = v;
    });
    showToast("Personal reset", "info");
}
function savePersonal(e) {
    e.preventDefault();
    // Submit and let the server redirect; toast will show after reload via flash
    personalForm.action = `profile/update`;
    personalForm.method = "POST";
    personalForm.submit();
}

document.addEventListener("DOMContentLoaded", () => {
    if (window.__FLASH__ && window.__FLASH__.message) {
        const type = window.__FLASH__.type || "info"; // "success" | "error" | "info"
        showToast(window.__FLASH__.message, type);
    }
});

// Forgot Password modal
function forgotPassword() {
    const ov = document.getElementById("fpOverlay");
    if (ov) ov.style.display = "flex";
}
function closeFP() {
    const ov = document.getElementById("fpOverlay");
    if (ov) ov.style.display = "none";
}

// Send email with reset code
function sendFP(e) {
    e.preventDefault();
    const email = document.getElementById("fp_email")?.value || "";
    const fd = new FormData();
    fd.append("email", email);

    fetch("profile/send-reset-code", { method: "POST", body: fd, credentials: "same-origin" })
        .then((r) => r.json())
        .then((data) => {
            if (data?.ok) {
                showToast("Reset code sent to your email", "success");
                closeFP();
            } else {
                showToast(data?.message || "Failed to send reset code", "error");
            }
        })
        .catch(() => showToast("Failed to send reset code", "error"));
}

// Account form: validate then submit (server will redirect and show toast)
const accountForm = document.getElementById("accountForm");
let accountOriginal = new FormData(accountForm);
function resetAccount() {
    accountForm.reset();
    accountOriginal.forEach((v, k) => {
        if (accountForm.elements[k]) accountForm.elements[k].value = v;
    });
    showToast("Account reset");
}

function saveAccount(e) {
    e.preventDefault();
    const fd = new FormData(accountForm);
    const op = (fd.get("Old_Password") || "").trim();
    const np = (fd.get("New_Password") || "").trim();

    if (op === '') {
        document.getElementsByName("Old_Password")[0].focus();
        return;
    }

    if (np === '') {
        document.getElementsByName("New_Password")[0].focus();
        return;
    }

    fetch('profile/update-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            Old_Password: op,
            New_Password: np
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                showToast(data.message, "success");
                document.getElementsByName("Old_Password")[0].value = "";
                document.getElementsByName("New_Password")[0].value = "";
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

}

// Avatar preview (both)
let pendingAvatarFile = null;
function bindAvatar(idInput, idImg) {
    const inputEl = document.getElementById(idInput);
    if (!inputEl) return;
    inputEl.addEventListener("change", (e) => {
        const f = e.target.files[0];
        if (!f) return;
        const r = new FileReader();
        r.onload = (ev) => {
            document.getElementById(idImg).src = ev.target.result;
            if (idInput === "avatarPublicInput") {
                pendingAvatarFile = f;
                document.getElementById("avatarSaveBtn").style.display = "inline-flex";
            }
            showToast("Photo updated (not saved)");
        };
        r.readAsDataURL(f);
    });
}

function saveAvatar() {
    const input = document.getElementById("avatarPublicInput");
    const file =
        input.files && input.files[0] ? input.files[0] : pendingAvatarFile;
    if (!file) {
        showToast("No new photo selected");
        return;
    }
    const fd = new FormData();
    fd.append("profile_pic", file);
    fetch("/profile/change-profile-pic", { method: "POST", body: fd })
        .then((data) => data.json())
        .then((data) => {

            if (data.type === 'success') {

                document.getElementById("avatarSaveBtn").style.display = "none";
                pendingAvatarFile = null;
                document.querySelector(".navbar .user-menu .user-avatar img").src = data.Profile_Picture;
                try {
                    input.value = "";
                } catch { }
                showToast("Photo saved");

            } else {
                throw new Error(data.message || "Failed to save photo");
            }
        })
        .catch((err) => {
            console.log(err);
            showToast("Failed to save photo");
        });
}
bindAvatar("avatarPublicInput", "avatarPublicPreview");
bindAvatar("avatarAccountInput", "avatarAccountPreview");

// --- Work Information (frontend only) ---
// const mockCategories = [
//     { id: 1, name: "Graphic Design" },
//     { id: 2, name: "Web Development" },
//     { id: 3, name: "Mobile Apps" },
//     { id: 4, name: "Content Writing" },
//     { id: 5, name: "Photography" },
// ];
// const mockLocations = [
//     { id: 1, name: "Colombo" },
//     { id: 2, name: "Kandy" },
//     { id: 3, name: "Galle" },
//     { id: 4, name: "Jaffna" },
// ];
// const mockSkills = [
//     { id: 1, name: "Photoshop" },
//     { id: 2, name: "Illustrator" },
//     { id: 3, name: "React" },
//     { id: 4, name: "Node.js" },
//     { id: 5, name: "SEO" },
// ];


// populate selects (modal)
// const mCatSelect = document.getElementById("m_cat_select");
// function refreshCategoryOptions() {
//     if (!mCatSelect) return;
//     mCatSelect.innerHTML = "";
//     mockCategories.forEach((c) => {
//         const opt = document.createElement("option");
//         opt.value = String(c.id);
//         opt.textContent = c.name;
//         mCatSelect.appendChild(opt);
//     });
// }

// refreshCategoryOptions();

let providerCats = [];

const categoryList = document.getElementById("service-card-wrapper");
const catSearch = document.getElementById("cat_search");
const catOverlay = document.getElementById("catOverlay");
const catViewOverlay = document.getElementById("catViewOverlay");
// modal fields
const mCatTitle = document.getElementById("m_cat_title");
const mCatDesc = document.getElementById("m_cat_desc");
const mCatPrice = document.getElementById("m_cat_price");
const mCatEditIndex = document.getElementById("m_cat_edit_index");
const mCatLocations = document.getElementById("m_cat_locations");
const mCatSkills = document.getElementById("m_cat_skills");

function renderChips(container, items) {
    if (!container) return;
    container.innerHTML = "";
    items.forEach((it) => {
        const chip = document.createElement("span");
        chip.className = "inline-badge";
        chip.style.marginRight = "6px";
        chip.textContent = it.name;
        container.appendChild(chip);
    });
}

let catFilter = "";
function renderCategoryList() {


    if (!categoryList) return;

    const term = document.getElementById("cat_search").value.toLowerCase();

    const filtered = providerCats.filter(service => 
        service.Title.toLowerCase().includes(term) ||
        service.Category_Name.toLowerCase().includes(term) ||
        service.Description.toLowerCase().includes(term)
    );;
    

    categoryList.innerHTML = filtered.length
        ? ""
        : '<div class="small">No categories found.</div>';

    filtered.forEach((pc) => {

        const card = document.createElement("div");
        card.className = "req-card";

        var skills = pc.skills || [];

        card.innerHTML = `
                    <div class="req-head">
                        <img class="req-avatar" src="/file/category-icons/${pc.Category_Icon || 'default-category.png'}" alt="" onerror="this.style.visibility='hidden'"/>
                        <div class="req-main">
                            <span class="req-name">${pc.Category_Name}</span>
                            <span class="req-title">${pc.Title}</span>
                            <span class="req-time">${pc.Formatted_Location_String}</span>
                        </div>
                        <div class="req-actions">
                            <button style='display:none;' class="btn-outline-blue" data-action="view"><i class="fa-solid fa-eye"></i> View</button>
                            <button style='display:none;' class="btn-outline-blue" data-action="edit"><i class="fa-solid fa-pen"></i> Edit</button>
                            <button class="btn-outline-rose" data-action="remove"><i class="fa-solid fa-xmark"></i> Delete</button>
                        </div>
                    </div>
                    <div class="req-tags">
                        <span class="tag">
                            <i class="fa-solid fa-tag"></i> ${pc.Price_Type}: <strong>${Number(pc.Default_Price).toFixed(2)}</strong></span>
                            ${skills.map((s) => `<span class=\"tag\">${s}</span>`).join("")}
                    </div>
                    <div class="req-desc">${pc.Description || "—"}</div>
                `;


        const originalIndex = providerCats.indexOf(pc);
        card
            .querySelector('[data-action="view"]')
            .addEventListener("click", () => openCategoryView(pc));
        card
            .querySelector('[data-action="edit"]')
            .addEventListener("click", () =>
                openCategoryModal("edit", originalIndex)
            );
        card
            .querySelector('[data-action="remove"]')
            .addEventListener("click", () => removeCategory(originalIndex));
        categoryList.appendChild(card);
    });
}

// search
if (catSearch) {
    catSearch.addEventListener("input", (e) => {
        catFilter = String(e.target.value || "")
            .trim()
            .toLowerCase();
        renderCategoryList();
    });
}

function removeCategory(idx) {
    if (!confirm("Remove this category?")) return;

    const formData = new FormData();
    formData.append("provider_category_id", providerCats[idx].Provider_Categories_ID);
    fetch("/profile/remove-service", {
        method: "POST",
        body: formData,
        credentials: "same-origin",
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                providerCats.splice(idx, 1);
                renderCategoryList();
                updateCounts();
                showToast("Category removed", "success");
            } else {
                showToast(data.message || "Failed to remove category", "error");
            }
        })
        .catch(() => {
            showToast("Failed to remove category", "error");
        });
    
}

function openCategoryModal(mode = "add", idx = -1) {
    // populate
    document.getElementById("catTitle").textContent =
        mode === "edit" ? "Edit Category" : "Add Category";
    if (mCatSelect && mCatSelect.options.length) mCatSelect.selectedIndex = 0;
    mCatTitle.value = "";
    mCatDesc.value = "";
    mCatPrice.value = "";
    mCatLocations.innerHTML = "";
    mCatSkills.innerHTML = "";
    mCatEditIndex.value = "";
    if (mode === "edit" && providerCats[idx]) {
        const pc = providerCats[idx];
        mCatSelect.value = String(pc.category_id);
        mCatTitle.value = pc.title || "";
        mCatDesc.value = pc.description || "";
        mCatPrice.value = pc.default_price || "";
        renderChips(mCatLocations, pc.locations || []);
        renderChips(mCatSkills, pc.skills || []);
        mCatEditIndex.value = String(idx);
    }
    catOverlay.style.display = "flex";
}
function closeCategoryModal() {
    catOverlay.style.display = "none";
}

function saveCategoryModal(e) {
    e.preventDefault();
    const idx =
        mCatEditIndex.value !== "" ? parseInt(mCatEditIndex.value, 10) : -1;
    const category_id = parseInt((mCatSelect || { value: "0" }).value, 10) || 0;
    const title = mCatTitle.value.trim();
    const description = mCatDesc.value.trim();
    const default_price = mCatPrice.value.trim();
    const locations = Array.from(
        (mCatLocations || { querySelectorAll: () => [] }).querySelectorAll(
            ".inline-badge"
        )
    ).map((el) => ({ id: 0, name: el.textContent }));
    const skills = Array.from(
        (mCatSkills || { querySelectorAll: () => [] }).querySelectorAll(
            ".inline-badge"
        )
    ).map((el) => ({ id: 0, name: el.textContent }));
    const entry = {
        category_id,
        title,
        description,
        default_price,
        locations,
        skills,
    };
    if (idx >= 0) providerCats[idx] = entry;
    else providerCats.push(entry);
    renderCategoryList();
    updateCounts();
    closeCategoryModal();
    showToast("Category saved");
}

function openCategoryView(pc) {
    const cat = mockCategories.find((c) => c.id === pc.category_id);
    const body = document.getElementById("catViewBody");
    const skills = pc.skills || [];
    const locations = pc.locations || [];
    body.innerHTML = `
                <div class="view-meta">
                    <span class="pill"><i class="fa-regular fa-layer-group"></i> ${cat ? cat.name : "—"
        }</span>
                    <span class="pill price"><i class="fa-regular fa-tag"></i> Default: <strong>${pc.default_price || "—"
        }</strong></span>
                    <span class="pill"><i class="fa-regular fa-location-dot"></i> ${locations.length
            ? locations.map((l) => l.name).join(", ")
            : "—"
        }</span>
                </div>
                <div class="kv">
                    <div class="row"><div class="k">Title</div><div class="v">${pc.title || "—"
        }</div></div>
                    <div class="row"><div class="k">Description</div><div class="v">${pc.description || "—"
        }</div></div>
                    <div class="row"><div class="k">Skills</div><div class="v"><div class="chips">${skills.length
            ? skills
                .map((s) => `<span class=\"chip\">${s.name}</span>`)
                .join("")
            : "—"
        }</div></div></div>
                </div>
            `;
    catViewOverlay.style.display = "flex";
}
function closeCategoryView() {
    catViewOverlay.style.display = "none";
}

function pickLocations(isModal = false) {
    const names = prompt("Add locations (comma separated):", "Colombo, Kandy");
    if (!names) return;
    const items = names
        .split(",")
        .map((s) => s.trim())
        .filter(Boolean)
        .map((n) => ({ id: 0, name: n }));
    renderChips(
        isModal ? mCatLocations : document.getElementById("cat_locations"),
        items
    );
}

function pickSkills(isModal = false) {
    const names = prompt("Add skills (comma separated):", "Photoshop, SEO");
    if (!names) return;
    const items = names
        .split(",")
        .map((s) => s.trim())
        .filter(Boolean)
        .map((n) => ({ id: 0, name: n }));
    renderChips(
        isModal ? mCatSkills : document.getElementById("cat_skills"),
        items
    );
}

// initial render
// renderCategoryList();
window.addEventListener("DOMContentLoaded", () => {

    var url = "/providers/services";

    const providerId = document.getElementById("providerId").value;
    url += `?provider_id=${providerId}`;
    url += `&limit=1000`;

    fetch(url, { method: "GET", credentials: "same-origin" })
        .then((r) => r.json())
        .then((data) => {
            console.log(data);

            if (data.success && Array.isArray(data.services)) {
                providerCats = data.services;
                renderCategoryList();
                updateCounts();
            }
        })
        .catch((err) => {
            console.error(err);
            showToast("Failed to load categories", "error");
        });
});

function updateCounts() {
    const el = document.getElementById("countCategories");
    if (el) el.textContent = String(providerCats.length);
}
updateCounts();

// Forgot password modal


// Delete account
function deleteAccount() {
    if (!confirm("Delete account permanently? This cannot be undone.")) {
        return;
    }

    const tmp = document.createElement("form");
    tmp.method = "POST";
    tmp.action = "profile/delete-account";
    tmp.style.display = "none";
    document.body.appendChild(tmp);
    tmp.submit();
    return;
}
