#!/bin/bash

# Make this script executable with: chmod +x docker-helper.sh

case "$1" in
  "exec")
    # Execute a command in the container
    docker exec -it laravel_app bash
    ;;
  "copy-from")
    # Copy files from container to host
    docker cp laravel_app:/var/www/html/$2 ./$2
    echo "Copied $2 from container to host"
    ;;
  "copy-to")
    # Copy files from host to container
    docker cp ./$2 laravel_app:/var/www/html/$2
    echo "Copied $2 from host to container"
    ;;
  "rebuild")
    # Rebuild containers
    docker-compose down
    docker-compose up -d --build
    ;;
  *)
    echo "Usage: $0 {exec|copy-from|copy-to|rebuild}"
    echo "  exec - Opens a shell in the Laravel container"
    echo "  copy-from [path] - Copies files from container to host"
    echo "  copy-to [path] - Copies files from host to container"
    echo "  rebuild - Rebuilds and restarts the containers"
    ;;
esac
