<?php

namespace Drupal\clarity\Plugin\Preprocess;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "node" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("node")
 */
class Node extends PreprocessBase implements PreprocessInterface{
  protected function preprocessVariables(Variables $variables) {
    parent::preprocessVariables($variables);

    /** @var \Drupal\node\Entity\Node $node */
    $node = $variables['elements']['#node'];

    $variables['element_id'] = $node->getType().'-'.$node->id().'-'.$variables['elements']['#view_mode'];

    switch($node->getType()){

      case 'alternating_content':
        $variables['background_color'] = '';
        $variables['text_color'] = '';
        $variables['link_color'] = '';

        switch($node->field_background_color->value){
          case 'gray':
            $variables['background_color'] = '#ececec';
            break;
          case 'red':
            $variables['background_color'] = '#4e0b2d';
            $variables['text_color'] = '#fff';
            $variables['link_color'] = '#5091a4';
            break;
          case 'light_blue':
            $variables['background_color'] = '#99bac3';
            break;
          case 'purple':
            $variables['background_color'] = '#19234b';
            $variables['text_color'] = '#fff';
            $variables['link_color'] = '#5091a4';
            break;
          case 'gold':
            $variables['background_color'] = '#977614';
            $variables['text_color'] = '#fff';
            $variables['link_color'] = '#5091a4';
            break;
        }

        break;

    }
  }

}