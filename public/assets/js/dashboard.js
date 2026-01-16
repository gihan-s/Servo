document.addEventListener('DOMContentLoaded', function () {
  const buttonLinks = {
    viewPaymentsButton: '/payments',
    viewRequestsButton: '/requests',
    viewProjectsButton: '/projects'
  };

  Object.entries(buttonLinks).forEach(([id, url]) => {
    const btn = document.getElementById(id);
    if (btn) {
      btn.addEventListener('click', () => {
        window.location.href = url;
      });
    }
  });

  const cardLinks = {
    activeRequestsCard: '/requests',
    pendingPaymentsCard: '/payments',
    totalProjectsCard: '/projects',
    totalSpentCard: '/payments'
  };

  Object.entries(cardLinks).forEach(([id, url]) => {
    const card = document.getElementById(id);
    if (card) {
      card.style.cursor = 'pointer';
      card.addEventListener('click', () => {
        window.location.href = url;
      });
    }
  });
});