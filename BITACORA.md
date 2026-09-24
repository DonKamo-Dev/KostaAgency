# Bitácora del Proyecto Kamo Platform

## Propósito

Kamo Platform es una plataforma SaaS para la gestión operativa y financiera de una agencia digital. Centraliza clientes, servicios, cotizaciones, cuentas de cobro, facturas, pagos, gastos y casos de estudio.

## Estado actual

- Stack: Laravel 13, Livewire 3, Tailwind CSS, MySQL 8 y Docker.
- Interfaz: tema oscuro con acento coral, navegación lateral en escritorio y navegación inferior en móvil.
- Acceso local: `http://localhost:8000` mediante `docker-compose up -d`.

## Módulos disponibles

| Módulo | Estado | Alcance |
| --- | --- | --- |
| Dashboard | Disponible | KPIs financieros, actividad reciente y métricas comerciales. |
| Clientes | Disponible | Creación, edición, búsqueda y eliminación controlada. |
| Servicios | Disponible | Catálogo de servicios y precios unitarios. |
| Cotizaciones | Disponible | Creación, edición, duplicación, cancelación y conversión a factura. |
| Cuentas de cobro | Disponible | Gestión de documentos de cobro y registro de pagos. |
| Facturas | Disponible | Creación, edición, anulación, PDF y registro de pagos. |
| Gastos | Disponible | Registro por fecha, categoría y monto. |
| Casos de estudio | Disponible | Administración del portafolio público. |

## Pendientes

Ordenados según el plan [2026-09-17-cierre-pendientes-plataforma](docs/superpowers/plans/2026-09-17-cierre-pendientes-plataforma.md):

| # | Pendiente | Fase | Estado |
| --- | --- | --- | --- |
| 1 | Repositorio Git inexistente | Fase 1 | Completada (remoto `origin` en `main`) |
| 2 | Código muerto residual de tenancy/roles | Fase 2 | Completada (commit `b48b7c7`) |
| 3 | Generación de Meta Ads síncrona (sin job en cola) | Retirado | Módulo eliminado a solicitud del usuario |
| 4 | Reporte de auditoría final `docs/audits/2026-08-23-platform-remediation.md` | Fase 4 | Completada (commit `399089b`) |
| 5 | Planes/documentación desincronizados | Fase 5 | Completada (commit de cierre) |
| 6 | Jerarquía editorial landing (Web → Films → Estrategia → Branding) | Landing | Completada |

### 2026-09-24 - Transición de Categoría 'Social' a 'Films & Reels' y Anclaje Dinámico a Empresa o Sitio Web

- **Requerimiento:**
  - Sustituir la categoría `social` ("Redes Sociales") por `films` ("Films & Reels") en casos de estudio y portafolio público para destacar reels y producciones audiovisuales.
  - Implementar anclaje de proyectos de films con dos opciones: vincular a una **Empresa** registrada (cliente con NIT) o a un **Sitio Web** existente en la plataforma.
- **Implementación y Solución:**
  - **Base de Datos y Modelos:**
    - Creada migración `database/migrations/2026_09_24_000002_add_source_links_to_case_studies.php` añadiendo `source_type` (varchar 20), `client_id` (foreignId nullOnDelete) y `linked_case_study_id` (foreignId nullOnDelete) a `case_studies`, y migrando registros existentes de `social` a `films`.
    - Clase de soporte resiliente `App\Support\CaseStudySource::ensureSchema()` para garantizar columnas y sincronización automática en entornos serverless (TiDB Cloud / Vercel), con caché estática y chequeo condicional dinámico en `scopeForDisplay()` y `PublicCaseStudies::active()` para prevenir errores de columnas no existentes.
    - Modelo `CaseStudy` actualizado con `$fillable`, relaciones `client()` y `linkedCaseStudy()`, y `scopeForDisplay()` con selección defensiva de columnas.
  - **Formulario Administrativo (`CaseStudies\Form` & `form.blade.php`):**
    - Opción `films` ("Films & Reels") incorporada al selector de categorías personalizado con acento rose/crimson (`#f43f5e`).
    - Al seleccionar `films`, se despliega automáticamente el panel interactivo "Anclar Caso de Films" con selector tipo pastilla: **Empresa (Cliente)** vs **Sitio Web**.
    - Al elegir Empresa, se carga el selector de clientes con su nombre y NIT (`tax_id`).
    - Al elegir Sitio Web, se carga el selector de sitios web existentes con su título y URL demo (autovinculando la demo si no se especifica una manual).
  - **Listado Administrativo (`CaseStudies\Index` & `index.blade.php`):**
    - Pestaña y badges actualizados a `Films & Reels`.
    - Visualización del anclaje (empresa con NIT o sitio web vinculado) directamente en la tabla de casos.
    - Menú lateral del panel con acceso directo a `Films & Reels`.
  - **Portafolio Público (`portfolio.blade.php` y `services.blade.php`):**
    - Pestaña de filtrado `Films` en `/portafolio` con soporte bidireccional (`films`/`social`).
    - Tarjeta de proyecto con badge institucional y mención del cliente o sitio web vinculado.
    - Manejo defensivo con `($estudio->source_type ?? null)` para renderizado seguro en cualquier estado de base de datos.
    - Botones de acción contextualizados ("Ver film" / "Watch film").
    - Diccionarios en español e inglés (`lang/es/landing.php`, `lang/en/landing.php`, `lang/es/services.php`, `lang/en/services.php`) actualizados.
  - **Control de Calidad y Pruebas:**
    - 108 de 108 pruebas superadas (442 aserciones) en PHPUnit, incluyendo tests dedicados para anclaje a cliente y a sitio web.
    - Build de producción con Vite (`npm run build`) validado en 10.1s.

### 2026-09-23 - Soporte Bilingüe (ES/EN) y Corrección de Responsividad Móvil en Página Bento / CV (/kamo)

- **Requerimiento:**
  - Agregar soporte bilingüe completo (Español e Inglés) a la página interactiva Bento / CV (`/kamo`, `/cv`, `/perfil`) con selector de idioma interactivo `[ ES | EN ]`.
  - Corregir el descuadre y desbordamiento de la barra superior en dispositivos móviles señalado en capturas de pantalla.
