<?php
namespace Mezon\CrudService\Tests;

use Mezon\CrudService\CrudServiceClient;

/**
 * Class CrudServiceClientUnitTests
 *
 * @package CrudServiceClient
 * @subpackage CrudServiceClientUnitTests
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/18)
 * @copyright Copyright (c) 2019, aeon.org
 */

class HackedCrudServiceClient extends CrudServiceClient
{

    public function publicGetCompiledFilter($filter, $amp = true): string
    {
        return (parent::getCompiledFilter($filter, $amp));
    }

    /**
     * Method returns concrete url byit's locator
     *
     * @param string $urlLocator
     *            url locator
     * @return string concrete URL
     */
    public function getRequestUriPublic(string $urlLocator): string
    {
        return $this->getRequestUrl($urlLocator);
    }
}
