<?php

/**
 * @file
 * Contains \Drupal\actividad\Form\AprobarProgramacionActividadForm.
 */

namespace Drupal\actividad\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\node\Entity\Node;
use Drupal\actividad\Entity\ActividadEntity;
use Drupal\Core\Url;

class AprobarProgramacionActividadForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'aceptar_actividad_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        //nid de la actividad
        $id = $this->id = \Drupal::request()->get('id');

        //configuracion del sistema
        $table = 'sistema_configuracion_general';
        $campo = 'nid';
        $codigo = 58; //nid de la configuracion general del sistema
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $sistema) {
            $url_sistema = $sistema->field_nombre_sistema_value;
            $url_imagenes = $sistema->field_path_imagenes_value;
        }
        $url = $url_sistema . drupal_get_path('module', 'actividad') . $url_imagenes;

        //recuperando la data de la actividad
        $table = 'actividad_programada';
        $campo = 'nid';
        $codigo = $id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
            $codigo_madre = $creada->field_actividad_madre_actividad_target_id;
            $codigo_actividad = $creada->field_codigo_actividad_value;
            $actividad_titulo = $creada->title;
            $aceptar_actividad = $creada->field_aceptar_actividad_value;
            $uid_usuario = $creada->uid;
        }

        $table = 'crear_actividad';
        $campo = 'nid';
        $codigo = $codigo_madre;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
            $categoria_cactividad = $creada->field_categoria_ut_cactividad_target_id;
            $descripcion_cactividad = $creada->field_descripcion_cactivida_value;
            $dia_max_reproduciblidad = $creada->field_dia_reproduciblidad_cacti_target_id;
            $inicio_cactividad = $creada->field_fecha_inicial_cactividad_value;
            $fin_cactividad = $creada->field_fecha_final_cactividad_value;
            $monitor_cactividad = $creada->field_monitor_cactividad_target_id;
            $componente_cactividad = $creada->field_componente_cactividad_target_id;
            $titulo_cactividad = $creada->title;
            $prioridad_cactividad_id = $creada->field_prioridad_cactividad_target_id;
            $tipo_cactividad_id = $creada->field_tipo_actividad_cactividad_target_id;
            $reproducibilidad_cactividad_id = $creada->field_reproducibilidad_cact_target_id;
        }

        $table = 'usuario';
        $campo = 'uid';
        $codigo = $monitor_cactividad;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $user) {
            $monitor_nombre = $user->field_nombre_completo_value;
            $entidad_id_monitor = $user->field_entidad_usuario_target_id;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $componente_cactividad;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $componente = $taxonomy->name;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $tipo_cactividad_id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $tipo_actividad = $taxonomy->name;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $prioridad_cactividad_id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $prioridad_actividad = $taxonomy->name;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $reproducibilidad_cactividad_id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $reproducibilidad_actividad = $taxonomy->name;
        }

        if ($reproducibilidad_cactividad_id != '22') {

            $table = 'crear_actividad_reproducibilidad';
            $campo = 'nid';
            $codigo = $codigo_madre;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $reproduc) {
                $reproduc_dia_id = $reproduc->field_dia_reproducibilidad_cacti_target_id;
            }

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $reproduc_dia_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $reproduc_dia = $taxonomy->name;
            }

            $reproduc_dia_texto = '<b>Dia Limite de Reproducibilidad: </b>' . $reproduc_dia . ' dia<p>';
        }

        $table = 'node_field_data';
        $campo = 'nid';
        $codigo = $categoria_cactividad;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $node) {
            $categoria_ut = $node->title;
        }

        $table = 'usuario';
        $campo = 'uid';
        $codigo = $uid_usuario;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $user) {
            $reportador = $user->field_nombre_completo_value;
            $entidad_id = $user->field_entidad_usuario_target_id;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $entidad_id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $entidad = $taxonomy->name;
        }

        //actividad aceptada
        if ($aceptar_actividad == 1) {
            $estado_actividad_texto = "Actividad Aceptada";

            $table = 'actividad_aceptar_si';
            $campo = 'nid';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $aceptar) {
                $fecha_inicio = $aceptar->field_fecha_inicio_rea_actividad;
                $fecha_fin = $aceptar->field_fecha_fin_rea_actividad;
            }

            $fechas_texto = '<b>Fecha de Inicio de la Actividad: </b>' . $fecha_inicio . '<p>
                             <b>Fecha de Finalizacion de la Actividad: </b>' . $fecha_fin . '<p>';
        }
        //actividad no aceptada
        else {
            $estado_actividad_texto = "Actividad No Aceptada";

            $table = 'actividad_aceptar_no';
            $campo = 'nid';
            $codigo = $id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $aceptar) {
                $justificacion_no = $aceptar->field_justificacion_no_actividad_value;
            }

            $justificacion_texto = '<b>Justificacion de la no Aceptacion de la Actividad: </b>' . $justificacion_no . '<p>';
        }

        $archivos = '<table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Nombre del Documento</th>
                            <th>Tipo del Documento </th>
                            <th>Numero de Adjuntos</th>
                            <th>Modelo del Documento</th>
                          </tr>
                        </thead>
                        <tbody>';

        $table = 'crear_actividad_fc_archivos';
        $campo = 'nid';
        $codigo = $codigo_madre;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $archivo) {
            $nombre_documento = $archivo->field_nombre_del_archivo_value;
            $tipo_documento_id = $archivo->field_tipo_de_documento_target_id;
            $numero_adjuntos_id = $archivo->field_numero_de_adjuntos_target_id;
            $fc_id = $archivo->field_archivos_fcolection_cactiv_value;

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $tipo_documento_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $tipo_documento = $taxonomy->name;
            }

            $table = 'crear_actividad_fc_documento_modelo';
            $campo = 'field_archivos_fcolection_cactiv_value';
            $codigo = $fc_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $modelo) {
                $nombre_modelo = $modelo->filename;
                $uri_modelo = $modelo->uri;
            }

            $link = file_create_url($uri_modelo);
            $link = '<a href="' . $link . '">' . $nombre_modelo . '</a>';

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $numero_adjuntos_id;
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $numero_adjuntos = $taxonomy->name;
            }

            $archivos = $archivos . '<tr>
                                        <th scope="row">1</th>
                                        <td>' . $nombre_documento . '</td>
                                        <td>' . $tipo_documento . '</td>
                                        <td>' . $numero_adjuntos . '</td>
                                        <td>' . $link . '</td>    
                                        </tr>';

            //resetenado valores del documento modelo
            $nombre_modelo = '';
            $uri_modelo = '';
            $link = '';
        }

        $archivos = $archivos . '</tbody>
                                </table>';

        $form['datos_generales'] = array(
            '#type' => 'details',
            '#title' => $this->t('<img src="' . $url . 'ver.png" height="30" />
                                    Revisar la Actividad Programada'),
            '#description' => $this->t('<hr><b>Codigo de Actividad: </b>' . $codigo_actividad . '<p>
                                        <b>Actividad: </b>' . $titulo_cactividad . '<p>
                                        <b>Descripcion: </b>' . $descripcion_cactividad . '<p>
                                        <b>Componente: </b>' . $componente . '<p>
                                        <b>Categoria de Unidades de Transparencia: </b>' . $categoria_ut . '<p>
                                        <b>Entidad: </b>' . $entidad . '<p>
                                        <b>Reportado por: </b>' . $reportador . '<p>
                                        <b>Monitor de la Actividad: </b>' . $monitor_nombre . '<p>
                                        <b>Prioridad de la Actividad: </b>' . $prioridad_actividad . '<p>
                                        <b>Tipo de actividad: </b>' . $tipo_actividad . '<p>
                                        ' . $fechas_texto . '    
                                        <b>Reproducibilidad de la Actividad: </b>' . $reproducibilidad_actividad . '<p>
                                        ' . $reproduc_dia_texto . '
                                        <b>Archivos Solicitados de la Actividad: </b><p><p>' . $archivos . '<p><p>
                                        <b>Estado de la Actividad: </b>' . $estado_actividad_texto . '<p>
                                        ' . $justificacion_texto . '
                                        ')
        );

        $table = 'actividad_observacion_programada';
        $campo = 'nid';
        $codigo = $id;
        $c_obs = 1;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $observacion) {
            $form['observacion' . $c_obs] = array(
                '#type' => 'details',
                '#title' => $this->t('<img src="' . $url . 'observacion.png" height="30" />[' . $observacion->field_fecha_observacion_value . '] <b>' . $observacion->field_titulo_observacion_value.'</b>'),
                '#description' => $this->t($observacion->field_detalle_observacion_value . '<p>')
            );

            $c_obs++;
        }

        $form['accept_term'] = array(
            '#type' => 'select',
            '#title' => t('Despues de revisar los Detalles de la actividad <br> ¿Acepta los Terminos de la Actividad Programada?'),
            '#options' => array(
                0 => t('No'),
                1 => t('Yes'),
            ),
            '#required' => TRUE,
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

        $form['save_info_no']['nombre_observacion'] = array(
            '#type' => 'textfield',
            '#title' => t('Título de la Observación'),
                //'#default_value' => $titulo_cactividad,
        );

        $form['save_info_no']['detalle_observacion'] = array(
            '#type' => 'text_format',
            '#title' => 'Detalle de la Observación',
            '#format' => 'full_html',
        );

        $form['show'] = [
            '#type' => 'submit',
            '#value' => $this->t('Guardar Elección')
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        //Mostrar todos los campos a guardar
//        foreach ($form_state->getValues() as $key => $value) {
//            drupal_set_message($key . ': ' . $value);
//        }
        //recuperando el nid
        $id = $this->id = \Drupal::request()->get('id');

        // Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

        //recuperando la data de la actividad
        $table = 'crear_actividad';
        $campo = 'nid';
        $codigo = $id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
            $titulo_cactividad = $creada->title;
        }

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

        $codigo_actividad = rand(11111, 99999);
        $codigo_actividad = $id . $codigo_actividad;

        //1 => Programacion Aprobada
        if ($form_state->getValue(accept_term) == '1') {
            $node = Node::create([
                        'type' => 'reportar_actividad',
                        'langcode' => 'en',
                        'created' => REQUEST_TIME,
                        'changed' => REQUEST_TIME,
                        'uid' => $user->get('uid')->value,
                        'title' => $titulo_cactividad . ' - ' . $entidad,
                        'field_actividad_madre_actividad' => [$id],
                        'field_codigo_actividad' => [$codigo_actividad],
                        'field_aceptar_actividad' => [$form_state->getValue(accept_term)],
                        'field_fecha_inicio_rea_actividad' => [$form_state->getValue(date_ini)],
                        'field_fecha_fin_rea_actividad' => [$form_state->getValue(date_fin)],
                        'field_estado_actividad' => [21], //21 => Programada Creada
            ]);
            $node->save();
            drupal_set_message('Se envio la informacion de la actividad ' . $codigo_actividad);
        } else {
            //recuperando el nid del nodo
            $id = $this->id = \Drupal::request()->get('id');

            //actualizando el nodo principal
            $node = \Drupal::entityTypeManager()->getStorage('node')->load($id);
            $node->field_estado_actividad = 23; //23 => Programada Observada
            $node->save();

            //field colection
            $nombre_observacion = $form_state->getValue(nombre_observacion);
            $detalle_observacion = $form_state->getValue(detalle_observacion);
            $hoy = date('Y-m-d\TH:i:s');
            $estado_actividad = 23; //23 => Programada Observada

            $node = \Drupal::entityTypeManager()->getStorage('node')->load($id);
            $field_collection_item = entity_create('field_collection_item', array('field_name' => 'field_observacion_fc_actividad'));
            $field_collection_item->setHostEntity($node);
            $field_collection_item->set('field_titulo_observacion', $nombre_observacion);
            $field_collection_item->set('field_detalle_observacion', $detalle_observacion);
            $field_collection_item->set('field_fecha_observacion', $hoy);
            $field_collection_item->set('field_tipo_observacion', $estado_actividad);

            $field_collection_item->save();

            drupal_set_message('Se envio la observacion a la UT de la actividad ' . $codigo_actividad);
        }

        $form_state->setRedirect(actividad_bandeja_entrada);
    }

}
