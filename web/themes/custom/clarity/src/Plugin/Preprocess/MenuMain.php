<?php

namespace Drupal\clarity\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\resources\ResourcesMenuService;

/**
 * Pre-processes variables for the "menu__main" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("menu__main")
 */
class MenuMain extends PreprocessBase implements PreprocessInterface{
  protected function preprocessVariables(Variables $variables) {
    foreach($variables['items'] as $key => $item){
      if($item['title'] == 'Solutions' || $item['title'] == 'Resources'){
        /** @var BlockManagerInterface $blockManager */
        $blockManager = \Drupal::service('plugin.manager.block');
        $pluginBlock = $blockManager->createInstance(strtolower($item['title']).'_menu_block');

        $variables['items'][$key]['is_expanded'] = true;
        $variables['items'][$key]['below_content'] = $pluginBlock->build();
      }
    }

    /** @var ResourcesMenuService $resourcesMenuService */
    $resourcesMenuService = \Drupal::service('resources.menu.service');

    if($resourcesMenuService){
      foreach($variables['items'] as $key => $item){

        if($item['title'] == 'Resources'){
          if($resourcesMenuService->pageIsNews() || $resourcesMenuService->pageIsWhitepaper()){
            $variables['items'][$key]['in_active_trail'] = true;
          }
        }

        //Check if this is the menu block
        if(isset($variables['menu_block_configuration'])){
          $below_menu = [];
          $is_expanded = false;
          $in_active_trail = $item['in_active_trail'];

          switch($item['title']){
            case 'News':
              if($resourcesMenuService->pageIsNews()){
                $below_menu = $resourcesMenuService->getNewsCategoryMenu();
                $is_expanded = true;
                $in_active_trail = true;
              }
              break;
            case 'White Papers':
              if($resourcesMenuService->pageIsWhitepaper()){
                $below_menu = $resourcesMenuService->getWhitepaperCategoryMenu();
                $is_expanded = true;
                $in_active_trail = true;
              }
              break;
          }

          $variables['items'][$key]['is_expanded'] = $is_expanded;
          $variables['items'][$key]['in_active_trail'] = $in_active_trail;
          $variables['items'][$key]['below'] = $below_menu;
        }
      }
    }
  }
}