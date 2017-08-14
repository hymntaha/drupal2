<?php

namespace Drupal\whitepaper\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\whitepaper\WhitepaperService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WhitepaperForm.
 *
 * @package Drupal\whitepaper\Form
 */
class WhitepaperForm extends FormBase {

  /**
   * @var WhitepaperService
   */
  protected $whitepaperService;

  /**
   * WhitepaperForm constructor.
   * @param WhitepaperService $whitepaperService
   */
  public function __construct($whitepaperService) {
    $this->whitepaperService = $whitepaperService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('whitepaper.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'whitepaper_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'item',
      '#markup' => '<h3>'.$this->t('White Paper Download').'</h3>',
    ];

    $form['i_am'] = array(
      '#type' => 'select',
      '#title' => $this->t('I am'),
      '#options' => array(
        'dentist' => $this->t('Dentist'),
        'doctor' => $this->t('Doctor'),
        'engineer' => $this->t('Engineer'),
      ),
      '#required' => true,
    );

    $form['first_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#required' => true,
    );

    $form['last_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#required' => true,
    );

    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Email Address'),
      '#required' => true,
    );

    $form['actions'] = array(
      '#type' => 'actions',
    );

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Download Whitepaper'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var Node $whitepaper */
    $whitepaper = $form_state->getBuildInfo()['args'][0];

    if($this->whitepaperService->saveWhitepaperEmailFromFormSubmit($form_state, $whitepaper)){
      $this->whitepaperService->flagUserSubmittedWhitepaperForm();
    }
  }

}
