# Microservicio Miramar Productos

Microservicio para la gestión de productos turísticos (servicios y paquetes) de la agencia de turismo Miramar.

## 📋 Descripción

Este proyecto forma parte del trabajo práctico de la Diplomatura FullStack y demuestra la implementación de un microservicio con dos arquitecturas diferentes:

- **Arquitectura Tradicional** (v1): Implementación clásica con Laravel siguiendo patrones MVC
- **Arquitectura Hexagonal** (v2): Implementación avanzada usando Ports & Adapters pattern

## 🚀 Instalación

### Requisitos
- PHP 8.1 o superior
- Composer
- MySQL 8.0 o superior
- Git

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/isantander/miramar-productos.git
   cd miramar-productos
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   ```
   
   Editar `.env` con tus datos de base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=miramar_productos
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_password
   ```

4. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

5. **Crear base de datos**
   ```sql
   CREATE DATABASE miramar_productos;
   ```

6. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

7. **Levantar servidor**
   ```bash
   php artisan serve
   ```

El proyecto estará disponible en: `http://localhost:8000`

## 📚 Arquitecturas Implementadas

### V1 - Arquitectura Tradicional

Implementación siguiendo el patrón clásico de Laravel con separación en capas:

- **Controllers**: Manejan requests HTTP y coordinan respuestas
- **Services**: Contienen la lógica de negocio
- **Models**: Representan entidades de base de datos con Eloquent
- **FormRequests**: Validan datos de entrada
- **Resources**: Formatean respuestas JSON

**Endpoints disponibles:**
```
GET    /api/servicios
POST   /api/servicios
GET    /api/servicios/{id}
PUT    /api/servicios/{id}
DELETE /api/servicios/{id}

GET    /api/paquetes
POST   /api/paquetes
GET    /api/paquetes/{id}
PUT    /api/paquetes/{id}
DELETE /api/paquetes/{id}
```

### V2 - Arquitectura Hexagonal (Plus Técnico)

Implementación avanzada siguiendo el patrón Ports & Adapters para demostrar:

- **Independencia de framework**: La lógica de negocio no depende de Laravel
- **Testabilidad**: Cada capa se puede probar independientemente
- **Flexibilidad**: Fácil intercambio de tecnologías (BD, APIs, etc.)

**Estructura hexagonal:**
```
app/
├── Domain/                    # Núcleo del negocio
│   ├── Entities/             # Objetos de dominio puros
│   ├── Services/             # Lógica de negocio
│   └── Ports/                # Interfaces (contratos)
├── Application/              # Casos de uso
│   ├── UseCases/            # Orquestadores de flujo
│   └── DTOs/                # Objetos de transferencia
└── Infrastructure/           # Adaptadores externos
    ├── Http/                # Adaptador web (controllers, requests)
    └── Repositories/        # Adaptador de base de datos
```

**Endpoints V2:**
```
GET    /api/v2/servicios
POST   /api/v2/servicios
GET    /api/v2/servicios/{id}
PUT    /api/v2/servicios/{id}
DELETE /api/v2/servicios/{id}
```

## 🧪 Pruebas con Postman

### Crear un Servicio
```http
POST /api/servicios
Content-Type: application/json

{
    "codigo_servicio": "SRV-001",
    "nombre": "Tour Bariloche",
    "descripcion": "Tour completo por Bariloche",
    "destino": "Bariloche",
    "fecha": "2024-12-25",
    "precio": 1500.50
}
```

### Crear un Paquete
```http
POST /api/paquetes
Content-Type: application/json

{
    "servicios": [1, 2, 3]
}
```

## 📁 Estructura del Proyecto

```
├── app/
│   ├── Domain/                # Arquitectura hexagonal
│   ├── Application/           # Casos de uso
│   ├── Infrastructure/        # Adaptadores
│   ├── Http/Controllers/     # Controllers tradicionales
│   ├── Models/               # Modelos Eloquent
│   └── Services/             # Services tradicionales
├── database/
│   └── migrations/           # Esquema de base de datos
├── routes/
│   └── api.php              # Definición de rutas
└── README.md
```

## 🔧 Tecnologías Utilizadas

- **Framework**: Laravel 11
- **Base de Datos**: MySQL 8.0
- **Lenguaje**: PHP 8.1
- **Arquitectura**: MVC tradicional + Hexagonal
- **Validaciones**: FormRequests
- **API**: RESTful
- **Documentación**: Markdown

## 📈 Decisiones de Diseño

### Base de Datos
- **Soft Deletes**: Para mantener integridad referencial
- **Foreign Keys con RESTRICT**: Mayor seguridad ante eliminaciones accidentales
- **Índices**: En campos de búsqueda frecuente

### Validaciones
- **FormRequests separados**: `StoreRequest` y `UpdateRequest` para mayor flexibilidad
- **Validación de arrays**: Para servicios en paquetes
- **Reglas de negocio**: Mínimo 2 servicios por paquete

### Arquitectura Hexagonal
- **Entities**: Sin dependencias externas, solo lógica de dominio
- **Ports**: Interfaces que definen contratos
- **Adapters**: Implementaciones específicas (Eloquent, HTTP)

## 🤝 Contribución

Este proyecto es parte de un trabajo práctico académico. Para consultas:

- **Estudiante**: Ivan Santander
- **Institución**: Diplomatura FullStack - Fundación COLSECOR
- **Fecha**: Julio 2024

## 📄 Licencia

Proyecto académico - Uso educativo únicamente.

---

