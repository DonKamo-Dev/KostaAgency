<?php

return [
    'ui' => [
        'breadcrumbs_home' => 'Inicio',
        'breadcrumbs_services' => 'Servicios',
        'request_quote' => 'Solicitar Cotización de :service',
        'view_success_cases' => 'Ver Casos de Éxito',
        'deliverables_eyebrow' => 'Alcance & Soluciones',
        'deliverables_title' => 'Todo lo que incluye nuestro servicio de :service',
        'deliverables_subtitle' => 'Un modelo de entrega estructurado para que obtengas resultados tangibles, sin sorpresas ni costos ocultos.',
        'stack_eyebrow' => 'Stack & Herramientas',
        'stack_title_prefix' => 'Tecnología y estándares',
        'stack_title_highlight' => 'sin compromisos',
        'stack_subtitle' => 'Utilizamos herramientas probadas en la industria para garantizar rendimiento, escalabilidad y propiedad total.',
        'process_eyebrow' => 'Metodología Kosta',
        'process_title_prefix' => 'Cómo trabajamos en',
        'process_subtitle' => 'Un proceso iterativo y transparente de 4 fases para garantizar entregas a tiempo y con la máxima calidad.',
        'related_eyebrow' => 'Casos Reales',
        'related_title_prefix' => 'Resultados en',
        'view_all_projects' => 'Ver todos los proyectos ↗',
        'empty_related_title' => 'Proyectos en producción',
        'empty_related_desc' => 'Estamos finalizando nuevos casos de éxito para este servicio. Conoce nuestras soluciones globales en el portafolio.',
        'explore_full_gallery' => 'Explorar Galería Completa',
        'cta_eyebrow' => 'Siguiente Paso',
        'cta_title_prefix' => '¿Listo para llevar tu',
        'cta_title_suffix' => 'al siguiente nivel?',
        'cta_subtitle' => 'Cuéntanos tu reto. Diseñamos una propuesta clara con tiempos, arquitectura y presupuesto transparente en menos de 24 horas.',
        'cta_start_btn' => 'Iniciar Proyecto de :service',
        'cta_whatsapp_btn' => 'Hablar por WhatsApp ↗',
        'cta_whatsapp_msg' => 'Hola Kosta, me interesa el servicio de :service',
        'other_eyebrow' => 'Soluciones Complementarias',
        'other_title_prefix' => 'Explora otros servicios de',
        'other_learn_more' => 'Conocer servicio',
    ],

    'items' => [
        'desarrollo-web' => [
            'slug' => 'desarrollo-web',
            'title' => 'Desarrollo Web',
            'contact_name' => 'Desarrollo Web',
            'category_key' => 'web',
            'pillar' => '01 · Desarrollo Web',
            'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'headline' => 'Sitios web y plataformas construidas para <span class="text-red-accent">convertir y liderar</span>',
            'subtitle' => 'Desarrollamos arquitectura web a medida con WordPress, React, Laravel y PHP. Combinamos estética visual editorial, código limpio y ultra-velocidad para transformar visitantes en oportunidades reales de negocio.',
            'tags' => ['WordPress', 'React', 'Laravel', 'PHP'],
            'all_tags' => ['WordPress', 'React', 'Laravel', 'PHP', 'Tailwind CSS', 'REST APIs', 'MySQL', 'SEO Técnico', 'Core Web Vitals'],
            'btn_text' => 'Explorar Desarrollo Web',
            'stats' => [
                ['value' => '< 0.8s', 'label' => 'Tiempo de carga promedio'],
                ['value' => '100%', 'label' => 'Propiedad del código y datos'],
                ['value' => '95+', 'label' => 'Score en Google PageSpeed'],
            ],
            'deliverables' => [
                [
                    'title' => 'Sitios Corporativos de Alta Gama',
                    'desc' => 'Portales institucionales que proyectan máxima solidez, autoridad y elegancia, diferenciándote de tu competencia.',
                ],
                [
                    'title' => 'Landing Pages de Alta Conversión',
                    'desc' => 'Estructuradas con psicología de compra, copys orientados a la acción y tiempos de respuesta instantáneos para tus campañas.',
                ],
                [
                    'title' => 'WordPress & Paneles Autogestionables',
                    'desc' => 'Sistemas fáciles de administrar para tu equipo, sin ataduras a constructores lentos o plugins innecesarios.',
                ],
                [
                    'title' => 'Aplicaciones Web & Portales a Medida',
                    'desc' => 'Desarrollo con React y Laravel para modelos de negocio que requieren paneles privados, cotizadores o plataformas internas.',
                ],
            ],
            'process' => [
                ['step' => '01', 'title' => 'Diagnóstico & Arquitectura', 'desc' => 'Mapeamos los objetivos del negocio, el perfil de tus clientes y la estructura óptima de contenidos.'],
                ['step' => '02', 'title' => 'Diseño UX/UI de Alta Fidelidad', 'desc' => 'Diseñamos cada pantalla con jerarquía editorial y prototipado interactivo antes del código.'],
                ['step' => '03', 'title' => 'Desarrollo Frontend & Backend', 'desc' => 'Escribimos código limpio, modular y seguro con WordPress, React, Laravel y PHP.'],
                ['step' => '04', 'title' => 'Auditoría, Velocidad & Go-Live', 'desc' => 'Optimizamos caché, compresión y SEO técnico para un lanzamiento impecable sin interrupciones.'],
            ],
        ],

        'ecommerce' => [
            'slug' => 'ecommerce',
            'title' => 'E-commerce',
            'contact_name' => 'E-commerce',
            'category_key' => 'ecommerce',
            'pillar' => '01 · Desarrollo Web',
            'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
            'headline' => 'Tiendas virtuales creadas para <span class="text-red-accent">vender sin fricción</span>',
            'subtitle' => 'Desarrollamos tiendas online optimizadas para checkout rápido, pasarelas de pago seguras y gestión de inventario fluida con Shopify, WooCommerce y desarrollos custom de alto rendimiento.',
            'tags' => ['Shopify', 'WooCommerce', 'Custom'],
            'all_tags' => ['Shopify', 'WooCommerce', 'Stripe', 'MercadoPago', 'Wompi', 'Analytics 4', 'Checkout Optimizado'],
            'btn_text' => 'Explorar E-commerce',
            'stats' => [
                ['value' => '+45%', 'label' => 'Conversión promedio en checkout'],
                ['value' => '0 Fricción', 'label' => 'Flujo de compra optimizado'],
                ['value' => 'Multi-Pago', 'label' => 'Tarjetas, PSE, Nequi y wallets'],
            ],
            'deliverables' => [
                [
                    'title' => 'Tiendas en Shopify & WooCommerce',
                    'desc' => 'Desarrollo personalizado que supera las plantillas estándar, adaptado al catálogo y operativa de tu marca.',
                ],
                [
                    'title' => 'Pasarelas de Pago Locales & Globales',
                    'desc' => 'Integración completa con Wompi, MercadoPago, Stripe y métodos locales con total seguridad SSL.',
                ],
                [
                    'title' => 'Checkout Ágil & Optimizado',
                    'desc' => 'Diseño de checkout enfocado en reducir carritos abandonados y maximizar el ticket promedio de compra.',
                ],
                [
                    'title' => 'Analítica de Comercio & Tracking',
                    'desc' => 'Conexión precisa con Google Analytics 4, Pixel de Meta y eventos de compra para optimizar pauta.',
                ],
            ],
            'process' => [
                ['step' => '01', 'title' => 'Estrategia de Catálogo', 'desc' => 'Definimos categorías, variantes, pasarelas y la logística adecuada para tu mercado.'],
                ['step' => '02', 'title' => 'Diseño Orientado a Venta', 'desc' => 'Maquetamos fichas de producto de alto impacto visual con gatillos de confianza y claridad.'],
                ['step' => '03', 'title' => 'Configuración & Pagos', 'desc' => 'Programamos pasarelas, reglas de envío, notificaciones automáticas y base de datos.'],
                ['step' => '04', 'title' => 'Pruebas de Compra & Lanzamiento', 'desc' => 'Simulamos compras reales para asegurar transacciones fluidas antes de la apertura pública.'],
            ],
        ],

        'films-contenido' => [
            'slug' => 'films-contenido',
            'title' => 'Films & Contenido',
            'contact_name' => 'Films y Contenido',
            'category_key' => 'social',
            'pillar' => '02 · Films',
            'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664zM21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'headline' => 'Producción audiovisual con <span class="text-red-accent">calidad cinematográfica</span>',
            'subtitle' => 'Una marca que se ve amateur termina compitiendo por precio. Producimos films corporativos, reels estratégicos, piezas de motion graphics y relatos que elevan el valor percibido de tu empresa.',
            'tags' => ['Reels', 'Motion', 'Storytelling'],
            'all_tags' => ['Films 4K', 'Reels', 'Motion Graphics', 'Storytelling', 'Dirección de Arte', 'Color Grading', 'Sound Design'],
            'btn_text' => 'Conocer Producción & Films',
            'stats' => [
                ['value' => 'Cinema 4K', 'label' => 'Equipos y óptica profesional'],
                ['value' => '100% Relato', 'label' => 'Narrativa con propósito de marca'],
                ['value' => 'Multi-Red', 'label' => 'Formatos listos para web y pauta'],
            ],
            'deliverables' => [
                [
                    'title' => 'Brand Films & Videos Corporativos',
                    'desc' => 'Piezas insignia que transmiten el propósito, escala y calidad de tu empresa con estética de cine.',
                ],
                [
                    'title' => 'Reels & Contenido Vertical Estratégico',
                    'desc' => 'Videos dinámicos diseñados para captar atención en los primeros 3 segundos y generar retención real.',
                ],
                [
                    'title' => 'Motion Graphics & Animación',
                    'desc' => 'Animaciones gráficas para explicar productos digitales, servicios complejos o métricas de negocio.',
                ],
                [
                    'title' => 'Creativos de Alto Impacto para Pauta',
                    'desc' => 'Variaciones audiovisuales pensadas específicamente para campañas publicitarias de alto retorno.',
                ],
            ],
            'process' => [
                ['step' => '01', 'title' => 'Guión & Storyboard', 'desc' => 'Construimos el concepto creativo, la narrativa y la escaleta antes del rodaje.'],
                ['step' => '02', 'title' => 'Producción & Rodaje', 'desc' => 'Dirección de fotografía, iluminación, audio profesional y captura con cámaras de cine.'],
                ['step' => '03', 'title' => 'Edición & Color Grading', 'desc' => 'Montaje narrativo, corrección de color cinemática y diseño sonoro envolvente.'],
                ['step' => '04', 'title' => 'Adaptación & Entrega', 'desc' => 'Exportación optimizada en todos los formatos requeridos para web, redes y pauta.'],
            ],
        ],

        'estrategia-digital' => [
            'slug' => 'estrategia-digital',
            'title' => 'Estrategia Digital',
            'contact_name' => 'Estrategia Digital',
            'category_key' => 'web',
            'pillar' => '03 · Estrategia',
            'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
            'headline' => 'Ruta de crecimiento clara para <span class="text-red-accent">escalar con certeza</span>',
            'subtitle' => 'Si cada canal va por su lado, tu negocio también. Diseñamos estrategias digitales integradas que alinean tu web, contenidos, branding y pauta publicitaria hacia un único objetivo de crecimiento comercial.',
            'tags' => ['Strategy', 'Audit', 'Growth'],
            'all_tags' => ['Auditoría Digital', 'Customer Journey', 'Embudos de Venta', 'KPIs & Dashboards', 'Estrategia de Canales'],
            'btn_text' => 'Conocer Estrategia Digital',
            'stats' => [
                ['value' => '360°', 'label' => 'Visión integral del negocio'],
                ['value' => 'Cero Caos', 'label' => 'Canales sincronizados'],
                ['value' => 'Roadmap', 'label' => 'Plan de acción ejecutable'],
            ],
            'deliverables' => [
                [
                    'title' => 'Auditoría Digital Completa',
                    'desc' => 'Evaluamos tu presencia web, velocidad, funnel de ventas y competencia para detectar fugas de ingresos.',
                ],
                [
                    'title' => 'Diseño de Embudo de Ventas',
                    'desc' => 'Estructuramos el viaje del cliente desde el primer contacto hasta el cierre y la recompra.',
                ],
                [
                    'title' => 'Alineación de Canales & Mensaje',
                    'desc' => 'Aseguramos que tu pauta, web y redes comuniquen la misma propuesta de valor contundente.',
                ],
                [
                    'title' => 'Cuadro de Mando & KPIs',
                    'desc' => 'Definición de las métricas que verdaderamente mueven la aguja de tu negocio para tomar decisiones con datos.',
                ],
            ],
            'process' => [
                ['step' => '01', 'title' => 'Inmersión & Diagnóstico', 'desc' => 'Analizamos tus números actuales, oferta comercial y canales de adquisición.'],
                ['step' => '02', 'title' => 'Definición del Plan Estratégico', 'desc' => 'Construimos el mapa de acciones prioritarias con mayor retorno para tu modelo.'],
                ['step' => '03', 'title' => 'Alineación Operativa', 'desc' => 'Configuramos herramientas, audiencias y directrices para los equipos creativos.'],
                ['step' => '04', 'title' => 'Seguimiento & Optimización', 'desc' => 'Revisión periódica de resultados y ajustes continuos para mantener el rumbo.'],
            ],
        ],

        'performance-marketing' => [
            'slug' => 'performance-marketing',
            'title' => 'Performance Marketing',
            'contact_name' => 'Performance Marketing',
            'category_key' => 'social',
            'pillar' => '03 · Estrategia',
            'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
            'headline' => 'Pauta publicitaria guiada por datos con <span class="text-red-accent">retorno medible</span>',
            'subtitle' => 'Sin estrategia, la pauta no es inversión: es gasto. Diseñamos y optimizamos campañas en Meta Ads, Google Ads y TikTok Ads basadas en experimentación rigurosa, audiencias cualificadas y maximización de ROAS.',
            'tags' => ['Meta Ads', 'Google Ads', 'Analytics'],
            'all_tags' => ['Meta Ads', 'Google Search', 'Google Shopping', 'TikTok Ads', 'CAPI Server-Side', 'Optimización de ROAS'],
            'btn_text' => 'Ver Performance & Pauta',
            'stats' => [
                ['value' => 'Data-First', 'label' => 'Decisiones basadas en números'],
                ['value' => 'Server-Side', 'label' => 'Tracking 100% confiable'],
                ['value' => 'Escalable', 'label' => 'Presupuesto optimizado'],
            ],
            'deliverables' => [
                [
                    'title' => 'Campañas en Meta Ads (FB & IG)',
                    'desc' => 'Estructura de prospección y retargeting dinámico para captar clientes en redes sociales.',
                ],
                [
                    'title' => 'Campañas en Google Ads (Search & Shopping)',
                    'desc' => 'Capturamos la intención de compra directa de usuarios que buscan activamente tus soluciones en Google.',
                ],
                [
                    'title' => 'Medición Avanzada & Conversions API',
                    'desc' => 'Implementación de Server-Side Tracking para evitar pérdidas de datos causadas por bloqueadores de cookies.',
                ],
                [
                    'title' => 'Optimización Continua del Retorno (ROAS)',
                    'desc' => 'Monitoreo diario de costo por adquisición (CPA) y reasignación de presupuesto a lo que mejor convierte.',
                ],
            ],
            'process' => [
                ['step' => '01', 'title' => 'Auditoría & Setup de Píxeles', 'desc' => 'Configuramos eventos de conversión y aseguramos la salud del tracking.'],
                ['step' => '02', 'title' => 'Estrategia de Audiencias & Copy', 'desc' => 'Definimos los ángulos de comunicación y segmentos de público más rentables.'],
                ['step' => '03', 'title' => 'Lanzamiento & Testeo A/B', 'desc' => 'Activamos múltiples creativos y copys para identificar a los ganadores.'],
                ['step' => '04', 'title' => 'Escalamiento Progresivo', 'desc' => 'Invertimos más en lo comprobado para escalar el volumen de leads y ventas.'],
            ],
        ],

        'diseno-branding' => [
            'slug' => 'diseno-branding',
            'title' => 'Diseño & Branding',
            'contact_name' => 'Diseño y Branding',
            'category_key' => 'branding',
            'pillar' => '04 · Diseño & Branding',
            'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
            'headline' => 'Identidades visuales que comunican <span class="text-red-accent">calidad y distinción</span>',
            'subtitle' => 'Una marca que se ve amateur termina compitiendo por precio. Construimos identidades profesionales, coherentes y memorables: logotipos, paletas de color, tipografía y manuales de marca listos para crecer.',
            'tags' => ['Logo', 'Identidad', 'Brand Guidelines'],
            'all_tags' => ['Identidad Visual', 'Logotipos', 'Manual de Marca', 'Tipografía Curada', 'Diseño de Empaques', 'Sistemas UI'],
            'btn_text' => 'Explorar Diseño & Branding',
            'stats' => [
                ['value' => '100% Único', 'label' => 'Sin plantillas genéricas'],
                ['value' => 'Manual Pro', 'label' => 'Reglas claras de aplicación'],
                ['value' => 'Escalable', 'label' => 'Listo para digital y físico'],
            ],
            'deliverables' => [
                [
                    'title' => 'Diseño de Logotipo & Sistema Visual',
                    'desc' => 'Símbolo principal, variaciones responsivas, paleta cromática y selección tipográfica premium.',
                ],
                [
                    'title' => 'Manual de Identidad de Marca',
                    'desc' => 'Documento normativo con reglas claras de uso, proporciones, versiones correctas e incorrectas.',
                ],
                [
                    'title' => 'Papelería & Aplicaciones Digitales',
                    'desc' => 'Diseño de tarjetas, firmas de correo, plantillas para redes y piezas corporativas uniformes.',
                ],
                [
                    'title' => 'Assets Listos para Producción',
                    'desc' => 'Archivos vectoriales (SVG, AI, PDF) y formatos ligeros (PNG, WebP) listos para cualquier uso.',
                ],
            ],
            'process' => [
                ['step' => '01', 'title' => 'Briefing & Moodboard', 'desc' => 'Investigamos la personalidad de tu marca, referencias visuales y territorio de diseño.'],
                ['step' => '02', 'title' => 'Exploración Conceptual', 'desc' => 'Desarrollamos rutas creativas sólidas con justificación estratégica y visual.'],
                ['step' => '03', 'title' => 'Refinamiento & Feedback', 'desc' => 'Pulimos la dirección seleccionada hasta alcanzar precisión geométrica y tipográfica.'],
                ['step' => '04', 'title' => 'Documentación & Entrega', 'desc' => 'Generamos el manual de marca y el paquete completo de archivos fuente.'],
            ],
        ],
    ],
];
