
<div class="card  mb-2">
    USUARIO : {{ $cargo_enum->id_usuario }}
    <br>
    ID CARGO :
    @if ($cargo_enum->cargo_sm != null)
        {{ 'CARGO SM : '.$cargo_enum->id_cargo_sm }}
    @endif

    @if ($cargo_enum->cargo_mae != null)
        {{ 'CARGO MAE : '.$cargo_enum->id_cargo_mae }}
    @endif

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">:::::::: CARGO ::
            {{ $cargo_enum->cargo_sm->nombre ?? ($cargo_enum->cargo_mae->nombre ?? 'NOMBRE NO DISPONIBLE') }}
        :::::::: </h5>
    </div>
</div>

<div class="row">
    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
        <div class="card card-border-shadow-secondary h-100">
            <a type="button">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="ti ti-layout ti-md"></i>
                            </span>
                        </div>
                        <h4 class="ms-1 mb-0">42</h4>
                    </div>
                    <p class="mb-1">CORRESPONDENCIA</p>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
        <div class="card card-border-shadow-success h-100">
            <a type="button"  >
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-success"><i
                                    class='ti ti-alert-triangle ti-md'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">8</h4>
                    </div>
                    <p class="mb-1">BANDEJA DE ENTRADA</p>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
        <div class="card card-border-shadow-primary h-100">
            <a type="button"  >
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class='ti ti-cloud-down ti-md'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">27</h4>
                    </div>
                    <p class="mb-1">RECIBIDOS</p>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
        <div class="card card-border-shadow-info h-100">
            <a type="button">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info"><i class='ti ti-cloud-up ti-md'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">4</h4>
                    </div>
                    <p class="mb-1">ENVIADOS</p>
                </div>
            </a>

        </div>
    </div>

    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
        <div class="card card-border-shadow-danger h-100">
            <a type="button">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-danger"><i class='ti ti-thumb-down ti-md'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">8</h4>
                    </div>
                    <p class="mb-1">OBSERVADOS</p>
                </div>
            </a>
        </div>
    </div>

    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-4">
        <div class="card card-border-shadow-warning h-100">
            <a type="button">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i class='ti ti-server ti-md'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">3</h4>
                    </div>
                    <p class="mb-1">ARCHIVADOS</p>
                </div>
            </a>
        </div>
    </div>
</div>
