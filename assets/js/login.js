// hide and show password
function togglePasswordView(e) {
    var pwd = document.getElementById('password');
    var toggle = document.getElementById('togglePassword');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        pwd.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
    if(e) e.preventDefault();
}
// form validation
const form = document.querySelector('.login-form');
if (form) {
    form.addEventListener('submit', function(e) {
        // email validation
        const email = document.getElementById('email').value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            alert('Please enter a valid email address.');
            e.preventDefault();
            return;
        }
        // password validation (must contain a number and a special character)
        const password = document.getElementById('password').value;
        const passwordPattern = /^(?=.*[0-9])(?=.*[!@#$%^&*])/;
        if (!passwordPattern.test(password)) {
            alert('Password must contain at least one number and one special character.');
            e.preventDefault();
            return;
        }
    });
}
let isProvider = false;
const toggleBtn = document.querySelectorAll('.user-change .toggle-button');
// change submit button text based on user type
const submitBtn = document.querySelector('.login-form button[type="submit"]');
toggleBtn.forEach((btn) => {
    btn.addEventListener('click', () => {
        toggleBtn.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // change form action based on user type
        if (btn.textContent.trim().includes('Provider')) {
            isProvider = true;
            submitBtn.textContent = 'Continue as Provider';
            form.action = 'provider-login.php';
        } else {
            isProvider = false;
            submitBtn.textContent = 'Continue as Client';
            form.action = 'client-login.php';
        }
    });
});