- **Causa Raíz de Descuadre Móvil:**
  - En desktop, los componentes de la barra (`[← Volver a Kosta]`, pastilla `[• kosta.studio/kamo]`, botón de tema `[☼ Claro]` y botón `[Contactar ↗]`) sumaban más de 535px de ancho fijo. En anchos de pantalla móvil (320px a 414px), los elementos colisionaban y generaban scroll horizontal y desajuste visual.
- **Implementación y Solución:**
  - **Diccionarios Bilingües Dedicados:** Creados `lang/es/bento.php` y `lang/en/bento.php` cubriendo metadatos SEO (`<title>`, `<meta name="description">`), biografía, tags de estado, proyectos recientes, métricas/hitos, enlaces sociales, horario/zona horaria, tarjeta de disponibilidad/radar, stack tecnológico, propuesta de valor, botón de WhatsApp, avisos dinámicos de copiado de email y tooltips de modo Claro/Oscuro.
  - **Selector de Idioma `[ ES | EN ]`:** Integrado en la barra superior con variables CSS del sistema Bento (`--bento-surface`, `--bento-border`, `--bento-primary-btn-bg`), adaptándose automáticamente tanto en modo Claro como Oscuro.
  - **Responsividad Móvil de la Barra Superior:**
    - En pantallas `<= 640px`, la pastilla URL decorativa `.bento-url-pill` se oculta (`display: none;`).
    - El botón de cambio de tema `.btn-theme-toggle` pasa a modo solo-ícono en móvil, ocultando el texto (`.theme-label`).
    - El botón de volver adapta su texto según viewport: *"Volver a Kosta"* / *"Back to Kosta"* en desktop y *"Volver"* / *"Back"* en móvil mediante clases responsive.
    - El ancho total de la barra se redujo de ~535px a ~250px en móvil, permitiendo una convivencia espaciosa y sin desbordamiento desde pantallas de 320px.
    - Cuadrícula de enlaces sociales adaptada a 1 columna en pantallas `< 500px`.
  - **Control de Calidad y Pruebas:**
    - Se incorporaron pruebas específicas en `tests/Feature/LocalizationTest.php` comprobando la traducción exacta en español e inglés en `/kamo`.
    - 99 de 99 pruebas del proyecto aprobadas sin fallos (392 aserciones en verde).
  - **Despliegue Vercel:**
    - Build de Vite generado limpiamente (`npm run build`).
    - Cambios desplegados y comprobados en vivo en producción: `https://kosta-agency.vercel.app/kamo` y `https://kosta-agency.vercel.app/locale/en`.

### 2026-09-23 - Soporte Bilingüe Español / Inglés en Vistas Públicas de Kosta y Despliegue en Vercel

- **Requerimiento:**
  - Permitir a los usuarios navegar e interactuar en español o inglés en todas las páginas públicas de Kosta Agency (`/`, `/portafolio`, `/contacto`, `/servicios/{slug}`, `/privacidad`), manteniendo el panel administrativo (`/dashboard`) exclusivamente en español.
- **Implementación:**
  - Creado el middleware `App\Http\Middleware\SetLocale` registrado en `bootstrap/app.php` con fallback automático a `config('app.locale', 'es')`.
  - Ruta `/locale/{locale}` que actualiza la sesión y asigna una cookie permanente de 1 año (`locale`), redirigiendo a la página previa.
  - Diccionarios completos de traducción en `lang/es/` y `lang/en/` (`landing.php`, `services.php`).
  - Selector glass segmentado `[ ES | EN ]` en la barra de navegación de escritorio, drawer móvil y footer.
  - Componente Livewire `ServiceDetail` dinámico a partir de `trans('services.items')` y `Contact` con mensajes de validación internacionalizados.
  - Suite de 9 pruebas dedicadas en `LocalizationTest.php` y 97/97 tests pasando en la aplicación.
  - Desplegado y verificado en vivo en Vercel (`https://kosta-agency.vercel.app/` y `/locale/en`).

### 2026-09-23 - Sincronización de Casos de Estudio a TiDB Cloud, Soporte de Imágenes en Vercel y URL Bento a kosta.studio/kamo

- **Causa Diagnosticada:**
  - Los casos de estudio creados localmente se guardaban en la base de datos MySQL local de Docker (`kamo_laravel`), mientras que Vercel se conecta a la base remota de TiDB Cloud Serverless (`gateway01.us-east-1.prod.aws.tidbcloud.com`), la cual aún no tenía registros en la tabla `case_studies`.
  - Las imágenes subidas se guardaban en `storage/app/public/case-studies/`, ignoradas por `.gitignore` y con un symlink junction local que no se despliega en el runtime serverless de Vercel.
- **Solución Implementada:**
  - Se sincronizaron los 8 casos de estudio creados localmente (CLASSY CARTAGENA, CARTAGENA ENGLISH SPEAKING DENTIST, COMPULAGO, FIAO, SIT, LaLizas, Marine Center, HB Mantenimiento) directamente a la tabla `case_studies` en TiDB Cloud.
  - En `bento.blade.php`, se actualizaron las menciones a `kosta.studio/kamo`, `kosta.studio/portafolio` y `Volver a Kosta`.
  - Se copiaron y habilitaron para Git las imágenes de portada en `public/storage/case-studies/` y `storage/app/public/case-studies/`.
  - En `routes/web.php`, se añadió una ruta de respaldo resiliente `Route::get('/storage/{path}')` que sirve archivos estáticos tanto desde `public/storage/` como desde `storage/app/public/`.
  - En `CaseStudies/Form.php`, se añadió duplicación automática de subida hacia `public/storage/case-studies/` para compatibilidad local y de producción.
  - En `config/database.php`, se condicionó el certificado SSL CA para que no intente usar SSL obligatorio en conexiones locales a contenedores Docker (`db`, `127.0.0.1`).

### 2026-09-22 - Corrección de Error 500 en Vercel: Compatibilidad con PHP 8.5 y Enrutamiento Serverless

