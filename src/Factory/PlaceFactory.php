<?php

namespace App\Factory;

use App\Entity\Place;
use Zenstruck\Foundry\LazyValue;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Place>
 */
final class PlaceFactory extends PersistentProxyObjectFactory
{
    protected static int $i = 0;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Place::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        // add name if you want more places
        $name = ['Cathedrale', 'Musee', 'Burger King', 'La Marotte', 'Bowling', 'Laser Game', 'Musee2', 'Restaurant2'];
        ++PlaceFactory::$i;

        return [
            'PostalCode' => '51100',
            'address' => self::faker()->address(),
            'category' => LazyValue::new(fn () => null),
            'city' => 'Reims',
            'latitude' => self::faker()->randomFloat(6, 49.2167, 49.2833),
            'longitude' => self::faker()->randomFloat(6, 3.9667, 4.05),
            'name' => $name[(PlaceFactory::$i - 1) % 8],
            'price_min' => self::faker()->randomDigit(),
            'price_max' => self::faker()->randomNumber(2),
            'visit_time' => self::faker()->randomFloat(2, 0.5, 2),
            'description' => self::faker()->text(100),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this// ->afterInstantiate(function(Place $place): void {})
        ;
    }
}
