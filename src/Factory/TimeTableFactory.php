<?php

namespace App\Factory;

use App\Entity\TimeTable;
use Zenstruck\Foundry\LazyValue;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TimeTable>
 */
final class TimeTableFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return TimeTable::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $opening = self::faker()->randomFloat(2, 8, 16);
        $closing = self::faker()->randomFloat(2, $opening, 22);

        return [
            'closing' => $closing,
            'day' => self::faker()->randomElement($days),
            'opening' => $opening,
            'place' => LazyValue::new(fn () => null),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(TimeTable $timeTable): void {})
        ;
    }
}
