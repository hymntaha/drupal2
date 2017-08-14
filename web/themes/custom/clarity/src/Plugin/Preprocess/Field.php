<?php

namespace Drupal\clarity\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "field" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("field")
 */
class Field extends PreprocessBase implements PreprocessInterface {
  protected function preprocessVariables(Variables $variables) {
    parent::preprocessVariables($variables);

    switch($variables['element']['#field_name']){
      case 'field_hero_image':
        $variables['items'][0]['content']['#theme'] = 'hero_image';
        break;
    }
  }

}