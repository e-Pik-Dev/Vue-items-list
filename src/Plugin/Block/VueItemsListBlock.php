<?php

namespace Drupal\vue_items_list\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Vue items list' block.
 *
 * @Block(
 *   id = "vue_list_items_block",
 *   admin_label = @Translation("Vue items list")
 * )
 */
class VueItemsListBlock extends BlockBase {

  /**
   * Endpoint configuration filed name
   *
   * @var string
   */
  protected string $endpointFieldName = 'endpoint';

  /**
   * Max items no configuration filed name
   *
   * @var string
   */
  protected string $maxItemsNoFieldName = 'max_item_no';

  /**
   * Default endpoint to initially use when not provided
   *
   * @var string
   */
  protected const DEFAULT_ENDPOINT = 'https://jsonplaceholder.typicode.com/posts';

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $attached = array_merge_recursive(
      $this->attachSettings(),
      $this->attachLibraries()
    );

    return [
      '#markup' => "<div id='app'></div>",
      '#attached' => $attached,
    ];
  }

  /**
   * Returns component attachment settings
   *
   * @return array[]
   */
  public function attachSettings(): array {
    $config = $this->getConfiguration();

    $attached = [];
    $attached['drupalSettings']['itemsList']['endpoint'] = $config[$this->endpointFieldName] ?? "";
    $attached['drupalSettings']['itemsList']['maxItemsNo'] = $config[$this->maxItemsNoFieldName] ?? "";

    return $attached;
  }

  /**
   * Returns component attachment libraries
   *
   * @return array[]
   */
  public function attachLibraries(): array {
    return [
      'library' => [
        'vue_items_list/item.list.component',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    // Add the endpoint form field to the existing block configuration form.
    $form[$this->endpointFieldName] = [
      '#type' => 'textfield',
      '#title' => $this->t('API endpoint'),
      '#default_value' => $config[$this->endpointFieldName] ?? self::DEFAULT_ENDPOINT,
    ];

    // Add the endpoint form field to the existing block configuration form.
    $form[$this->maxItemsNoFieldName] = [
      '#type' => 'textfield',
      '#title' => $this->t('Maximum items to show in list'),
      '#default_value' => $config[$this->maxItemsNoFieldName] ?? "",
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $endpoint = $form_state->getValue($this->endpointFieldName);
    // Remove all illegal characters from the endpoint url
    $endpoint = filter_var($endpoint, FILTER_SANITIZE_URL);
    $this->setConfigurationValue($this->endpointFieldName, $endpoint);

    $maxItemNo = $form_state->getValue($this->maxItemsNoFieldName);
    $this->setConfigurationValue($this->maxItemsNoFieldName, $maxItemNo);
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state): void {
    $endpoint = $form_state->getValue($this->endpointFieldName);

    // Remove all illegal characters from the endpoint url
    $endpoint = filter_var($endpoint, FILTER_SANITIZE_URL);

    // Validate endpoint to be an URL
    if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
      $form_state->setErrorByName(
        $this->endpointFieldName,
        t('Needs to be a valid url')
      );
    }

    $maxItemNo = $form_state->getValue($this->maxItemsNoFieldName);

    // Validate maxItemNo
    if (!empty($maxItemNo) && !filter_var($maxItemNo, FILTER_VALIDATE_INT)) {
      $form_state->setErrorByName(
        $this->maxItemsNoFieldName,
        t('Needs to be a positive integer or 0')
      );
    }
  }

}
