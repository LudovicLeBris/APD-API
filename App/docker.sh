composer install && \
php bin/console d:d:c --no-interaction && \
php bin/console do:mi:mi --no-interaction && \
php bin/console do:fi:lo --no-interaction
# exec apache2-foreground