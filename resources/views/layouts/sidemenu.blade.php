<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <img src="" alt="">
            <a href="#">Painel</a>
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

                    @can('users')
                        <li class="{{ @$users_ ? 'ativo' : '' }}">
                            <a href="{{ route('users') }}">
                                <i class="fas fa-users"></i>
                                <span>Usuários</span>
                            </a>
                        </li>
                    @endcan

                   @can('publishers')
                        <li class="{{ @$publisher_ ? 'ativo' : '' }}">
                            <a href="{{ route('publishers') }}">
                                <i class="fas fa-pen-fancy"></i>
                                <span>Editoras</span>
                            </a>
                        </li>
                   @endcan

                   @can('books')
                        <li class="{{ @$books_ ? 'ativo' : '' }}">
                            <a href="{{ route('books') }}">
                                <i class="fas fa-book"></i>
                                <span>Livros</span>
                            </a>
                        </li>
                    @endcan

                    @can('templates')
                        <li class="{{ @$templates_ ? 'ativo' : '' }}">
                            <a href="{{ route('templates') }}">
                                <i class="fas fa-vector-square"></i>
                                <span>Templates</span>
                            </a>
                        </li>
                    @endcan

                    @can('events')
                        <li class="{{ @$events_ ? 'ativo' : '' }}">
                            <a href="{{ route('events') }}">
                                <i class="fas fa-chart-bar"></i>
                                <span>Eventos</span>
                            </a>
                        </li>
                    @endcan

                    @can('roles')
                        <li class="{{ @$roles_ ? 'ativo' : '' }}">
                            <a href="{{ route('roles') }}">
                                <i class="fas fa-user"></i>
                                <span>Perfis</span>
                            </a>
                        </li>
                    @endcan

                    @can('permissions')
                        <li class="{{ @$permissions_ ? 'ativo' : '' }}">
                            <a href="{{ route('permissions') }}">
                                <i class="fas fa-lock-open"></i>
                                <span>Permisssões</span>
                            </a>
                        </li>
                    @endcan

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