<?php

/**
 * @file
 * Contains \Drupal\actividad\Controller\ActividadController.
 */

namespace Drupal\actividad\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\actividad\Entity\ActividadEntity;

class ActividadController extends ControllerBase
{

    public function bandejaEntradaUt($tabla_id)
    {

        $header = array('nid' => t('Nid'),
            'Componente' => t('Componente'),
            'Actividad' => t('Actividad'),
            'FechaInicio' => t('Fecha Inicio'),
            'Acciones' => t('Actions'),);

        $table = 'actividad';
        foreach (ActividadEntity::selectAll($table) as $actividad) {
// Row with attributes on the row and some of its cells.
            $Url = Url::fromRoute('actividad_add', array('id' => $actividad->nid));
            $codigo = $actividad->field_actividad_madre_actividad_target_id;

            $table = 'crear_actividad';
            $campo = 'nid';
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $componente_id = $creada->field_componente_cactividad_target_id;
                $titulo_actividad = $creada->title;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            foreach (ActividadEntity::selectOne($table, $campo, $componente_id) as $taxonomy) {
                $componente = $taxonomy->name;
            }

            $rows[] = array('data' => array($actividad->nid,
                $componente,
                $titulo_actividad,
                $actividad->field_fecha_inicio_rea_actividad_value,
                \Drupal::l('Reportar Actividad', $Url),
            ));
        }

        $table = array('#type' => 'table', '#header' => $header, '#rows' => $rows, '#attributes' => array('id' => 'actividad-table' . $tabla_id,));

        $html = '<h1>Bandeja de Entrada Unidad de Transparencia</h1>';
        $text = array(
            '#type' => 'markup',
            '#markup' => $html,
        );

        return array(
            $text,
            $table,
        );
    }

    public function actividadCreadaMonitor($tabla_id)
    {

        $header = array('Categoria' => t('Categoria'),
            'Componente' => t('Componente'),
            'Entidad' => t('Entidad'),
            'Codigo Actividad' => t('Codigo Actividad'),
            'Actividad' => t('Actividad'),
            'Estado de la Actividad' => t('Estado de la Actividad'),
            'Fecha de Inicio' => t('Fecha de Inicio'),
            'Acciones' => t('Actions'),);

// Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

        $table = 'crear_actividad';
        $campo = 'field_monitor_cactividad_target_id';
        $codigo = $user->get('uid')->value;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
            $nid = $creada->nid;
            $categoria_fc_id = $creada->field_categoria_ut_cactividad_target_id;
            $componente_id = $creada->field_componente_cactividad_target_id;
            $crear_actividad_titulo = $creada->title;

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            foreach (ActividadEntity::selectOne($table, $campo, $componente_id) as $taxonomy) {
                $componente = $taxonomy->name;
            }

            $table = 'categoria_ut_fc';
            $campo = 'nid';
            $codigo = $categoria_fc_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $categoria) {
                $categoria_nombre = $categoria->title;
                $entidad_id = $categoria->field_categoria_ut_target_id;
                $entidad = $categoria->name;

                $table = 'actividad';
                $campo = 'field_actividad_madre_actividad_target_id';
                $codigo = $nid;
                $campo2 = 'field_entidad_usuario_target_id';
                $codigo2 = $entidad_id;
                unset($actividad); //resetear o destruir objeto actividad                
                foreach (ActividadEntity::selectOneDouble($table, $campo, $codigo, $campo2, $codigo2) as $actividad) {
                    $actividad_id = $actividad->nid;
                    $actividad_codigo = $actividad->field_codigo_actividad_value;
                    $actividad_titulo = $actividad->title;
                    $actividad_estado_id = $actividad->field_estado_actividad_target_id;

                    $table = 'taxonomy_term_field_data';
                    $campo = 'tid';
                    $codigo = $actividad_estado_id;
                    foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                        $actividad_estado = $taxonomy->name;
                    }

                    if ($actividad_estado_id == '13') { //13 => No aceptada
                        $actividad_inicio = "--";
                    } else {
                        $table = 'node__field_fecha_inicio_rea_actividad';
                        $campo = 'entity_id';
                        $codigo = $actividad_id;
                        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                            $actividad_inicio = $taxonomy->field_fecha_inicio_rea_actividad_value;
                        }
                    }

