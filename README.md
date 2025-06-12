<div align="center">
  <h1>LaravelTechStore</h1>
  <p><strong>Tienda en línea de productos tecnológicos</strong></p>
  <img src="https://ugb.edu.sv/images/menus/logo-header.png" width="400" alt="Logo UGB">
  <h2>Universidad Gerardo Barrios</h2>
  <p><strong>Facultad de Ciencia y Tecnología</strong></p>
  <p><strong>Programación IV</strong></p>
</div>

## Presentación

Este proyecto es una tienda en línea de productos tecnológicos desarrollada con Laravel, como parte del curso de Programación IV en la Universidad Gerardo Barrios.

### Integrantes

- **Bryan Enrique Torres Alvarez**
- **Manuel Heriberto Martinez Aviles**

### Descripción

LaravelTechStore es una plataforma de comercio electrónico especializada en la venta de productos tecnológicos y electrónicos. Desarrollada utilizando Laravel, Livewire y Tailwind CSS, la aplicación ofrece una experiencia de compra intuitiva para los usuarios.

### Características principales

- **Catálogo organizado**: Productos clasificados en familias, categorías y subcategorías
- **Sistema de variantes**: Productos con múltiples opciones (color, tamaño, etc.)
- **Galería de imágenes**: Visualización de múltiples imágenes por variante
- **Reseñas y valoraciones**: Sistema de comentarios y calificaciones
- **Compartir en redes sociales**: Integración con múltiples plataformas
- **Carrito de compras**: Funcionalidad completa
- **Panel administrativo**: Gestión integral de la tienda

### Tecnologías utilizadas

- Laravel 10
- Livewire
- Tailwind CSS
- MySQL
- Jetstream
- Stripe (para pagos)

### Documentación

- [Integración de pagos con Stripe](docs/payment-integration.md)

## Instalación y configuración

Para instalar y configurar el proyecto, sigue estos pasos:

1. Clona el repositorio:
   ```
   git clone https://github.com/usuario/LaravelTechStore.git
   cd LaravelTechStore
   ```

2. Instala las dependencias:
   ```
   composer install
   npm install
   ```

3. Configura el archivo .env:
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. Configura la base de datos en el archivo .env y ejecuta las migraciones:
   ```
   php artisan migrate --seed
   ```

5. Enlaza el almacenamiento:
   ```
   php artisan storage:link
   ```

6. Compila los assets:
   ```
   npm run dev
   ```

7. Inicia el servidor:
   ```
   php artisan serve
   ```

## Estructura del proyecto

El proyecto está organizado siguiendo las mejores prácticas de Laravel:

## Modelos y relaciones

El sistema cuenta con los siguientes modelos principales:

- **Family**: Familias de productos (ej. Electrónicos, Informática)
- **Category**: Categorías que pertenecen a una familia
- **Subcategory**: Subcategorías que pertenecen a una categoría
- **Product**: Productos base con sus características generales
- **ProductVariant**: Variantes específicas de cada producto
- **ProductVariantImage**: Imágenes asociadas a cada variante
- **Review**: Sistema de reseñas y valoraciones
- **User**: Usuarios del sistema, incluyendo compradores y administradores
- **Order**: Pedidos realizados por los usuarios
- **OrderItem**: Líneas de cada pedido

## Componentes Livewire

El sistema utiliza Livewire para la interactividad en tiempo real:

- **AddToCart**: Gestión de añadir productos al carrito
- **CartDetail**: Vista detallada del carrito de compras
- **ProductGallery**: Galería de imágenes de productos
- **ProductReviews**: Sistema de reseñas y valoraciones
- **ShippingAddress**: Formulario de dirección de envío
- **OrderTracking**: Seguimiento de pedidos

## Capturas de pantalla

![Página de inicio](/public/img/screenshots/Home1.png)
*Vista de la página principal*
![Pagina de inicio](/public/img/screenshots/Home2.png) 

![Detalle de producto](/public/img/screenshots/Resenas.png)
*Vista detallada de un producto con variantes y reseñas*

![Carrito de compras](/public/img/screenshots/carrito.png)
*Vista del carrito de compras*



## Agradecimientos

Agradecemos a nuestro profesor de Programación IV por su guía y apoyo durante el ciclo.

## Licencia

Este proyecto es presentado como trabajo académico para la Universidad Gerardo Barrios.

---

<p align="center">Universidad Gerardo Barrios © 2025 | Programación IV</p>
