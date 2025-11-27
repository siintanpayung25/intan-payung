<!-- Sidebar -->
<div class="sidebar">
    <ul>
        @foreach($menus as $menu)
        <li>
            <a href="{{ $menu->url }}" title="{{ $menu->nama }}">
                <img src="{{ asset('gambar/menu/' . $menu->ikon) }}" alt="{{ $menu->nama }}" style="width: 24px; height: 24px; margin-right: 10px;">
                <span>{{ $menu->nama }}</span>
            </a>
            @if($menu->submenus->count() > 0)
            <ul class="submenu">
                @foreach($menu->submenus as $submenu)
                @if($submenu->levelUsers->contains('level_user_id', $user->pegawai->level_user_id))
                <li class="submenu-item">
                    <a href="{{ $submenu->url }}" class="submenu-link" title="{{ $submenu->nama }}">
                        {{ $submenu->nama }}
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach

        <!-- Tombol Logout Manual di Sidebar -->
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-link w-100" style="text-align: left; padding-left: 0; text-decoration: none;" title="Logout">
                    <img src="gambar/menu/logout.png" alt="Logout" style="width: 24px; height: 24px; margin-right: 10px; color: black;">
                    <span style="color: black;">Logout</span>
                </button>
            </form>
        </li>
    </ul>
</div>