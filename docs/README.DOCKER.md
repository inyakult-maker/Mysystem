# Docker: build and run (local)

Prerequisites: Docker and Docker Compose installed.

Build and run with docker-compose:

```bash
docker-compose build
docker-compose up -d
```

Open the app at http://localhost:8081/ (maps to container port 80).

Notes:
- Database is MySQL 5.7 exposed on host port 3307. Change `docker-compose.yml` env vars for production secrets.
- The `Dockerfile` uses the host-mounted volume so code changes are reflected immediately. For production, remove the volume and bake files into the image.
