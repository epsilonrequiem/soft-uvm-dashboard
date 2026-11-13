# CLAUDE.md

Este archivo da contexto y guía a Claude Code (claude.ai/code) para trabajar con el código de este repositorio.

## Qué es este proyecto

Dashboard en Laravel 11 para marketing de UVM que grafica **leads** (año actual vs. año anterior) tomándolos de una base MySQL externa de solo lectura, más un módulo interno de administración de usuarios.

Partió de la plantilla **Soft UI Dashboard Laravel** de Creative Tim: `README.md`, `CHANGELOG.md` y la mayoría de `resources/views/*` (billing, tables, rtl, virtual-reality, static-sign-*, laravel-examples) son boilerplate original sin tocar y **no** forman parte del producto. Lo vivo es solamente:

- `resources/views/dashboard.blade.php` — el dashboard de leads (~1280 líneas, HTML + todo su JS)
- `resources/views/users.blade.php` — CRUD de usuarios
- `resources/views/session/login-session.blade.php` — login

El código de producto, los textos de UI, los comentarios y los mensajes de commit están en **español**. Mantenlo así.

## Comandos

```powershell
php artisan serve                       # servidor de desarrollo (Laragon también sirve el docroot directo)
npm run watch                           # recompila SCSS/JS al guardar
npm run prod                            # OBLIGATORIO antes de commitear cambios de assets (ver abajo)
php artisan migrate --seed              # setup de BD local (solo conexión interna `mysql`)
php artisan db:seed --class=YearSeeder  # un seeder; la tabla `years` alimenta el select de año
                                        # los otros son PerfilSeeder y UserSeeder (DatabaseSeeder los orquesta)
php artisan tinker
vendor/bin/phpunit                      # suites: Unit, Feature (solo existen los ExampleTest del template)
vendor/bin/phpunit --filter=nombreTest
```

**Los assets compilados están commiteados.** `public/assets/css/soft-ui-dashboard*.css` y `public/assets/js/soft-ui-dashboard*.js` están versionados en git. `layouts/app.blade.php` carga los builds con cache-buster manual: el CSS **sin minificar** (`assets/css/soft-ui-dashboard.css?v=1.0.3`, línea 51) y el JS **minificado** (`assets/js/soft-ui-dashboard.min.js?v=1.0.3`, línea 120). Si cambias un asset y el navegador sigue mostrando el viejo, sube ese `?v=`. Editar `public/assets/scss/**` sin correr `npm run prod` no publica nada: eso ya provocó dos commits de arrastre ("olvidamos compilar para produccion", "minificamos archivos para master").

Ojo: `webpack.mix.js` hace tres cosas, y solo dos importan. Compila el SCSS **desde `public/assets/scss/soft-ui-dashboard.scss` hacia `public/assets/css/`** (no desde `resources/`) y minifica `public/assets/js/soft-ui-dashboard.js` — de ahí sale el `.min.js` que carga el layout. La tercera, `mix.js('resources/js/app.js', 'public/js')` + `postCss('resources/css/app.css', 'public/css')`, es residual: ninguna vista carga `public/js` ni `public/css`.

## Dos bases de datos

`config/database.php` define tres conexiones MySQL:

| Conexión | Prefijo env | Rol |
|---|---|---|
| `mysql` (default) | `DB_*` | Propia de la app: `users`, `perfiles`, `years`. Aquí aplican migraciones y seeders. |
| `mysqlciclo` | `DB_*_CICLO` | **Externa, solo lectura, sin migraciones.** Fuente de todos los datos de leads. |
| `mysqletl` | `DB_*_ETL` | Configurada pero no referenciada en el código. |

Nunca escribas una migración contra `mysqlciclo`. Cada consulta a esa base es un `DB::connection('mysqlciclo')->table(...)` explícito. Tablas usadas: `dashboard_view` (leads), `campus`, `programas`, `cat_dominios`, `cat_urlreferrer` y `lp_crm_leads_cicloactivo` (solo en `DashboardController::pruebaCiclo`, un endpoint de diagnóstico de conexión en `/pruebaciclo`).

