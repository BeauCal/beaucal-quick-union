<?php

namespace BeaucalQuickUnionTest\Adapter;

use BeaucalQuickUnion\Adapter;
use BeaucalQuickUnion\Options;

/**
 * @group beaucal_union
 */
class MemoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var UnionMemoryAdapter
     */
    protected $unionAdapter;

    public function setUp() {
        parent::setUp();
        $this->unionAdapter = new Adapter\Memory(new Options\MemoryAdapter);
    }

    public function testGetOptions() {
        $this->assertInstanceOf(
        'BeaucalQuickUnion\Options\MemoryAdapter',
        $this->unionAdapter->getOptions()
        );
    }

}
