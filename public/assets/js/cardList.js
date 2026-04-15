document.addEventListener('DOMContentLoaded', () => {

    // Add focus and blur event listeners to the search input field
    const searchButtonInput = document.querySelector('.search-button input');
    if (searchButtonInput) {
        searchButtonInput.addEventListener('focusin', function () {
            document.querySelector('.search-button').classList.add('focus');
        })

        // Remove focus class when the input field loses focus
        searchButtonInput.addEventListener('focusout', function () {
            document.querySelector('.search-button').classList.remove('focus');
        })
    }

    // Toggle the visibility of the sort options when the selection input field is clicked
    const sortSelectionField = document.querySelector('.advance-search .sort-selection .selection-input-field');
    if (sortSelectionField) {
        sortSelectionField.addEventListener('click', function () {
            document.querySelector('.advance-search .sort-selection .selection-options').classList.toggle('active');
            document.querySelector('.advance-search .sort-selection .selection-input-field i').classList.toggle('rotated');
        })
    }

    // Close the sort options when clicking outside of it
    const sortOptions = document.querySelector('.advance-search .sort-selection .selection-options');
    const sortInputField = document.querySelector('.advance-search .sort-selection .selection-input-field');
    
    if (sortOptions && sortInputField) {
        document.addEventListener('click', (e) => {
            if (e.target !== sortInputField) {
                sortOptions.classList.remove('active');
                const sortIcon = sortInputField.querySelector('i');
                if (sortIcon) {
                    sortIcon.classList.remove('rotated');
                }
            }
        });

        // Update the selection input field value when an option is clicked
        document.querySelectorAll('.advance-search .sort-selection .selection-options .opt').forEach(option => {
            option.addEventListener('click', function () {
                const sortInput = sortInputField.querySelector('input');
                if (sortInput) {
                    sortInput.value = this.textContent;
                }
                sortOptions.classList.remove('active');
            });
        });
    }

    const buttons = document.querySelectorAll(".container-changer .buttons");
    if (buttons.length > 0) {
        buttons.forEach(e => {
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
    }


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


    

});