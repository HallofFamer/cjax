<?php

namespace CJAX\Core;
use Codeception\Testcase\Test;

class CacheTest extends Test{

    /**
     * @var Cache
     */    
    protected $cache;    
    
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before(){
        $this->cache = new Cache(true);
        $this->cache->append(["do" => "_fn", "fn" => "append", "fn_data" => ["a" => "#response", "b" => "#other_element"]]);
        $this->cache->append(["do" => "_call", "url" => "ajax.php?clickajaxrequest/clickButton/Hello!"]);
        $this->cache->append(["do" => "AddEventTo", "elementId" => "button1", "event" => "click", "events" => 
                              ["do" => "_call", "url" => "ajax.php?clickajaxrequest/clickButton/Hello!", "event" => "click"]]);     
    }

    protected function _after(){
        
    }

    // tests
    public function testGetCache(){
        $cache = $this->cache->getCache();
        $this->assertTrue(is_array($cache));
        $this->assertEquals(count($cache), 3);
        $this->assertEquals($cache[0]["do"], "_fn");
        $this->assertEquals($cache[1]["url"], "ajax.php?clickajaxrequest/clickButton/Hello!");
        $this->assertEquals($cache[2]["elementId"], "button1");
        $this->assertEquals($cache[2]["event"],"click");
    }
    
    public function testSetCache(){
        $oldCache = $this->cache->getCache();
        $this->cache->setCache();
        $this->assertTrue(empty($this->cache->getCache()));
        $this->cache->setCache($oldCache);
        $this->assertFalse(empty($this->cache->getCache()));
        $this->assertEquals($this->cache->getCache(), $oldCache);
    }
    
    public function testGet(){
        $cache1 = $this->cache->get(1);
        $cache2 = $this->cache->get(2);
        $this->assertEquals($cache1["do"], "_call");
        $this->assertEquals($cache2["elementId"], "button1");
    }
    
    public function testGetId(){
        $cache2 = $this->cache->cache[2];        
        $this->assertEquals($this->cache->getId(), 2);
        unset($this->cache->cache[2]);
        $this->assertEquals($this->cache->getId(), 1);
        $this->cache->cache[2] = $cache2;
        $this->assertEquals($this->cache->getId(), 2);
    }
    
    public function testSet(){
        $cache3 = ["do" => "_call", "url" => "ajax.php?changeClass/addClass"];
        $this->cache->set(3, $cache3);
        $this->assertEquals(count($this->cache->getCache()), 4);
        $this->assertEquals($this->cache->cache[3], $cache3);
    }
}