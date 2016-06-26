<?php

use PHPUnit\Framework\TestCase;
use cache\Cache;

class CacheTest extends TestCase
{
    function test_putでデータを保存してgetで取得する()
    {
        $name = 'hoge';
        $value = 'hogehoge';

        $cache = new Cache();
        $cache->put($name, $value);
        $this->assertEquals($cache->get($name), $value);
    }

    function test_get_存在しない名前を与えるとnullを返す()
    {
        $cache = new Cache();
        $this->assertNull($cache->get("hoge"));
    }

    function test_put_保存しようとした名前が既に存在していた場合は上書きして保存する()
    {
        $name = 'hoge';
        $value = 'hogehoge';
        $newValue = 'fugafuga';

        $cache = new Cache();
        $cache->put($name, $value);
        $cache->put($name, $newValue);

        $this->assertEquals($cache->get($name), $newValue);
    }

    function test_remove_存在する名前を渡すとデータを削除してtrueを返す()
    {
        $name = 'hoge';
        $value = 'hogehoge';

        $cache = new Cache();
        $cache->put($name, $value);
        $this->assertTrue($cache->remove($name));
        $this->assertNull($cache->get($name));
    }

    function test_remove_存在しない値を削除しようとした場合はfalseを返す()
    {

        $cache = new Cache();
        $this->assertFalse($cache->remove("hoge"));
    }

    /**
     * @expectedException \RuntimeException
     */
    function test_put_nameがstring型でないときにExceptionを投げる()
    {
        $name = ["hoge"];
        $value = 'hogehoge';

        $cache = new Cache();
        $cache->put($name, $value);
    }

    /**
     * @expectedException \RuntimeException
     */
    function test_get_nameがstring型でないときにExceptionを投げる()
    {
        $name = ["hoge"];

        $cache = new Cache();
        $cache->get($name);
    }

    /**
     * @expectedException \RuntimeException
     */
    function test_remove_nameがstring型でないときにExceptionを投げる()
    {
        $name = ["hoge"];

        $cache = new Cache();
        $cache->remove($name);
    }

    /**
     * @dataProvider isString_dataProvider
     */
    function test_validateName_nameがstring型のときはtrueを返す($name)
    {
        $cache = new Cache();
        $this->assertTrue($cache->validateName($name));
    }

    /**
     * @dataProvider isNotString_dataProvider
     */
    function test_validateName_nameがstring型以外のときはfalseを返す($name)
    {
        $cache = new Cache();
        $this->assertFalse($cache->validateName($name));
    }

    function isString_dataProvider()
    {
        return [
            ["hoge"],
            ["あいあい"],
            ["1"],
            ["0"],
        ];
    }

    function isNotString_dataProvider()
    {
        return [
            [["aa"]],
            [["aa" => "dd"]],
            [0],
            [1],
        ];
    }
}
