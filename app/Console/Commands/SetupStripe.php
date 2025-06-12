<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupStripe extends Command
{
    protected $signature = 'setup:stripe {--key=} {--secret=}';
    protected $description = 'Configura las claves de API de Stripe en el archivo .env';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $key = $this->option('key') ?: $this->ask('¿Cuál es tu clave pública de Stripe? (comienza con pk_)');
        $secret = $this->option('secret') ?: $this->ask('¿Cuál es tu clave secreta de Stripe? (comienza con sk_)');

        if (!$key || !$secret) {
            $this->error('Debes proporcionar ambas claves');
            return 1;
        }

        // Verificar que las claves parecen válidas
        if (!preg_match('/^pk_/', $key) || !preg_match('/^sk_/', $secret)) {
            $this->error('Las claves no parecen tener el formato correcto');
            if (!$this->confirm('¿Quieres continuar de todas formas?')) {
                return 1;
            }
        }

        // Leer el archivo .env
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // Actualizar o agregar las claves de Stripe
        if (strpos($envContent, 'STRIPE_KEY=') !== false) {
            $envContent = preg_replace('/STRIPE_KEY=.*/', "STRIPE_KEY={$key}", $envContent);
        } else {
            $envContent .= "\nSTRIPE_KEY={$key}\n";
        }

        if (strpos($envContent, 'STRIPE_SECRET=') !== false) {
            $envContent = preg_replace('/STRIPE_SECRET=.*/', "STRIPE_SECRET={$secret}", $envContent);
        } else {
            $envContent .= "STRIPE_SECRET={$secret}\n";
        }

        // Guardar el archivo .env actualizado
        file_put_contents($envPath, $envContent);

        $this->info('Claves de Stripe configuradas correctamente');
        return 0;
    }
}
