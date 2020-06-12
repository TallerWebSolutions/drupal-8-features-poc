<?php
namespace Drupal\Tests\news\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;



/**
 * Tests for news list.
 *
 * @group nsc_news
 */
class NewsListTest extends EntityKernelTestBase {

  /**
   * @var 
   */
  private $nodeStorage;

  public function setUp() {
    $this->nodeStorage = $this->container->get('entity_type.manager')->getStorage($node);
  }

  public function testShouldLoadNewsNode() {

    $myNode = $this->nodeStorage->create([
      'name' => 'Minha notícia fabulosa'
    ]);
    
    $this->assert($myNode->name, 'Minha notícia fabulosa');
  }
}
