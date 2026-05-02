# =====================================================================
# 縁（en1150.co.jp）お墓じまいLP
# Cloud Run 用 Dockerfile
# Stack: PHP 8.1 + Apache（静的HTMLだが将来のフォーム連携を見据えPHP対応）
# =====================================================================
FROM php:8.1-apache

# Cloud Runは PORT=8080 が必須
ENV PORT=8080
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

# Apache mod_rewrite / mod_headers / mod_deflate / mod_expires 有効化
RUN a2enmod rewrite headers deflate expires

# Apache を 8080 で動かすため ports.conf と仮想ホスト設定を上書き
COPY apache/ports.conf            /etc/apache2/ports.conf
COPY apache/000-default.conf      /etc/apache2/sites-enabled/000-default.conf

# サイトファイルを配置
COPY . /var/www/html/

# 不要ファイルをイメージから除外（COPY後のクリーンアップ）
RUN rm -rf /var/www/html/Dockerfile \
           /var/www/html/.dockerignore \
           /var/www/html/cloudbuild.yaml \
           /var/www/html/apache \
           /var/www/html/README.md \
           /var/www/html/CHANGELOG.md \
           /var/www/html/.git*

# 権限設定
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type d -exec chmod 755 {} \; \
 && find /var/www/html -type f -exec chmod 644 {} \;

# AllowOverride All を有効にする（.htaccess を効かせるため）
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

EXPOSE 8080

CMD ["apache2-foreground"]
