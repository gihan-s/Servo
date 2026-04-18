/**
 * Project Detail View
 * Shared module for rendering a full ongoing project detail overlay
 * with primary info, progress bar, progress chart, and activity timeline.
 *
 * Usage:
 *   ProjectDetailView.open(postId, { role: 'provider' | 'client' });
 */
const ProjectDetailView = (function () {
    let _chartInstance = null;
    let _currentRole   = 'client';
    let _currentData   = null;

    /* ===== Helpers ===== */

    function _esc(text) {
        const d = document.createElement('div');
        d.textContent = String(text ?? '');
        return d.innerHTML;
    }

    function _fmtDate(str) {
        if (!str) return '—';
        return new Date(str).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function _fmtDateTime(str) {
        if (!str) return '—';
        const d = new Date(str);
        return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
            + ' ' + d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    function _barColor(pct) {
        return pct >= 75 ? '#22c55e' : pct >= 40 ? '#f59e0b' : '#3b82f6';
    }

    /* ===== Build HTML ===== */

    function _buildOverlay() {
        if (document.getElementById('projectDetailOverlay')) return;

        const el = document.createElement('div');
        el.id = 'projectDetailOverlay';
        el.className = 'project-detail-overlay deactive';
        el.innerHTML = `
            <div class="project-detail-panel">
                <div class="pd-header">
                    <div class="pd-header-title">Project Details</div>
                    <button class="pd-close-btn" id="pdCloseBtn"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="pd-body" id="pdBody">
                    <div class="pd-loading" id="pdLoading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Loading project details&hellip;</p>
                    </div>
                    <div id="pdContent" style="display:none;"></div>
                </div>
                <div class="pd-footer" id="pdFooter" style="display:none;"></div>
            </div>`;
        document.body.appendChild(el);

        // Close events
        document.getElementById('pdCloseBtn').addEventListener('click', close);
        el.addEventListener('click', function (e) { if (e.target === el) close(); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !el.classList.contains('deactive')) close();
        });
    }

    function _renderContent(d) {
        const progress = Math.min(100, Math.max(0, d.progress || 0));
        const color    = _barColor(progress);

        const isProvider = _currentRole === 'provider';
        const userName   = isProvider ? d.client_name : d.provider_name;
        const userPic    = isProvider ? d.client_picture : d.provider_picture;
        const userLabel  = isProvider ? 'Client' : 'Service Provider';

        const avatarUrl = userPic
            ? `${window.BASE_URL || ''}/file/user-files/${_esc(userPic)}`
            : `${window.BASE_URL || ''}/assets/img/default-avatar.jpg`;

        let html = `
            <div class="pd-primary">
                <div class="pd-user-row">
                    <img class="pd-avatar" src="${avatarUrl}" alt="${_esc(userName)}" onerror="this.src='${window.BASE_URL || ''}/assets/img/default-avatar.jpg'">
                    <div class="pd-user-info">
                        <div class="pd-user-name">${_esc(userName)}</div>
                        <div class="pd-user-date"><i class="fa-solid fa-calendar-days"></i> Started ${_fmtDate(d.started_at)}</div>
                    </div>
                </div>
                <div class="pd-title">${_esc(d.title)}</div>
                <div class="pd-description">${_esc(d.description)}</div>
                <div class="pd-meta-chips">
                    <span class="pd-chip pd-chip-budget"><i class="fa-solid fa-coins"></i> ${_esc(d.budget_display)}</span>
                    <span class="pd-chip pd-chip-category"><i class="fa-solid fa-tag"></i> ${_esc(d.category)}</span>
                    <span class="pd-chip pd-chip-level"><i class="fa-solid fa-layer-group"></i> ${_esc(d.level)}</span>
                    <span class="pd-chip pd-chip-timeline"><i class="fa-solid fa-calendar"></i> Est. ${_esc(d.est_date || '—')}</span>
                    <span class="pd-chip pd-chip-type"><i class="fa-solid fa-briefcase"></i> ${_esc(d.post_type)}</span>
                </div>
            </div>`;

        // Progress section
        html += `
            <div class="pd-progress-section">
                <div class="pd-section-title"><i class="fa-solid fa-chart-simple"></i> Current Progress</div>
                <div class="pd-progress-bar-wrap">
                    <div class="pd-progress-header">
                        <span class="pd-progress-label">Completion</span>
                        <span class="pd-progress-pct" style="color:${color}">${progress}%</span>
                    </div>
                    <div class="pd-progress-track">
                        <div class="pd-progress-fill" style="width:${progress}%; background:${color};"></div>
                    </div>
                </div>
            </div>`;

        // Chart section
        html += `
            <div class="pd-chart-section">
                <div class="pd-section-title"><i class="fa-solid fa-chart-line"></i> Progress Over Time</div>
                <div class="pd-chart-container" id="pdChartContainer">
                    <canvas id="pdProgressChart"></canvas>
                </div>
            </div>`;

        // Deliverables section — only when submission has files
        if (d.deliverables && d.deliverables.files && d.deliverables.files.length > 0) {
            const fileLinks = d.deliverables.files.map(f => {
                const name = f.split('/').pop();
                const url  = `${window.BASE_URL || ''}/file/project-updates/${_esc(f)}`;
                const ext  = name.split('.').pop().toLowerCase();
                const iconClass = ['jpg','jpeg','png','gif','webp'].includes(ext) ? 'fa-image'
                    : ['pdf'].includes(ext) ? 'fa-file-pdf'
                    : ['zip','rar','7z'].includes(ext) ? 'fa-file-zipper'
                    : ['doc','docx'].includes(ext) ? 'fa-file-word'
                    : ['xls','xlsx'].includes(ext) ? 'fa-file-excel'
                    : 'fa-file';
                return `<a href="${url}" target="_blank" class="pd-deliverable-file" title="${_esc(name)}">
                    <i class="fa-solid ${iconClass}"></i>
                    <span>${_esc(name)}</span>
                </a>`;
            }).join('');

            const noteHtml = d.deliverables.note
                ? `<div class="pd-deliverable-note">${_esc(d.deliverables.note)}</div>`
                : '';

            const dateHtml = d.deliverables.date
                ? `<div class="pd-deliverable-date"><i class="fa-solid fa-calendar-check"></i> Submitted ${_fmtDate(d.deliverables.date)}</div>`
                : '';

            html += `
            <div class="pd-deliverables-section">
                <div class="pd-section-title"><i class="fa-solid fa-box-open"></i> Deliverables</div>
                ${noteHtml}
                ${dateHtml}
                <div class="pd-deliverable-files">${fileLinks}</div>
            </div>`;
        }

        // Timeline section
        html += `
            <div class="pd-timeline-section">
                <div class="pd-section-title"><i class="fa-solid fa-timeline"></i> Project Activity</div>
                ${_renderTimeline(d.timeline)}
            </div>`;

        document.getElementById('pdContent').innerHTML = html;
        document.getElementById('pdContent').style.display = '';

        // Render footer actions
        _renderFooter(d);

        // Render chart after DOM is ready
        setTimeout(() => _renderChart(d.progress_history), 50);
    }

    function _renderTimeline(events) {
        if (!events || events.length === 0) {
            return `<div class="pd-timeline-empty">
                <i class="fa-solid fa-timeline"></i>
                <p>No activity recorded yet.</p>
            </div>`;
        }

        const items = events.map(ev => {
            const dotClass = 'pd-timeline-dot-' + (ev.color || 'gray');
            const desc = ev.description
                ? `<div class="pd-timeline-desc">${_esc(ev.description)}</div>`
                : '';

            let filesHtml = '';
            if (ev.files && ev.files.length > 0) {
                filesHtml = `<div class="pd-timeline-files">
                    ${ev.files.map(f => {
                        const name = f.split('/').pop();
                        const url  = `${window.BASE_URL || ''}/file/project-updates/${f}`;
                        return `<a href="${url}" target="_blank" class="pd-timeline-file" title="${_esc(name)}">
                                    <i class="fa-solid fa-file"></i> ${_esc(name)}
                                </a>`;
                    }).join('')}
                </div>`;
            }

            let meta = '';
            if (ev.type === 'progress_update') {
                const parts = [];
                if (ev.progress_completed > 0) {
                    parts.push(`<span><i class="fa-solid fa-chart-simple"></i> Progress: ${ev.progress_completed}%</span>`);
                }
                if (ev.worked_hours > 0) {
                    parts.push(`<span><i class="fa-solid fa-clock"></i> ${ev.worked_hours}h worked</span>`);
                }
                if (parts.length) {
                    meta = `<div class="pd-timeline-meta">${parts.join('')}</div>`;
                }
            } else if (ev.type === 'status_change') {
                const statusLabels = {
                    'pending-review': 'Pending Review',
                    'completed':      'Completed',
                    'cancelled':      'Cancelled',
                };
                const label = statusLabels[ev.status] || ev.status || '';
                if (label) {
                    meta = `<div class="pd-timeline-meta">
                        <span class="pd-timeline-status-badge pd-status-${_esc(ev.status || '')}">
                            <i class="fa-solid fa-flag"></i> ${_esc(label)}
                        </span>
                    </div>`;
                }
            }

            return `
                <div class="pd-timeline-item">
                    <div class="pd-timeline-dot ${dotClass}">
                        <i class="fa-solid ${ev.icon || 'fa-circle'}"></i>
                    </div>
                    <div class="pd-timeline-content">
                        <div class="pd-timeline-head">
                            <div class="pd-timeline-title">${_esc(ev.title)}</div>
                            <div class="pd-timeline-date">${_fmtDateTime(ev.date)}</div>
                        </div>
                        ${desc}
                        ${meta}
                        ${filesHtml}
                    </div>
                </div>`;
        }).join('');

        return `<div class="pd-timeline">${items}</div>`;
    }

    function _renderFooter(d) {
        const footer = document.getElementById('pdFooter');
        if (!footer) return;

        const isProvider = _currentRole === 'provider';
        const partnerId = isProvider ? d.client_id : d.provider_id;

        let btns = '';
        if (isProvider) {
            btns = `
                <button class="btn-primary" id="pdBtnUpdate"><i class="fa-solid fa-arrow-up-right-dots"></i> Update Progress</button>
                <button class="btn-outline" id="pdBtnRequirements"><i class="fa-solid fa-list-check"></i> Requirements</button>
                <button class="btn-outline" id="pdBtnMessage"><i class="fa-solid fa-comments"></i> Message Client</button>`;
        } else {
            btns = `
                <button class="btn-outline" id="pdBtnRequirements"><i class="fa-solid fa-list-check"></i> Requirements</button>
                <button class="btn-outline" id="pdBtnMessage"><i class="fa-solid fa-comments"></i> Message Provider</button>`;
        }

        footer.innerHTML = btns;
        footer.style.display = '';

        // Wire buttons
        const msgBtn = footer.querySelector('#pdBtnMessage');
        if (msgBtn) {
            msgBtn.addEventListener('click', () => {
                window.location.href = `${window.BASE_URL || ''}/messages?new=${partnerId}`;
            });
        }

        const reqBtn = footer.querySelector('#pdBtnRequirements');
        if (reqBtn) {
            reqBtn.addEventListener('click', () => {
                close();
                if (isProvider && typeof openProviderRequirementsModal === 'function') {
                    openProviderRequirementsModal(d.post_id);
                } else if (!isProvider && typeof updateRequest === 'function') {
                    updateRequest(d.post_id);
                }
            });
        }

        const updateBtn = footer.querySelector('#pdBtnUpdate');
        if (updateBtn) {
            updateBtn.addEventListener('click', () => {
                close();
                if (window.ongoingProjectsManager && window.ongoingProjectsManager.currentData) {
                    const project = window.ongoingProjectsManager.currentData.find(p => p.Post_ID === d.post_id);
                    if (project) {
                        window.ongoingProjectsManager.currentProject = project;
                        window.ongoingProjectsManager.openProgressModal(project);
                    }
                }
            });
        }
    }

    /* ===== Chart ===== */

    function _renderChart(points) {
        const canvas = document.getElementById('pdProgressChart');
        if (!canvas) return;

        if (!points || points.length < 2) {
            const container = document.getElementById('pdChartContainer');
            if (container) {
                container.innerHTML = `<div class="pd-chart-empty">
                    <i class="fa-solid fa-chart-line"></i>
                    <p>Not enough data to display chart yet.</p>
                </div>`;
            }
            return;
        }

        if (typeof Chart === 'undefined') {
            const container = document.getElementById('pdChartContainer');
            if (container) {
                container.innerHTML = `<div class="pd-chart-empty">
                    <i class="fa-solid fa-chart-line"></i>
                    <p>Chart library not loaded.</p>
                </div>`;
            }
            return;
        }

        if (_chartInstance) {
            _chartInstance.destroy();
            _chartInstance = null;
        }

        const labels = points.map(p => _fmtDate(p.date));
        const data   = points.map(p => p.progress);

        const ctx = canvas.getContext('2d');
        _chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Progress %',
                    data: data,
                    borderColor: '#008500',
                    backgroundColor: 'rgba(0, 133, 0, 0.08)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#008500',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleFont: { size: 12, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (ctx) {
                                return `Progress: ${ctx.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 25,
                            callback: function (v) { return v + '%'; },
                            font: { size: 11 },
                            color: '#9ca3af',
                        },
                        grid: { color: '#f3f4f6' },
                        border: { display: false },
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            color: '#9ca3af',
                            maxRotation: 45,
                        },
                        grid: { display: false },
                        border: { display: false },
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    }

    /* ===== Public API ===== */

    function open(postId, opts) {
        opts = opts || {};
        _currentRole = opts.role || 'client';

        _buildOverlay();

        const overlay = document.getElementById('projectDetailOverlay');
        const loading = document.getElementById('pdLoading');
        const content = document.getElementById('pdContent');
        const footer  = document.getElementById('pdFooter');

        loading.style.display = '';
        loading.innerHTML = '<i class="fas fa-spinner fa-spin"></i><p>Loading project details&hellip;</p>';
        content.style.display = 'none';
        content.innerHTML = '';
        footer.style.display = 'none';

        overlay.classList.remove('deactive');
        document.body.style.overflow = 'hidden';

        fetch(`${window.BASE_URL || ''}/project/details/${postId}`)
            .then(r => r.json())
            .then(json => {
                if (!json.success) throw new Error(json.error || 'Failed to load');
                loading.style.display = 'none';
                _currentData = json.data;
                _renderContent(json.data);
            })
            .catch(err => {
                loading.innerHTML = `
                    <div class="pd-loading">
                        <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i>
                        <p>Failed to load project details.</p>
                    </div>`;
            });
    }

    function close() {
        const overlay = document.getElementById('projectDetailOverlay');
        if (overlay) {
            overlay.classList.add('deactive');
            document.body.style.overflow = '';
        }
        if (_chartInstance) {
            _chartInstance.destroy();
            _chartInstance = null;
        }
    }

    return { open, close };
})();
