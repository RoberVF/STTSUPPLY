<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Str;

class CleanTitles extends Command
{
    protected $signature = 'app:clean-titles';
    protected $description = 'Limpia caracteres chinos y paréntesis de los títulos y regenera los slugs';

    public function handle()
    {
        $this->info('Iniciando purga de caracteres asiáticos en la DB con control de colisiones...');
        $products = Product::all();
        $cleaned = 0;

        foreach ($products as $product) {
            $originalTitle = $product->title;

            // 1. Limpieza de caracteres y paréntesis chinos
            $title = preg_replace('/\s*\([^)]*[\x{4e00}-\x{9fa5}]+[^)]*\)/u', '', $originalTitle);
            $title = preg_replace('/[\x{4e00}-\x{9fa5}]+/u', '', $title);
            $title = preg_replace('/\s*\(\s*\)/', '', $title);
            $title = trim(preg_replace('/\s+/', ' ', $title));

            // 2. 🔥 EVASIÓN DE COLISIONES DE SLUG
            $slug = \Illuminate\Support\Str::slug($title);
            $originalSlug = $slug;
            $counter = 1;

            // Si el slug ya existe en OTRO producto que no sea este mismo, añadimos un sufijo numérico
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // 3. Actualización segura
            if ($originalTitle !== $title || $product->slug !== $slug) {
                $product->update([
                    'title' => $title,
                    'slug'  => $slug
                ]);
                $this->line("  ✔ [Limpiado] {$originalTitle} ➔ {$title} (Slug: {$slug})");
                $cleaned++;
            }
        }

        $this->info("\n¡Saneamiento completado! Se actualizaron {$cleaned} productos sin duplicados.");
        return Command::SUCCESS;
    }
}
