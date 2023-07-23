<?php

declare(strict_types=1);

namespace TestsCodeception\Support;

use App\Models\User;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */
    public function castToString(mixed $object): string
    {
        $this->assertType("string", $object);
        return $object; // @phpstan-ignore-line
    }

    public function castToModelUser(mixed $object): User
    {
        $this->assertType(User::class, $object);
        return $object; // @phpstan-ignore-line
    }

    public function assertType(string $expectedType, mixed $something): void
    {
        if (is_object($something)) {
            $actualType = get_class($something);
        } else {
            $actualType = gettype($something);
        }
        if ($expectedType != $actualType) {
            $message = "Type assertion failed! Expected '$expectedType' but got '$actualType'.";
            $this->fail($message);
            exit($message);
        }
    }
}