- **Causa Raíz Diagnosticada:**
  - Vercel compila con PHP 8.5, versión en la cual la constante `PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT` fue deprecada en favor de `Pdo\Mysql::ATTR_SSL_VERIFY_SERVER_CERT`. El aviso de deprecación impreso antes del envío de cabeceras HTTP corrompía la respuesta en rutas con base de datos (`/login`, `/portafolio`), disparando un error 500.
  - Además, las reglas iniciales de `rewrites` en `vercel.json` colisionaban con el archivo `public/index.php` al definir `outputDirectory: public`.
- **Solución Implementada:**
  - En `config/database.php`, se condicionó `MYSQL_ATTR_SSL_VERIFY_SERVER_CERT` y `MYSQL_ATTR_SSL_CA` con `PHP_VERSION_ID >= 80500 ? Mysql::ATTR_* : PDO::ATTR_*` y detección automática del certificado CA del sistema (`/etc/ssl/certs/ca-certificates.crt`).
  - En `api/index.php`, se suprimió la salida visual de avisos de deprecación (`error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED)` y `display_errors: 0`).
  - En `bootstrap/app.php` y `AppServiceProvider.php`, se configuró `trustProxies(at: '*')` y `URL::forceScheme('https')` para garantizar URLs seguras sin contenido mixto.
  - En `vercel.json`, se migraron las directivas a `routes` jerárquicas garantizando que las peticiones se deleguen al runtime serverless `api/index.php`.
- **Verificación en Vivo:**
  - Comprobado en vivo: `https://kosta-agency.vercel.app/` (200 OK), `https://kosta-agency.vercel.app/login` (200 OK con formulario de acceso completo) y `https://kosta-agency.vercel.app/portafolio` (200 OK con conexión a TiDB Cloud activa).

### 2026-09-22 - Configuración de Despliegue Serverless para Vercel

- **Configuración de Vercel (`vercel.json` y `api/index.php`):**
  - Se configuró el runtime serverless de PHP (`vercel-php@0.9.0`) para gestionar las peticiones a Laravel a través del puente `apps/laravel/api/index.php`.
  - Se implementó la inicialización dinámica de directorios en el sistema de archivos efímero (`/tmp/views`, `/tmp/storage/...`) para mitigar las restricciones de solo lectura de AWS Lambda / Vercel.
  - Se enrutaron los archivos estáticos de Vite (`/build/(.*)` -> `/public/build/$1`), favicon, robots y assets públicos.
  - En `bootstrap/app.php`, se activó `$app->useStoragePath('/tmp/storage')` de manera condicional al detectar el entorno `VERCEL_ENV`.
  - Se agregó `.vercelignore` para evitar subir tests, archivos de Docker y temporales locales.
- **Control de Calidad:**
  - Build de Vite exitoso (`npm run build`).
  - Laravel Pint superado (120/120 archivos aprobados).
  - Pruebas automatizadas en verde (3/3 tests, 23 aserciones).

### 2026-09-22 - Integración de Header y Footer Idénticos en Páginas Internas de Servicios (/servicios/{slug})

- **Header Global Flotante (`partials.landing-nav`):**
  - Se integró la barra de navegación flotante con estética rojo vino/borgoña, logotipo Kosta, enlaces principales, botón *"Empezar proyecto ↗"*, enlace a WhatsApp oficial y drawer móvil interactivo con hamburguesa animada en `service-detail.blade.php`.
  - Se añadió detección activa para `request()->is('servicios*')` tanto en escritorio como en móvil, resaltando visualmente el ítem de navegación "Servicios".
  - Se incorporaron las orbes de fondo resplandecientes (`bg-red-accent`, `var(--accent-red-dark)`) y la trama sutil de cuadrícula (`grid-pattern`).
- **Footer Global (`partials.landing-footer`):**
  - Se integró el pie de página institucional en `service-detail.blade.php`, con el imagotipo Kosta, copyright oficial (`© 2026 Kosta Studio Films`) y enlaces de pie de página incluyendo "Servicios".
  - Se incluyó el script de control de navegación interactiva y scroll suave (`partials.landing-nav-js`).
- **Control de Calidad y Pruebas:**
  - Se agregaron aserciones en `ServiceDetailTest.php` comprobando la presencia del navbar flotante, CTA de proyecto, copyright y firma Kosta Studio Films (3/3 tests, 23 aserciones en verde).
  - Laravel Pint validado sin errores (`119/119 passed`).
  - Verificación visual completa mediante subagente de navegador en `http://localhost:8000/servicios/desarrollo-web`, confirmando el renderizado nítido del header y footer.

### 2026-09-22 - Actualización de Tags Tech y Páginas Internas Dedicadas por Servicio (/servicios/{slug})

- **Actualización de Etiquetas Tecnológicas en Desarrollo Web (`index.blade.php`):**
  - Se sustituyeron las 3 etiquetas originales (`Next.js`, `Laravel`, `Headless CMS`) en la tarjeta de Desarrollo Web por: **WordPress**, **React**, **Laravel** y **PHP**.
- **Llamadas a la Acción Atractivas en Tarjetas de Servicio:**
  - Se incorporó un botón de acción estilizado con micro-animaciones en hover (`.service-action-btn`) en cada una de las 6 tarjetas de servicio de la landing principal:
    - *Desarrollo Web:* `Explorar Desarrollo Web ↗`
    - *E-commerce:* `Explorar E-commerce ↗`
    - *Films & Contenido:* `Conocer Producción & Films ↗`
    - *Estrategia Digital:* `Conocer Estrategia Digital ↗`
    - *Performance Marketing:* `Ver Performance & Pauta ↗`
    - *Diseño & Branding:* `Explorar Diseño & Branding ↗`
  - Cada botón enlaza mediante navegación SPA instantánea (`wire:navigate`) a su página interna dedicada.
