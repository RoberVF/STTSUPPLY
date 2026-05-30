<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;

class SyncCatalog extends Command
{
    protected $signature = 'app:sync-catalog {league : El identificador (laliga, seriea, premier, ligue1, bundesliga, retro)}';
    protected $description = 'Volcar todas las páginas de una liga en kkgool de forma segura, eficiente e indetectable';

    private array $supportedLeagues = [
        'laliga'      => ['name' => 'La Liga', 'url' => 'https://www.kkgool1.com/La-Liga-c50070.html'],
        'seriea'      => ['name' => 'Serie A', 'url' => 'https://www.kkgool1.com/Serie-A-c50068.html'],
        'premier'     => ['name' => 'Premier League', 'url' => 'https://www.kkgool1.com/Premier-League-c50069.html'],
        'ligue1'      => ['name' => 'Ligue 1', 'url' => 'https://www.kkgool1.com/Ligue-1-c50064.html'], // ◄ ¡REEMPLAZA CON ESTA URL!
        'bundesliga'  => ['name' => 'Bundesliga', 'url' => 'https://www.kkgool1.com/Bundesliga-c50075.html'],
        'retro'       => ['name' => 'Retro', 'url' => 'https://www.kkgool1.com/Retro-Jerseys-c50082.html'],
    ];

    public function handle()
    {
        $leagueKey = strtolower($this->argument('league'));

        if (!array_key_exists($leagueKey, $this->supportedLeagues)) {
            $this->error("La liga '{$leagueKey}' no está configurada.");
            return Command::FAILURE;
        }

        $leagueData = $this->supportedLeagues[$leagueKey];
        $nextPageUrl = $leagueData['url'];
        $pageCounter = 1;

        $this->info("Iniciando raspado optimizado para: {$leagueData['name']}");

        while ($nextPageUrl) {
            $this->comment("\n--- Analizando Estructura Página {$pageCounter} ---");

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ])->get($nextPageUrl);

            if (!$response->successful()) {
                $this->error("Error al conectar con la página {$pageCounter}. Abortando.");
                break;
            }

            $crawler = new Crawler($response->body());

            // 🎯 SELECTOR CORREGIDO: Buscamos primero en la lista principal limpia (.common_pro_list), 
            // si no existiera en esa liga, cae a la genérica como fallback.
            $items = $crawler->filter('.common_pro_list > li');
            if ($items->count() === 0) {
                $items = $crawler->filter('.common_pro_list2 > li');
            }

            if ($items->count() === 0) {
                $this->warn("Fin del catálogo detectado o la estructura de la página ha cambiado.");
                break;
            }

