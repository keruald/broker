<?php declare(strict_types=1);

namespace Keruald\Broker\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase {
    /**
     * Asserts the specified object follows a method cascading pattern
     *
     * @param object $instance The object to tests
     * @param array $methodsCascade An array, each item an array following the
     *                              call_user_func_array format
     */
    protected function assertMethodCascading ($instance, $methodsCascade) {
        // We determine the type of the object when first passed
        // to our test, then we call one par one each method,
        // each time verifying the returned type is still the same.

        $type = $instance::class;

        foreach ($methodsCascade as $method) {
            $methodName = array_shift($method);
            $instance = call_user_func_array(
                [$instance, $methodName],
                $method
            );
            $this->assertInstanceOf(
                $type,
                $instance,
                "$type::$methodName should return \$this"
            );
        }
    }
}
