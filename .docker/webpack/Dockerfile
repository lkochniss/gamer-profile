FROM node:8

WORKDIR /var/www/symfony

COPY docker-entrypoint.sh /usr/src/docker-entrypoint.sh
RUN chmod 775 /usr/src/docker-entrypoint.sh
ENTRYPOINT ["/bin/sh", "-c", "/usr/src/docker-entrypoint.sh ${*}", "--"]

CMD ["watch"]
