# Prueba técnica de Check24

## Tiempo estimado

~8 horas

---

## Inicialización & configuración del aplicativo

Instalación de dependencias (desde máquina host preferiblemente), se omite la carpeta de dependencias en fichero comprimido.
```shell
composer install --ignore-platform-reqs
```

Inicialización del entorno local
```shell
docker-compose -p "check24_tech_test" up -d
```

Inicialización del esquema relacional (Dentro del contenedor php-fpm preferiblemente) ...
```shell
php bin/console doctrine:database:create
```

Migraciones...
```shell
php bin/console doctrine:schema:update --force
```

---

## Añadir una campaña para realizar la verificación

Se realiza una recuperación de filas de la base de datos en función al flag "isActive" y al rango de fechas estalbecido.

```sql
INSERT INTO campaign (discount, from_at, to_at, is_active)
VALUES (5, NOW() - INTERVAL + 1 DAY, NOW() + INTERVAL + 1 DAY, 1);
```

