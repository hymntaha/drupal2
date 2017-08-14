<?php

namespace Drupal\clarity\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;
use Drupal\taxonomy\Entity\Term;

/**
 * Pre-processes variables for the "taxonomy_term" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("taxonomy_term")
 */
class TaxonomyTerm extends PreprocessBase implements PreprocessInterface {
  protected function preprocessVariables(Variables $variables) {
    parent::preprocessVariables($variables);

    /** @var Term $term */
    $term = $variables['elements']['#taxonomy_term'];
    switch($term->getVocabularyId()){
      case 'solution_categories':
        if($variables['view_mode'] == 'teaser'){
          $variables['has_video'] = false;
          $variables['has_image'] = false;

          if(!is_null($term->field_vimeo_id->value)){
            $variables['has_video'] = true;
          }

          if(!is_null($term->field_image->target_id)){
            $variables['has_image'] = true;
          }
        }

        break;
      case 'how_we_make_it_easy':
        $variables['description_clean'] = strip_tags($term->getDescription());
        break;
    }
  }

}