<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use Illuminate\Console\Command;

class ManageApiKey extends Command
{
    protected $signature = 'api:manage-key 
                          {id : ID de la API Key}
                          {action : Acción a realizar (enable, disable, info)}';

    protected $description = 'Gestiona API Keys (habilitar, deshabilitar, ver info)';

    public function handle(): int
    {
        $id = $this->argument('id');
        $action = $this->argument('action');

        // Validar acción
        if (!in_array($action, ['enable', 'disable', 'info'])) {
            $this->error("Acción inválida. Use: enable, disable, info");
            return 1;
        }

        // Buscar API Key
        $apiKey = ApiKey::find($id);
        if (!$apiKey) {
            $this->error("❌ API Key con ID {$id} no encontrada");
            return 1;
        }

        return match($action) {
            'enable' => $this->enableApiKey($apiKey),
            'disable' => $this->disableApiKey($apiKey),
            'info' => $this->showApiKeyInfo($apiKey),
        };
    }

    private function enableApiKey(ApiKey $apiKey): int
    {
        if ($apiKey->is_active) {
            $this->warn("⚠️  La API Key '{$apiKey->name}' ya está activa");
            return 0;
        }

        $apiKey->update(['is_active' => true]);
        $this->info("✅ API Key '{$apiKey->name}' habilitada exitosamente");
        
        $this->showBasicInfo($apiKey);
        return 0;
    }

    private function disableApiKey(ApiKey $apiKey): int
    {
        if (!$apiKey->is_active) {
            $this->warn("⚠️  La API Key '{$apiKey->name}' ya está inactiva");
            return 0;
        }

        // Confirmar deshabilitación para keys internas
        if ($apiKey->type === 'internal') {
            if (!$this->confirm("⚠️  Esta es una API Key INTERNA. ¿Está seguro de deshabilitarla?")) {
                $this->info("Operación cancelada");
                return 0;
            }
        }

        $apiKey->update(['is_active' => false]);
        $this->info("🔒 API Key '{$apiKey->name}' deshabilitada exitosamente");
        
        if ($apiKey->type === 'internal') {
            $this->error("❗ IMPORTANTE: La comunicación entre microservicios puede verse afectada");
        }
        
        $this->showBasicInfo($apiKey);
        return 0;
    }

    private function showApiKeyInfo(ApiKey $apiKey): int
    {
        $this->line("🔑 <options=bold>Información de API Key:</>");
        $this->newLine();

        $status = $apiKey->is_active ? '<fg=green>✅ Activa</>' : '<fg=red>❌ Inactiva</>';
        $type = match($apiKey->type) {
            'frontend' => '<fg=blue>Frontend</>',
            'internal' => '<fg=yellow>Internal</>',
            'admin' => '<fg=red>Admin</>',
            default => $apiKey->type
        };

        $this->table(['Campo', 'Valor'], [
            ['ID', $apiKey->id],
            ['Nombre', $apiKey->name],
            ['Tipo', $type],
            ['Estado', $status],
            ['Key Prefijo', $apiKey->key_prefix],
            ['Key Enmascarada', $apiKey->masked_key],
            ['Rate Limit', $apiKey->rate_limit_per_minute . ' requests/minuto'],
            ['Total Requests', number_format($apiKey->total_requests)],
            ['Último Uso', $apiKey->last_used_at ? $apiKey->last_used_at->format('d/m/Y H:i:s') : 'Nunca'],
            ['Creada', $apiKey->created_at->format('d/m/Y H:i:s')],
            ['Actualizada', $apiKey->updated_at->format('d/m/Y H:i:s')],
        ]);

        // Endpoints permitidos
        if (!empty($apiKey->allowed_endpoints)) {
            $this->newLine();
            $this->line("🎯 <options=bold>Endpoints Permitidos:</>");
            foreach ($apiKey->allowed_endpoints as $endpoint) {
                $this->line("  • {$endpoint}");
            }
        } else {
            $this->newLine();
            $this->line("🌐 <fg=green>Acceso a todos los endpoints</>");
        }

        // Metadata
        if (!empty($apiKey->metadata)) {
            $this->newLine();
            $this->line("📋 <options=bold>Metadata:</>");
            foreach ($apiKey->metadata as $key => $value) {
                $this->line("  • {$key}: {$value}");
            }
        }

        // Rate limiting actual
        if ($apiKey->type !== 'internal') {
            $this->newLine();
            $this->showCurrentRateLimit($apiKey);
        }

        return 0;
    }

    private function showBasicInfo(ApiKey $apiKey): void
    {
        $this->newLine();
        $status = $apiKey->is_active ? '<fg=green>✅ Activa</>' : '<fg=red>❌ Inactiva</>';
        $this->table(['Campo', 'Valor'], [
            ['ID', $apiKey->id],
            ['Nombre', $apiKey->name],
            ['Tipo', $apiKey->type],
            ['Estado', $status],
            ['Key', $apiKey->masked_key],
        ]);
    }

    private function showCurrentRateLimit(ApiKey $apiKey): void
    {
        $cacheKey = "api_rate_limit:{$apiKey->id}:" . now()->format('Y-m-d-H-i');
        $currentRequests = cache()->get($cacheKey, 0);
        $remaining = max(0, $apiKey->rate_limit_per_minute - $currentRequests);

        $color = match(true) {
            $remaining == 0 => 'red',
            $remaining <= 10 => 'yellow',
            default => 'green'
        };

        $this->line("⏱️  <options=bold>Rate Limit Actual (este minuto):</>");
        $this->line("  • Requests usados: <fg={$color}>{$currentRequests}/{$apiKey->rate_limit_per_minute}</>");
        $this->line("  • Requests restantes: <fg={$color}>{$remaining}</>");
    }
}