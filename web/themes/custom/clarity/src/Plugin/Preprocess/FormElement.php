<?php
/**
 * @file
 * Contains \Drupal\bootstrap\Plugin\Preprocess\FormElement.
 */

namespace Drupal\clarity\Plugin\Preprocess;

use Drupal\bootstrap\Annotation\BootstrapPreprocess;
use Drupal\bootstrap\Utility\Element;
use Drupal\bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "form_element" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("form_element")
 */
class FormElement extends \Drupal\bootstrap\Plugin\Preprocess\FormElement {

  /**
   * {@inheritdoc}
   */
  public function preprocessElement(Element $element, Variables $variables) {
    parent::preprocessElement($element, $variables);

    if($element->isType(['textarea'])){
      $variables['is_form_group'] = true;
    }
  }

}
