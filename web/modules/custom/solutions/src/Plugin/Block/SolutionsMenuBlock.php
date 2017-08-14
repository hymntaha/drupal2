<?php

namespace Drupal\solutions\Plugin\Block;

use Drupal\avatar_utils\AvatarUtilsTermsService;
use Drupal\avatar_utils\AvatarUtilsUrlService;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'SolutionsMenuBlock' block.
 *
 * @Block(
 *  id = "solutions_menu_block",
 *  admin_label = @Translation("Solutions menu block"),
 * )
 */
class SolutionsMenuBlock extends BlockBase implements ContainerFactoryPluginInterface{

  protected $avatarUtilsUrlService;
  protected $avatarUtilsTermsService;

  /**
   * SolutionsMenuBlock constructor.
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\avatar_utils\AvatarUtilsUrlService $avatarUtilsUrlService
   * @param \Drupal\avatar_utils\AvatarUtilsTermsService $avatarUtilsTermsService
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AvatarUtilsUrlService $avatarUtilsUrlService, AvatarUtilsTermsService $avatarUtilsTermsService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->avatarUtilsUrlService = $avatarUtilsUrlService;
    $this->avatarUtilsTermsService = $avatarUtilsTermsService;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('avatar.utils.url'),
      $container->get('avatar.utils.terms')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var Node $node */
    $node = $this->avatarUtilsUrlService->getNodeFromAlias('/solutions');

    $build = [
      '#theme' => 'solutions_drop_down',
      '#overview_link' => Link::fromTextAndUrl($this->t('Solutions Overview'), Url::fromRoute('entity.node.canonical', ['node' => $node->id()])),
      '#intro_text' =>  $node->get('field_intro_text')->value,
      '#sub_menus' => $this->buildSubMenus(),
      '#cache' => [
        'tags' => [
          'node_type:solution',
        ]
      ],
    ];

    return $build;
  }

  private function buildSubMenus(){
    $subMenus = [];
    $solutionCategories = $this->avatarUtilsTermsService->getTermsForVocabulary('solution_categories');

    /** @var Term $term */
    foreach($solutionCategories as $term){
      $links = [];
      $nodes = $this->avatarUtilsTermsService->getNodesForTerm($term, 'weight');

      /** @var Node $node */
      foreach($nodes as $node){
        $links[] = Link::fromTextAndUrl($node->title->value, Url::fromRoute('entity.node.canonical', ['node' => $node->id()]));
      }

      $subMenus[] = [
        'title' => $term->name->value,
        'links' => $links,
      ];
    }

    return $subMenus;
  }

}
