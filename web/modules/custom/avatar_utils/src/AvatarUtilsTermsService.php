<?php

namespace Drupal\avatar_utils;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;

class AvatarUtilsTermsService {

  protected $entityTypeManager;
  protected $moduleHandler;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, ModuleHandlerInterface $moduleHandler) {
    $this->entityTypeManager = $entityTypeManager;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * @param string $machine_name
   * @return Term[]|bool
   */
  public function getTermsForVocabulary($machine_name){
    /** @var Vocabulary $vocabulary */
    $vocabulary = Vocabulary::load($machine_name);

    if($vocabulary){
      return $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vocabulary->id(), 0, NULL, true);
    }

    return false;
  }

  /**
   * @param Term $term
   * @return Node[]
   */
  public function getNodesForTerm($term, $sort = false){
    /** @var QueryInterface $query */
    $query = \Drupal::entityQuery('node')
    ->condition('status', 1)
    ->condition('field_solution_category', $term->id());

    if($sort == 'weight' && $this->moduleHandler->moduleExists('weight')){
      $query->sort('field_weight', 'ASC');
    }

    $nids = $query->execute();

    return $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
  }
}