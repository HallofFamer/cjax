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
        $caches = $this->cache->getCache();
        $this->assertTrue(is_array($caches));
        $this->assertEquals(count($caches), 3);
        $this->assertEquals($caches[0]["do"], "_fn");
        $this->assertEquals($caches[1]["url"], "ajax.php?clickajaxrequest/clickButton/Hello!");
        $this->assertEquals($caches[2]["elementId"], "button1");
        $this->assertEquals($caches[2]["event"],"click");
    }
    
    public function testSetCache(){
        $oldCaches = $this->cache->getCache();
        $this->cache->setCache();
        $this->assertTrue(empty($this->cache->getCache()));
        $this->cache->setCache($oldCaches);
        $this->assertFalse(empty($this->cache->getCache()));
        $this->assertEquals($this->cache->getCache(), $oldCaches);
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
        $cache3 = ["do" => "_call", "url" => "ajax.php?changeclass/addClass"];
        $this->cache->set(3, $cache3);
        $this->assertEquals(count($this->cache->getCache()), 4);
        $this->assertEquals($this->cache->cache[3], $cache3);
    }
    
    public function testAppend(){
        $cache3 = ["do" => "_call", "url" => "ajax.php?changeclass/addClass"];
        $cache4 = ["do" => "_fn", "fn" => "actions", "fn_data" => ["a" => "#response"]];
        $this->cache->append($cache3);
        $this->cache->append($cache4, 6);
        $this->cache->append($cache4, "actions");
        $this->assertEquals($this->cache->cache[3], $cache3);
        $this->assertEquals($this->cache->cache[6], $cache4);
        $this->assertEquals($this->cache->actions[0], $cache4);
    }
    
    public function testAppendLast(){
        $cache3 = ["do" => "_call", "url" => "ajax.php?changeclass/addClass"];
        $cache4 = ["do" => "_fn", "fn" => "actions", "fn_data" => ["a" => "#response"]];
        $this->cache->appendLast($cache3);
        $this->cache->appendLast($cache4, 4);
        $this->assertEquals($this->cache->lastCache[0], $cache3);
        $this->assertEquals($this->cache->lastCache[4], $cache4);        
    }
    
    public function testMerge(){
        $cache3 = ["do" => "_call", "url" => "ajax.php?changeclass/addClass"];
        $cache4 = ["do" => "_fn", "fn" => "actions", "fn_data" => ["a" => "#response"]];
        $this->cache->appendLast($cache3);
        $this->cache->appendLast($cache4, 4);
        $this->cache->merge();
        $newCaches = $this->cache->getCache();
        $this->assertEquals(count($this->cache->getCache()), 5);
        $this->assertEquals($newCaches[3], $cache3);
        $this->assertEquals($newCaches[4], $cache4);              
    }
    
    public function testHasContents(){
        $this->assertTrue($this->cache->hasContents());
        $this->cache->flushAll();
        $this->assertFalse($this->cache->hasContents());
    }
    
    public function testGetContents(){
        $cache3 = ["do" => "_call", "url" => "ajax.php?changeclass/addClass"];
        $cache4 = ["do" => "_fn", "fn" => "actions", "fn_data" => ["a" => "#response"]];
        $this->cache->appendLast($cache3);
        $this->cache->append($cache4, "actions");
        $contents = $this->cache->getContents();
        $this->assertEquals(count($contents), 5);
        $this->assertEquals($contents[4], $cache3);
        $this->assertEquals($contents[3], $cache4);
    }
    
    public function testRemove(){
        $this->assertTrue(isset($this->cache->cache[0]));
        $this->assertTrue(isset($this->cache->cache[1]));  
        $this->assertTrue(isset($this->cache->cache[2]));
        $this->cache->remove(1);
        $this->assertFalse(isset($this->cache->cache[1]));
        $this->cache->remove([0, 2]);
        $this->assertFalse(isset($this->cache->cache[0]));
        $this->assertFalse(isset($this->cache->cache[2]));         
    }
    
    public function testDelete(){
        $this->assertTrue(isset($this->cache->cache[1]));
        $this->cache->delete(1);  
        $this->assertFalse(isset($this->cache->cache[1]));
    }
    
    public function testDeleteLast(){
        $this->assertTrue(isset($this->cache->cache[1]));  
        $this->assertTrue(isset($this->cache->cache[2]));
        $this->cache->deleteLast(2);
        $this->assertFalse(isset($this->cache->cache[1]));
        $this->assertFalse(isset($this->cache->cache[2]));           
    }
    
    public function testFlush(){
        $this->assertFalse(empty($this->cache->cache));
        $this->cache->flush();
        $this->assertTrue(empty($this->cache->cache));
    }
    
    public function testFlushAll(){
        $this->cache->append(["do" => "_fn", "fn" => "actions", "fn_data" => ["a" => "#response"]], "actions"); 
        $this->cache->appendLast(["do" => "_call", "url" => "ajax.php?changeclass/addClass"]);
        $this->assertFalse(empty($this->cache->cache));  
        $this->assertFalse(empty($this->cache->actions));  
        $this->assertFalse(empty($this->cache->lastCache));          
        $this->cache->flushAll();
        $this->assertTrue(empty($this->cache->cache));
        $this->assertTrue(empty($this->cache->actions));  
        $this->assertTrue(empty($this->cache->lastCache));        
    }
}