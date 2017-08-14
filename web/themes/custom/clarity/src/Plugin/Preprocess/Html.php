<?php

namespace Drupal\clarity\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;
use Drupal\node\NodeInterface;

/**
 * Pre-processes variables for the "html" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("html")
 */
class Html extends PreprocessBase implements PreprocessInterface{
  protected function preprocessVariables(Variables $variables) {
    parent::preprocessVariables($variables);

    $variables['has_intro_text'] = false;
    $variables['pagination_subpage'] = false;
    $variables['lead_form_submission'] = false;

    if (($node = \Drupal::routeMatch()->getParameter('node')) && $node instanceof NodeInterface) {
      $variables['has_intro_text'] = isset($node->field_intro_text->value);

      if($node->getType() == 'page' && $node->getTitle() == 'Thank You'){
        $variables['lead_form_submission'] = true;
      }
    }

    if(\Drupal::request()->query->getDigits('page') > 0){
      $variables['pagination_subpage'] = true;
    }
  }
}