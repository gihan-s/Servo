console.log(document.querySelector('.pop-up-section .pop-up-header i'));  

document.addEventListener('DOMContentLoaded', () => {

    // Add focus and blur event listeners to the search input field

    document.querySelector('.search-button input').addEventListener('focusin', function() { 
        document.querySelector('.search-button').classList.add('focus');
    })

    // Remove focus class when the input field loses focus

    document.querySelector('.search-button input').addEventListener('focusout', function() { 
        document.querySelector('.search-button').classList.remove('focus');
    })

    // Toggle the visibility of the sort options when the selection input field is clicked

    document.querySelector('.search-content .advance-search .sort-selection .selection-input-field').addEventListener('click', function() { 
        document.querySelector('.search-content .advance-search .sort-selection .selection-options').classList.toggle('active');
        document.querySelector('.search-content .advance-search .sort-selection .selection-input-field i').classList.toggle('rotated');
    })

    // Close the sort options when clicking outside of it
    
    document.addEventListener('click', (e) => {
        if (e.target !== document.querySelector('.search-content .advance-search .sort-selection .selection-input-field')) {
            document.querySelector('.search-content .advance-search .sort-selection .selection-options').classList.remove('active');
            document.querySelector('.search-content .advance-search .sort-selection .selection-input-field i').classList.remove('rotated');
        }
    });

    // Update the selection input field value when an option is clicked

    document.querySelectorAll('.search-content .advance-search .sort-selection .selection-options .opt').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelector('.search-content .advance-search .sort-selection .selection-input-field input').value = this.textContent;
            document.querySelector('.search-content .advance-search .sort-selection .selection-options').classList.remove('active');
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

    // Toggle the filter options when the title is clicked

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



    // Function to toggle the visibility of pop-up sections
    // and the pop-up itself

    function togglePopUp(popUpId) {
        document.getElementsByClassName(popUpId)[0].classList.toggle('deactive');
        document.getElementsByClassName(popUpId)[0].querySelector('.pop-up').classList.toggle('deactive');
    }

    document.querySelectorAll('.search-bottom .search-content .advance-search .search-selection .selection').forEach(selection => {
        selection.addEventListener('click', function() {
            togglePopUp(this.id);
        });
    }); 
    
    document.querySelectorAll('.pop-up-section .pop-up-header i').forEach(close => {
        close.addEventListener('click', function() {
            togglePopUp(this.id);
        });
    }); 

    // Event listener for the pop-up items
    // to update the selection and close the pop-up

    document.querySelectorAll('.pop-up-section').forEach(section => {
        section.querySelectorAll('.pop-up-content .pop-up-item').forEach(option => {
            option.addEventListener('click', function() {
                //console.log(section.querySelector('.pop-up'));
                document.getElementById(section.classList[1]).querySelector('span').innerHTML = this.querySelector('.pop-up-item-name').textContent;
                section.classList.add('deactive');
                section.querySelector('.pop-up').classList.add('deactive');
            });
        });
    }); 

    // Search functionality for the pop-up sections
    // to filter items based on the search term
    
    document.querySelectorAll('.pop-search-button').forEach(section => {
        section.querySelector('input').addEventListener('keyup', function() {
            console.log(section.parentElement.querySelectorAll(".pop-up-content .filter-item"));
            
            let searchTerm = this.value.toLowerCase();
            section.parentElement.querySelectorAll('.pop-up-content .pop-up-item').forEach(option => {
                if (option.querySelector('.pop-up-item-name').textContent.toLowerCase().includes(searchTerm)) {
                    option.style.display = 'flex';
                }
                else {
                    option.style.display = 'none'; 
                } 
            });
            section.parentElement.querySelectorAll(".pop-up-content .filter-item").forEach(filtering =>{
                var flexCount = 0;
                filtering.querySelectorAll('.pop-up-item').forEach(element => {
                    const style = window.getComputedStyle(element);
                    console.log(style.display);
                    if (style.display === 'flex') {
                        flexCount++;
                    }
                });
                console.log(filtering);
                if( flexCount === 0) {
                    filtering.style.display = 'none';
                }else {
                    filtering.style.display = 'block';
                    filtering.querySelector('.filter-options').style.height = 40 * flexCount + 'px';
                    filtering.querySelector('i').classList.toggle('rotated')
                }
                if (searchTerm === '') {
                    filtering.querySelector('.filter-options').style.height = '0px';
                }
            });
        });
    }); 



});