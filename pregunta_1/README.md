# PHP JSON Service

Servicio web mínimo en **PHP** que devuelve **JSON**.

## Requisitos
- PHP >= 8.0
- Opcional: Docker

## Ejecutar con el servidor embebido de PHP
```bash
php -S 0.0.0.0:8000 router.php
# En otro terminal:
curl http://localhost:8000/
curl http://localhost:8000/api/status
curl http://localhost:8000/api/time
curl -X POST http://localhost:8000/api/echo -H "Content-Type: application/json" -d '{"curso":"Calidad de Software"}'
```

## Ejecutar en Apache (XAMPP/WAMP/LAMP)
1. Copia los archivos a una carpeta dentro de `htdocs` o `public_html`.
2. Asegúrate de tener `mod_rewrite` activo y `AllowOverride All` para usar `.htaccess`.
3. Accede desde el navegador: `http://localhost/tu-carpeta/`

## Docker (opcional)
```bash
docker build -t php-json-service .
docker run --rm -p 8080:80 php-json-service
# Probar
curl http://localhost:8080/
```

## Endpoints
- `GET /`             -> info del servicio
- `GET /api/status`   -> JSON estático
- `GET /api/time`     -> hora (ISO, epoch ms)
- `POST /api/echo`    -> devuelve el JSON enviado
