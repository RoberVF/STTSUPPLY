<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class FixCategories extends Command
{
    protected $signature = 'app:fix-categories';
    protected $description = 'Recorre toda la base de datos y reclasifica las categorías según las nuevas palabras clave';

    public function handle()
    {
        $this->info('Iniciando proceso de normalización de categorías...');
        
        // Traemos todos los productos de la base de datos
        $products = Product::all();
        $updatedCount = 0;

        foreach ($products as $product) {
            $titleLower = strtolower($product->title);
            $newCategory = 'Shirt'; // Valor por defecto

            // 🔍 Matriz de clasificación jerárquica (de más específico a más genérico)
            if (str_contains($titleLower, 'kids')) {
                $newCategory = 'Kids';
            } elseif (str_contains($titleLower, 'baby') || str_contains($titleLower, 'infant')) {
                $newCategory = 'Baby';
            } elseif (str_contains($titleLower, 'jacket tracksuit')) {
                $newCategory = 'Jacket Tracksuit';
            } elseif (str_contains($titleLower, 'half pull tracksuit')) {
                $newCategory = 'Half Pull Tracksuit';
            } elseif (str_contains($titleLower, 'windbreaker')) {
                $newCategory = 'Windbreaker';
            } elseif (str_contains($titleLower, 'hoodie')) {
                $newCategory = 'Hoodie';
            } elseif (str_contains($titleLower, 'polo')) {
                $newCategory = 'Polo';
            } elseif (str_contains($titleLower, 'long sleeve') || str_contains($titleLower, '长袖')) {
                $newCategory = 'Long Sleeve';
            } elseif (str_contains($titleLower, 'shorts pants') || str_contains($titleLower, 'short pants') || str_contains($titleLower, 'shorts')) {
                $newCategory = 'Short Pants';
            }

            // Si la categoría calculada es distinta a la que tenía, la actualizamos
            if ($product->category !== $newCategory) {
                $oldCategory = $product->category;
                $product->update(['category' => $newCategory]);
                
                $this->line("  ✔ [Corregido] {$product->title}");
                $this->line("    └ En DB: {$oldCategory} ➔ Nueva: {$newCategory}");
                $updatedCount++;
            }
        }

        $this->info("\n¡Proceso terminado! Se han corregido {$updatedCount} prendas con éxito.");
        return Command::SUCCESS;
    }
}