- **Módulo de Detalle de Servicio Público (`/servicios/{slug}`):**
  - **Componente Livewire:** `App\Livewire\Landing\ServiceDetail` (`apps/laravel/app/Livewire/Landing/ServiceDetail.php`) con catálogo estructurado para los 6 servicios (`desarrollo-web`, `ecommerce`, `films-contenido`, `estrategia-digital`, `performance-marketing`, `diseno-branding`).
  - **Vista Detallada de Alto Impacto (`service-detail.blade.php`):**
    - Migas de pan (Breadcrumb) y Badge de pilar editorial (`01 · DESARROLLO WEB`, etc.).
    - Hero principal con título tipográfico `Syne`, descripción de alcance y 3 métricas de impacto clave.
    - Rejilla de 4 entregables y beneficios de alto valor con fondo obsidiana y bordes glassmorphism.
    - Nube de tecnologías y herramientas aplicadas.
    - Proceso paso a paso en 4 etapas progresivas con línea de tiempo y acentos en rojo borgoña.
    - Sección de Casos de Éxito relacionados obtenidos dinámicamente de la base de datos con fallback elegante.
    - Banner de conversión inferior con botón directo a `/contacto?servicio=...`.
    - Selector rápido de otros servicios para favorecer la navegación y el SEO interno.
- **Preselección Reactiva en Formulario de Contacto (`Landing/Contact.php`):**
  - Se agregó el método `mount()` en el componente de Contacto para detectar el parámetro `?servicio=...` y preseleccionar automáticamente el servicio de interés del visitante.
- **Control de Calidad y Pruebas:**
  - Suite de pruebas automatizadas en `ServiceDetailTest.php` (3 pruebas, 19 aserciones superadas).
  - Formateo de código y estilo con Laravel Pint (119/119 archivos aprobados).
  - Inspección visual interactiva en navegador verificando la landing, los tags, la animación del botón y el renderizado completo de la página de servicio.

### 2026-09-22 - Selector de Categorías Estilizado (Custom Select) y Nueva Opción 'Sistema'

- **Selector personalizado moderno (Custom Select con Alpine.js):**
  - Se sustituyó el elemento nativo `<select>` (cuyo menú emergente del sistema operativo era gris, rectangular y sin esquinas redondeadas) por un componente de selección personalizado y estilizado.
  - El nuevo trigger y el menú desplegable cuentan con esquinas redondeadas (`12px` y `14px`), fondo oscuro obsidian (`#141416`), desenfoque glassmorphism (`backdrop-filter: blur(24px)`), flecha chevron animada con rotación suave y sombras de profundidad.
  - Cada opción incluye bordes redondeados (`10px`), indicador de color luminoso con resplandor suave, título destacado, descripción de alcance y checkmark de estado activo.
- **Incorporación de la categoría 'Sistema':**
  - Se añadió la opción **Sistema** (`sistema`) orientada a plataformas SaaS, software a medida y paneles de control.
  - Se configuró con acento celeste/cian (`#38bdf8` / `rgba(14,165,233,0.15)`), badge distintivo y llamada a la acción contextualizada *"Ver sistema ↗"*.
  - Soporte integrado en reglas de validación de backend (`Form.php` e `Index.php`), listado administrativo (`index.blade.php`), vista previa en tiempo real y portafolio público (`portfolio.blade.php`) con pestaña de filtrado "Sistemas".
- **Control de Calidad y Pruebas:**
  - Nueva prueba automatizada `test_admin_can_create_case_study_with_sistema_category` en `CaseStudyTest.php` (5/5 tests pasados en verde).
  - Laravel Pint validado (118/118 archivos superados).
  - Inspección visual interactiva en navegador confirmando el despliegue del menú y la reactividad de la vista previa en vivo.

### 2026-09-22 - Corrección de Estado de Carga (wire:loading) en Botones de Formularios y Acciones Masivas

- **Causa raíz identificada:** El layout administrativo (`components/layouts/app.blade.php`) incluía `@livewireScripts` pero omitía `@livewireStyles`. Sin los estilos base de Livewire ni reglas de ocultación para `[wire:loading]`, y debido a estilos en línea con `display: inline-flex` en elementos `wire:loading`, el navegador representaba simultáneamente el texto de acción ("Guardar Cambios" / "Crear Caso de Estudio") y el estado de carga ("Guardando proyecto...").
- **Solución integral aplicada:**
  - **Inyección de estilos y prevención de FOUC:** Se agregó `@livewireStyles` y la regla de protección `[wire:loading], [wire:loading.*] { display: none; }` en el `<head>` de `components/layouts/app.blade.php`.
  - **Estructuración en `case-studies/form.blade.php`:** Se desacopló la propiedad de visualización del contenedor `wire:loading`, anidando el layout flex en un sub-elemento para que Livewire controle la visibilidad sin colisión de especificidad CSS.
  - **Blindaje en acciones masivas (`case-studies/index.blade.php`):** Se aplicó el mismo patrón en los botones "Hacer Visibles", "Ocultar" y "Borrar seleccionados" de la vista de listado.
- **Verificación:** Inspección interactiva y capturas de pantalla en navegador en rutas de creación (`/case-studies/create`) y edición (`/case-studies/1/edit`), confirmando que solo se muestra el texto correspondiente en reposo y el spinner únicamente durante el guardado. Pint validado (118/118 archivos OK).

### 2026-09-22 - Corrección de Contraste en Botones Primarios (Portafolio y Contacto)

- **Botón 'Visitar página' en Tarjetas de Portafolio (`portfolio.blade.php`):**
  - Se corrigió el estilo de `.project-link.visit` para evitar texto blanco sobre fondo blanco (`color: #09090B` sobre `background: #FFFFFF` con borde `#FFFFFF` y hover suave `#E4E4E7`).
  - Ahora el texto *"Visitar página ↗"* (o *"Visitar red ↗"*) es 100% visible, nítido y con contraste accesible.
- **Botón de Envío en Contacto (`contact.blade.php`):**
  - Se ajustó `.btn-submit` y el botón de retorno en pantalla de éxito para mantener texto oscuro (`#000000`) sobre fondo blanco, asegurando legibilidad uniforme.
- **Control de Calidad:** Validado con Laravel Pint (118/118 archivos aprobados) e inspección visual en navegador.

### 2026-09-21 - Optimización del Ritmo Vertical y Reducción de Espacios Entre Secciones

