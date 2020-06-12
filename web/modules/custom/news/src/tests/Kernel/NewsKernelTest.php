<?php
namespace Drupal\Tests\news\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;



/**
 * Tests for news list.
 *
 * @group nsc_news
 */
class NewsListTest extends EntityKernelTestBase {

  public static $modules = ['node', 'news', 'menu_ui'];

  /**
   * @var \Drupal\node\NodeStorage $nodeStorage
   */
  private $nodeStorage;

  public function setUp() {
    parent::setUp();
    $this->installConfig(['news']);
    $this->nodeStorage = $this->container->get('entity_type.manager')->getStorage('node');
  }

  public function testShouldLoadNewsNode() {

    $myNode = $this->nodeStorage->create([
      'nid' => 1,
      'title' => 'Minha notícia fabulosa',
      'field_my_field' => 'My field',
      'type' => 'foo'
    ]);

    $myNode->save();

    $nodes = $this->nodeStorage->loadByProperties([
      'type' => 'foo'
    ]);

    $node = reset($nodes);
    $this->assertEqual($node->get('field_my_field')->getString(), 'My field');
  }

  public function testNodeQuery() {

    $nodes = [
      [
        'title' => 'Minha notícia fabulosa 1',
        'field_my_field' => 'My field',
        'type' => 'foo'
      ],
      [
        'title' => 'Minha notícia fabulosa 2',
        'field_my_field' => 'My field',
        'type' => 'foo'
      ],
      [
        'title' => 'Minha notícia fabulosa 3',
        'field_my_field' => 'My field 2',
        'type' => 'foo'
      ]
    ];

    foreach($nodes as $node){
      $this->nodeStorage->create($node)->save();
    }

    $query = $this->nodeStorage->getQuery();
    $results = $query->condition('field_my_field', 'My Field')->execute();

    $this->assertEqual(count($results), 2);
  } 
}
