const registerForm = document.querySelector('.register-form');
if (registerForm) {
    const errorElement = document.querySelector('.register__error-message');
    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();

        fetch('/registration/signup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: registerForm.elements.username.value,
                password: registerForm.elements.password.value,
            })
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = '/login';
                } else {
                    errorElement.textContent = res.message;
                    errorElement.classList.add('active');
                }
            });
    });
}

const loginForm = document.querySelector('.login-form');
if (loginForm) {
    const errorElement = document.querySelector('.login__error-message');
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        fetch('/login/signin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: loginForm.elements.username.value,
                password: loginForm.elements.password.value,
            })
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = '/';
                } else {
                    errorElement.textContent = res.message;
                    errorElement.classList.add('active');
                }
            });
    });
}