- **Tokens de Espaciado Global (`landing.blade.php`):**
  - Se redujo `--section-spacing` de `140px` a `64px` en escritorio (reduciendo la brecha acumulada entre secciones contiguas de 280px a 128px).
  - Se redujo `--section-spacing-mobile` de `96px` a `44px` en móviles (reduciendo la brecha de 192px a 88px).
- **Márgenes de Cabecera Internos (`index.blade.php`):**
  - Se calibraron los márgenes inferiores de los títulos y textos introductorios en cada sección (`servicios`, `diferencia`, `proceso`, `testimonios` y `contacto`) de `mb-20 sm:mb-24/28` a `mb-12 sm:mb-16`.
  - El resultado es una navegación mucho más dinámica, cohesiva y continua sin vacíos excesivos de scroll.
- **Control de Calidad:** Validado con Laravel Pint (118/118 archivos superados) e inspección visual en vivo con el subagente de navegador.

### 2026-09-21 - Comparativa: Estilo Rojo Vino (Navbar), Inversión de Tarjetas y Espaciado Ampliado

- **Paleta y Tonalidad (Rojo Vino / Burgundy del Navbar):**
  - Se sustituyó el gradiente amarillo/ámbar de la tarjeta "Con Kosta" por el elegante rojo vino/borgoña de la barra de navegación superior (`rgba(46, 17, 23, 0.98)` a `rgba(14, 8, 10, 0.99)` con brillo radial carmesí `rgba(230, 57, 70, 0.32)` y borde sutil `rgba(230, 57, 70, 0.38)`).
  - Se adaptaron los elementos internos: badge `✨ Kosta Studio` en vidrio carmesí, titular en blanco puro, subtítulo en blanco atenuado y micro-badges de verificación `✓` en rojo carmesí (`#FF5A68`).
- **Inversión de Posiciones:**
  - **Lado Izquierdo:** Tarjeta **"Sin Kosta"** (estilo oscuro grafito con iconos `✕` y puntos de dolor).
  - **Lado Derecho:** Tarjeta **"Con Kosta"** (estilo rojo vino con iconos `✓` y propuesta integral).
- **Espaciado Superior Ampliado:**
  - Se incrementó la separación entre el párrafo superior descriptivo y las tarjetas a `mb-20 sm:mb-28` junto con `pt-4 sm:pt-6` en la rejilla para otorgar un flujo visual amplio y despejado.
  - Se configuró `scroll-margin-top: 110px` en la sección `#diferencia` para evitar solapamientos con el navbar flotante al navegar por ancla.
- **Control de Calidad:** Validado con Laravel Pint (118/118 archivos aprobados) e inspección visual en navegador.

### 2026-09-21 - Actualización de Copy Comercial en la Comparativa (Con Kosta vs Sin Kosta)

- **Actualización de Textos de Alto Impacto:**
  - **Tarjeta Izquierda (`Con Kosta`):**
    - *Presencia Digital:* Hacemos que tu negocio exista donde tus clientes realmente te buscan.
    - *Redes & Contenido:* Creamos contenido con intención: pensado para atraer, posicionar y convertir.
    - *Estrategia & Pauta:* Diseñamos campañas con estrategia, seguimiento y optimización real.
    - *Diseño & Branding:* Construimos una identidad profesional, coherente y fácil de reconocer.
    - *Crecimiento Digital:* Conectamos branding, contenido, web y pauta para que todo trabaje hacia el mismo objetivo.
  - **Tarjeta Derecha (`Sin Kosta`):**
    - *Presencia Digital:* Google no sabe quién eres. Tus clientes tampoco.
    - *Redes & Contenido:* Tener Instagram no significa tener presencia digital.
    - *Estrategia & Pauta:* Sin estrategia, la pauta no es inversión. Es gasto.
    - *Diseño & Branding:* Una marca que se ve amateur termina compitiendo por precio.
    - *Crecimiento Digital:* Si cada canal va por su lado, tu negocio también.
- **Auditoría y Definición Tipográfica:**
  - **Títulos y Encabezados (`h1`, `h2`, `h3`, `.font-display`):** `Syne` (Google Fonts, pesos 400 a 800).
  - **Cuerpo, Párrafos y Listas (`body`, `p`, `.font-body`, `.comparison-item-*`):** `Inter` (Google Fonts, pesos 300 a 700).
- **Control de Calidad:** Validado con Laravel Pint (118/118 archivos aprobados) e inspección con subagente de navegador.

### 2026-09-21 - Sección de Alto Impacto: "Sin Kosta Studio vs Con Kosta Studio" en la Landing Principal (/)

- **Componente de Comparación Integral:** Se implementó una nueva sección (`#diferencia`) ubicada estratégicamente entre *Servicios* y *Proceso* para contrastar los 4 servicios de Kosta Studio frente a agencias tradicionales:
  - **Tarjeta "Sin Kosta Studio":** Tarjeta oscura obsidiana con badge `🔒 Sin Kosta Studio`, titular *"Servicios fragmentados y ataduras"* y 5 puntos de dolor con iconos `✕` abarcando Desarrollo Web (editores cerrados y código rehén), Films & Contenido (video de stock y sin másters), Estrategia & Pauta (presupuesto quemado a ciegas y cuentas ajenas), Diseño & Branding (logos genéricos de plantilla) y Gestión (4 proveedores desconectados).
  - **Tarjeta "Con Kosta Studio":** Tarjeta destacada en gradiente ámbar/dorado de alto contraste con badge `✨ Con Kosta Studio`, titular *"Ecosistema completo y propiedad total"* y 5 ventajas con iconos `✓` cubriendo Desarrollo Web (código 100% en GitHub propio y libertad de servidor), Films & Contenido (producción audiovisual original y entrega de editables), Estrategia & Pauta (campañas en Meta y Google Ads optimizadas por ROI en tus cuentas), Diseño & Branding (sistemas de marca escalables y UI/UX a medida) y Gestión Integral con IA (un solo equipo ágil multidisciplinario).
