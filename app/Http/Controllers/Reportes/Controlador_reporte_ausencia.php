<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Biometrico\Permiso\Permiso;
use Illuminate\Http\Request;


use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;



class Controlador_reporte_ausencia extends Controller
{
    /* public function permiso_boleta_pdf($id){
        //desencripto el dato
        $id_permiso = desencriptar($id);

        //para listar todo lo que lleva el permiso
        $data['permiso'] = Permiso::with(['permiso_desglose'=>function($pd){
            $pd->with(['tipo_permiso']);
        },'usuario_creado','usuario_editado','persona', 'contrato'=>function($co){
            $co->with(['nivel', 'tipo_contrato', 'cargo_mae'=>function($cm){
                $cm->with(['unidad_mae']);
            }, 'cargo_sm'=>function($cs){
                $cs->with(['unidades_admnistrativas', 'direccion'=>function($dir){
                    $dir->with(['secretaria_municipal']);
                }]);
            }, 'profesion', 'grado_academico', ]);
        }])
        ->find($id_permiso);

        // Crear el código QR


        $options = new Options;
        $options->set('isPhpEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        // Definir la cabecera
        $html_cabecera = '<div style="text-align: right; font-weight: bold;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos ipsam nulla quae repudiandae nam, molestiae in deserunt quo ipsa animi dignissimos, fugiat modi doloribus soluta corrupti sed. Expedita, voluptate sit!</div>';

        // Definir el pie de página
        $html_pie = '<div style="text-align: center; font-style: italic;">Página {PAGE_NUM} de {PAGE_COUNT}</div>';

        // Crear el contexto HTTP
        $context = stream_context_create([
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        // Agregar la cabecera y el pie de página
        $dompdf->setHttpContext($context, $html_cabecera, $html_pie);

        $data['persona'] = 'Persona';
        $data['id_permiso'] = $id_permiso;
        $html = View::make('administrador.reportes.boleta_permiso.pdf_boleta_permiso')->with($data)->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter');
        $dompdf->render();
        $pdfContent = $dompdf->output();
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
    } */



    /* public function permiso_boleta_pdf($id){
        // desencripto el dato
        $id_permiso = desencriptar($id);

        // para listar todo lo que lleva el permiso
        $data['permiso'] = Permiso::with(['permiso_desglose'=>function($pd){
            $pd->with(['tipo_permiso']);
        },'usuario_creado','usuario_editado','persona', 'contrato'=>function($co){
            $co->with(['nivel', 'tipo_contrato', 'cargo_mae'=>function($cm){
                $cm->with(['unidad_mae']);
            }, 'cargo_sm'=>function($cs){
                $cs->with(['unidades_admnistrativas', 'direccion'=>function($dir){
                    $dir->with(['secretaria_municipal']);
                }]);
            }, 'profesion', 'grado_academico', ]);
        }])
        ->find($id_permiso);

        // Crear el código QR

        $options = new Options;
        $options->set('isPhpEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        // Crear el contexto HTTP
        $context = stream_context_create([
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        // Agregar el contexto HTTP
        $dompdf->setHttpContext($context);

        $data['persona'] = 'Persona';
        $data['id_permiso'] = $id_permiso;
        $html = View::make('administrador.reportes.boleta_permiso.pdf_boleta_permiso')->with($data)->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter');
        $dompdf->render();
        $pdfContent = $dompdf->output();
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
    } */

    public function permiso_boleta_pdf($id){
        // desencripto el dato
        $id_permiso = desencriptar($id);

        // para listar todo lo que lleva el permiso
        $data['permiso'] = Permiso::with(['permiso_desglose' => function ($pd) {
            $pd->with(['tipo_permiso']);
        }, 'usuario_creado', 'usuario_editado', 'persona', 'contrato' => function ($co) {
            $co->with(['nivel', 'tipo_contrato', 'cargo_mae' => function ($cm) {
                $cm->with(['unidad_mae']);
            }, 'cargo_sm' => function ($cs) {
                $cs->with(['unidades_admnistrativas', 'direccion' => function ($dir) {
                    $dir->with(['secretaria_municipal']);
                }]);
            }, 'profesion', 'grado_academico',]);
        }])
            ->find($id_permiso);

        // Crear el objeto Dompdf
    $options = new Options();
    $options->set('isPhpEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
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
    $data['persona'] = 'Persona';
    $data['id_permiso'] = $id_permiso;
    $html = view('administrador.reportes.boleta_permiso.pdf_boleta_permiso', $data)->render();

    // Cargar el HTML en Dompdf y renderizar el PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter');

    // Registrar el evento para agregar el número de página
    $dompdf->setCallbacks([
        'pageNumber' => function() use ($dompdf) {
            return $dompdf->getCanvas()->get_page_number();
        },
        'totalPages' => function() use ($dompdf) {
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