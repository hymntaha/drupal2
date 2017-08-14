<?php

namespace Drupal\resources;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\TermBreadcrumbBuilder;
use Drupal\Core\Link;
use Drupal\Core\Breadcrumb\Breadcrumb;

class ResourcesBreadcrumbBuilder extends TermBreadcrumbBuilder{

  public function __construct(EntityManagerInterface $entityManager) {
    parent::__construct($entityManager);
  }

  public function build(RouteMatchInterface $route_match) {
    /** @var Term $term */
    $term = $route_match->getParameter('taxonomy_term');

    $resource_vocabularies = [
      'news_categories',
      'white_paper_categories',
    ];

    if(in_array($term->getVocabularyId(), $resource_vocabularies)){
      $breadcrumb = new Breadcrumb();
      $breadcrumb->addLink(Link::createFromRoute($this->t('Home'), '<front>'));
      $term = $route_match->getParameter('taxonomy_term');
      // Breadcrumb needs to have terms cacheable metadata as a cacheable
      // dependency even though it is not shown in the breadcrumb because e.g. its
      // parent might have changed.
      $breadcrumb->addCacheableDependency($term);

      $breadcrumb->addLink(Link::fromTextAndUrl($this->t('Resources'), Url::fromUri('internal:/resources')));
      switch($term->getVocabularyId()){
        case 'news_categories':
          $breadcrumb->addLink(Link::fromTextAndUrl($this->t('News'), Url::fromUri('internal:/resources/news')));
          break;
        case 'white_paper_categories':
          $breadcrumb->addLink(Link::fromTextAndUrl($this->t('White Papers'), Url::fromUri('internal:/resources/white-papers')));
          break;
      }

      // @todo This overrides any other possible breadcrumb and is a pure
      //   hard-coded presumption. Make this behavior configurable per
      //   vocabulary or term.
      $parents = $this->termStorage->loadAllParents($term->id());
      // Remove current term being accessed.
      array_shift($parents);
      foreach (array_reverse($parents) as $term) {
        $term = $this->entityManager->getTranslationFromContext($term);
        $breadcrumb->addCacheableDependency($term);
        $breadcrumb->addLink(Link::createFromRoute($term->getName(), 'entity.taxonomy_term.canonical', array('taxonomy_term' => $term->id())));
      }

      // This breadcrumb builder is based on a route parameter, and hence it
      // depends on the 'route' cache context.
      $breadcrumb->addCacheContexts(['route']);

      return $breadcrumb;
    }

    return parent::build($route_match);
  }

}