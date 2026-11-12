# SlideOne

Paquete mínimo de prueba para la funcionalidad de plugins en ProcessMaker 4.

## Incluye

- Ruta de prueba autenticada: `GET /slideone/test`
- Botón en la barra del modeler que abre un modal simple

## Desarrollo

```bash
composer install
npm install
npm run dev
```

## Instalación

```bash
composer require processmaker/package-slideone
php artisan slideone:install
```

## Desinstalación

```bash
php artisan slideone:uninstall
composer remove processmaker/package-slideone
```
