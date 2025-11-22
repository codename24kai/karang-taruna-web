document.getElementById('togglePassword').addEventListener('click', function() {
  const passwordInput = document.getElementById('password');
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);
});

document.getElementById('loginForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value;
  const alertBox = document.getElementById('alertBox');

  const admins = JSON.parse(localStorage.getItem('admins')) || [];

  const match = admins.find(a => a.username === username && a.password === password);

  if (match) {
    alertBox.className = 'alert alert-success';
    alertBox.textContent = 'Login berhasil! Mengalihkan ke dashboard...';
    alertBox.style.display = 'block';
    setTimeout(() => {
      window.location.href = 'dashboard.html';
    }, 1500);
  } else {
    alertBox.className = 'alert alert-error';
    alertBox.textContent = 'Username atau password salah.';
    alertBox.style.display = 'block';
    setTimeout(() => {
      alertBox.style.display = 'none';
    }, 3000);
  }
});


// Redirect ke dashboard setelah login sukses
if (document.getElementById('loginForm')) {
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const admins = JSON.parse(localStorage.getItem('admins')) || [];
    const match = admins.find(a => a.username === username && a.password === password);
    if (match) {
      localStorage.setItem('admin_logged_in', JSON.stringify({username: username}));
      window.location.href = '../dashboard/dashboard admin.html';
    }
  });
}
