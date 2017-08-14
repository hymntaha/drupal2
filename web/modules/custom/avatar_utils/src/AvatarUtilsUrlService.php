<?php

namespace Drupal\avatar_utils;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;

class AvatarUtilsUrlService {

  protected $entityTypeManager;

  /**
   * AvatarUtilsUrlService constructor.
   * @param EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct($entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @param string $alias
   * @return bool|\Drupal\node\Entity\Node|null
   */
  public function getNodeFromAlias($alias){
    $path = Url::fromUserInput($alias)->getInternalPath();

    if($path){
      $pathParts = explode('/', $path);
      if($pathParts[0] == 'node'){
        return $this->entityTypeManager->getStorage('node')->load($pathParts[1]);
      }
    }

    return false;
  }
}