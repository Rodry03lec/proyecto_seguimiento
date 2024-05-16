<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo ">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0" />
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">RRHH</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Misc -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Misc">INICIO</span>
        </li>
        <li class="menu-item @if ($menu=='0') active @endif ">
            <a href="{{ route('inicio') }}"  class="menu-link">
                <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
                <div>INICIO</div>
            </a>
        </li>

        @can('admin_usuario_menu')
            <li class="menu-item @if ($menu == '1'||$menu == '2'|| $menu == '3') open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div data-i18n="Users">ADMIN USUARIOS</div>
                </a>
                <ul class="menu-sub">
                    @can('usuario_submenu')
                        <li class="menu-item @if($menu == '1') active @endif">
                            <a href="{{ route('usuarios') }}" class="menu-link">
                                Usuarios
                            </a>
                        </li>
                    @endcan

                    @can('roles_submenu')
                        <li class="menu-item @if($menu == '2') active @endif">
                            <a href="{{ route('roles') }}" class="menu-link">
                                Roles
                            </a>
                        </li>
                    @endcan

                    @can('permisos_submenu')
                        <li class="menu-item @if($menu == '3') active @endif">
                            <a href="{{ route('permisos') }}" class="menu-link">
                                Permisos
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan


        @can('configuracion_menu')
            <li class="menu-item @if ($menu == '4' || $menu == '5'|| $menu == '6'|| $menu=='7'|| $menu=='8'||$menu == '9'||$menu == '10'||$menu == '11'||$menu == '12') open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div data-i18n="Users">CONFIGURACIÓN</div>
                </a>
                <ul class="menu-sub">
                    @can('tipo_contrato_submenu')
                        <li class="menu-item @if($menu == '4') active @endif">
                            <a href="{{ route('ctc_tipoContato') }}" class="menu-link">
                                Tipo de Contrato
                            </a>
                        </li>
                    @endcan

                    @can('tipo_categoria_submenu')
                        <li class="menu-item @if($menu == '5') active @endif">
                            <a href="{{ route('xtc_tipocategoria') }}" class="menu-link">
                                Tipo de categoría
                            </a>
                        </li>
                    @endcan

                    @can('grados_academicos_submenu')
                        <li class="menu-item @if($menu == '6') active @endif">
                            <a href="{{ route('gac_index') }}" class="menu-link">
                                Grados Académicos
                            </a>
                        </li>
                    @endcan

                    @can('ambito_profesional_submenu')
                        <li class="menu-item @if($menu == '7') active @endif">
                            <a href="{{ route('cap_index') }}" class="menu-link">
                                Ambito - Profesional
                            </a>
                        </li>
                    @endcan

                    @can('mae_unidad_submenu')
                        <li class="menu-item @if($menu == '8') active @endif">
                            <a href="{{ route('mae_index') }}" class="menu-link">
                                Mae - Unidad
                            </a>
                        </li>
                    @endcan

                    @can('unidades_administrativas_submenu')
                        <li class="menu-item @if($menu == '9') active @endif">
                            <a href="{{ route('uadm_index') }}" class="menu-link">
                                Unidades Administrativas
                            </a>
                        </li>
                    @endcan

                    @can('secretaria_municipales_direccion_submenu')
                        <li class="menu-item @if($menu == '10') active @endif">
                            <a href="{{ route('smun_index') }}" class="menu-link">
                                Secretarias municipales - Dirección
                            </a>
                        </li>
                    @endcan

                    @can('horarios_submenu')
                        <li class="menu-item @if($menu == '11') active @endif">
                            <a href="{{ route('hor_index') }}" class="menu-link">
                                Horarios
                            </a>
                        </li>
                    @endcan

                    @can('genero_estado_civil_submenu')
                        <li class="menu-item @if($menu == '12') active @endif">
                            <a href="{{ route('ges_index') }}" class="menu-link">
                                Genero - Estado Civil
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan


        @can('registros_menu')
            <li class="menu-item @if ($menu == '13'|| $menu == '14') open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div data-i18n="Users">REGISTROS</div>
                </a>
                <ul class="menu-sub">
                    @can('persona_submenu')
                        <li class="menu-item @if($menu == '13') active @endif">
                            <a href="{{ route('per_index') }}" class="menu-link">
                                Personas
                            </a>
                        </li>
                    @endcan

                    @can('contrato_submenu')
                        <li class="menu-item @if($menu == '14') active @endif">
                            <a href="{{ route('cco_index') }}" class="menu-link">
                                Contratos
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan


        {{-- VAMOS A OCUPAR EL MENU  E 15 AL 20 --}}
        @can('adm_biometrico_menu')
            <li class="menu-item @if ($menu == '15'|| $menu == '16'|| $menu == '17'||$menu == '18'||$menu == '19') open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-server"></i>
                    <div data-i18n="Users">ADM. BIOMETRICO</div>
                </a>
                <ul class="menu-sub">
                    @can('subir_biometrico_submenu')
                        <li class="menu-item @if($menu == '15') active @endif">
                            <a href="{{ route('bio_index') }}" class="menu-link">
                                Subir Biometrico
                            </a>
                        </li>
                    @endcan

                    @can('especial_submenu')
                        <li class="menu-item @if($menu == '16') active @endif">
                            <a href="{{ route('bio_especial') }}" class="menu-link">
                                Especial
                            </a>
                        </li>
                    @endcan


                    @can('asistencia_submenu')
                        <li class="menu-item @if($menu == '17') active @endif">
                            <a href="{{ route('asist_index') }}" class="menu-link">
                                Asistencias
                            </a>
                        </li>
                    @endcan


                    @can('boletas_submenu')
                        <li class="menu-item @if($menu == '18') active @endif">
                            <a href="{{ route('cbol_index') }}" class="menu-link">
                                Boletas
                            </a>
                        </li>
                    @endcan


                    {{-- <li class="menu-item @if($menu == '19') active @endif">
                        <a href="{{ route('cbol_index') }}" class="menu-link">
                            Aprobar boletas
                        </a>
                    </li> --}}
                </ul>
            </li>
        @endcan


        {{-- AQUI VAMOS A OCUPAR EL MENU DE 21 A 25 --}}
        {{-- <li class="menu-item @if ($menu == '21'|| $menu == '22') open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Users">REPORTES</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if($menu == '21') active @endif">
                    <a href="{{ route('bio_index') }}" class="menu-link">
                        Biometrico asistencia
                    </a>
                </li>
            </ul>
        </li> --}}
    </ul>
</aside>
<!-- / Menu -->
