<!-- Navbar -->
<nav class="navbar navbar-light bg-light">
  <!-- Tombol Hamburger di kiri -->
  <button class="navbar-toggler" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Judul Aplikasi di sebelah kanan tombol hamburger -->
  <a class="navbar-brand" href="#">Aplikasi SDM</a>

  <!-- Info Pengguna dengan Dropdown di kanan -->
  <div style="margin-left: auto;">
    <div class="dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: black; text-decoration: none;">
        {{ Auth::user()->pegawai->nama }} <!-- Display the logged-in user's name -->
      </a>
      <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="list-style-type: none; padding: 0;">
        <li><a class="dropdown-item" href="#">My Profile</a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="dropdown-item">Logout</button>
        </form>
      </ul>
    </div>
  </div>
</nav>