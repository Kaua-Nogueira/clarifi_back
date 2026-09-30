<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Content;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::create(['name' => 'Restaurante Sabor da Vila', 'segment' => 'Gastronomia']);

        $festival = $this->createContent($client->id, [
            'title' => 'Festival de Massas', 'slug' => 'festival-de-massas',
            'channel' => 'Instagram Feed', 'type' => 'Carrossel', 'status' => 'pending',
            'publication_date' => '2026-10-02 19:00:00', 'responsible' => 'Bianca Mendes',
            'objective' => 'Gerar desejo e reservas para o Festival de Massas, destacando a variedade e o preparo artesanal dos pratos.',
            'editorial_line' => 'Experiência & Gastronomia', 'cta' => 'Reserve sua mesa pelo link da bio',
            'audience' => 'Casais e famílias de 25 a 55 anos da região',
            'caption' => "Uma viagem pela Itália sem sair da Vila. 🍝\n\nDe 03 a 12 de outubro, nosso Festival de Massas reúne receitas artesanais, ingredientes frescos e aquele cuidado que transforma um jantar em memória.\n\nQual prato vai chegar primeiro à sua mesa? Reserve pelo link da bio.",
            'agency_notes' => 'A campanha abre com forte apelo visual. No slide final, reforçamos período e reserva sem poluir a composição.',
        ]);

        foreach ([
            ['/images/festival-massas-01.png', 'Tagliatelle artesanal ao molho de tomates e manjericão'],
            ['/images/festival-massas-02.png', 'Seleção de três massas artesanais do festival'],
            ['/images/festival-massas-03.png', 'Experiência de jantar no Sabor da Vila'],
        ] as $index => [$url, $alt]) {
            $festival->assets()->create(['kind' => 'image', 'url' => $url, 'alt_text' => $alt, 'position' => $index + 1]);
        }

        $festival->versions()->createMany([
            ['version_number' => 3, 'notes' => 'CTA mais direto e ajuste de contraste no slide final.', 'status' => 'current', 'created_at' => Carbon::parse('2026-09-28 14:30')],
            ['version_number' => 2, 'notes' => 'Novas fotos e revisão da legenda conforme feedback.', 'status' => 'superseded', 'created_at' => Carbon::parse('2026-09-26 10:15')],
            ['version_number' => 1, 'notes' => 'Primeira proposta criativa para validação.', 'status' => 'superseded', 'created_at' => Carbon::parse('2026-09-23 16:40')],
        ]);

        $assetOne = $festival->assets()->where('position', 1)->first();
        $assetTwo = $festival->assets()->where('position', 2)->first();
        $comment = $festival->comments()->create([
            'content_asset_id' => $assetOne->id, 'author_name' => 'Marina Costa', 'author_role' => 'Cliente',
            'body' => 'A foto está linda. Podemos dar um pouco mais de destaque ao manjericão?',
            'type' => 'specific', 'status' => 'open', 'position_x' => 58.5, 'position_y' => 48.0,
            'created_at' => Carbon::parse('2026-09-28 09:42'),
        ]);
        $comment->replies()->create([
            'content_id' => $festival->id, 'author_name' => 'Bianca Mendes', 'author_role' => 'Agência',
            'body' => 'Sim! Já separei uma versão com esse ajuste para o próximo refinamento.',
            'type' => 'general', 'status' => 'open', 'created_at' => Carbon::parse('2026-09-28 10:08'),
        ]);
        $festival->comments()->create([
            'content_asset_id' => $assetTwo->id, 'author_name' => 'Rafael Lima', 'author_role' => 'Agência',
            'body' => 'Incluímos as três opções mais pedidas para comunicar variedade.',
            'type' => 'specific', 'status' => 'resolved', 'position_x' => 47.0, 'position_y' => 62.0,
            'created_at' => Carbon::parse('2026-09-27 15:20'),
        ]);
        $festival->comments()->create([
            'author_name' => 'Marina Costa', 'author_role' => 'Cliente',
            'body' => 'A sequência ficou muito boa e a legenda está no tom certo.',
            'type' => 'general', 'status' => 'open', 'created_at' => Carbon::parse('2026-09-28 11:35'),
        ]);

        $samples = [
            ['Bastidores da cozinha', 'bastidores-da-cozinha', 'Instagram Reels', 'Vídeo', 'in_review', '2026-10-05 12:00:00', 'Humanizar a marca e valorizar o preparo artesanal.', '/images/festival-massas-03.png'],
            ['Almoço executivo da semana', 'almoco-executivo-da-semana', 'Instagram Feed', 'Post', 'approved', '2026-10-01 11:00:00', 'Aumentar o movimento no almoço durante a semana.', '/images/festival-massas-01.png'],
            ['Noite especial de vinhos', 'noite-especial-de-vinhos', 'Instagram Stories', 'Stories', 'changes_requested', '2026-10-08 18:30:00', 'Divulgar a harmonização especial de quinta-feira.', '/images/festival-massas-03.png'],
            ['Nossa massa, nossa história', 'nossa-massa-nossa-historia', 'Instagram Feed', 'Post', 'published', '2026-09-25 18:00:00', 'Reforçar tradição e reconhecimento de marca.', '/images/festival-massas-02.png'],
            ['Menu de primavera', 'menu-de-primavera', 'Instagram Feed', 'Carrossel', 'pending', '2026-10-12 12:00:00', 'Apresentar os pratos sazonais do novo menu.', '/images/festival-massas-02.png'],
        ];

        foreach ($samples as $sample) {
            $this->createSample($client->id, $sample);
        }
    }

    private function createContent(int $clientId, array $data): Content
    {
        return Content::create(['client_id' => $clientId, ...$data]);
    }

    private function createSample(int $clientId, array $sample): void
    {
        [$title, $slug, $channel, $type, $status, $date, $objective, $image] = $sample;
        $content = $this->createContent($clientId, [
            'title' => $title, 'slug' => $slug, 'channel' => $channel, 'type' => $type,
            'status' => $status, 'publication_date' => $date, 'objective' => $objective,
            'responsible' => 'Bianca Mendes', 'editorial_line' => 'Marca & Experiência',
            'cta' => 'Saiba mais pelo link da bio', 'audience' => 'Clientes e seguidores da região',
            'caption' => 'Conteúdo preparado com carinho para aproximar nossa comunidade dos sabores da Vila.',
            'agency_notes' => 'Peça pronta para revisão do cliente.',
        ]);
        $content->assets()->create(['kind' => 'image', 'url' => $image, 'alt_text' => $title, 'position' => 1]);
        $content->versions()->create(['version_number' => 1, 'notes' => 'Primeira versão enviada para revisão.', 'status' => 'current']);
    }
}
