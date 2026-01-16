<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Projects</title>
  <style>
    .section { display: none; }
    .section.active { display: block; }
    .nav-btn.active { font-weight: bold; }
    .pagination { margin-top: 20px; }
    .page-btn.active { background: #007bff; color: #fff; }
  </style>
</head>
<body>
  <div id="section-nav">
    <button class="nav-btn active" data-section="pending">Pending Requests</button>
    <button class="nav-btn" data-section="approved">Approved Requests</button>
    <button class="nav-btn" data-section="ongoing">Ongoing Projects</button>
    <button class="nav-btn" data-section="review">Pending Review</button>
    <button class="nav-btn" data-section="completed">Completed Projects</button>
  </div>

  <div id="sections">
    <div class="section active" id="pending">Pending Requests Content</div>
    <div class="section" id="approved">Approved Requests Content</div>
    <div class="section" id="ongoing">Ongoing Projects Content</div>
    <div class="section" id="review">Pending Review Content</div>
    <div class="section" id="completed">Completed Projects Content</div>
  </div>

  <div class="pagination" id="pagination-bar"></div>

  <script>
    // Section and pagination state
    const sectionIds = ['pending', 'approved', 'ongoing', 'review', 'completed'];
    const pageState = { pending: 1, approved: 1, ongoing: 1, review: 1, completed: 1 };
    const totalPages = 3; // Example: 3 pages per section

    function renderPagination(section) {
      const bar = document.getElementById('pagination-bar');
      let html = `<button class="page-btn prev" ${pageState[section] === 1 ? 'disabled' : ''}>&lt;</button>`;
      for (let i = 1; i <= totalPages; i++) {
        html += `<button class="page-btn${pageState[section] === i ? ' active' : ''}" data-page="${i}">${i}</button>`;
      }
      html += `<button class="page-btn next" ${pageState[section] === totalPages ? 'disabled' : ''}>&gt;</button>`;
      bar.innerHTML = html;

      // Pagination events
      bar.querySelectorAll('.page-btn').forEach(btn => {
        btn.onclick = function() {
          if (btn.classList.contains('prev') && pageState[section] > 1) {
            pageState[section]--;
          } else if (btn.classList.contains('next') && pageState[section] < totalPages) {
            pageState[section]++;
          } else if (btn.dataset.page) {
            pageState[section] = parseInt(btn.dataset.page);
          }
          renderPagination(section);
          // TODO: Load content for pageState[section]
        };
      });
    }

    // Section navigation
    document.querySelectorAll('.nav-btn').forEach(btn => {
      btn.onclick = function() {
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        sectionIds.forEach(id => {
          document.getElementById(id).classList.toggle('active', id === btn.dataset.section);
        });
        renderPagination(btn.dataset.section);
      };
    });

    // Initial render
    renderPagination('pending');
  </script>
</body>
</html>