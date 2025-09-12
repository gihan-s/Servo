document.addEventListener('DOMContentLoaded', function() {
    // Hire provider buttons
    const hireButtons = document.querySelectorAll('.provider-card .btn-primary');
    hireButtons.forEach(button => {
        button.addEventListener('click', function() {
            const providerName = this.closest('.provider-card').querySelector('h4').textContent;
            alert(`Initiating hiring process for ${providerName}`);
        });
    });
    
    // Job action buttons
    const jobActions = document.querySelectorAll('.job-actions .btn');
    jobActions.forEach(button => {
        button.addEventListener('click', function() {
            const jobTitle = this.closest('.job-card').querySelector('.job-title').textContent;
            const actionText = this.textContent;
            
            if (actionText.includes('Review')) {
                alert(`Redirecting to review page for ${jobTitle}`);
            } else if (actionText.includes('Message')) {
                alert(`Opening chat for ${jobTitle}`);
            } else if (actionText.includes('Leave Review')) {
                alert(`Redirecting to rating page for ${jobTitle}`);
            } else {
                alert(`Opening details for ${jobTitle}`);
            }
        });
    });
    
    // Quick action cards
    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach(card => {
        card.addEventListener('click', function() {
            const actionTitle = this.querySelector('h3').textContent;
            console.log(`Navigating to ${actionTitle} section`);
        });
    });
    
    // Notification icon
    const notificationIcon = document.querySelector('.notification-icon');
    notificationIcon.addEventListener('click', function() {
        alert('Showing notifications');
    });
});
