<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Tramite\Hojas_ruta;
use App\Models\Tramite\Tramite;

class Controlador_reporte_tramite extends Controller
{
    public function reporte_tramite_pdf($id)
    {
        $id_tramite = desencriptar($id);


        $hoja_ruta_listar = Hojas_ruta::with([
            'remitente_user' => function($rem_us) {
                $rem_us->with([
                    'cargo_sm' => function($re_car_sm) {
                        $re_car_sm->with([
                            'unidades_admnistrativas',
                            'direccion' => function($ca_direc) {
                                $ca_direc->with('secretaria_municipal');
                            }
                        ]);
                    },
                    'cargo_mae' => function($car_mae) {
                        $car_mae->with([
                            'unidad_mae' => function($un_mae) {
                                $un_mae->with('mae');
                            }
                        ]);
                    },
                    'contrato' => function($con) {
                        $con->with('grado_academico');
                    },
                    'persona'
                ]);
            },
            'destinatario_user' => function($des_user) {
                $des_user->with([
                    'cargo_sm' => function($des_car_sm) {
                        $des_car_sm->with([
                            'unidades_admnistrativas',
                            'direccion' => function($des_car_direc) {
                                $des_car_direc->with('secretaria_municipal');
                            }
                        ]);
                    },
                    'cargo_mae' => function($des_car_mae) {
                        $des_car_mae->with([
                            'unidad_mae' => function($des_car_unid) {
                                $des_car_unid->with('mae');
                            }
                        ]);
                    },
                    'contrato' => function($des_car_con) {
                        $des_car_con->with('grado_academico');
                    },
                    'persona'
                ]);
            },
            'estado_tipo',
            'tramite' => function($des_car_trami) {
                $des_car_trami->with([
                    'hojas_ruta' => function($tram_hoja) {
                        $tram_hoja->where('actual', 1)->with([
                            'remitente_user' => function($tram_hoja_rem) {
                                $tram_hoja_rem->with([
                                    'cargo_sm' => function($tram_carsm) {
                                        $tram_carsm->with([
                                            'unidades_admnistrativas',
                                            'direccion' => function($tram_direc) {
                                                $tram_direc->with('secretaria_municipal');
                                            }
                                        ]);
                                    },
                                    'cargo_mae' => function($tram_carmae) {
                                        $tram_carmae->with([
                                            'unidad_mae' => function($tra_car_unida_mae) {
                                                $tra_car_unida_mae->with('mae');
                                            }
                                        ]);
                                    },
                                    'contrato' => function($tra_con_con) {
                                        $tra_con_con->with('grado_academico');
                                    },
                                    'persona'
                                ]);
                            },
                            'destinatario_user' => function($des_us) {
                                $des_us->with([
                                    'cargo_sm' => function($des_car_sm) {
                                        $des_car_sm->with([
                                            'unidades_admnistrativas',
                                            'direccion' => function($des_dirrec) {
                                                $des_dirrec->with('secretaria_municipal');
                                            }
                                        ]);
                                    },
                                    'cargo_mae' => function($des_cargo_mae) {
                                        $des_cargo_mae->with([
                                            'unidad_mae' => function($des_uni_mae) {
                                                $des_uni_mae->with('mae');
                                            }
                                        ]);
                                    },
                                    'contrato' => function($des_contra) {
                                        $des_contra->with('grado_academico');
                                    }
                                ]);
                            },
                            'estado_tipo'
                        ]);
                    },
                    'tipo_prioridad',
                    'tipo_tramite',
                    'estado_tipo',
                    'remitente_user'=>function($rem_user){
                        $rem_user->with(['cargo_sm'=>function($car_sm){
                            $car_sm->with([
                                'unidades_admnistrativas',
                                'direccion' => function($ca_direc) {
                                    $ca_direc->with('secretaria_municipal');
                                }
                            ]);
                        },
                        'cargo_mae'=>function($car_mae){
                            $car_mae->with([
                                'unidad_mae' => function($un_mae) {
                                    $un_mae->with('mae');
                                }
                            ]);
                        },
                        'contrato'=>function($con){
                            $con->with('grado_academico');
                        },
                        'persona']);
                    },
                    'destinatario_user'=>function($des_user){
                        $des_user->with(['cargo_sm'=>function($car_sm){
                            $car_sm->with([
                                'unidades_admnistrativas',
                                'direccion' => function($ca_direc) {
                                    $ca_direc->with('secretaria_municipal');
                                }
                            ]);
                        },
                        'cargo_mae'=>function($car_mae){
                            $car_mae->with([
                                'unidad_mae' => function($un_mae) {
                                    $un_mae->with('mae');
                                }
                            ]);
                        },
                        'contrato'=>function($con){
                            $con->with('grado_academico');
                        },
                        'persona']);
                    },
                    'user_cargo_tramite'
                ]);
            },
            'ruta_archivado'
        ])->where('tramite_id', $id_tramite)
        ->OrderBy('id','asc')
        ->get();



        //par el tramite
        $tramite = Tramite::with(['tipo_prioridad','tipo_tramite', 'estado_tipo', 'remitente_user'=>function ($ruse) {
            $ruse->with(['persona', 'contrato'=>function($cotn){
                $cotn->with(['grado_academico']);
            }]);
        }, 'destinatario_user'=>function($des_user){
            $des_user->with(['cargo_sm','cargo_mae','persona', 'contrato'=>function ($cdes) {
                $cdes->with(['grado_academico']);
            }]);
        }, 'user_cargo_tramite'=>function ($uct) {
            $uct->with(['cargo_sm'=>function($des_csm){
                $des_csm->with(['unidades_admnistrativas', 'direccion']);
            }, 'cargo_mae'=>function($desca_m){
                $desca_m->with(['unidad_mae']);
            },'persona', 'contrato'=>function($cto){
                $cto->with(['grado_academico']);
            }]);
        }])->find($id_tramite);

        $data['hoja_ruta'] = $hoja_ruta_listar;
        $data['tramite'] = $tramite;


        // Crear el objeto Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('tempDir', '/path/to/temp');
        $options->set('chroot', '/path/to/chroot');
        $options->set('logOutputFile', '/path/to/logfile');
        $options->set('defaultPaperSize', 'letter');
        $options->set('defaultPaperOrientation', 'portrait');
        $dompdf = new Dompdf($options);


        // Crear el contexto HTTP para Dompdf
        $context = stream_context_create([
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        // Agregar el contexto HTTP
        $dompdf->setHttpContext($context);

        // Renderizar la vista como HTML
        $html = view('administrador.reportes.tramite.reporte_tramite', $data)->render();

        // Cargar el HTML en Dompdf y renderizar el PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');

        // Registrar el evento para agregar el número de página
        $dompdf->setCallbacks([
            'pageNumber' => function () use ($dompdf) {
                return $dompdf->getCanvas()->get_page_number();
            },
            'totalPages' => function () use ($dompdf) {
                return $dompdf->getCanvas()->get_page_count();
            },
        ]);

        // Renderizar el PDF
        $dompdf->render();

        // Obtener el contenido del PDF como una cadena
        $pdfContent = $dompdf->output();

        // Retornar el PDF como una respuesta HTTP con el encabezado adecuado para mostrar en el navegador
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="documento.pdf"');
    }
}
