<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <img src="" alt="">
            <a href="#">ACL</a>
            <div id="close-sidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="sidebar-header">
            <div class="sidebar-menu">
                <ul id="sitemaps">
                    <li class="header-menu">
                        <span>Navegação</span>
                    </li>

                    <li class="{{ @$home ? 'ativo' : '' }}">
                        <a href="{{ route('painel') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Home</span>
                        </a>
                    </li>
                   
                   @can('publishers')
                        <li class="{{ @$publisher_ ? 'ativo' : '' }}">
                            <a href="{{ route('publishers') }}">
                                <i class="fas fa-pen-fancy"></i>
                                <span>Editoras</span>
                            </a>
                        </li>
                   @endcan

                    <li class="{{ @$books_ ? 'ativo' : '' }}">
                        <a href="{{ route('books') }}">
                            <i class="fas fa-book"></i>
                            <span>Livros</span>
                        </a>
                    </li>

                    <li class="{{ @$templates_ ? 'ativo' : '' }}">
                        <a href="{{ route('templates') }}">
                            <i class="fas fa-vector-square"></i>
                            <span>Templates</span>
                        </a>
                    </li>

                    <li class="{{ @$events_ ? 'ativo' : '' }}">
                        <a href="{{ route('events') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Eventos</span>
                        </a>
                    </li>
{{-- 
                    <li class="sidebar-dropdown {{ @$relatorio ? 'active_side' : '' }}">
                        <a>
                            <i class="fas fa-chart-line"></i>
                            <span>Relatório</span>
                        </a>
                        <div class="sidebar-submenu {{ @$relatorio ? 'd-block' : '' }}">
                            <ul>
                                <li class="">
                                    <a href="#">Painel</a>
                                </li>
                            </ul>
                        </div>
                    </li> --}}

                    <li class="">
                        <a href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
</nav>

<style>
    .ativo a {
        color: #16c7ff !important;
    }

    .ativo a i {
        color: #16c7ff;
    }

</style>