            $items->each(function (Crawler $node) use ($leagueData) {
                try {
                    $anchor = $node->filter('a.pic');
                    if ($anchor->count() === 0) return;

                    $title = trim($anchor->attr('title'));

                    $title = preg_replace('/\s*\([^)]*[\x{4e00}-\x{9fa5}]+[^)]*\)/u', '', $title);
                    $title = preg_replace('/[\x{4e00}-\x{9fa5}]+/u', '', $title);
                    $title = preg_replace('/\s*\(\s*\)/', '', $title);
                    $title = trim(preg_replace('/\s+/', ' ', $title));

                    $productUrl = 'https://www.kkgool1.com' . $anchor->attr('href');

                    if (Product::where('provider_url', $productUrl)->exists()) {
                        $this->line("  ✔ [Ya indexado] {$title} (Saltando navegación profunda)");
                        return;
                    }

                    // 1. EXTRAER PRECIO SEGURO
                    $priceContent = $node->filter('.pro_content')->count() > 0 ? $node->filter('.pro_content')->text() : $node->text();
                    if (preg_match('/(?:US\$|\$)\s*([\d.]+)/i', $priceContent, $matches)) {
                        $providerPrice = (float) $matches[1];
                    } else {
                        $providerPrice = 14.50;
                    }

                    $margin = 7.50;
                    $sellingPrice = $providerPrice + $margin;

                    // 2. CLASIFICACIÓN POR PALABRAS CLAVE
                    $titleLower = strtolower($title);
                    $category = 'Shirt';

                    if (str_contains($titleLower, 'kids')) {
                        $category = 'Kids';
                    } elseif (str_contains($titleLower, 'baby') || str_contains($titleLower, 'infant')) {
                        $category = 'Baby';
                    } elseif (str_contains($titleLower, 'jacket tracksuit')) {
                        $category = 'Jacket Tracksuit';
                    } elseif (str_contains($titleLower, 'half pull tracksuit')) {
                        $category = 'Half Pull Tracksuit';
                    } elseif (str_contains($titleLower, 'windbreaker')) {
                        $category = 'Windbreaker';
                    } elseif (str_contains($titleLower, 'hoodie')) {
                        $category = 'Hoodie';
                    } elseif (str_contains($titleLower, 'polo')) {
                        $category = 'Polo';
                    } elseif (str_contains($titleLower, 'long sleeve') || str_contains($titleLower, '长袖')) {
                        $category = 'Long Sleeve';
                    } elseif (str_contains($titleLower, 'shorts pants') || str_contains($titleLower, 'short pants') || str_contains($titleLower, 'shorts')) {
                        $category = 'Short Pants';
                    }

                    // 3. NAVEGACIÓN EN PROFUNDIDAD
                    $this->line("  -> Descubriendo nueva prenda: {$title}");

                    $detailResponse = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                    ])->get($productUrl);

                    sleep(1);

                    $team = 'Otros';
                    $images = [];

                    if ($detailResponse->successful()) {
                        $detailCrawler = new Crawler($detailResponse->body());

                        $breadcrumbLinks = $detailCrawler->filter('#breadcrumb a');
                        if ($breadcrumbLinks->count() >= 3) {
                            $team = trim($breadcrumbLinks->eq(2)->text());
                        }

                        $imgNode = $detailCrawler->filter('.big_pic img, #main_img')->first();
                        if ($imgNode->count() > 0) {
                            $images[] = $imgNode->attr('src') ?? $imgNode->attr('data-original');
                        }
                    }

                    if (empty($images)) {
                        $listImg = $node->filter('img')->first();
                        $images[] = $listImg->count() > 0 ? ($listImg->attr('data-original') ?? $listImg->attr('src')) : null;
                    }

                    // 4. GUARDAR EN BASE DE DATOS CON CONTROL ANTI-COLISIÓN FIX
                    $slug = \Illuminate\Support\Str::slug($title);
                    $originalSlug = $slug;
                    $counter = 1;

                    while (Product::where('slug', $slug)->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }

                    Product::create([
                        'provider_url'   => $productUrl,
                        'title'          => $title,
                        'slug'           => $slug, // ◄ ✔️ CORREGIDO: Ahora sí guarda el slug verificado
                        'league'         => $leagueData['name'],
                        'category'       => $category,
                        'team'           => $team,
                        'provider_price' => $providerPrice,
                        'selling_price'  => $sellingPrice,
                        'images'         => $images,
                        'is_active'      => true,
                    ]);

                    $this->info("    ✔ [Nuevo Guardado] -> {$team} | {$sellingPrice}€");
                } catch (\Exception $e) {
                    $this->warn('  ❌ Error en producto: ' . $e->getMessage());
                }
            });

            // CONTROL DE PAGINACIÓN
            $nextPageAnchor = $crawler->filter('.common_pages a.next');

            if ($nextPageAnchor->count() > 0) {
                $nextPageUrl = 'https://www.kkgool1.com' . $nextPageAnchor->attr('href');
                $pageCounter++;
                usleep(500000);
            } else {
                $nextPageUrl = null;
            }
        }

        $this->info("\n¡Sincronización segura de {$leagueData['name']} completada!");
        return Command::SUCCESS;
    }
}