                    //link from node ID in Drupal 8
                    $options = array('absolute' => TRUE);
                    $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $actividad_id], $options);
                }
                $arsenio = count($actividad);

                if (count($actividad) == '0') { //actividad no creada para una sola entidad
                    $actividad_codigo = "Sin Asignación";
                    $actividad_titulo = $crear_actividad_titulo;
                    $actividad_estado = "Creada";
                    $actividad_inicio = "Por Definir";

                    //link from node ID in Drupal 8
                    $options = array('absolute' => TRUE);
                    $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid], $options);
                }

                //reseteando valores de la actividad

                $rows[] = array('data' => array($categoria_nombre,
                    $componente,
                    $entidad,
                    $actividad_codigo,
                    $actividad_titulo,
                    $actividad_estado,
                    $actividad_inicio,
                    \Drupal::l('Ver Detalles', $url),
                    //kamisate: Crear las mismas opciones de la bandeja de entrada para los demas casos de actividades    
                ));
            }
        }

        $table = array('#type' => 'table', '#header' => $header, '#rows' => $rows, '#attributes' => array('id' => 'actividad-table' . $tabla_id,));

//        $html = '<h1>Bandeja de Entrada Monitor</h1>';
        $text = array(
            '#type' => 'markup',
            '#markup' => $html,
        );

        return array(
            $text,
            $table,
        );
    }

    public function aceptarActividad($tabla_id)
    {

        $header = array('nid' => t('Nid'),
            'Componente' => t('Componente'),
            'Actividad' => t('Actividad'),
            'Acciones' => t('Actions'),);

// Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

        $table = 'crear_actividad';
        foreach (ActividadEntity::selectAll($table) as $creada) {
            $nid = $creada->nid;
            $categoria_fc_id = $creada->field_categoria_ut_cactividad_target_id;
            $componente_id = $creada->field_componente_cactividad_target_id;
            $crear_actividad_titulo = $creada->title;

            $Url = Url::fromRoute('actividad_aceptar', array('id' => $nid, 'id_tipo' => 0));

            $table = 'categoria_ut_fc';
            $campo = 'nid';
            $codigo = $categoria_fc_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $categoria) {
                $categoria_nombre = $categoria->title;
                $entidad_id = $categoria->field_categoria_ut_target_id;
                $entidad = $categoria->name;

                $table = 'usuario';
                $campo = 'uid';
                $codigo = $user->get('uid')->value;
                foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $userEnt) {
                    $entidad_id_user = $userEnt->field_entidad_usuario_target_id;
                }

                $table = 'taxonomy_term_field_data';
                $campo = 'tid';
                foreach (ActividadEntity::selectOne($table, $campo, $componente_id) as $taxonomy) {
                    $componente = $taxonomy->name;
                }

                if ($entidad_id == $entidad_id_user) {

                    $table = 'actividad';
                    $campo = 'field_actividad_madre_actividad_target_id';
                    $codigo = $nid;
                    $campo2 = 'field_entidad_usuario_target_id';
                    $codigo2 = $entidad_id_user;
                    unset($actividad); //resetear o destruir objeto actividad  
                    foreach (ActividadEntity::selectOneDouble($table, $campo, $codigo, $campo2, $codigo2) as $actividad) {
                        //actividades sin crear, solo programadas
                    }

                    if (count($actividad) == '0') {
                        $rows[] = array('data' => array($nid,
                            $componente,
                            $crear_actividad_titulo,
                            \Drupal::l('Aceptar Actividad', $Url),
                        ));
                    }
                }
            }
        }

        $table = array('#type' => 'table', '#header' => $header, '#rows' => $rows, '#attributes' => array('id' => 'actividad-table' . $tabla_id,));

        //$html = '<h1>Bandeja de Entrada Unidad de Transparencia</h1>';
        $text = array(
            '#type' => 'markup',
            '#markup' => $html,
        );

        return array(
            $text,
            $table,
        );
    }

    public function aprobarActividadProgramada($tabla_id)
    {

        $header = array('Codigo Actividad' => t('codigo Actividad'),
            'Componente' => t('Componente'),
            'Entidad' => t('Entidad'),
            'Actividad' => t('Actividad'),
            'Detalle' => t('Detalle'),
            'Acciones' => t('Actions'),);

// Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

        $table = 'actividad_programada';
        $campo = 'field_monitor_cactividad_target_id';
        $codigo = $user->get('uid')->value;
        $c_ap = 0;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $actividad) {

            $Url = Url::fromRoute('actividad_aprobar_programacion', array('id' => $actividad->nid));
            $codigo_madre = $actividad->field_actividad_madre_actividad_target_id;
            $codigo_actividad[$c_ap] = $actividad->field_codigo_actividad_value;
            $aceptar_actividad[$c_ap] = $actividad->field_aceptar_actividad_value;
            $actividad_titulo[$c_ap] = $actividad->title;
            $actividad_uid[$c_ap] = $actividad->uid;

            $table = 'crear_actividad';
            $campo = 'nid';
            foreach (ActividadEntity::selectOne($table, $campo, $codigo_madre) as $creada) {
                $componente_id = $creada->field_componente_cactividad_target_id;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            foreach (ActividadEntity::selectOne($table, $campo, $componente_id) as $taxonomy) {
                $componente = $taxonomy->name;
            }

            $table = 'usuario';
            $campo = 'uid';
            $codigo = $actividad_uid[$c_ap];
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $userEnt) {
                $entidad_id = $userEnt->field_entidad_usuario_target_id;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $entidad_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $entidad = $taxonomy->name;
            }

            if ($aceptar_actividad[$c_ap] == '1') {
                $aceptar_actividad_texto = 'Aceptada';
            } else {
                $aceptar_actividad_texto = 'No Aceptada';
            }

            $rows[] = array('data' => array($codigo_actividad[$c_ap],
                $componente,
                $entidad,
                $actividad_titulo[$c_ap],
                $aceptar_actividad_texto,
                \Drupal::l('Aprobar Programación', $Url),
            ));

            $c_ap++;
        }

        $table = array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#attributes' => array('id' => 'actividad-table' . $tabla_id),
        );

        $html = '<h1>Bandeja de Entrada Unidad Monitor</h1>';
        $text = array(
            '#type' => 'markup',
            '#markup' => $html,
        );

        return array(
//$text,
            $table,
        );
    }

    public function bandejaEntradaTab()
    {

//configuracion del sistema
        $table = 'sistema_configuracion_general';
        $campo = 'nid';
        $codigo = 58; //nid de la configuracion general del sistema
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $sistema) {
            $url_sistema = $sistema->field_nombre_sistema_value;
            $path_imagenes = $sistema->field_path_imagenes_value;
            $path_js = $sistema->field_path_javascript_value;
            $path_css = $sistema->field_path_css_value;
        }
        $url_img = $url_sistema . drupal_get_path('module', 'actividad') . $path_imagenes;
        $url_js = $url_sistema . drupal_get_path('module', 'actividad') . $path_js;
        $url_css = $url_sistema . drupal_get_path('module', 'actividad') . $path_css;

