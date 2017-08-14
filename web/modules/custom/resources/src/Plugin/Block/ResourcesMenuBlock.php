<?php

namespace Drupal\resources\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\avatar_utils\AvatarUtilsUrlService;

/**
 * Provides a 'ResourcesMenuBlock' block.
 *
 * @Block(
 *  id = "resources_menu_block",
 *  admin_label = @Translation("Resources menu block"),
 * )
 */
class ResourcesMenuBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\avatar_utils\AvatarUtilsUrlService definition.
   *
   * @var \Drupal\avatar_utils\AvatarUtilsUrlService
   */
  protected $avatarUtilsUrlService;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\avatar_utils\AvatarUtilsUrlService $avatarUtilsUrlService
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    AvatarUtilsUrlService $avatarUtilsUrlService
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->avatarUtilsUrlService = $avatarUtilsUrlService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('avatar.utils.url')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var Node $node */
    $node = $this->avatarUtilsUrlService->getNodeFromAlias('/resources');

    $build = [
      '#theme' => 'resources_drop_down',
      '#overview_link' => Link::fromTextAndUrl($this->t('Resources Overview'), Url::fromRoute('entity.node.canonical', ['node' => $node->id()])),
      '#intro_text' => $node->get('field_intro_text')->value,
      '#sub_menus' => $this->buildSubMenus(),
    ];

    return $build;
  }

  private function buildSubMenus() {

    $subMenu = [
      [
        'title' => '',
        'links' => [
          Link::fromTextAndUrl($this->t('News'), Url::fromUri('internal:/resources/news')),
        ]
      ],
      /* Re-enable when they want white papers back on the site
      [
        'title' => '',
        'links' => [
          Link::fromTextAndUrl($this->t('White Papers'), Url::fromUri('internal:/resources/white-papers')),
        ],
      ],
      */
      [
        'title' => '',
        'links' => [
          Link::fromTextAndUrl($this->t('Trainings'), Url::fromUri('internal:/resources/trainings')),
        ],
      ],
      [
        'title' => '',
        'links' => [
          Link::fromTextAndUrl($this->t('Partners'), Url::fromUri('internal:/resources/partners')),
        ],
      ],
    ];

    return $subMenu;
  }

}
