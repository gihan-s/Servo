console.log(document.querySelectorAll('.search-bottom .search-filters .filter-item .filter-title')[1].nextElementSibling.clientHeight);  

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.search-button input').addEventListener('focusin', function() { 
        document.querySelector('.search-button').classList.add('focus');
    })

    document.querySelector('.search-button input').addEventListener('focusout', function() { 
        document.querySelector('.search-button').classList.remove('focus');
    })

    document.querySelector('.search-header .sort-selection .selection-input-field').addEventListener('click', function() { 
        document.querySelector('.search-header .sort-selection .selection-options').classList.toggle('active');
        document.querySelector('.search-header .sort-selection .selection-input-field i').classList.toggle('rotated');
    })
    
    document.addEventListener('click', (e) => {
        if (e.target !== document.querySelector('.search-header .sort-selection .selection-input-field')) {
            document.querySelector('.search-header .sort-selection .selection-options').classList.remove('active');
            document.querySelector('.search-header .sort-selection .selection-input-field i').classList.remove('rotated');
        }
    });

    document.querySelectorAll('.search-header .sort-selection .selection-options .opt').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelector('.search-header .sort-selection .selection-input-field input').value = this.textContent;
            document.querySelector('.search-header .sort-selection .selection-options').classList.remove('active');
        });
    });

    document.querySelectorAll('.search-bottom .search-filters .filter-item .radios li').forEach(option => {
        option.addEventListener('click', function() {
            if (!this.querySelector('input').checked){
                this.querySelector('input').checked = true;
            }
        });
    });

    document.querySelectorAll('.search-bottom .search-filters .filter-item .checkboxes li').forEach(option => {
        option.addEventListener('click', function() {
            this.querySelector('input').checked = !this.querySelector('input').checked;
        });
    });

    document.querySelectorAll('.search-bottom .search-filters .filter-item .filter-title').forEach(option => {
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


});