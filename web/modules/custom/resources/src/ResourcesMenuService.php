<?php

namespace Drupal\resources;

use Drupal\avatar_utils\AvatarUtilsTermsService;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\TermInterface;

class ResourcesMenuService {

  protected $entityTypeManager;
  protected $currentPathStack;
  protected $avatarUtilsTermsService;

  /**
   * ResourcesMenuService constructor.
   * @param EntityTypeManagerInterface $entityTypeManager
   * @param CurrentPathStack $currentPathStack
   * @param AvatarUtilsTermsService $avatarUtilsTermsService
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, CurrentPathStack $currentPathStack, AvatarUtilsTermsService $avatarUtilsTermsService) {
    $this->entityTypeManager = $entityTypeManager;
    $this->currentPathStack = $currentPathStack;
    $this->avatarUtilsTermsService = $avatarUtilsTermsService;
  }

  public function pageIsNews(){
    if($this->currentPathStack->getPath() == '/resources/news'){
      return true;
    }
    else if($node = \Drupal::request()->attributes->get('node')){
      if($node->getType() == 'article'){
        return true;
      }
    }
    else if($taxonomy_term = \Drupal::request()->attributes->get('taxonomy_term')){
      if($taxonomy_term->getVocabularyId() == 'news_categories'){
        return true;
      }
    }

    return false;
  }

  public function pageIsWhitepaper(){
    if($this->currentPathStack->getPath() == '/resources/white-papers'){
      return true;
    }
    else if($node = \Drupal::request()->attributes->get('node')){
      if($node->getType() == 'white_paper'){
        return true;
      }
    }
    else if($taxonomy_term = \Drupal::request()->attributes->get('taxonomy_term')){
      if($taxonomy_term->getVocabularyId() == 'white_paper_categories'){
        return true;
      }
    }

    return false;
  }

  public function getNewsCategoryMenu(){
    return $this->buildMenuForTerms($this->avatarUtilsTermsService->getTermsForVocabulary('news_categories'));
  }

  public function getWhitepaperCategoryMenu(){
    return $this->buildMenuForTerms($this->avatarUtilsTermsService->getTermsForVocabulary('white_paper_categories'));
  }

  /**
   * @param object[]|TermInterface[] $terms
   * @return array
   */
  private function buildMenuForTerms($terms){
    $menu = [];

    foreach($terms as $term){
      $in_active_trail = false;

      /** @var Term $taxonomy_term */
      if($taxonomy_term = \Drupal::request()->attributes->get('taxonomy_term')){
        if($taxonomy_term->id() == $term->id()){
          $in_active_trail = true;
        }
      }

      $url = taxonomy_term_uri($term);
      $options['data-drupal-link-system-path'] = $url->getInternalPath();

      $menu[] = [
        'title' => $term->getName(),
        'url' => $url,
        'in_active_trail' => $in_active_trail,
        'attributes' => new Attribute($options),
      ];
    }

    return $menu;
  }

}