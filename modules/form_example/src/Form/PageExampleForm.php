<?php

/**
 * @file
 * Contains \Drupal\form_example\Form\PageExampleForm
 */

namespace Drupal\form_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class PageExampleForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'page_example_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Your .com email address.')
        ];
        
        $form['numero'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Number')
        ];

        $form['show'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit')
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        if (strpos($form_state->getValue('email'), '.com') === FALSE) {
            $form_state->setErrorByName('email', $this->t('This is not a .com email address.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        drupal_set_message(
                $this->t(
                        'Your email address is @email', [
                    '@email' => $form_state->getValue('email')
                        ]
                )
        );

        //creacion de nodo

        $node = Node::create([
                    // The node entity bundle.
                    'type' => 'article',
                    'langcode' => 'en',
                    'created' => REQUEST_TIME,
                    'changed' => REQUEST_TIME,
                    // The user ID.
                    'uid' => 1,
                    'title' => $form_state->getValue('email'),
                    // An array with taxonomy terms.
                    'field_tags' => [2],
                    'field_numero' => [$form_state->getValue('numero')],
                    'body' => [
                        'summary' => '',
                        'value' => '<p>The body of my node.</p>',
                        'format' => 'full_html',
                    ],
                    
            
                    //campo extra
//                    'field_images' => [
//                        [
//                            'target_id' => $file->id(),
//                            'alt' => "My 'alt'",
//                            'title' => "My 'title'",
//                        ],
//                    ],
        ]);
        $node->save();
    }

}