- **Diseño Responsivo:** Rejilla fluida de 2 columnas en escritorio y 1 columna en móviles, con micro-animaciones en hover y tipografía `Syne`.
- **Verificación:** Validado con renderizado en navegador y pruebas de estilo con Laravel Pint.

- **Esquinas estilizadas (no tan redondas):** Se redujeron radicalmente los radios de curvatura excesivos (de 26px / 22px / 999px) a esquinas arquitectónicas modernas:
  - Tarjetas y contenedores: `12px` (`--radius-card`).
  - Botones y acciones: `8px` (`--radius-btn`).
  - Etiquetas, pills y badges: `6px` (`--radius-badge`).
  - Marco del avatar de perfil: `16px` (`--radius-avatar`), sustituyendo el círculo burbuja flotante por un marco tech estructurado.
- **Paleta 100% monocromática blanco y negro:**
  - Se eliminaron por completo todos los acentos y fondos rojizos/corales (`#E63946`, `#FECDD3`, `#FFF1F2`).
  - Sistema basado estrictamente en negro obsidiana (`#09090B`), gris carbón (`#121214`), bordes sutiles en zinc (`rgba(255,255,255,0.08)` / `rgba(0,0,0,0.08)`) y blanco puro con alto contraste.
  - Iconos técnicos y enlaces sociales adaptados a monocromo de alta fidelidad.
- **Modo oscuro nativo con conmutador claro/oscuro (Dark Mode por defecto):**
  - Se implementó hidratación temprana antes del primer pintado en el layout `resources/views/layouts/bento.blade.php` para evitar FOUC.
  - Se integró un botón interactivo en la barra superior con iconos dinámicos de Sol/Luna y persistencia en `localStorage`.
- **Verificación visual:** Comprobación interactiva en navegador en ambos estados (Dark y Light Mode).

### 2026-09-21 - Tipografía de alto impacto (Syne) en perfil Bento personal (/kamo)

- **Sustitución de Outfit por Syne:** Se reemplazó la fuente `Outfit` por `Syne` (pesos 600, 700 y 800) en los titulares, cabeceras de tarjetas y números destacados en `resources/views/layouts/bento.blade.php` y `resources/views/livewire/landing/bento.blade.php`.
- **Identidad unificada con Kosta:** Al utilizar `Syne`, la marca personal de Yohan Blanco se alinea estéticamente con el lenguaje de diseño editorial de vanguardia de la landing de Kosta.
- **Jerarquía y legibilidad mejorada:**
  - El nombre **Yohan Blanco ⚡** se calibró a 28px con peso 800 y `letter-spacing: -0.03em` para que respire en una sola línea dentro de la columna de bio sin desbordarse.
  - El rol se transformó en un badge pill estilizado (`WORDPRESS DEVELOPER & DIGITAL MARKETING`) con fondo coral sutil, borde y espaciado en mayúsculas.
  - Los títulos de las tarjetas del mosaico (*Últimos Proyectos*, *Proyectos & Métricas*, *Conectar*, etc.) ganaron peso, nitidez y carácter de agencia moderna.
- **Verificación:** Comprobado mediante renderizado en navegador a través de subagente.

### 2026-09-21 - Optimización de Casos de Estudio y estado vacío limpio

- **Eliminación del indicador de carga engorroso:** Se removió la fila `<tr wire:loading>` con `<thinking-orb>` en `resources/views/livewire/case-studies/index.blade.php` que se mostraba en el render inicial y colisionaba con el estado vacío. Ahora se utiliza una transición suave de opacidad en el `<tbody>` (`wire:loading.class="opacity-60"`) sin saltos de maquetación ni elementos atascados.
- **Purga total de datos de prueba:** Se vació la tabla `case_studies` en MySQL a solicitud del usuario, asegurando que la base de datos no contenga proyectos ficticios.
- **Estado vacío impecable:** Se verificó que cuando no hay casos de estudio registrados, la interfaz muestra exclusivamente la tarjeta de estado vacío ("No hay casos de estudio - Crea tu primer caso para que aparezca en el portafolio") con su botón de acción directa hacia la página dedicada `/case-studies/create`.

### 2026-09-21 - Corrección definitiva de acceso administrativo y blindaje de pruebas

- **Descubrimiento de causa raíz profunda:** Cada vez que se ejecutaba `php artisan test` dentro del contenedor Docker, Laravel utilizaba las variables de entorno del contenedor (`DB_CONNECTION=mysql`, `DB_DATABASE=kamo_laravel`) en lugar de SQLite, debido a que las variables de Docker tienen precedencia sobre `phpunit.xml`. En consecuencia, el trait `RefreshDatabase` de los tests ejecutaba migraciones y vaciaba (`truncate`/`rollback`) la base de datos MySQL de desarrollo real, borrando al usuario cada vez que corrían los tests.
- **Blindaje permanente en `TestCase.php` y `phpunit.xml`:** Se sobreescribió `createApplication()` en `tests/TestCase.php` para forzar programáticamente `config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:'])`, garantizando que ninguna prueba automatizada vuelva a tocar o borrar la base de datos MySQL local.
- **Seeding restaurado y verificado:** Se ejecutó `php artisan db:seed --force`, restaurando al administrador (`yohanblaro18@gmail.com` con contraseña `admin123`) y se verificó que tras correr pruebas automatizadas, el usuario permanece intacto en MySQL.
- **Tolerancia a alias de correo:** Compatibilidad bidireccional en `routes/auth.php` y `LoginForm.php` para que el sistema acepte indistintamente tanto `yohanblaro18@gmail.com` como `yohanblanco18@gmail.com` con la contraseña configurada (`admin123`).

### 2026-09-21 - Módulo de Casos de Estudio: migración de modal a página dedicada

