<?php

namespace Drupal\custom_vud\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'vote_up_down_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "custom_vud_formatter_type",
 *   label = @Translation("Custom VUD formatter type"),
 *   field_types = {
 *     "vote_up_down_field"
 *   }
 * )
 */
class CustomVudFormatterType extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $widget = \Drupal::service('plugin.manager.vud')
      ->createInstance($this->getFieldSetting('widget'));

    $element = $widget->build($items->getEntity());
    $element['items'] = [];

    return $element;
  }

}
