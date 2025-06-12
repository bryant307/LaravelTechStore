# Integración de Pagos con Stripe en LaravelTechStore

Este documento explica cómo está configurada la integración de pagos con Stripe en el proyecto LaravelTechStore.

## Configuración

Para configurar las claves de Stripe, tienes dos opciones:

### Opción 1: Configuración Manual

1. Regístrate en [Stripe](https://stripe.com) y obtén tus claves de API del [Dashboard de Stripe](https://dashboard.stripe.com/test/apikeys).
2. Abre el archivo `.env` en la raíz del proyecto y actualiza las siguientes líneas:
   ```
   STRIPE_KEY=tu_clave_publicable_de_stripe
   STRIPE_SECRET=tu_clave_secreta_de_stripe
   ```

### Opción 2: Usando el Comando Artisan

Ejecuta el siguiente comando y sigue las instrucciones:
```
php artisan setup:stripe
```

O proporciona las claves directamente:
```
php artisan setup:stripe --key=pk_test_tu_clave --secret=sk_test_tu_clave_secreta
```

## Flujo de Pago

1. El usuario selecciona productos y los añade al carrito
2. El usuario navega a la página de envío y selecciona o crea una dirección
3. El usuario hace clic en "Proceder al pago"
4. En la página de pago, el usuario puede elegir entre:
   - Pago con tarjeta de crédito/débito (procesado con Stripe)
   - Pago contra entrega
5. Si elige pagar con tarjeta, ingresa los datos y se procesa el pago
6. Se muestra una página de confirmación con los detalles del pedido

## Entorno de Prueba vs Producción

- Para pruebas, usa las claves que comienzan con `pk_test_` y `sk_test_`.
- Para producción, usa las claves que comienzan con `pk_live_` y `sk_live_`.

## Tarjetas de Prueba

Para probar la integración puedes usar las siguientes tarjetas de prueba:

- **Pago exitoso**: `4242 4242 4242 4242`
- **Pago fallido**: `4000 0000 0000 0002`

Usa cualquier:
- Fecha de caducidad futura (MM/AA)
- CVC de 3 dígitos
- Código postal: cualquier 5 dígitos

Para más tarjetas de prueba, consulta la [documentación de Stripe](https://stripe.com/docs/testing).
