FROM nginx:1.17-alpine

RUN apk update && \
  apk add bash && \
  apk add --update nodejs nodejs-npm && \
  npm install n -g && \
  n 14.16.1

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./src /work

EXPOSE 80