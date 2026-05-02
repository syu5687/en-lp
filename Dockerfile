# =====================================================================
# 縁（en1150.co.jp）マルチLP Cloud Run 構成
# - /ohaka/  : お墓じまいLP
# - /pet/    : ペット供養LP（将来）
#
# Stack: PHP 8.1 + Apache
# Port:  8080 (Cloud Run要件)
# =====================================================================
FROM php:8.1-apache

ENV PORT=8080
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

# Apache モジュール有効化
RUN a2enmod rewrite headers deflate expires

# Apache 設定（PORT=8080）
COPY apache/ports.conf       /etc/apache2/ports.conf
COPY apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# 全ファイルをドキュメントルートに配置
COPY . /var/www/html/

# AllowOverride All（.htaccess を効かせる）
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# 不要ファイルをイメージから除外
RUN rm -rf /var/www/html/Dockerfile \
           /var/www/html/.dockerignore \
           /var/www/html/cloudbuild.yaml \
           /var/www/html/apache \
           /var/www/html/.git \
           /var/www/html/README.md \
           /var/www/html/CHANGELOG.md

# 権限設定
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type d -exec chmod 755 {} \; \
 && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 8080

CMD ["apache2-foreground"]
