FROM srnd/docker-php:de08bebdbf02337861c68630004d9bbc74704a1e
WORKDIR /app

# Install dependencies
COPY composer.* ./
RUN composer install --no-autoloader --no-scripts 2>&1

# Copy code to the docker container
COPY . .
RUN composer dump-autoload

# Fix permissions for some directories
RUN mkdir -p /app/storage /app/storage/logs /app/storage/framework /app/boostrap \
    && chown -R www-data /app/storage \
    && chown -R www-data /app/bootstrap