// Inicio MultiTab
        $script = "$(function() {
                    $( \"#tabs\" ).tabs({
                      beforeLoad: function( event, ui ) {
                        ui.jqXHR.fail(function() {
                          ui.panel.html(
                            \"Couldn't load this tab. We'll try to fix this as soon as possible. \" +
                            \"If this wouldn't be a demo.\" );
                        });
                      }
                    });
                  });";

        $script2 = "$(document).ready(function() {
                $('#actividad-table1').DataTable();
                $('#actividad-table2').DataTable();
                $('#actividad-table3').DataTable();
                $('#actividad-table4').DataTable();
                $('#actividad-table5').DataTable();
                $('#actividad-table6').DataTable();
                $('#actividad-table7').DataTable();
                $('#actividad-table8').DataTable();
                $('#actividad-table9').DataTable();
                $('#actividad-table10').DataTable();
                $('#actividad-table11').DataTable();
                $('#actividad-table12').DataTable();
                } );";

        $meta_default = array(
//css
            'A1' => array(
                '#type' => 'html_tag',
                '#tag' => 'link',
                '#attributes' => array(
                    'rel' => 'stylesheet',
                    'type' => 'text/css',
                    'href' => $url_css . 'jquery-ui.css',
                ),
            ),
            'A2' => array(
                '#type' => 'html_tag',
                '#tag' => 'link',
                '#attributes' => array(
                    'rel' => 'stylesheet',
                    'type' => 'text/css',
                    'href' => $url_css . 'jquery.dataTables.min.css',
                ),
            ),
            //js
            'A3' => array(
                '#type' => 'html_tag',
                '#tag' => 'script',
                '#attributes' => array(
                    'type' => 'text/javascript',
                    'language' => 'javascript',
                    'src' => $url_js . 'jquery-1.10.2.js',
                ),
            ),
            'A4' => array(
                '#type' => 'html_tag',
                '#tag' => 'script',
                '#attributes' => array(
                    'type' => 'text/javascript',
                    'language' => 'javascript',
                    'src' => $url_js . 'jquery-ui.js',
                ),
            ),
            'A5' => array(
                '#type' => 'html_tag',
                '#tag' => 'script',
                '#attributes' => array(
                    'type' => 'text/javascript',
                    'language' => 'javascript',
                    'src' => $url_js . 'jquery.dataTables.min.js',
                ),
            ),
            'A6' => array(
                '#type' => 'html_tag',
                '#tag' => 'script',
                '#value' => $script,
            ),
            'A7' => array(
                '#type' => 'html_tag',
                '#tag' => 'script',
                '#attributes' => array(
                    'type' => 'text/javascript',
                    'class' => 'init',
                ),
                '#value' => $script2,
            ),
        );

        foreach ($meta_default as $key => $value) {
            $head_tag['#attached']['html_head'][] = [$value, $key];
        }

