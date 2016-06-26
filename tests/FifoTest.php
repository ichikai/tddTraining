<?php

use PHPUnit\Framework\TestCase;
use cache\Fifo;

class FifoTest extends TestCase
{
    /**
     * @dataProvider isNotNaturalNumber_dataProvider
     * @expectedException \RuntimeException
     */
    function test_construct_パタメータとして渡されるデータの制限数が自然数でないときExceptionを投げる($saveLimit)
    {
        $fifo = new Fifo($saveLimit);
    }

    function test_putでデータを保存してgetで取得する()
    {
        $name = 'hoge';
        $value = 'hogehoge';

        $fifo = new Fifo(5);
        $fifo->put($name, $value);
        $this->assertEquals($fifo->get($name), $value);
    }

    function test_get_存在しない名前を与えるとnullを返す()
    {
        $fifo = new Fifo(5);
        $this->assertNull($fifo->get("hoge"));
    }

    function test_put_保存しようとした名前が既に存在していた場合は上書きして保存する()
    {
        $name = 'hoge';
        $value = 'hogehoge';
        $newValue = 'fugafuga';

        $fifo = new Fifo(5);
        $fifo->put($name, $value);
        $fifo->put($name, $newValue);
        $this->assertEquals($fifo->get($name), $newValue);
    }

    function test_remove_存在する名前を渡すとデータを削除してtrueを返す()
    {
        $name = 'hoge';
        $value = 'hogehoge';

        $fifo = new Fifo(5);
        $fifo->put($name, $value);
        $this->assertTrue($fifo->remove($name));
        $this->assertNull($fifo->get($name));
    }

    function test_remove_存在しない値を削除しようとした場合はfalseを返す()
    {
        $fifo = new Fifo(5);
        $this->assertFalse($fifo->remove("hoge"));
    }

    function test_put_制限に達した場合一番古いデータが削除される()
    {
        $fifo = new Fifo(5);
        for ($i=0; $i<5; $i++) {
            $fifo->put("hoge{$i}", "hogehoge{$i}");
        }
        // 制限の5個を超えた6個目のcache
        $fifo->put("hoge", "hogehoge");
        $this->assertNull($fifo->get("hoge0"));
        $this->assertEquals("hogehoge", $fifo->get("hoge"));
    }

    /**
     * @expectedException \RuntimeException
     */
    function test_put_nameがstring型でないときにExceptionを投げる()
    {
        $name = ["hoge"];
        $value = 'hogehoge';

        $fifo = new Fifo(5);
        $fifo->put($name, $value);
    }

    /**
     * @expectedException \RuntimeException
     */
    function test_get_nameがstring型でないときにExceptionを投げる()
    {
        $name = ["hoge"];

        $fifo = new Fifo(5);
        $fifo->get($name);
    }

    /**
     * @expectedException \RuntimeException
     */
    function test_remove_nameがstring型でないときにExceptionを投げる()
    {
        $name = ["hoge"];

        $fifo = new Fifo(5);
        $fifo->remove($name);
    }

    /**
     * @dataProvider isString_dataProvider
     */
    function test_validateName_nameがstring型のときはtrueを返す($name)
    {
        $fifo = new Fifo(5);
        $this->assertTrue($fifo->validateName($name));
    }

    /**
     * @dataProvider isNotString_dataProvider
     */
    function test_validateName_nameがstring型以外のときはfalseを返す($name)
    {
        $fifo = new Fifo(5);
        $this->assertFalse($fifo->validateName($name));
    }

    function isNotNaturalNumber_dataProvider()
    {
        return [
            ["hoge"],
            [1.3],
            [0],
            [-1]
        ];
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
