<?php

/**
 * @file
 * Contains \Drupal\actividad\Form\AceptarActividadForm.
 */

namespace Drupal\actividad\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\node\Entity\Node;
use Drupal\actividad\Entity\ActividadEntity;
use Drupal\Core\Url;

class AceptarActividadForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'aceptar_actividad_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        //nid de la actividad
        $id = $this->id = \Drupal::request()->get('id');
        $id_tipo = $this->id = \Drupal::request()->get('id_tipo');

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
        $url = $url_sistema . drupal_get_path('module', 'actividad') . $path_imagenes;
        //$url_js = $url_sistema . drupal_get_path('module', 'actividad') . $path_js;
        //$url_css = $url_sistema . drupal_get_path('module', 'actividad') . $path_css;

        if ($id_tipo == '0') {  //0 => actividad sin Observacion
            //recuperando la data de la actividad
            $table = 'crear_actividad';
            $campo = 'nid';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $categoria_cactividad = $creada->field_categoria_ut_cactividad_target_id;
                $descripcion_cactividad = $creada->field_descripcion_cactivida_value;
                $fecha_ini = $creada->field_fecha_inicial_cactividad_value;
                $monitor_cactividad = $creada->field_monitor_cactividad_target_id;
                $componente_cactividad = $creada->field_componente_cactividad_target_id;
                $titulo_cactividad = $creada->title;
                $prioridad_cactividad = $creada->field_prioridad_cactividad_target_id;
                $programacion_fechas = $creada->field_programacion_fechas_cactiv_target_id;
            }

            $table = 'node__field_fecha_inicial_cactividad';
            $campo = 'entity_id';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $fecha_ini = $creada->field_fecha_inicial_cactividad_value;
            }

            $table = 'node__field_fecha_final_cactividad';
            $campo = 'entity_id';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $fecha_fin = $creada->field_fecha_final_cactividad_value;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $componente_cactividad;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $componente = $taxonomy->name;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $programacion_fechas;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $programacion_fechas_texto = $taxonomy->name;
            }

            $table = 'crear_actividad_reproducibilidad';
            $campo = 'nid';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $reprod) {
                $dia_max_reproducibilidad = $reprod->field_dia_reproducibilidad_cacti_target_id;
                $periodo_reproducibilidad = $reprod->field_reproducibilidad_cact_target_id;
            }

            $table = 'usuario';
            $campo = 'uid';
            $codigo = $monitor_cactividad;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $reprod) {
                $monitor_nombre = $reprod->field_nombre_completo_value;
            }

            $codigo_actividad = "En Proceso";
        } else {

            //recuperando la data de la actividad
            $table = 'actividad';
            $campo = 'nid';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $actividad) {
                $actividad_madre_id = $actividad->field_actividad_madre_actividad_target_id;
                $codigo_actividad = $actividad->field_codigo_actividad_value;
                $aceptar_actividad = $actividad->field_aceptar_actividad_value;
            }

            $table = 'node__field_fecha_inicio_rea_actividad';
            $campo = 'entity_id';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $actividad) {
                $fecha_inicio_rea = $actividad->field_fecha_inicio_rea_actividad_value;
            }

            $table = 'node__field_fecha_fin_rea_actividad';
            $campo = 'entity_id';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $actividad) {
                $fecha_fin_rea = $actividad->field_fecha_fin_rea_actividad_value;
            }

            $table = 'node__field_justificacion_no_actividad';
            $campo = 'entity_id';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $actividad) {
                $justificacion_rea = $actividad->field_justificacion_no_actividad_value;
            }

            $table = 'crear_actividad';
            $campo = 'nid';
            $codigo = $actividad_madre_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $categoria_cactividad = $creada->field_categoria_ut_cactividad_target_id;
                $descripcion_cactividad = $creada->field_descripcion_cactivida_value;
                $fecha_ini = $creada->field_fecha_inicial_cactividad_value;
                $monitor_cactividad = $creada->field_monitor_cactividad_target_id;
                $componente_cactividad = $creada->field_componente_cactividad_target_id;
                $titulo_cactividad = $creada->title;
                $prioridad_cactividad = $creada->field_prioridad_cactividad_target_id;
                $programacion_fechas = $creada->field_programacion_fechas_cactiv_target_id;
            }

            $table = 'node__field_fecha_inicial_cactividad';
            $campo = 'entity_id';
            $codigo = $actividad_madre_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $fecha_ini = $creada->field_fecha_inicial_cactividad_value;
            }

            $table = 'node__field_fecha_final_cactividad';
            $campo = 'entity_id';
            $codigo = $actividad_madre_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $fecha_fin = $creada->field_fecha_final_cactividad_value;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $componente_cactividad;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $componente = $taxonomy->name;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $programacion_fechas;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $programacion_fechas_texto = $taxonomy->name;
            }

            $table = 'crear_actividad_reproducibilidad';
            $campo = 'nid';
            $codigo = $actividad_madre_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $reprod) {
                $dia_max_reproducibilidad = $reprod->field_dia_reproducibilidad_cacti_target_id;
                $periodo_reproducibilidad = $reprod->field_reproducibilidad_cact_target_id;
            }

            $table = 'usuario';
            $campo = 'uid';
            $codigo = $monitor_cactividad;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $reprod) {
                $monitor_nombre = $reprod->field_nombre_completo_value;
            }
        }


        //creando el formulario
        $form['datos_generales'] = array(
            '#type' => 'details',
            '#title' => $this->t('<img src="' . $url . 'ver.png" height="30" />
                                    Ver Datos Generales de la Actividad'),
            '#description' => $this->t('<b>Codigo de Actividad: </b>' . $codigo_actividad . '<p>
                        <b>Componente: </b>' . $componente . '<p>
                        <b>Monitor de la Actividad: </b>' . $monitor_nombre . '<p>
                        <b>Actividad: </b>' . $titulo_cactividad . '<p>
                        <b>Descripcion: </b>' . $descripcion_cactividad . '<p>
                        <b>Tipo de Programacion: </b>' . $programacion_fechas_texto . '<p>
                        <b>Fecha Inicial: </b>' . $fecha_ini . '<p>
                        <b>Fecha Final: </b>' . $fecha_fin . '<p>
                        ')
        );

        if ($prioridad_cactividad == '5') { //5 => Codigo de Prioridad Alta
            $form['acuse'] = [
                '#type' => 'html_tag',
                '#tag' => 'p',
                '#value' => $this->t('<b><br>
                                        <img src="' . $url . 'info.png" height="30" />
                                        Se enviará al Monitor un acuse de Recibo de esta actividad, la prioridad de esta actividad es alta por lo que su aceptación es obligatoria.</b>'),
            ];

            if ($programacion_fechas == '26') { //26 => Fechas Exactas de Realización de la Actividad
                $form['programacion_fechas'] = [
                    '#type' => 'html_tag',
                    '#tag' => 'p',
                    '#value' => $this->t('<b><br>
                                            <img src="' . $url . 'info.png" height="30" />
                                            Las fechas de realización de la actividad ya han sido definidas por el creador de la actividad, las mismas no pueden ser cambiadas.</b>'),
                ];
            } else {

                $form['una_fecha'] = [
                    '#type' => 'html_tag',
                    '#tag' => 'p',
                    '#value' => $this->t('<b><br>
                                        <img src="' . $url . 'idea.png" height="30" />
                                        Si la actividad será realizada en un solo día, solo elija fecha inicial.</b>'),
                ];

                $form['date_ini'] = [
                    '#title' => 'Fecha Inicio de la Actividad',
                    '#type' => 'date',
                    '#attributes' => array('type' => 'date', 'min' => $fecha_ini, 'max' => $fecha_fin),
                    '#date_date_format' => 'd/m/Y',
                ];

                $form['date_fin'] = [
                    '#title' => 'Fecha Fin de la Actividad',
                    '#type' => 'date',
                    '#attributes' => array('type' => 'date', 'min' => $fecha_ini, 'max' => $fecha_fin),
                    '#date_date_format' => 'd/m/Y',
                ];
            }

        } else {

            if ($id_tipo == '1') { //1 => actividad con observacion
                $table = 'actividad_observacion_programada';
                $campo = 'nid';
                $codigo = $id;
                $c_obs = 1;
                foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $observacion) {
                    $form['observacion' . $c_obs] = array(
                        '#type' => 'details',
                        '#title' => $this->t('<img src="' . $url . 'observacion.png" height="30" />[' . $observacion->field_fecha_observacion_value . '] <b>' . $observacion->field_titulo_observacion_value . '</b>'),
                        '#description' => $this->t($observacion->field_detalle_observacion_value . '<p>')
                    );

                    $c_obs++;
                }
            }

            if ($id_tipo == '1') { //1 => actividad con observacion
                if ($aceptar_actividad == '0') { //0= => Actividad no Aceptada
                    $selected = 0;
                } else {
                    $selected = 1;
                }
            } else {
                $selected = 1;
            }

            $form['accept_term'] = array(
                '#type' => 'select',
                '#title' => t('¿Acepta la Actividad?'),
                '#options' => array(
                    1 => t('Yes'),
                    0 => t('No'),
                ),
                '#default_value' => $selected,
            );

            $form['save_info'] = array(
                '#type' => 'container',
                '#states' => array(
                    'invisible' => array(
                        ':input[name="accept_term"]' => array('value' => 0),
                    ),
                ),
            );

            $form['save_info_no'] = array(
                '#type' => 'container',
                '#states' => array(
                    'invisible' => array(
                        ':input[name="accept_term"]' => array('value' => 1),
                    ),
                ),
            );

            $form['save_info']['actions'] = array('#type' => 'actions');

            if ($programacion_fechas == '26') { //26 => Fechas Exactas de Realización de la Actividad
                $form['acuse'] = [
                    '#type' => 'html_tag',
                    '#tag' => 'p',
                    '#value' => $this->t('<b><br>
                                        <img src="' . $url . 'idea.png" height="30" />
                                        Si la actividad será realizada en un solo día, solo elija fecha inicial.</b>'),
                ];

                $form['programacion_fechas'] = [
                    '#type' => 'html_tag',
                    '#tag' => 'p',
                    '#value' => $this->t('<b><br>
                                            <img src="' . $url . 'info.png" height="30" />
                                            Las fechas de realización de la actividad ya han sido definidas por el creador de la actividad, las mismas no pueden ser cambiadas.</b>'),
                ];
            } else {
                $form['save_info']['date_ini'] = [
                    '#title' => 'Fecha Inicio de la Actividad',
                    '#type' => 'date',
                    '#attributes' => array('type' => 'date', 'min' => $fecha_ini, 'max' => $fecha_fin),
                    '#date_date_format' => 'd/m/Y',
                    '#default_value' => $fecha_inicio_rea,
                ];

                $form['save_info']['date_fin'] = [
                    '#title' => 'Fecha Fin de la Actividad',
                    '#type' => 'date',
                    '#attributes' => array('type' => 'date', 'min' => $fecha_ini, 'max' => $fecha_fin),
                    '#date_date_format' => 'd/m/Y',
                    '#default_value' => $fecha_fin_rea,
                ];
            }

            $form['save_info_no']['no_aceptacion'] = array(
                '#type' => 'text_format',
                '#title' => 'Justificación',
                '#format' => 'full_html',
                '#default_value' => $justificacion_rea,
            );
        }


        $form['show'] = [
            '#type' => 'submit',
            '#value' => $this->t('Enviar al Monitor')
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        //Mostrar todos los campos a guardar
//        foreach ($form_state->getValues() as $key => $value) {
//            drupal_set_message($key . ': ' . $value);
//        }
        //recuperando el nid de la actividad madre
        $id = $this->id = \Drupal::request()->get('id');
        //recuperando el tipo de proceso de la actividad
        $id_tipo = $this->id = \Drupal::request()->get('id_tipo');

        // Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

        $table = 'usuario';
        $campo = 'uid';
        $codigo = $user->get('uid')->value;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $userEnt) {
            $entidad_id = $userEnt->field_entidad_usuario_target_id;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $entidad_id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $entidad = $taxonomy->name;
        }

        if ($id_tipo == '0') { //0 => actividad sin observacion
            //recuperando la data de la actividad
            $table = 'crear_actividad';
            $campo = 'nid';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                $titulo_cactividad = $creada->title;
                $descripcion_cactividad = $creada->field_descripcion_cactivida_value;
                $prioridad_cactividad = $creada->field_prioridad_cactividad_target_id;
                $programacion_fechas = $creada->field_programacion_fechas_cactiv_target_id;
            }

            $codigo_actividad = rand(11111, 99999);
            $codigo_actividad = $id . $codigo_actividad;

            if ($programacion_fechas == '26' && $prioridad_cactividad == '5') {
                $estado_actividad = 12;
            } else {
                $estado_actividad = 21;
            }

            if ($programacion_fechas == '26') { //26 => Fechas Exactas de Realización de la Actividad
                $table = 'node__field_fecha_inicial_cactividad';
                $campo = 'entity_id';
                $codigo = $id;
                foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                    $fecha_ini = $creada->field_fecha_inicial_cactividad_value;
                }

                $table = 'node__field_fecha_final_cactividad';
                $campo = 'entity_id';
                $codigo = $id;
                foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
                    $fecha_fin = $creada->field_fecha_final_cactividad_value;
                }
            } else {
                $fecha_ini = $form_state->getValue(date_ini);
                $fecha_fin = $form_state->getValue(date_fin);
            }

            if ($prioridad_cactividad == '5') { //5 => Codigo de Prioridad Alta
                $node = Node::create([
                    'type' => 'reportar_actividad',
                    'langcode' => 'es',
                    'created' => REQUEST_TIME,
                    'changed' => REQUEST_TIME,
                    'uid' => $user->get('uid')->value,
                    'title' => $titulo_cactividad,
                    'field_descripcion_activida' => array($descripcion_cactividad, 'basic_html'),
                    'field_actividad_madre_actividad' => $id,
                    'field_codigo_actividad' => $codigo_actividad,
                    'field_aceptar_actividad' => 1,
                    'field_fecha_inicio_rea_actividad' => $fecha_ini,
                    'field_fecha_fin_rea_actividad' => $fecha_fin,
                    'field_estado_actividad' => $estado_actividad,
                ]);
                $node->save();
            } else {

                if ($form_state->getValue(accept_term) == '1') { //1 => Aceptar Actividad
                    $node = Node::create([
                        'type' => 'reportar_actividad',
                        'langcode' => 'es',
                        'created' => REQUEST_TIME,
                        'changed' => REQUEST_TIME,
                        'uid' => $user->get('uid')->value,
                        'title' => $titulo_cactividad,
                        'field_descripcion_activida' => array($descripcion_cactividad, 'basic_html'),
                        'field_actividad_madre_actividad' => $id,
                        'field_codigo_actividad' => $codigo_actividad,
                        'field_aceptar_actividad' => $form_state->getValue(accept_term),
                        'field_fecha_inicio_rea_actividad' => $fecha_ini,
                        'field_fecha_fin_rea_actividad' => $fecha_fin,
                        'field_estado_actividad' => $estado_actividad,
                    ]);
                    $node->save();
                } else {
                    $item = $form_state->getValue(no_aceptacion);
                    $node = Node::create([
                        'type' => 'reportar_actividad',
                        'langcode' => 'es',
                        'created' => REQUEST_TIME,
                        'changed' => REQUEST_TIME,
                        'uid' => $user->get('uid')->value,
                        'title' => $titulo_cactividad,
                        'field_descripcion_activida' => array($descripcion_cactividad, 'basic_html'),
                        'field_actividad_madre_actividad' => $id,
                        'field_codigo_actividad' => $codigo_actividad,
                        'field_aceptar_actividad' => $form_state->getValue(accept_term),
                        'field_justificacion_no_actividad' => $item,
                        'field_estado_actividad' => $estado_actividad,
                    ]);
                    $node->save();
                }
            }

            drupal_set_message('Se envio la informacion de la actividad ' . $codigo_actividad .'al monitor de la actividad');
        } else {

            //actualizando el nodo principal
            $node = \Drupal::entityTypeManager()->getStorage('node')->load($id);
            $node->field_aceptar_actividad = $form_state->getValue(accept_term);
            $node->field_fecha_inicio_rea_actividad = $form_state->getValue(date_ini);
            $node->field_fecha_fin_rea_actividad = $form_state->getValue(date_fin);
            $node->field_justificacion_no_actividad = $form_state->getValue(no_aceptacion);
            $node->field_estado_actividad = $estado_actividad;
            $node->save();

            drupal_set_message('Las correcciones realizadas han sido Guardadas y Enviadas al Monitor para su Aprobación');
        }

        $form_state->setRedirect(actividad_bandeja_entrada);
    }

}
