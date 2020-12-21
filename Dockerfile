FROM docker-registry.dmdmedia.net:443/wellandgood/wp-base:5

ARG NPM_ENV
ARG NPM_URL

ENV WP_DIR "/usr/src/wordpress"

COPY . ${WP_DIR}

RUN cd ${WP_DIR}/wp-content/themes/wellgood-2016 \
    && npm install --unsafe-perm=true --allow-root \
    && npm run build -- --SITE_ENV=${NPM_ENV} --URL=${NPM_URL} \
    && chown www-data:www-data -R ${WP_DIR} \
    && cp ${WP_DIR}/email-server/msmtprc /etc/msmtprc  \
    # Deleting unneeded packages/libs
    && rm -rf /opt \
    && apt-get remove -y \
       gcc \
	g++ \
	curl \
	python3.7 \
	python3-minimal \
	python3.7-minimal \
	libstdc++-8-dev:amd64 \
	libc6-dev:amd64 \
	libgcc-8-dev:amd64 \
	git \
	git-man \
	bzip2 \
	make \
    # Deleting node modules after building FE
    && rm -rf ${WP_DIR}/wp-content/themes/wellgood-2016/node_modules/

CMD ["php-fpm"]
