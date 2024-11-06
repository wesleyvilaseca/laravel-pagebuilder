FROM php:8.3

ARG user=wesley
ARG uid=1000

# Atualiza os pacotes e instala dependências essenciais
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libjpeg-dev \
    libwebp-dev \
    nodejs \
    npm \
    nano

# Limpa o cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala as extensões PHP necessárias
RUN docker-php-ext-configure gd --with-jpeg --with-webp && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets zip

# Instala o Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copia o Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria um usuário do sistema para executar Composer e comandos Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www/html

# Copia os arquivos da aplicação
COPY ./ ./

RUN chown -R $user:$user /var/www/html

# Instala dependências do Composer
RUN composer update \
    && chown -R $user:$user /var/www/html/vendor

# Muda para o usuário não-root
USER $user

# Define o ponto de entrada
ENTRYPOINT ["/var/www/html/run-after-build.sh"]