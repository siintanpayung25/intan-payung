<!-- resources/views/sidebar.blade.php -->
<div class="sidebar">
    <ul>
        @foreach($menus as $menu)
            <li>
                <a href="{{ $menu->url }}">
                    <i class="{{ $menu->ikon }}"></i> {{ $menu->nama }}
                </a>
                <!-- Jika ada submenu, tampilkan submenu -->
                @if($menu->submenus->count() > 0)
                    <ul class="submenu">
                        @foreach($menu->submenus as $submenu)
                            <li><a href="{{ $submenu->url }}">{{ $submenu->nama }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
