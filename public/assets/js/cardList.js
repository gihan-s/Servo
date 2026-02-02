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


    //filter accordion

    document.querySelectorAll('.filter-item .filter-title').forEach(option => {
        option.addEventListener('click', function() {
            this.querySelector('i').classList.toggle('rotated');
            if(this.nextElementSibling.clientHeight === 0) {
                this.nextElementSibling.style.height = this.nextElementSibling.children[0].offsetHeight * this.nextElementSibling.childElementCount + 'px';
            }
            else {
                this.nextElementSibling.style.height = '0';
            }
        });
    });


    // Toggle the radio button state when the label is clicked

    document.querySelectorAll('.search-filters .filter-item .radios li').forEach(option => {
        option.addEventListener('click', function() {
            if (!this.querySelector('input').checked){
                this.querySelector('input').checked = true;
            }
        });
    });

    // Toggle the checkbox state when the label is clicked

    document.querySelectorAll('.search-filters .filter-item .checkboxes li').forEach(option => {
        option.addEventListener('click', function() {
            if (!this.querySelector('input').checked){
                this.querySelector('input').checked = true;
            }
            else {
                this.querySelector('input').checked = false;
            }
        });
    });

    // Toggle the checkbox state when the input is clicked

    document.querySelectorAll('.search-filters .filter-item .checkboxes li input').forEach(option => {
        option.addEventListener('click', function() {
            if (!this.checked){
                this.checked = true;
            }
            else {
                this.checked = false;
            }
        });
    });

    

    // Function to toggle the visibility of pop-up sections
    // and the pop-up itself

    function togglePopUp(popUpId) {
        document.getElementsByClassName(popUpId)[0].classList.toggle('deactive');
        document.getElementsByClassName(popUpId)[0].querySelector('.pop-up').classList.toggle('deactive');
    }

    document.querySelectorAll('button').forEach(selection => {
        selection.addEventListener('click', function() {
            togglePopUp(this.id);
        });
    }); 
    
    document.querySelectorAll('.pop-up-section .pop-up-header i').forEach(close => {
        close.addEventListener('click', function() {
            togglePopUp(this.id);
        });
    }); 


    // Pagination functionality for .page-btn buttons
    // Handles click events, active state management, and prev/next navigation
    
    const paginationContainers = document.querySelectorAll('.pagination');
    
    paginationContainers.forEach(pagination => {
        // Use event delegation for better performance
        pagination.addEventListener('click', function(e) {
            const button = e.target.closest('.page-btn');
            if (!button || button.disabled) return;
            
            const allPageBtns = Array.from(pagination.querySelectorAll('.page-btn:not(.prev):not(.next)'));
            const prevBtn = pagination.querySelector('.page-btn.prev');
            const nextBtn = pagination.querySelector('.page-btn.next');
            
            // Handle prev/next buttons
            if (button.classList.contains('prev') || button.classList.contains('next')) {
                const currentActive = pagination.querySelector('.page-btn.active');
                const currentIndex = allPageBtns.indexOf(currentActive);
                
                let targetIndex;
                if (button.classList.contains('prev')) {
                    targetIndex = Math.max(0, currentIndex - 1);
                } else {
                    targetIndex = Math.min(allPageBtns.length - 1, currentIndex + 1);
                }
                
                if (allPageBtns[targetIndex]) {
                    // Remove active class from all page buttons
                    allPageBtns.forEach(btn => {
                        btn.classList.remove('active');
                        btn.removeAttribute('aria-current');
                    });
                    
                    // Add active class to target button
                    allPageBtns[targetIndex].classList.add('active');
                    allPageBtns[targetIndex].setAttribute('aria-current', 'page');
                    
                    // Update button states
                    if (prevBtn) prevBtn.disabled = targetIndex === 0;
                    if (nextBtn) nextBtn.disabled = targetIndex === allPageBtns.length - 1;
                }
                return;
            }
            
            // Handle numbered page buttons
            const currentIndex = allPageBtns.indexOf(button);
            
            // Remove active class from all page buttons
            allPageBtns.forEach(btn => {
                btn.classList.remove('active');
                btn.removeAttribute('aria-current');
            });
            
            // Add active class to clicked button
            button.classList.add('active');
            button.setAttribute('aria-current', 'page');
            
            // Update prev/next button states
            if (prevBtn) prevBtn.disabled = currentIndex === 0;
            if (nextBtn) nextBtn.disabled = currentIndex === allPageBtns.length - 1;
        });
    });

});