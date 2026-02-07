<?php
// database/factories/DocumentFactory.php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'file_name' => $this->faker->word . '.pdf',
            'file_path' => 'documents/' . $this->faker->year . '/' . $this->faker->month . '/' . $this->faker->uuid . '.pdf',
            'file_size' => $this->faker->numberBetween(1024, 10485760), // 1KB to 10MB
            'mime_type' => 'application/pdf',
            'version' => 1,
            'category_id' => Category::factory(),
            'uploaded_by' => User::factory(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
        ];
    }

    /**
     * Indicate that the document is published.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
            ];
        });
    }

    /**
     * Indicate that the document is draft.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
            ];
        });
    }
}