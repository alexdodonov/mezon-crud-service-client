<?php
namespace Mezon\CrudService\Tests;

use Mezon\CrudService\CrudServiceClient;
use Mezon\Service\Tests\ServiceClientUnitTests;

/**
 * Class CrudServiceClientUnitTests
 *
 * @package CrudServiceClient
 * @subpackage CrudServiceClientUnitTests
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/18)
 * @copyright Copyright (c) 2019, aeon.su
 */

/**
 * Common unit tests for CrudServiceClient and all derived client classes
 *
 * @author Dodonov A.A.
 * @group baseTests
 */
class CrudServiceClientUnitTests extends ServiceClientUnitTests
{

    /**
     * Getting mock object for Crud service client
     *
     * @return object Mock object
     */
    protected function getCrudServiceClientMock()
    {
        $mock = $this->getMockBuilder(CrudServiceClient::class)
            ->setMethods([
            'sendGetRequest',
            'sendPostRequest'
        ])
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * Method make full setup of the mock object
     *
     * @param string $configName
     * @return object Mock object
     */
    protected function getSetupMockWithGetMethod(string $configName)
    {
        $mock = $this->getCrudServiceClientMock();

        $mock->method('sendGetRequest')->willReturn(
            json_decode(file_get_contents(__DIR__ . '/Conf/' . $configName . '.json')));
        $mock->method('sendPostRequest')->willReturn(
            json_decode(file_get_contents(__DIR__ . '/Conf/' . $configName . '.json')));

        return $mock;
    }

    /**
     * Testing 'getCompiledFilter' method
     */
    public function testGetCompiledFilter1()
    {
        // setup
        $client = new HackedCrudServiceClient('https://ya.ru');

        // test body
        $result = $client->publicGetCompiledFilter(false);

        // assertions
        $this->assertEquals('', $result, 'Empty string must be returned');
    }

    /**
     * Testing 'getCompiledFilter' method
     */
    public function testGetCompiledFilter2()
    {
        // setup
        $client = new HackedCrudServiceClient('https://ya.ru');

        // test body
        $result = $client->publicGetCompiledFilter([
            'field1' => 1,
            'field2' => 2
        ], true);

        // assertions
        $this->assertStringContainsString('filter[field1]=1', $result);
        $this->assertStringContainsString('filter[field2]=2', $result);
    }

    /**
     * Testing 'getCompiledFilter' method
     */
    public function testGetCompiledFilter3()
    {
        // setup
        $client = new HackedCrudServiceClient('https://ya.ru');

        // test body
        $result = $client->publicGetCompiledFilter([
            [
                'arg1' => '$id',
                'op' => '=',
                'arg2' => '1'
            ]
        ], true);

        // assertions
        $this->assertStringContainsString('&filter%5B0%5D%5Barg1%5D=%24id', $result);
        $this->assertStringContainsString('&filter%5B0%5D%5Bop%5D=%3D', $result);
        $this->assertStringContainsString('&filter%5B0%5D%5Barg2%5D=1', $result);
    }

    /**
     * Testing 'getByIdsArray' method
     */
    public function testGetByIdsArray()
    {
        // setup
        $client = $this->getSetupMockWithGetMethod('GetByIdsArray');

        // test body
        $ids = [
            1,
            2
        ];
        $result = $client->getByIdsArray($ids); // compile
        $result2 = $client->getByIdsArray($ids); // cache

        // assertions
        $this->assertEquals(2, count($result));
        $this->assertEquals(2, count($result2));
    }

    /**
     * Testing 'getByIdsArray' method
     */
    public function testGetByIdsArrayNull()
    {
        // setup
        $client = $this->getSetupMockWithGetMethod('GetByIdsArray');

        // test body
        $result = $client->getByIdsArray([]);

        // assertions
        $this->assertEquals(0, count($result));
    }

    /**
     * Testing 'recordsCountByField' method
     */
    public function testRecordsCountByField()
    {
        // setup
        $client = $this->getSetupMockWithGetMethod('RecordsCountByField');

        // test body
        $result = $client->recordsCountByField('id');
        $result2 = $client->recordsCountByField('id');

        // assertions
        $this->assertEquals(3, count($result));
        $this->assertEquals(3, count($result2));
    }

    /**
     * Testing instance method
     */
    public function testInstance()
    {
        // setup and test body
        $client = \Mezon\CrudService\CrudServiceClient::instance('http://auth', 'token');

        // assertions
        $this->assertEquals('token', $client->getToken());
    }

    /**
     * Testing 'getList' method
     */
    public function testGetList()
    {
        // setup
        $client = $this->getSetupMockWithGetMethod('GetList');

        // test body
        $result = $client->getList(0, 1, false, false, false);

        // assertions
        $this->assertEquals(2, count($result));
    }

    /**
     * Testing 'getList' method
     */
    public function testGetListOrder()
    {
        // setup
        $client = $this->getSetupMockWithGetMethod('GetList');

        // test body
        $result = $client->getList(0, 1, false, false, [
            'field' => 'id',
            'order' => 'ASC'
        ]);

        // assertions
        $this->assertEquals(2, count($result));
    }

    /**
     * Testing 'create' method
     */
    public function testCreate()
    {
        // setup
        $client = $this->getSetupMockWithGetMethod('Create');

        // test body
        $result = $client->create(
            [
                'field' => 1,
                'avatar' => [
                    'name' => 'n',
                    'size' => 's',
                    'type' => 't',
                    'tmp_name' => __FILE__
                ],
                'scans' => [
                    'name' => [
                        'n'
                    ],
                    'size' => [
                        's'
                    ],
                    'type' => [
                        't'
                    ],
                    'tmp_name' => [
                        __FILE__
                    ]
                ],
                'invalid-file' => []
            ]);

        // assertions
        $this->assertEquals(1, $result->id);
    }

    /**
     * Testing getFields method
     */
    public function testGetFields(): void
    {
        // setup
        $client = $this->getCrudServiceClientMock();
        $client->method('sendGetRequest')->willReturn(
            json_decode(file_get_contents(__DIR__ . '/Conf/GetFields.json'), true));

        // test body
        $result = $client->getFields();

        // assertions
        $this->assertArrayHasKey('fields', $result);
        $this->assertArrayHasKey('layout', $result);
    }

    /**
     * Testing method
     */
    public function testGetRequestUrlException(): void
    {
        // setup and assertions
        $this->expectException(\Exception::class);
        $client = new HackedCrudServiceClient('https://some-service');

        // test body
        $client->getRequestUriPublic('unexistingUri');
    }
}
