<?php
namespace Drupal\Tests\news\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;



/**
 * Tests for news list.
 *
 * @group nsc_news
 */
class NewsListTest extends EntityKernelTestBase {

  public static $modules = ['node', 'news'];

  /**
   * @var 
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

    $this->assertEqual($node->get('field_my_field')->getValue(), 'My field');
  }
}
