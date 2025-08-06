<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ApiKey extends Model
{
    protected $fillable = [
        'name',
        'key',
        'key_prefix', 
        'type',
        'rate_limit_per_minute',
        'allowed_endpoints',
        'metadata',
        'is_active',
        'last_used_at',
        'total_requests'
    ];

    protected $casts = [
        'allowed_endpoints' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'key', // clave hasheada
    ];

    protected $appends = [
        'masked_key'
    ];

    // Genera una nueva API Key 

    public static function generate(
        string $name,
        string $type = 'frontend',
        int $rateLimitPerMinute = 60,
        array $allowedEndpoints = null,
        array $metadata = null
    ): self {
        
        $rawKey = self::generateRawKey($type);
        $hashedKey = Hash::make($rawKey);
        $prefix = substr($rawKey, 0, 8);

        $apiKey = self::create([
            'name' => $name,
            'key' => $hashedKey,
            'key_prefix' => $prefix,
            'type' => $type,
            'rate_limit_per_minute' => $rateLimitPerMinute,
            'allowed_endpoints' => $allowedEndpoints,
            'metadata' => $metadata,
        ]);

        // devuelve la apikey en texto plano para que se pueda copiar, 
        // lo hace una vez nada más
        $apiKey->raw_key = $rawKey;
        
        return $apiKey;
    }

    // genera un indentificador unico para cada tipo de API Key
    private static function generateRawKey(string $type): string
    {
        $prefix = match($type) {
            'frontend' => 'ak_',
            'internal' => 'ik_', 
            'admin' => 'admin_',
            default => 'ak_'
        };

        return $prefix . Str::random(40);
    }

    // Verifica si una key es válida
    public static function validateKey(string $rawKey): ?self
    {
        $prefix = substr($rawKey, 0, 8);
        
        $apiKey = self::where('key_prefix', $prefix)
            ->where('is_active', true)
            ->first();

        if ($apiKey && Hash::check($rawKey, $apiKey->key)) {
            return $apiKey;
        }

        return null;
    }

    // actualiza estadísticas de uso
    public function recordUsage(): void
    {
        $this->increment('total_requests');
        $this->update(['last_used_at' => now()]);
    }

    // Verificar el rate limiting
    public function hasExceededRateLimit(): bool
    {
        if ($this->type === 'internal') {
            return false; // los servicios internos no tienen límite
        }

        $cacheKey = "api_rate_limit:{$this->id}:" . now()->format('Y-m-d-H-i');
        $requests = cache()->get($cacheKey, 0);

        return $requests >= $this->rate_limit_per_minute;
    }

    // incrementa contador de rate limit
    public function incrementRateLimit(): void
    {
        if ($this->type === 'internal') {
            return; // los servicios internos no tienen límite
        }

        $cacheKey = "api_rate_limit:{$this->id}:" . now()->format('Y-m-d-H-i');
        cache()->increment($cacheKey, 1, 60); // TTL de 60 segundos (ver si es suficiente)
    }

    // verificar si tiene acceso a un endpoint
    public function hasAccessToEndpoint(string $endpoint): bool
    {
        if (empty($this->allowed_endpoints)) {
            return true; // acceso a todo si no hay ninguna restricción
        }

        foreach ($this->allowed_endpoints as $allowedPattern) {
            if (fnmatch($allowedPattern, $endpoint)) {
                return true;
            }
        }

        return false;
    }

    // key enmascarada para mostrar en las estadísticas y logs
    public function getMaskedKeyAttribute(): string
    {
        return $this->key_prefix . '****' . substr($this->key_prefix, -4);
    }

    // scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecentlyUsed($query, int $days = 30)
    {
        return $query->where('last_used_at', '>=', now()->subDays($days));
    }
}