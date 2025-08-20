document.addEventListener('DOMContentLoaded', () => {

    // Add focus and blur event listeners to the search input field

    document.querySelector('.search-button input').addEventListener('focusin', function () {
        document.querySelector('.search-button').classList.add('focus');
    })

    // Remove focus class when the input field loses focus

    document.querySelector('.search-button input').addEventListener('focusout', function () {
        document.querySelector('.search-button').classList.remove('focus');
    })

    // Toggle the visibility of the sort options when the selection input field is clicked

    document.querySelector('.advance-search .sort-selection .selection-input-field').addEventListener('click', function () {
        document.querySelector('.advance-search .sort-selection .selection-options').classList.toggle('active');
        document.querySelector('.advance-search .sort-selection .selection-input-field i').classList.toggle('rotated');
    })

    // Close the sort options when clicking outside of it

    document.addEventListener('click', (e) => {
        if (e.target !== document.querySelector('.advance-search .sort-selection .selection-input-field')) {
            document.querySelector('.advance-search .sort-selection .selection-options').classList.remove('active');
            document.querySelector('.advance-search .sort-selection .selection-input-field i').classList.remove('rotated');
        }
    });

    // Update the selection input field value when an option is clicked

    document.querySelectorAll('.advance-search .sort-selection .selection-options .opt').forEach(option => {
        option.addEventListener('click', function () {
            document.querySelector('.advance-search .sort-selection .selection-input-field input').value = this.textContent;
            document.querySelector('.advance-search .sort-selection .selection-options').classList.remove('active');
        });
    });

    document.querySelectorAll(".container-changer .buttons").forEach(e => {
        e.addEventListener('click', function () {
            document.querySelectorAll(".container-changer .buttons").forEach(btn => {
                btn.classList.remove('active');
            });
            e.classList.add('active');
            document.querySelectorAll(".request-content .requests-section").forEach(sec => {
                if (sec.classList.contains(e.id)) {
                    sec.classList.add("active");
                }
                else {
                    sec.classList.remove("active");
                }
            });
        });
    });

});