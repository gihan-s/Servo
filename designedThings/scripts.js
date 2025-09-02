document.addEventListener('DOMContentLoaded', () => {

    //create active class for always active accordion

    document.querySelectorAll('.accordion-container .item-content.active').forEach(item => {
        item.parentElement.querySelector('.item-title i').classList.add('rotated');
        item.style.height = item.querySelector('.content').clientHeight + 'px';
        console.log();
    });

    //special for only one accordion actives

    document.querySelectorAll("#one-active-accordion-container").forEach(container => {
        container.querySelectorAll('.item-title').forEach((option, index) => {
            option.addEventListener('click', function () {
                let all_contents = document.querySelectorAll('#one-active-accordion-container .item-content');
                for (let i = 0; i < all_contents.length; i++) {
                    if (i != index) {
                        all_contents[i].style.height = '0';
                        all_contents[i].parentElement.querySelector('.item-title i').classList.remove('rotated');
                    }
                }
            });
        });
    });

    //all common accordion actives

    document.querySelectorAll(".accordion-container").forEach(container => {
        container.querySelectorAll('.item-title').forEach(option => {
            option.addEventListener('click', function () {
                this.querySelector('i').classList.toggle('rotated');
                let content = this.parentElement.querySelector('.item-content');
                console.log(content.classList);
                if (content.clientHeight === 0) {
                    content.style.height = content.querySelector('.content').clientHeight + 'px';
                }
                else {
                    content.style.height = '0';
                }
            });
        });
    });




});