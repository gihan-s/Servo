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
// Simplified toggle: only update button label (backend determines role via hidden field on page script)
document.querySelectorAll('.user-change .toggle-button').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.querySelectorAll('.user-change .toggle-button').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        const submitBtn = document.querySelector('.login-form button[type="submit"]');
        if(submitBtn){ submitBtn.textContent = btn.textContent.trim().includes('Provider') ? 'Continue as Provider' : 'Continue as Client'; }

        if(btn.textContent.trim().includes('Provider')){
            document.getElementById('loginType').value = 'Provider';
        } else {
            document.getElementById('loginType').value = 'Client';
        }
    });
});