// Fin MultiTab
// Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

        $table = 'usuario';
        $campo = 'uid';
        $codigo = $user->get('uid')->value;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $userEnt) {
            $rol = $userEnt->roles_target_id;
        }

        if ($rol == 'creador') {
            $link_crear_actividad = '<a href="http://localhost/sagt/node/add/crear_actividad">Crear Una Nueva Actividad</a>';
            $link_crear_categoria = '<a href="http://localhost/sagt/node/add/categorias_de_unidades_de_transp">Crear Categoria de Unidades de Transparencia</a>';
            $link_crear_parametricas = '<a href="http://localhost/sagt/admin/structure/taxonomy">Creación de Parametricas</a>';


            $html = '
                    <div id="tabs">
                        <ul>
                          <li><a href="#tabs-1">Crear Categoria</a></li>
                          <li><a href="#tabs-2">Crear Actividad</a></li>
                          <li><a href="#tabs-3">Crear Parametricas</a></li>
                        </ul>
                          <div id="tabs-1">
                              <p>' . $link_crear_categoria . '</p>
                          </div> 
                          <div id="tabs-2">
                              <p>' . $link_crear_actividad . '</p>
                          </div> 
                          <div id="tabs-3">
                              <p>' . $link_crear_parametricas . '</p>
                          </div> 
                    </div>
                ';
        }

        if ($rol == 'monitor') {

// instanciando controlador
            $actividadController = new ActividadController;
            $actividadCreadaMonitor = $actividadController->actividadCreadaMonitor(1);
            $aprobarActividadProgramada = $actividadController->aprobarActividadProgramada(2);

            $html = '
                    <div id="tabs">
                        <ul>
                          <li><a href="#tabs-1">Creadas</a></li>  
                          <li><a href="#tabs-2">Programadas por Aprobar</a></li>
                          <li><a href="#tabs-3">No Aceptadas</a></li>
                          <li><a href="#tabs-4">Realizadas por Aprobar</a></li>
                          <li><a href="#tabs-5">Concluidas</a></li>
                          <li><a href="#tabs-5">Reprobadas</a></li>
                          <li><a href="#tabs-5">Reprogramadas por Aprobar</a></li>
                          
                          
                          
                        </ul>
                          <div id="tabs-1">
                              <p>' . render($actividadCreadaMonitor) . '</p>
                          </div> 
                          <div id="tabs-2">
                              <p>' . render($aprobarActividadProgramada) . '</p>
                          </div> 
                    </div>
                ';
        }

        if ($rol == 'ut') {

// instanciando controlador
            $actividadController = new ActividadController;
            $aceptarActividad = $actividadController->aceptarActividad(1);
            $actividadEstado = $actividadController->actividadEstado(23, 2);
            $actividadEstadoCreada = $actividadController->actividadEstado(12, 3);
            $actividadEstadoNoAceptada = $actividadController->actividadEstado(13, 4);
            $actividadEstadoReportadaObservada = $actividadController->actividadEstado(15, 5);


            $html = '
                    <div id="tabs">
                        <ul>
                          <li><a href="#tabs-1">Programadas</a></li>
                          <li><a href="#tabs-2">Programadas Observadas</a></li>
                          <li><a href="#tabs-3">Por Realizar</a></li>
                          <li><a href="#tabs-4">No Aceptadas</a></li>
                          <li><a href="#tabs-5">Realizadas Observadas</a></li>
                          <li><a href="#tabs-6">Reprogramadas</a></li>
                          <li><a href="#tabs-7">Concluidas</a></li>
                          <li><a href="#tabs-8">No Realizadas</a></li>
                          <li><a href="#tabs-9">Reprobadas</a></li>
                        </ul>
                          <div id="tabs-1">
                              <p>' . render($aceptarActividad) . '</p>
                          </div> 
                          <div id="tabs-2">
                              <p>' . render($actividadEstado) . '</p>
                          </div>
                          <div id="tabs-3">
                              <p>' . render($actividadEstadoCreada) . '</p>
                          </div>
                          <div id="tabs-4">
                              <p>' . render($actividadEstadoNoAceptada) . '</p>
                          </div>
                          <div id="tabs-5">
                              <p>' . render($actividadEstadoReportadaObservada) . '</p>
                          </div>
                    </div>
                ';
        }

        $tab = array(
            '#type' => 'markup',
            '#markup' => $html,
        );

        return array(
            $head_tag,
            $tab,
        );
    }

    public function actividadEstado($id, $tabla_id)
    {

        $header = array('nid' => t('Nid'),
            'Componente' => t('Componente'),
            'Actividad' => t('Actividad'),
            'Detalle' => t('Detalle'),
            'Acciones' => t('Actions'),);

        $table = 'actividad';
        $campo = 'field_estado_actividad_target_id';
        $codigo = $id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $actividad) {

            if ($id == '23') { //23 => programada observada
                $route = 'actividad_aceptar';
                $array_route = array('id' => $actividad->nid, 'id_tipo' => 1);
            }

            if ($id == '12') {  //12 => Aceptada
                $route = 'actividad_add';
                $array_route = array('id' => $actividad->nid);
            }

            if ($id == '13') {  //13 => No Aceptada
                $route = 'actividad_aceptar';
                $array_route = array('id' => $actividad->nid, 'id_tipo' => 1);
            }

            if ($id == '15') {  //15 => Reportada Observada
                $route = 'actividad_add';
                $array_route = array('id' => $actividad->nid);
            }

            $Url = Url::fromRoute($route, $array_route);
            $codigo_madre = $actividad->field_actividad_madre_actividad_target_id;

            $table = 'crear_actividad';
            $campo = 'nid';
            $codigo = $codigo_madre;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $componente_id = $creada->field_componente_cactividad_target_id;
                $titulo_actividad = $creada->title;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            foreach (ActividadEntity::selectOne($table, $campo, $componente_id) as $taxonomy) {
                $componente = $taxonomy->name;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $detalle = $taxonomy->name;
            }

            $logo_render_array = [
                '#theme' => 'image_style',
                '#width' => $variables['width'],
                '#height' => $variables['height'],
                '#style_name' => $variables['style_name'],
                '#uri' => $variables['uri'],
            ];

            $rows[] = array('data' => array($actividad->nid,
                $componente,
                $titulo_actividad,
                $detalle,
                \Drupal::l('Ver Actividad', $Url),
            ));
        }

        $table = array('#type' => 'table', '#header' => $header, '#rows' => $rows, '#attributes' => array('id' => 'actividad-table' . $tabla_id,));

//$html = '<h1>Bandeja de Entrada Unidad de Transparencia</h1>';
        $text = array(
            '#type' => 'markup',
            '#markup' => $html,
        );

        return array(
            $text,
            $table,
        );
    }

}