- Se eliminó el modal de creación y edición en la vista principal (`/case-studies`) que presentaba latencia al abrirse (`wire:click="openCreateForm"`).
- Se implementaron rutas dedicadas `case-studies.create` (`/case-studies/create`) y `case-studies.edit` (`/case-studies/{caseStudy}/edit`) utilizando `wire:navigate` para transiciones instantáneas sin recarga completa del navegador.
- Se desarrolló el componente Livewire `App\Livewire\CaseStudies\Form` (`apps/laravel/app/Livewire/CaseStudies/Form.php`) y su vista `resources/views/livewire/case-studies/form.blade.php`.
- Se diseñó un layout ergonómico de 2 columnas:
  - Columna izquierda: formulario completo con validación reactiva en tiempo real (título, cliente, categoría, métrica destacada, etiquetas, URL y selector de imagen).
  - Columna derecha: maqueta de tarjeta con cabecera de navegador (live preview) que renderiza de forma reactiva los datos del proyecto, badges, métricas y la imagen seleccionada o existente.
- Manejo optimizado de carga de archivos mediante `WithFileUploads` con previsualización inmediata y limpieza de archivos anteriores al reemplazar imágenes.
- Se actualizó `resources/views/livewire/case-studies/index.blade.php`: botones "+ Nuevo Caso" y "Editar" convertidos en enlaces directos con `wire:navigate`, remoción del modal residual y adición de banner flash para retroalimentación visual al guardar o actualizar.
- Se expandió la suite de pruebas en `CaseStudyTest.php` cubriendo la visita a las rutas `/case-studies/create` y `/case-studies/{id}/edit`, creación con redirección y actualización exitosa.
- Verificación completa: `php artisan test` (83 pruebas / 306 aserciones en verde), `vendor/bin/pint --test` (0 issues), `npm run test:js` (7 pruebas en verde), `npm run build` (build Vite exitoso) y sesión con navegador confirmando la ausencia de modales y la reactividad del formulario.

### 2026-09-21 - Reorganización de jerarquía editorial de la landing

- Se reestructuró la jerarquía editorial pública de Kosta para que la propuesta de valor y los servicios se presenten de manera consistente en el orden: **Desarrollo Web** → **Films** → **Estrategia** → **Diseño y Branding**.
- Se reorganizó el catálogo de servicios en la landing principal bajo 4 pilares explícitos sin suprimir servicios:
  - `01 · Desarrollo Web`: Desarrollo Web y E-commerce.
  - `02 · Films`: Films & Contenido.
  - `03 · Estrategia`: Estrategia Digital y Performance Marketing.
  - `04 · Diseño & Branding`: Diseño & Branding.
- Se agregó el indicador visual `.service-pillar` en las tarjetas de servicio y se actualizaron el titular y la descripción del Hero.
- Se actualizaron el `<title>` y `<meta name="description">` en `layouts/landing.blade.php` para reflejar con precisión las cuatro disciplinas.
- Se ordenaron las opciones del selector de servicios en el formulario de contacto (`/contacto`) siguiendo la misma jerarquía.
- Se alinearon las pestañas de filtro en el portafolio público (`/portafolio`) con el orden editorial: Todos → Páginas Web → E-commerce → Redes Sociales → Branding.
- Se amplió la cobertura en `PublicViewsTest` para verificar el orden de los 4 pilares en la página de inicio, el orden de opciones en contacto y el orden de filtros en portafolio.
- Verificación ejecutada: `php artisan test` (80 pruebas / 293 aserciones en verde), `npm run test:js` (7 pruebas en verde), `npm run build` (build Vite exitoso), `vendor/bin/pint --test` (0 issues).

### 2026-09-21 - Identidad Kosta, rutas y eliminación recuperable

- Se consolidó la identidad visual monocromática de Kosta en las interfaces públicas, autenticadas, dashboard y documentos PDF.
- Se estableció `Kosta` como nombre predeterminado de la aplicación y se actualizaron los textos de marca y navegación relacionados.
- Se incorporaron las rutas públicas `/kamo` y `/perfil`; `/bento` permanece como redirección compatible hacia `/kamo`.
- Se añadió el alias local `/entrar-kosta`. Los accesos locales requieren `KAMO_LOCAL_ACCESS_EMAIL` y fallan de forma cerrada si no está configurado o no corresponde a un usuario.
- Se habilitó eliminación lógica para servicios y casos de estudio mediante la migración `2026_09_18_100000_add_soft_deletes_to_services_and_case_studies.php`.
- Se completó `ServiceFactory` y se agregó cobertura de regresión para confirmar que los servicios eliminados quedan recuperables y fuera de las consultas normales.
- Se protegió el entorno de producción contra comandos destructivos de base de datos mediante `DB::prohibitDestructiveCommands`.
- Se retiraron de README y de esta bitácora las referencias obsoletas al worker y al módulo eliminado de Meta Ads.
- Verificación: migración aplicada en MySQL; 80 pruebas PHP / 288 aserciones; 7 pruebas JavaScript; build de producción; Laravel Pint; smoke visual de `/kamo` en escritorio, 768x1024 y 375x667 sin overflow horizontal ni errores de consola.

### 2026-09-18

- Se agregó el enlace de acceso directo a **Bento** (`/bento`) en la barra lateral debajo de Dashboard en la sección "Principal".
- Se actualizaron los archivos `README.md` (raíz y `apps/laravel`) retirando las instrucciones y comandos obsoletos del worker de cola.
- Se eliminó completamente el sistema de IA automático de Meta Ads:
  - Eliminados: job `GenerateMetaAdsQuote`, servicio `ClaudeMetaAdsService`, modelo `AiMetaQuote`, factory `AiMetaQuoteFactory`, controlador `MetaAdsController`, componentes Livewire `Wizard` y `History`.
  - Eliminadas: vistas de Livewire y plantilla PDF de cotización Meta Ads (`resources/views/livewire/meta-ads/`, `resources/views/pdf/meta-ads-quote.blade.php`).
  - Eliminado el subenlace de navegación en el sidebar (`components/layouts/app.blade.php`), rutas en `routes/web.php`, y credenciales en `config/services.php` (`groq`, `anthropic`).
  - Creada y ejecutada la migración `2026_09_18_000000_drop_ai_meta_quotes_table.php` para eliminar la tabla `ai_meta_quotes` en MySQL.
  - Retirado el servicio worker `kamo_queue` de `docker-compose.yml`, liberando recursos de cómputo.
