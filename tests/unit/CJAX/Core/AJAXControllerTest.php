<?php

namespace CJAX\Core;
use Codeception\TestCase\Test;

class AJAXControllerTest extends Test{

    /**
     * @var AjaxController
     */    
    protected $ajaxController;
    
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    
    protected function _before(){
        $this->ajaxController = $this->getMockBuilder("CJAX\Core\AJAXController")
                                     ->setConstructorArgs([CJAX::getInstance()])->getMockForAbstractClass();
    }

    protected function _after(){
        
    }

    // tests
    public function testGetAJAX(){
        $ajax = CJAX::getInstance();
        $this->assertEquals($ajax, $this->ajaxController->getAJAX());
    }
}
