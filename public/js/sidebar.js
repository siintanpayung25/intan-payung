// sidebar.js

// Toggle sidebar (open/close) ketika tombol hamburger diklik
document.getElementById('sidebarToggle').addEventListener('click', function() {
  var sidebar = document.querySelector('.sidebar');
  var mainContent = document.getElementById('mainContent');
  sidebar.classList.toggle('minimized'); // Toggle class untuk meminimalkan/menampilkan sidebar
  // Menyesuaikan margin-left konten utama berdasarkan status sidebar
  if (sidebar.classList.contains('minimized')) {
    mainContent.style.marginLeft = '60px';
  } else {
    mainContent.style.marginLeft = '250px';
  }
});

// Fungsi untuk menyesuaikan sidebar saat ukuran layar berubah
function adjustSidebar() {
    var sidebar = document.querySelector('.sidebar');
    var mainContent = document.getElementById('mainContent');
    
    if (window.innerWidth <= 768) { // Jika lebar layar kurang dari atau sama dengan 768px
        sidebar.classList.add('minimized');
        mainContent.style.marginLeft = '60px';
    } else {
        sidebar.classList.remove('minimized');
        mainContent.style.marginLeft = '250px';
    }
}

// Panggil fungsi pertama kali saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    adjustSidebar();
});

// Panggil fungsi saat ukuran layar berubah
window.addEventListener('resize', adjustSidebar);

// Fungsi untuk toggle submenu saat diklik
document.querySelectorAll('.submenu-toggle').forEach(function(button) {
    button.addEventListener('click', function() {
        var submenu = this.closest('li').querySelector('.submenu');
        submenu.classList.toggle('open'); // Toggle kelas untuk membuka/menutup submenu
    });
});
