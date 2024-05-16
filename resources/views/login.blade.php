<!DOCTYPE html>
<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-semi-dark" data-assets-path="../../assets/" data-template="vertical-menu-template-semi-dark">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>LOGIN</title>
        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/css/rtl/theme-semi-dark.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('admin_template/css/demo.css') }}" />
        
        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/node-waves/node-waves.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/typeahead-js/typeahead.css') }}" /> 
        <!-- Vendor -->
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/%40form-validation/form-validation.css') }}" />

        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/css/pages/page-auth.css') }}">
        <!-- Helpers -->
        <script src="{{ asset('admin_template/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/js/template-customizer.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('rodry/css/loader.css') }}">

        <link rel="stylesheet" href="{{ asset('rodry/css/css.css') }}">
    </head>

    <body>

    <!-- Loading wrapper start -->
    <div id="loading-wrapper">
        <div class="spinner-border"></div>
        Cargando login . . . .
    </div>
    <!-- Loading wrapper end -->

    <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
        <!-- Login -->
        <div class="card">
            <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="index.html" class="app-brand-link gap-2">

                <span class="app-brand-text demo text-body fw-bold ms-1">LOGIN</span>
                </a>
            </div>

            <div id="mensaje_error"></div>

            <form id="formulario_login" class="mb-3" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label for="usuario" class="form-label">USUARIO</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese usuario" autofocus >
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">CONTRASEÑA</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" autofocus>
                </div>
            </form>
            <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" id="btn_ingresar_usuario">INGRESAR</button>
            </div>
        </div>
        <!-- /Register -->
        </div>
    </div>
    </div>
    
    <script src="{{ asset('admin_template/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('admin_template/vendor/js/menu.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('admin_template/js/pages-auth.js') }}"></script>
</body>

</html>



<script>
    // Espera a que la página se cargue completamente
    window.addEventListener('load', function() {
        setTimeout(() => {
            var loader = document.getElementById('loading-wrapper');
            loader.style.display = 'none';
        }, 500);
    });

    //para ingresar con usuario y contraseña
    let login_btn   = document.getElementById('btn_ingresar_usuario');
    // Crea un nuevo elemento <i> con las clases proporcionadas
    let nuevo_icono = document.createElement('span');
    let nuevo_mensaje = document.createElement('span');
    login_btn.addEventListener('click', async ()=>{
        //verificamos los datos
        let datos = Object.fromEntries(new FormData(document.getElementById('formulario_login')).entries());
        //ahora activamos el boton
        console.log(datos);

        validar_boton(true, "Verificando datos . . . . ");
        try {
            let respuesta = await fetch("{{ route('cl_ingresar') }}", {
                method : 'POST',
                headers:{
                    'Content-Type':'aplication/json'
                },
                body: JSON.stringify(datos)
            });
            let data = await respuesta.json();
            if(data.tipo === 'success'){
                document.getElementById('mensaje_error').innerHTML = `<p id="success_estilo" >`+data.mensaje+`</p>`;
                window.location = '';
                validar_boton(false, 'Datos correctos . . . . ');
            }
            if(data.tipo === 'error'){
                document.getElementById('mensaje_error').innerHTML = `<p id="error_estilo" >`+data.mensaje+`</p>`;
                validar_boton(false, 'INGRESAR');
            }
        } catch (error) {
            console.log('Existe un error : '+error);
            validar_boton(false, 'INGRESAR');
        }
    });


    function validar_boton(valor, msj){
        if(valor===true){
           // Al elemento <span> le agregamos la clase
            nuevo_icono.className = "spinner-border";
            // Creamos un contenedor para el spinner y el contenido original del botón
            let contenedor = document.createElement("div");
            // Agregamos el spinner al contenedor
            contenedor.appendChild(nuevo_icono);
            // Agregamos un espacio en blanco al contenedor
            contenedor.appendChild(document.createTextNode(" "));
            // Adicionamos el contenido original del botón al contenedor
            contenedor.appendChild(login_btn.firstChild);
            // Inserta el nuevo contenedor antes del nuevo contenido del botón
            login_btn.insertBefore(contenedor, login_btn.firstChild);
            // Adicionamos una clase al botón
            login_btn.disabled = true;
        }
        if(valor===false){
            login_btn.innerHTML = msj;
            login_btn.disabled = false;
        }
    }

</script>

<!-- beautify ignore:end -->