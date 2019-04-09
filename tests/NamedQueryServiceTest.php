<?php

namespace Tests;

use Jeidison\NamedQuery\Enums\TypeBind;
use Jeidison\NamedQuery\Enums\TypeFile;
use Jeidison\NamedQuery\NamedQuery;

class NamedQueryServiceTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        config(['named-query' => [
            'path-sql' => 'database/queries',
            'type' => TypeFile::XML,
            'type-bind' => TypeBind::TWO_POINTS,
        ],]);
    }

    /**
     * @covers
     */
    public function testDebug()
    {
        $sql = NamedQuery::executeNamedQuery('courses_student', 'named-query', [], null, true);
        $this->assertNotNull($sql);
    }

}