Las tres conexiones comparten `'url' => env('DATABASE_URL')`. Si alguna vez defines `DATABASE_URL` en el `.env`, sobreescribe host/base de **las tres** y las apunta al mismo lugar; deja esa variable vacía y configura cada conexión con su prefijo.

Columnas relevantes de `dashboard_view`: `procesado`, `nivelInteres`, `urlreferrer`, `banner`, `campus`, `carrera`, `landingPage` (`'WEB'` | `'CALCULADORA'`), `fechaRegistro`.

## Semántica de la consulta de leads (`DashboardController::leadsCiclo`)

Es el corazón de la app; estas reglas son lógica de negocio, no filtros incidentales.

- **Exclusiones base aplicadas a toda consulta de leads**: `procesado = 1`, `nivelInteres != 'EC'`, `urlreferrer NOT LIKE '%simulador%'`, `banner NOT IN ('HB_REACT','HB-WA-REACT')`.
- **La granularidad se infiere, no se envía**: si `fechaInicio == fechaFin` agrupa por `HOUR()` (`$tipo = 1`); si no, agrupa por `DATE()` (`$tipo = 2`). El frontend repite esa misma comparación **en tres lugares**, todos en `dashboard.blade.php`: para elegir entre gráfica de línea y de barra (`:1134`), para armar los datos de la serie (`:937`) y para etiquetar el indicador como "Dia" o "Rango Dias" (`:1068`). Si cambias la regla de granularidad, tienes que cambiar los cuatro puntos (el del controlador y estos tres).
- Los resultados por hora se **densifican en el servidor** en un arreglo fijo de 24 posiciones, para que las horas sin datos rendericen `0`.
- Las tres series se derivan clonando la consulta base y filtrando `landingPage`: `leads_calculadora`, `leads_general` (`WEB`), `leads_total` (ambos).
- La **comparación con el año anterior** usa `obtenerDiaMasCercanoDelAnioAnterior()`: resta un año y luego se ajusta a la fecha más cercana con el **mismo día de la semana**. Es intencional (YoY alineado por día de semana), no un bug de fechas.
- El bloque del año anterior es una copia casi literal del bloque del año actual (`$leads` / `$leads_last`, `DashboardController.php:84-161` vs. `:171-247`). Cualquier cambio de filtro debe aplicarse a **los dos**, o la comparación YoY se desalinea en silencio.
- Los filtros multi-selección (`campus`, `programa`, `paginas`) llegan como strings JSON y se pasan por `json_decode`. El centinela `'TODOS'` dentro de uno de esos arreglos significa "sin filtro" — se valida con `!in_array('TODOS', ...)` antes de aplicar el `whereIn`.
- `dominios` **no** es JSON: llega como string suelto y se compara contra `'TODOS'` directamente. El filtro de URL está anidado dentro de ese `if`, así que **`paginas` solo surte efecto cuando `dominios != 'TODOS'`**: si el dominio es `'TODOS'`, no se aplica ningún filtro de `urlreferrer` aunque vengan páginas concretas (`DashboardController.php:91-101`). Con dominio elegido: si `paginas` trae valores concretos se arma un grupo de `orWhere('urlreferrer', '=', $pagina)`; si trae `'TODOS'`, se cae a `urlreferrer LIKE %$dominios%`.
- `banner` llega como string separado por comas y pasa por `array_filter(explode(...))`; solo se aplica el `whereIn` si el arreglo resultante tiene al menos un elemento, o sea que cadena vacía = sin filtro.

## Rutas y endpoints

Todo vive en `routes/web.php`; no hay `api.php` en uso. Casi todo está dentro de `Route::group(['middleware' => 'auth'])`:

