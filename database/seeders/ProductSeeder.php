<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductProperty;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevent double execution
        if (Product::count() > 0) {
            return;
        }

        $faker = Faker::create();

        // Create 9-18 random unique property names
        $fakeProperties = [];
        for ($i = 0; $i < rand(9, 18); ++$i) {
            $fakeName = $faker->sentence();
            while (array_search($fakeName, $fakeProperties)) {
                $fakeName = $faker->sentence();
            }

            $fakeProperties[] = $fakeName;
        }

        // Create 140 random products with unique random name and random properties
        for ($i = 0; $i < rand(100, 140); ++$i) {
            $fakeName = $faker->sentence();
            while (Product::where('name', $fakeName)->first()) {
                $fakeName = $faker->sentence();
            }

            $product = Product::create([
                'name' => $fakeName,
                'count' => rand(10, 1000),
            ]);

            // Add 3-9 random properties to product
            for ($j = 0; $j < rand(3, 9); ++$j) {
                $propertyName = $fakeProperties[rand(0, count($fakeProperties) - 1)];

                $property = $product->properties()->where('name', $propertyName)->first();
                if (!$property) {
                    $property = new ProductProperty();
                    $property->name = $propertyName;
                    $property->product()->associate($product);
                }
                $property->value = $faker->words(1);
                $property->save();
            }
        }
    }
}
