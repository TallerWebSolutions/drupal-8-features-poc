<?php

namespace Drupal\news\Plugin\GraphQL\Fields;

use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Retrieve a list of nodes.
 *
 * @GraphQLField(
 *   id = "custom_node_query",
 *   secure = true,
 *   name = "customNodeQuery",
 *   type = "Entity",
 *   multi = true
 * )
 */
class CustomNodeQuery extends FieldPluginBase implements ContainerFactoryPluginInterface
{
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $pluginId, $pluginDefinition, EntityTypeManagerInterface $entityTypeManager)
  {
    $this->entityTypeManager = $entityTypeManager;
    parent::__construct($configuration, $pluginId, $pluginDefinition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition)
  {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('entity_type.manager')
    );
  }

  public function getCacheDependencies($result, $parent, $args, $context, $info) {
    $metadata = parent::getCacheDependencies($result, $parent, $args, $context, $info);
    $metadata[0]->addCacheTags(['custom_node']);
    return $metadata;
  }

  public function resolveValues($value, $args, $context, $info)
  {
    $nodeStorage = $this->entityTypeManager->getStorage('node');
    $query = $nodeStorage->getQuery();

    $result = $query->execute();

    foreach ($result as $node) {
      yield $nodeStorage->load($node);
    }
  }
}
