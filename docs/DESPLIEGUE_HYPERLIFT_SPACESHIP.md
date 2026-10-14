# Guía de Despliegue en Spaceship Hyperlift 🚀

Esta guía contiene los pasos exactos y las variables de entorno listas para copiar y pegar en **Hyperlift (Spaceship)**.

---

## 1. Base de Datos MySQL Gratuita (En la nube)

Como Hyperlift ejecuta el contenedor web de Laravel, necesitamos una base de datos MySQL en la nube para que guarde tus datos de forma persistente y gratis.

### Opción recomendada: **TiDB Cloud Serverless** (100% Gratis)
1. Entra a [tidbcloud.com](https://tidbcloud.com) y regístrate con Google/GitHub (gratis).
2. Crea un clúster **Serverless** (Plan Free, $0/mes).
3. Selecciona la región más cercana a EE.UU. (ej. `us-east-1` o `us-west-2`).
4. Al crearse, haz clic en **Connect** y elige **General** o **PHP (PDO)**.
5. Copia los datos: Host, Puerto (suele ser 4000 o 3306), Usuario, Contraseña y Nombre de Base de Datos.

---

## 2. Variables de Entorno para Hyperlift

En el panel de **Hyperlift Manager** en Spaceship, ve a **Environment Variables** y agrega las siguientes:

```env
APP_NAME=Kosta
APP_ENV=production
APP_KEY=base64:tu_app_key_aqui
APP_DEBUG=false
APP_URL=https://tudominio.com

# Puerto de Hyperlift
PORT=8080

# Conexión Base de Datos (ejemplo TiDB Cloud o MySQL remoto)
DB_CONNECTION=mysql
DB_HOST=gateway01.us-east-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=kamo_production
DB_USERNAME=usuario_de_tidb
DB_PASSWORD=password_de_tidb

# Sesiones y Caché seguros para contenedor
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

> [!NOTE]
> Genera tu `APP_KEY` ejecutando localmente `php artisan key:generate --show` o copia la que tienes en tu archivo `.env` local.

---

## 3. Pasos de Despliegue en Hyperlift (Spaceship)

1. **Ingresa a Spaceship** y abre **Hyperlift** desde el panel principal (*Unbox*).
2. Haz clic en **Create Application** / **New Deployment**.
3. Selecciona **GitHub** y autoriza tu repositorio `Kamo-Plataform`.
4. Selecciona la rama **`main`**.
5. Hyperlift detectará automáticamente el archivo `Dockerfile` en la raíz del proyecto.
6. En la pestaña **Configuration / Environment Variables**, pega las variables listadas arriba.
7. Haz clic en **Deploy**.
8. Una vez finalice el build (tarda ~2 minutos):
   - Ve a la sección **Domains** de Hyperlift.
   - Selecciona el dominio que compraste en Spaceship.
   - Hyperlift conectará el dominio y generará el certificado SSL (HTTPS) de forma automática.

---

## 4. Migraciones y Seeders Automáticos

El contenedor está configurado con un script de inicio (`entrypoint.sh`) que:
- Aplica las migraciones automáticamente en cuanto detecta conexión con la base de datos (`php artisan migrate --force`).
- Genera el symlink para archivos públicos (`php artisan storage:link`).
- Optimiza rutas, vistas y configuración de Laravel en caché de producción.
