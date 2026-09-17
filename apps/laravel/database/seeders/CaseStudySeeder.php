<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use Illuminate\Database\Seeder;

class CaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $studies = [
            [
                'titulo'          => 'NovaVet Clinic',
                'descripcion'     => 'Sistema de citas online y gestión para clínica veterinaria con área de cliente e historial médico.',
                'url_demo'        => 'novavet.com.co',
                'categoria'       => 'web',
                'metrica_valor'   => '+120%',
                'metrica_label'   => 'visitas orgánicas en 3 meses',
                'tags'            => ['Laravel', 'Tailwind CSS', 'SEO'],
                'gradient_inicio' => '#4c1d95',
                'gradient_fin'    => '#a855f7',
                'activo'          => true,
                'orden'           => 1,
            ],
            [
                'titulo'          => 'UrbanWear Store',
                'descripcion'     => 'Tienda online de moda urbana con catálogo de +500 productos, carrito y pasarela de pago integrada.',
                'url_demo'        => 'urbanwear.co',
                'categoria'       => 'ecommerce',
                'metrica_valor'   => '$45K',
                'metrica_label'   => 'en ventas el primer mes',
                'tags'            => ['WooCommerce', 'Diseño UI', 'Pagos Online'],
                'gradient_inicio' => '#92400e',
                'gradient_fin'    => '#ef4444',
                'activo'          => true,
                'orden'           => 2,
            ],
            [
                'titulo'          => 'El Rincón del Sabor',
                'descripcion'     => 'Landing page y estrategia digital completa para restaurante premium con reservas online.',
                'url_demo'        => 'rincondelasabor.com',
                'categoria'       => 'social',
                'metrica_valor'   => '+200%',
                'metrica_label'   => 'reservas online en 60 días',
                'tags'            => ['Landing Page', 'Instagram', 'Google Ads'],
                'gradient_inicio' => '#7f1d1d',
                'gradient_fin'    => '#ff6b6b',
                'activo'          => true,
                'orden'           => 3,
            ],
            [
                'titulo'          => 'LegalPro Consultores',
                'descripcion'     => 'Plataforma web corporativa con blog y portal de clientes para firma de abogados especializada.',
                'url_demo'        => 'legalpro.com.co',
                'categoria'       => 'web',
                'metrica_valor'   => '#1',
                'metrica_label'   => 'posición en Google local',
                'tags'            => ['Web Corporativa', 'Blog', 'SEO Local'],
                'gradient_inicio' => '#1e3a5f',
                'gradient_fin'    => '#3b82f6',
                'activo'          => true,
                'orden'           => 4,
            ],
            [
                'titulo'          => 'FitZone Studio',
                'descripcion'     => 'Identidad de marca completa y gestión de redes sociales para estudio de fitness de alta gama.',
                'url_demo'        => 'fitzonefit.com',
                'categoria'       => 'branding',
                'metrica_valor'   => '+800',
                'metrica_label'   => 'seguidores en los primeros 30 días',
                'tags'            => ['Branding', 'Instagram', 'Reels'],
                'gradient_inicio' => '#064e3b',
                'gradient_fin'    => '#10b981',
                'activo'          => true,
                'orden'           => 5,
            ],
            [
                'titulo'          => 'TechVentures',
                'descripcion'     => 'Landing page de alto impacto y pitch deck digital para startup de inteligencia artificial.',
                'url_demo'        => 'techventures.io',
                'categoria'       => 'web',
                'metrica_valor'   => '$120K',
                'metrica_label'   => 'levantados en ronda seed',
                'tags'            => ['React', 'Diseño UI', 'Animaciones'],
                'gradient_inicio' => '#1e1b4b',
                'gradient_fin'    => '#6366f1',
                'activo'          => true,
                'orden'           => 6,
            ],
            [
                'titulo'          => 'Fleur Boutique',
                'descripcion'     => 'Tienda online y branding de lujo para florería boutique con entregas programadas y suscripciones.',
                'url_demo'        => 'fleurboutique.co',
                'categoria'       => 'ecommerce',
                'metrica_valor'   => '3×',
                'metrica_label'   => 'retorno sobre la inversión',
                'tags'            => ['E-commerce', 'Branding', 'Email Marketing'],
                'gradient_inicio' => '#831843',
                'gradient_fin'    => '#f472b6',
                'activo'          => true,
                'orden'           => 7,
            ],
            [
                'titulo'          => 'Atlas Constructora',
                'descripcion'     => 'Identidad corporativa y web con catálogo de proyectos para empresa constructora de mediana escala.',
                'url_demo'        => 'atlasconstructora.co',
                'categoria'       => 'web',
                'metrica_valor'   => '+60',
                'metrica_label'   => 'leads calificados por mes',
                'tags'            => ['Web Corporativa', 'Branding', 'SEO Local'],
                'gradient_inicio' => '#111827',
                'gradient_fin'    => '#6b7280',
                'activo'          => true,
                'orden'           => 8,
            ],
        ];

        foreach ($studies as $data) {
            CaseStudy::create($data);
        }
    }
}
