<?php

/**
 * @file
 * Contains \Drupal\actividad\Form\ReportarActividadForm.
 */

namespace Drupal\actividad\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\node\Entity\Node;
use Drupal\actividad\Entity\ActividadEntity;
use Drupal\Core\Url;

class ReportarActividadForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'reportar_actividad_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        //nid de la actividad reportada
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

        //recuperando la data de la actividad a reportar
        $table = 'actividad';
        $campo = 'nid';
        $codigo = $id;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $actividad) {
            $nid_actividad_creada = $actividad->field_actividad_madre_actividad_target_id;
            $codigo_actividad = $actividad->field_codigo_actividad_value;
            $descripcion_actividad = $actividad->field_descripcion_actividad_value;
            $estado_actividad = $actividad->field_estado_actividad_target_id;
            $inicio_actividad = $actividad->field_fecha_inicio_rea_actividad_value;
            $fin_actividad = $actividad->field_fecha_fin_rea_actividad;
        }

        $table = 'crear_actividad';
        $campo = 'nid';
        $codigo = $nid_actividad_creada;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $creada) {
            $categoria_cactividad = $creada->field_categoria_ut_cactividad_target_id;
            $descripcion_cactividad = $creada->field_descripcion_cactivida_value;
            $dia_max_reproduciblidad = $creada->field_dia_reproduciblidad_cacti_target_id;
            $inicio_cactividad = $creada->field_fecha_inicial_cactividad_value;
            $fin_cactividad = $creada->field_fecha_final_cactividad_value;
            $monitor_cactividad = $creada->field_monitor_cactividad_target_id;
            $componente_cactividad = $creada->field_componente_cactividad_target_id;
            $titulo_cactividad = $creada->title;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $componente_cactividad;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $componente = $taxonomy->name;
        }

        $table = 'taxonomy_term_field_data';
        $campo = 'tid';
        $codigo = $estado_actividad;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
            $estado_actividad_tax = $taxonomy->name;
        }

        $table = 'crear_actividad_fc_archivos';
        $campo = 'nid';
        $codigo = $nid_actividad_creada;
        $i_archivo = 0;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $archivo) {
            $fc_id[$i_archivo] = $archivo->field_archivos_fcolection_cactiv_value;
            $nombre_archivo[$i_archivo] = $archivo->field_nombre_del_archivo_value;
            $tipo_documento_id[$i_archivo] = $archivo->field_tipo_de_documento_target_id;
            $numero_adjuntos_id[$i_archivo] = $archivo->field_numero_de_adjuntos_target_id;
            $i_archivo++;
        }

        //creando el formulario para reportar la actividad    
        $form['actividad'] = array(
            '#type' => 'fieldset',
            '#title' => t('Formulario para Reportar una Actividad Realizada'),
            //'#description' => '<h2>Describe a detalle la Actividad Realizada.</h2>',
            '#collapsible' => TRUE,
            '#collapsed' => FALSE,
        );

        $form['actividad']['datos_generales'] = array(
            '#type' => 'details',
            '#title' => $this->t('Ver Datos Generales de la Actividad'),
            '#description' => $this->t('<b>Codigo de Actividad: </b>' . $codigo_actividad . '<p>
                        <b>Componente: </b>' . $componente . '<p>
                        <b>Actividad: </b>' . $titulo_cactividad . '<p>
                        <b>Descripcion: </b>' . $descripcion_cactividad . '<p>
                        <b>Informado por: </b>Jose Ovando<p>
                        <b>Monitoreado por: </b>Liegibel Zuazo<p>
                        <b>Estado de la Actividad: </b>' . $estado_actividad_tax . '<p>')
        );

        $table = 'actividad_observacion_programada';
        $campo = 'nid';
        $codigo = $id;
        $c_obs = 1;
        foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $observacion) {
            $form['actividad']['observacion' . $c_obs] = array(
                '#type' => 'details',
                '#title' => $this->t('<img src="' . $url . 'observacion.png" height="30" />[' . $observacion->field_fecha_observacion_value . '] <b>' . $observacion->field_titulo_observacion_value . '</b>'),
                '#description' => $this->t($observacion->field_detalle_observacion_value . '<p>')
            );

            $c_obs++;
        }

        $form['actividad']['nombre_actividad'] = array(
            '#type' => 'textfield',
            '#title' => t('Titulo de la Actividad'),
            '#default_value' => $titulo_cactividad,
        );

        $form['actividad']['descripcion_actividad'] = array(
            '#type' => 'text_format',
            '#title' => 'Informe de la Actividad',
            '#format' => 'basic_html',
            //'#default_value' => '<p>Describe a detalle la Actividad Realizada.</p>',
            '#required' => TRUE,
        );

        //creando el field colection para los archivos de la actividad
        $form['actividad']['archivos'] = array(
            '#type' => 'table',
            '#caption' => $this->t('Documentos Solicitados por la Actividad'),
            '#header' => array($this->t('Adjuntar Documento'), $this->t('Modelo del Documento')),
        );

        $contador = 1;
        for ($i = 0; $i < $i_archivo; $i++) {

            $table = 'taxonomy_term_field_data';
            $campo = 'tid';
            $codigo = $numero_adjuntos_id[$i];
            foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                $numero_adjuntos[$i] = $taxonomy->name;
            }

            for ($j = 1; $j <= $numero_adjuntos[$i]; $j++) {

                $table = 'taxonomia_tipo_documento';
                $campo = 'tid';
                $codigo = $tipo_documento_id[$i];
                foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $taxonomy) {
                    $extension_documento = $taxonomy->field_extensiones_tdocumento_value;
                }

                $form['actividad']['archivos'][$contador]['archivo_actividad'] = array(
                    '#type' => 'managed_file',
                    '#title' => $nombre_archivo[$i],
                    '#required' => TRUE,
                    '#weight' => -4,
                    '#upload_location' => file_default_scheme() . '://theme/backgrounds/',
                    '#upload_validators' => array(
                        'file_validate_extensions' => array($extension_documento),
                    ),
                );

                $table = 'crear_actividad_fc_documento_modelo';
                $campo = 'field_archivos_fcolection_cactiv_value';
                $codigo = $fc_id[$i];
                foreach (ActividadEntity::selectOne($table, $campo, $codigo) as $modelo) {
                    $nombre_modelo = $modelo->filename;
                    $uri_modelo = $modelo->uri;
                }

                $link = Url::fromUri(file_create_url($uri_modelo));

                if ($uri_modelo != '') {
                    $form['actividad']['archivos'][$contador]['examples_link'] = [
                        '#title' => $this->t($nombre_modelo),
                        '#type' => 'link',
                        '#url' => Url::fromUri(file_create_url($uri_modelo)),
                    ];
                }

                $form['actividad']['archivos'][$contador]['nombre_documento'] = array('#type' => 'hidden', '#value' => $nombre_archivo[$i]);

                //resetenado valores del archivo modelo
                $nombre_modelo = '';
                $uri_modelo = '';

                $contador++;
            }
        }

        $form['actividad']['show'] = [
            '#type' => 'submit',
            '#value' => $this->t('Enviar Actividad para Aprobación del Monitor')
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
        //recuperando el nid del nodo
        $id = $this->id = \Drupal::request()->get('id');

        //actualizando el nodo principal
        $node = \Drupal::entityTypeManager()->getStorage('node')->load($id);
        $field = $form_state->getValue(nombre_actividad);
        $node->title = $field;
        $field = $form_state->getValue(descripcion_actividad);
        $node->field_descripcion_activida = $field;
        $field = $form_state->getValue(archivos);
        $node->field_archivos_fcolection_cactiv = $field;
        $node->save();

        //field colection
        $item = $form_state->getValue(archivos);
        for ($cfc = 1; $cfc <= count($item); $cfc++) {

            $nombre_documento = $item[$cfc]['nombre_documento'];
            $archivo = $item[$cfc]['archivo_actividad'];

            $node = \Drupal::entityTypeManager()->getStorage('node')->load($id);
            $field_collection_item = entity_create('field_collection_item', array('field_name' => 'field_documentos_fc_actividad'));
            $field_collection_item->setHostEntity($node);
            $field_collection_item->set('field_nombre_archivo', $nombre_documento);
            $field_collection_item->set('field_archivos_actividad', $archivo[0]);
            $field_collection_item->save();
        }

        drupal_set_message('Se actualizo el nodo ' . $id);

//            $node = Node::create([
//                    // The node entity bundle.
//                    'type' => 'article',
//                    'langcode' => 'en',
//                    'created' => REQUEST_TIME,
//                    'changed' => REQUEST_TIME,
//                    // The user ID.
//                    'uid' => 1,
//                    'title' => $form_state->getValue('email'),
//                    // An array with taxonomy terms.
//                    'field_tags' => [2],
//                    'field_numero' => [$form_state->getValue('numero')],
//                    'body' => [
//                        'summary' => '',
//                        'value' => '<p>The body of my node.</p>',
//                        'format' => 'full_html',
//                    ],
//
//        ]);
//        $node->save();
    }

}
