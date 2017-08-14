<?php

namespace Drupal\clarity\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "yamlform" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("yamlform")
 */
class Yamlform extends PreprocessBase implements PreprocessInterface {
  protected function preprocessVariables(Variables $variables) {
    parent::preprocessVariables($variables);
    if(isset($variables['element']['confirmation']['back_to'])){
      $variables['children'] = $variables['element']['confirmation']['confirmation']['#markup'];
    }
  }

}