- Formateo de código PHP heredado con Laravel Pint:
  - Se ejecutó `vendor/bin/pint` sobre `app`, `bootstrap`, `config`, `database`, `routes` y `tests`.
  - Verificación `vendor/bin/pint --test` con 0 archivos con advertencias (`passed`).
- Smoke visual multi-viewport interactivo con subagente de navegador:
  - Probado en Desktop (1440x900), Tablet (768x1024) y Mobile (375x667).
  - Flujo autenticado en `/dashboard`, `/clients`, `/quotes`, `/invoices`.
  - Verificada la correcta visualización de bottom nav móvil y FAB, sin desbordamiento horizontal (`scrollWidth <= innerWidth`).
  - Pruebas automatizadas: 77/77 tests PHP en verde (273 aserciones) y 7/7 tests JS en verde.
- Se renombró la rama principal a `main` (`git branch -M main`).
- Se configuró el repositorio remoto `origin` apuntando a `https://github.com/DonKamo-Dev/KostaAgency.git`.
- Se autenticó Git con la cuenta `DonKamo-Dev` y se subió el código completo a la rama `main` (`git push -u origin main`).

### 2026-09-17

- Se creó el plan por fases [2026-09-17-cierre-pendientes-plataforma](docs/superpowers/plans/2026-09-17-cierre-pendientes-plataforma.md) para el cierre de los 5 pendientes del proyecto.
- Fase 1 completada: se inicializó el repositorio Git en la raíz (rama `master`) con `git init`.
- Se creó el `.gitignore` raíz y se verificó la cobertura de `.env`, `backups/`, `tmp/`, tooling local del agente y artefactos de build.
- Commit baseline `f5aa1b7` (268 archivos). Se detectó y retiró en `bbc7f21` la base SQLite binaria `apps/laravel/kamo_laravel` (180 KB) que se había colado al staging.
- Working tree limpio y sin archivos sensibles trackeados.
- Verificación: `git log --oneline`, `git status --short`, `git check-ignore apps/laravel/.env backups/`.
- Fase 2 completada: se eliminó el código muerto de la tenancy/roles revertida (modelos `Role`, `Permission`, `RecurringDocument`; middleware `CheckPermission`; seeders `RolePermission`, `AdminUser`, `CoreData`, `CaseStudy`; factory `RecurringDocumentFactory`; comandos legacy vacíos) y la relación `Client::recurringDocuments`.
- Se retiraron las migraciones que creaban `roles_and_permissions_tables` y `recurring_documents`, y se añadió `2026_09_17_000000_drop_orphaned_tenancy_tables.php` para eliminar ambas tablas en bases existentes.
- Se marcó el plan `2026-08-23-security-tenancy-access.md` como `SUPERSEDED`.
- Commit `b48b7c7` (15 archivos, 332 eliminaciones). Verificación: `php artisan test` (85 tests / 294 aserciones, en verde) y búsqueda de símbolos huérfanos sin resultados.
- Fase 3 completada: la generación de Meta Ads ahora corre en segundo plano con el job `App\Jobs\GenerateMetaAdsQuote` (`tries=2`, `timeout=120`), la migración `2026_09_17_000001_add_generation_status_to_ai_meta_quotes.php` (`generation_status`, `error`, índice) y el servicio `queue` en `docker-compose.yml`.
- El componente `Wizard` crea la cotización en `pending`, despacha el job y hace polling con `wire:poll.3s="checkGeneration"`; `History` lista solo cotizaciones `completed`.
- Commit `f190689` (9 archivos, +355/−51); refinamiento de accesibilidad y cobertura de fallo en commit posterior. Verificación: `php artisan test` (91 tests / 310 aserciones, en verde), `npm run test:js` (7 tests) y `npm run build` sin errores.
- Fase 4 completada: se ejecutaron los gates finales (PHP 91/310, JS 7/7, build OK), se levantó el servicio `queue` (`kamo_queue` Up) y se verificó `intl` y la tabla `jobs`. El pipeline asíncrono se validó end-to-end: dispatch → cola → worker → `generation_status = failed` con `error` persistido ante una clave Groq inválida.
- Se publicó el reporte `docs/audits/2026-08-23-platform-remediation.md` con gates, smoke HTTP, hallazgos resueltos y hallazgos no bloqueantes (estilo Pint heredado, `KAMO_LOCAL_ACCESS_EMAIL` sin definir, `GROQ_API_KEY` inválida, smoke visual manual pendiente). Commit `399089b`.
- Fase 5 completada: se sincronizaron los planes `2026-08-23-ui-accessibility-consistency.md`, `2026-08-23-runtime-pdf-docker.md` y `2026-08-23-financial-document-workflows.md` con banner de estado y checkboxes marcados solo donde hay gate/artefacto verificable; la spec incorpora la sección "Estado final" (sin multi-empresa) y se actualizaron los README raíz y de `apps/laravel` (cola, verificación y estado de Git).

### 2026-09-16

- Se creó esta bitácora como fuente de seguimiento técnico y funcional del proyecto.
- Se documentó el alcance actual de la plataforma y sus módulos principales.
- Se confirmó que las pruebas focalizadas de clientes y pagos estaban operativas durante la revisión.

## Convención para nuevas entradas

Agregar una entrada por cambio relevante con este formato:

```md
### AAAA-MM-DD - Título breve

- Cambio realizado.
- Motivo o impacto funcional.
- Verificación ejecutada: `comando`.
```

## Comandos de verificación

```bash
# Entorno
docker compose up -d

# Migraciones (contenedor)
docker compose exec app php artisan migrate

# Pruebas: ejecutar en el host (usa SQLite en memoria y APP_ENV=testing de phpunit.xml)
php artisan test

# Pruebas JavaScript y build de producción (desde apps/laravel)
npm run test:js
npm run build
```

> Importante: `docker compose exec app php artisan test` (sin overrides) falla con errores `419`/CSRF porque el `APP_ENV=local` y `DB_CONNECTION=mysql` del compose pisan la configuración de PHPUnit. Para correr tests dentro del contenedor hay que forzar el entorno:
>
> ```bash
> docker compose exec -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: -e SESSION_DRIVER=array -e CACHE_STORE=array app php artisan test
> ```