| Ruta | Controlador | Qué devuelve |
|---|---|---|
| `GET /` | `HomeController@home` | redirect a `/dashboard` |
| `GET /dashboard` | `DashboardController@view` | la vista del dashboard |
| `GET /leads-ciclo` | `DashboardController@leadsCiclo` | las 6 series de leads (JSON `datos`) |
| `GET /pruebaciclo` | `DashboardController@pruebaCiclo` | 10 filas crudas, diagnóstico de conexión |
| `GET /usuarios`, `/usuarios-lista`, `POST /usuario`, `GET|PUT /usuario/{id}`, `PUT /usuario-status/{id}` | `UserController` | CRUD de usuarios |
| `GET /perfiles`, `/campus`, `/programas`, `/paginas/{dominio}`, `/dominios`, `/years` | `CatalogController` | catálogos de los filtros |
| `GET /keep-alive` | closure | `{"status":"ok"}`, solo para no dejar morir la sesión |
| `GET /logout` | `SessionsController@destroy` | cierra sesión |

Detalles de los catálogos que sí son lógica de negocio: `getCampus` excluye `id = 21` de la tabla `campus` (`CatalogController.php:58`), y `getPaginas($dominio)` filtra `cat_urlreferrer` con `name LIKE %$dominio%`, por eso el select de páginas se repuebla cada vez que cambia el dominio.

`/login` está declarado **tres veces** y gana la última definición registrada, la del final del archivo (`routes/web.php:103`), que renderiza `session/login-session` y lleva el nombre `login`. Las otras dos — `DashboardController@view` dentro del grupo `auth` (`:31`) y `SessionsController@create` dentro del grupo `guest` (`:93`) — quedan tapadas. Si tocas el login, ten claro cuál se está ejecutando.

## Convenciones del frontend

No hay framework SPA ni JS empaquetado de aplicación. La lógica de cada página vive en un bloque `@push('<nombre>')` dentro de su propio Blade, y `layouts/app.blade.php` tiene el `@stack('<nombre>')` correspondiente — **agregar una página nueva con scripts obliga a agregar su `@stack` en el layout**. Stacks existentes (`layouts/app.blade.php:81-84`): `rtl`, `dashboard`, `usuarios`, `login`. Ojo con `usuarios`: lo empujan dos vistas, la viva `users.blade.php` y la boilerplate `laravel-examples/user-management.blade.php`; solo la primera se enruta.

- El acceso a datos es `fetch()` plano con una cadena consistente: `.then(ok ? json : throw).then(desempaqueta).catch(log, return false)`, y después un guard sobre el resultado falsy.
- Existen **dos envolturas JSON incompatibles**. `CatalogController` / `UserController` responden `{ response: { status, message, data, code } }` (se lee `data.response.data`); `DashboardController` responde `{ estatus, mensaje, datos }` (se lee `data.datos`). Respeta la del controlador que estés tocando en lugar de "arreglar" una.
- Las gráficas son instancias de Chart.js creadas **una sola vez** al cargar (`createGraphLine` / `createGraphBar`) y luego mutadas con `graph.data.datasets[n].data = ...; graph.update()`. Los canvas de línea y de barra coexisten; la visibilidad se alterna con `style.display`. No reinstancies las gráficas.
- **El "hasta la hora" es un campo, no el reloj**: en vista por hora, los totales acumulados y el dato de la hora puntual se calculan contra el valor del `<input type="number" id="date-filtro-hora">` (0-23, precargado con `date('H')` desde PHP al renderizar), no contra `new Date().getHours()` (esa versión quedó comentada en `dashboard.blade.php:944`). `leadsTotalHora` suma las horas `<= horaActual` y `leadsTotalHoraAct` solo la hora igual; lo mismo para el año anterior. Ese comparativo por hora es el que alimenta los indicadores de variación.
- Las librerías de terceros se cargan **desde `layouts/app.blade.php`, no desde cada vista**: Choices.js, SweetAlert2 y Font Awesome por CDN (`:44`, `:47`, `:77-78`); Chart.js es una copia local (`assets/js/plugins/chartjs.min.js`, `:76`). Si la app tiene que correr sin internet, esos tres CDN son el punto de falla.
- Los `<select multiple>` usan Choices.js. La clase `select-invisible` oculta el elemento nativo hasta que Choices inicializa, y después se remueve. Los valores se leen con `choicesX.getValue(true)` y se pueblan con `setChoices(arr, 'value', 'label', true)`. El select de dominios (`id_dominio`) es la excepción: es un `<select>` nativo, su instancia de Choices está comentada (`dashboard.blade.php:402-409`).
- La retroalimentación al usuario es SweetAlert2 (mixin `Toast` en `dashboard.blade.php`). Los errores de validación se lanzan como strings simples (`throw "mayor"`) y se mapean a mensajes en español dentro del `catch`.
- Los colores de marca son variables SCSS `$uvm-*` en `public/assets/scss/soft-ui-dashboard/_variables.scss`, con clases auxiliares (`bg-gradient-uvm`, `btn-uvm`, `bg-gradient-uvm-icon`, …) en `custom/_styles.scss`. Los colores de las gráficas y de los indicadores de variación (`#82d616` sube / `#d7282f` baja / `#344767` sin cambio) están hardcodeados en hex dentro del JS del Blade.

