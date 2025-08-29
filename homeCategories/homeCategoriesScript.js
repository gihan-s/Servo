document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', e => {
        if (e.target.id === 'digital') {
            document.querySelector('.digital-section').classList.add('active');
            document.querySelector('.physical-section').classList.remove('active');
            document.getElementById('digital').classList.add('bt-border');
            document.getElementById('physical').classList.remove('bt-border');
        } else if (e.target.id === 'physical') {
            document.querySelector('.physical-section').classList.add('active');
            document.querySelector('.digital-section').classList.remove('active');
            document.getElementById('digital').classList.remove('bt-border');
            document.getElementById('physical').classList.add('bt-border');
        }
    });
});