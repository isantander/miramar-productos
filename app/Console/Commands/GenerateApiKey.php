<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use Illuminate\Console\Command;

class GenerateApiKey extends Command
{
    protected $signature = 'api:generate-key 
                          {name : Nombre descriptivo de la API Key}
                          {--type=frontend : Tipo de API Key (frontend, internal, admin)}
                          {--rate-limit=60 : Límite de requests por minuto}
                          {--endpoints=* : Endpoints permitidos (opcional)}
                          {--client= : Nombre del cliente}
                          {--app-version= : Versión de la aplicación}';

    protected $description = 'Genera una nueva API Key para autenticación';

    public function handle(): int
    {
        $name = $this->argument('name');
        $type = $this->option('type');
        $rateLimit = (int) $this->option('rate-limit');
        $endpoints = $this->option('endpoints');
        $client = $this->option('client');
        $version = $this->option('app-version');

        // Validar tipo
        if (!in_array($type, ['frontend', 'internal', 'admin'])) {
            $this->error("Tipo inválido. Use: frontend, internal, admin");
            return 1;
        }

        // Preparar metadata
        $metadata = [];
        if ($client) $metadata['client'] = $client;
        if ($version) $metadata['version'] = $version;
        $metadata['created_by'] = 'artisan_command';
        $metadata['created_at'] = now()->toISOString();

        try {
            // Generar API Key
            $apiKey = ApiKey::generate(
                name: $name,
                type: $type,
                rateLimitPerMinute: $rateLimit,
                allowedEndpoints: !empty($endpoints) ? $endpoints : null,
                metadata: !empty($metadata) ? $metadata : null
            );

            // Mostrar información
            $this->info("✅ API Key creada exitosamente!");
            $this->newLine();
            
            $this->line("📋 <options=bold>Detalles de la API Key:</>");
            $this->table(['Campo', 'Valor'], [
                ['ID', $apiKey->id],
                ['Nombre', $apiKey->name],
                ['Tipo', $apiKey->type],
                ['Rate Limit', $apiKey->rate_limit_per_minute . ' req/min'],
                ['Endpoints', empty($apiKey->allowed_endpoints) ? 'Todos permitidos' : implode(', ', $apiKey->allowed_endpoints)],
                ['Estado', $apiKey->is_active ? 'Activa' : 'Inactiva'],
                ['Creada', $apiKey->created_at->format('d/m/Y H:i:s')],
            ]);

            $this->newLine();
            $this->line("🔑 <options=bold>API Key (GUARDA ESTO - NO SE MOSTRARÁ NUEVAMENTE):</>");
            $this->line("<fg=yellow;options=bold>{$apiKey->raw_key}</>");
            
            $this->newLine();
            $this->line("📡 <options=bold>Uso en requests:</>");
            $this->line("<fg=cyan>curl -H \"X-API-Key: {$apiKey->raw_key}\" https://api.miramar.com/endpoint</>");

            if ($type === 'internal') {
                $this->newLine();
                $this->warn("⚠️  Esta es una API Key INTERNA - úsala solo para comunicación entre microservicios");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error al crear API Key: " . $e->getMessage());
            return 1;
        }
    }
}