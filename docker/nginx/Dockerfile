FROM nginx:1.17-alpine

RUN apk update; \
  apk add nodejs npm; \
  npm install -g browserify; \
  npm install -g babel-cli; \
  apk add git;
  
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY ./src /work

EXPOSE 80