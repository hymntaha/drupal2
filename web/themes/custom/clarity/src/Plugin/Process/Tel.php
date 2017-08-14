<?php

namespace Drupal\clarity\Plugin\Process;

use Drupal\bootstrap\Plugin\Process\ProcessBase;
use Drupal\bootstrap\Plugin\Process\ProcessInterface;
use Drupal\bootstrap\Utility\Element;
use Drupal\Core\Form\FormStateInterface;

/**
 * Processes the "tel" element.
 *
 * @ingroup plugins_process
 *
 * @BootstrapProcess("tel")
 */
class Tel extends ProcessBase implements ProcessInterface{

  public static function processElement(Element $element, FormStateInterface $form_state, array &$complete_form) {
    parent::processElement($element, $form_state, $complete_form);
    $element['#wrapper_attributes'] = [];
  }
}