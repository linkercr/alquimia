<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">Dashboard</li>
            <li>
                    <a href="{{ url('/') }}" aria-expanded="false">
                        <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                    </a>
            </li>
            <li>
                    <a href="{{ route('pedidos.index')}}" aria-expanded="false">
                        <i class="fa fa-list"></i><span class="nav-text">Pedidos</span>
                    </a>
                </li>
            @if (Auth::user()->hasRole('Admin'))


            <li>
                    <a href="{{ route('carro.index')}}" aria-expanded="false">
                        <i class="fa fa-shopping-cart"></i><span class="nav-text">Carro</span>
                    </a>
            </li>
            <li>
                    <a href="{{ route('facturas.index')}}" aria-expanded="false">
                        <i class="fa fa-history"></i><span class="nav-text">Facturado</span>
                    </a>
            </li>
            <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                         <i class="fa fa-check-square-o"></i> <span class="nav-text">Control</span>
                     </a>
                     <ul aria-expanded="false">
                       <li><a href="{{ route('control.index')}}">Lista</a></li>
                       <li><a href="{{ route('control.create')}}">Crear</a></li>
                     </ul>
             </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                     <i class="fa fa-th-large"></i> <span class="nav-text">Productos</span>
                 </a>
                 <ul aria-expanded="false">
                   <li><a href="{{ route('productos.index')}}">Lista</a></li>
                   <li><a href="{{ route('productos.create')}}">Crear</a></li>
                 </ul>
         </li>
            <li>
                    <a href="{{ route('empresa.index')}}" aria-expanded="false">
                        <i class="icon-globe-alt menu-icon"></i><span class="nav-text">Empresa</span>
                    </a>
            </li>
            <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                         <i class="fa fa-tags"></i> <span class="nav-text">Categorias</span>
                     </a>
                     <ul aria-expanded="false">
                       <li><a href="{{ route('categorias.index')}}">Lista</a></li>
                       <li><a href="{{ route('categorias.create')}}">Crear</a></li>
                     </ul>
             </li>
           <!-- <li>
                   <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="fa fa-users"></i> <span class="nav-text">Proveedores</span>
                    </a>
                    <ul aria-expanded="false">
                      <li><a href="{{ route('personas.index')}}">Lista</a></li>
                      <li><a href="{{ route('personas.create')}}">Crear</a></li>
                    </ul>
            </li>-->
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-list menu-icon"></i> <span class="nav-text">Permisos</span>
                </a>
                <ul aria-expanded="false">
                  <li><a href="{{ route('permissions.index')}}">Lista</a></li>
                  <li><a href="{{ route('roles.index')}}">Rols</a></li>

                </ul>
            </li>
            <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="icon-user menu-icon"></i> <span class="nav-text">Users</span>
                    </a>
                    <ul aria-expanded="false">
                      <li><a href="{{ route('users.create')}}">Crear</a></li>
                      <li><a href="{{ route('users.index')}}">Lista</a></li>
                    </ul>
            </li>
            @elseif(Auth::user()->hasRole('Cliente'))

            <li>
                    <a href="{{ route('carro.index')}}" aria-expanded="false">
                        <i class="fa fa-shopping-cart"></i><span class="nav-text">Carrito</span>
                    </a>
                </li>
            <li>
                    <a href="{{ route('productos.index')}}" aria-expanded="false">
                        <i class="fa fa-th-large"></i><span class="nav-text">Produtos</span>
                    </a>
            </li>

            @endif

        </ul>
    </div>
</div>
