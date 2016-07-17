<?php

namespace CJAX\Core;
use Codeception\TestCase\Test;

class CJAXTest extends Test{

    /**
     * @var CJAX
     */    
    protected $cjax;       
    
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before(){
        $this->cjax = CJAX::getInstance();
    }

    protected function _after(){
        
    }

    // tests
    public function testGetInstance(){
        $this->assertEquals($this->cjax, CJAX::getInstance());
    }
}
