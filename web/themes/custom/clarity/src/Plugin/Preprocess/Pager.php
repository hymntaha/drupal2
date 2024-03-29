<?php

namespace Drupal\clarity\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "pager" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("pager")
 */
class Pager extends PreprocessBase implements PreprocessInterface {
  protected function preprocessVariables(Variables $variables) {
    parent::preprocessVariables($variables);

    $element = $variables['pager']['#element'];
    $parameters = $variables['pager']['#parameters'];
    $quantity = $variables['pager']['#quantity'];
    $route_name = $variables['pager']['#route_name'];
    $route_parameters = isset($variables['pager']['#route_parameters']) ? $variables['pager']['#route_parameters'] : [];
    global $pager_page_array, $pager_total;

    // Nothing to do if there is only one page.
    if ($pager_total[$element] <= 1) {
      return;
    }

    $tags = $variables['pager']['#tags'];

    // Calculate various markers within this pager piece:
    // Middle is used to "center" pages around the current page.
    $pager_middle = ceil($quantity / 2);
    // current is the page we are currently paged to.
    $pager_current = $pager_page_array[$element] + 1;
    // first is the first page listed by this pager piece (re quantity).
    $pager_first = $pager_current - $pager_middle + 1;
    // last is the last page listed by this pager piece (re quantity).
    $pager_last = $pager_current + $quantity - $pager_middle;
    // max is the maximum page number.
    $pager_max = $pager_total[$element];
    // End of marker calculations.

    // Prepare for generation loop.
    $i = $pager_first;
    if ($pager_last > $pager_max) {
      // Adjust "center" if at end of query.
      $i = $i + ($pager_max - $pager_last);
      $pager_last = $pager_max;
    }
    if ($i <= 0) {
      // Adjust "center" if at start of query.
      $pager_last = $pager_last + (1 - $i);
      $i = 1;
    }
    // End of generation loop preparation.

    // Create the "first" and "previous" links if we are not on the first page.
    if ($pager_page_array[$element] > 0) {
      $items['first'] = array();

      $items['first']['href'] = \Drupal::request()->getPathInfo();

      if (isset($tags[0])) {
        $items['first']['text'] = $tags[0];
      }

      $items['previous'] = array();
      if($pager_page_array[$element] - 1 == 0){
        $items['previous']['href'] = \Drupal::request()->getPathInfo();
      }
      else{
        $options = array(
          'query' => pager_query_add_page($parameters, $element, $pager_page_array[$element] - 1),
        );
        $items['previous']['href'] = \Drupal::url($route_name, $route_parameters, $options);
      }

      if (isset($tags[1])) {
        $items['previous']['text'] = $tags[1];
      }
    }

    if ($i != $pager_max) {
      // Add an ellipsis if there are further previous pages.
      if ($i > 1) {
        $variables['ellipses']['previous'] = TRUE;
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {

        if($i - 1 == 0){
          $items['pages'][$i]['href'] = \Drupal::request()->getPathInfo();
        }
        else{
          $options = array(
            'query' => pager_query_add_page($parameters, $element, $i - 1),
          );
          $items['pages'][$i]['href'] = \Drupal::url($route_name, $route_parameters, $options);
        }

        if ($i == $pager_current) {
          $variables['current'] = $i;
        }
      }
      // Add an ellipsis if there are further next pages.
      if ($i < $pager_max + 1) {
        $variables['ellipses']['next'] = TRUE;
      }
    }

    // Create the "next" and "last" links if we are not on the last page.
    if ($pager_page_array[$element] < ($pager_max - 1)) {
      $items['next'] = array();
      $options = array(
        'query' => pager_query_add_page($parameters, $element, $pager_page_array[$element] + 1),
      );
      $items['next']['href'] = \Drupal::url($route_name, $route_parameters, $options);
      if (isset($tags[3])) {
        $items['next']['text'] = $tags[3];
      }

      $items['last'] = array();
      $options = array(
        'query' => pager_query_add_page($parameters, $element, $pager_max - 1),
      );
      $items['last']['href'] = \Drupal::url($route_name, $route_parameters, $options);
      if (isset($tags[4])) {
        $items['last']['text'] = $tags[4];
      }
    }

    $variables['items'] = $items;

    // The rendered link needs to play well with any other query parameter used
    // on the page, like exposed filters, so for the cacheability all query
    // parameters matter.
    $variables['#cache']['contexts'][] = 'url.query_args';
  }

}