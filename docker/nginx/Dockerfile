FROM nginx:1.17-alpine
# node command
COPY --from=node /usr/local/bin /usr/local/bin
# npm command
COPY --from=node /usr/local/lib /usr/local/lib
# yarn command
COPY --from=node /opt /opt
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./src /work

EXPOSE 80