## Autenticación y autorización

Hecha a mano, no con Breeze ni Fortify. `SessionsController::store` busca al usuario, valida con `Hash::check`, rechaza `status == 0`, y luego hace `Auth::login` + `session()->regenerate()` guardando `id_perfil` en sesión.

La autorización son **checks del valor de sesión solo en Blade**, los dos en `layouts/navbars/auth/sidebar.blade.php`: el link de Dashboard se muestra con `@if (session('id_perfil') == 1 || session('id_perfil') == 2)` (`:16`) y el de usuarios con `@if (session('id_perfil') == 1)` (`:40`). Perfiles: `1` = Administrador, `2` = Consultor. No hay gates, policies ni middleware de rol, así que **las rutas están protegidas únicamente por `auth`: un Consultor todavía puede golpear los endpoints `/usuario` directamente.** Si agregas una función restringida por rol, ten claro que ocultar el link del menú no es una restricción real.

La mayoría de rutas de auth (register, forgot/reset password, profile) están comentadas en `routes/web.php` — están deshabilitadas a propósito, no faltantes. Las sesiones usan `SESSION_LIFETIME` (120 min por defecto, `config/session.php:34`). El ping a `/keep-alive` cada 10 minutos vive **solo en la página de login** (`login-session.blade.php:63-65`): el dashboard no tiene ningún `setInterval`, así que una sesión abierta ahí sí expira a los 120 minutos de inactividad.

## Patrón de los controladores

Cada acción arma un arreglo `$response`, envuelve el cuerpo en `try / catch (QueryException) / catch (Exception)`, pone 500 en los catch y devuelve `response()->json(...)`. Cada controlador debe importar `Illuminate\Database\QueryException` y `Exception` arriba: sin los `use`, PHP los busca en `App\Http\Controllers` y los `catch` nunca atrapan nada (así estuvo `CatalogController` hasta agregar los imports). Las llaves de ese arreglo dependen del controlador y son las dos envolturas incompatibles de arriba: `UserController` / `CatalogController` usan `status`/`message`/`data`/`code` y envuelven la respuesta en `['response' => $response]`; `DashboardController` usa `estatus`/`mensaje`/`datos` con el HTTP status en una variable aparte (`$codigo`) y devuelve el arreglo pelón. La validación usa `Validator::make` con un arreglo `$messages` en español, y los errores se aplanan con el helper global `formatedError()` (`app/Helpers/validationHelper.php`, autocargado vía `files` de composer).

Regla de contraseña: `min:8`, `max:12`, `regex:/^(?=.*[A-Z])(?=.*\d).{8,12}$/`. En `store` es obligatoria (`UserController.php:72`). En `update` la validación se bifurca: si el request trae `password` se aplica la misma regla, y si no, se valida solo `name`/`email`/`id_perfil` y la contraseña queda sin tocar (`UserController.php:152-165`). O sea, editar un usuario sin cambiarle la contraseña es un caso soportado, no un hueco.

## Estilo de commits

Prefijo gitmoji + español en primera persona del plural: `✨agregamos filtro de hora actual`, `🐛 fix bug - ajuste tiempo de sesion`, `💄 ajuste en colores dashboard`, `🚀 compilamos archivos para produccion`.
