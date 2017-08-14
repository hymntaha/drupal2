<?php

namespace Drupal\avatar_utils\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'AboveContentBlock' block.
 *
 * @Block(
 *  id = "above_content_block",
 *  admin_label = @Translation("Above Content Block"),
 * )
 */
class AboveContentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    /** @var Node $node */
    if ($node = \Drupal::request()->attributes->get('node')) {
      $build = [
        'above_content' => node_view($node, 'above_content'),
        '#cache' => [
          'contexts' => [
            'url.path',
          ],
        ]
      ];
    }

    return $build;
  }

}
