# .ddev/Dockerfile
ARG BASE_IMAGE
FROM $BASE_IMAGE

# Install pdftk binary inside the web container
RUN apt-get update && apt-get install -y pdftk-java && ln -s /usr/bin/pdftk-java /usr/local/bin/pdftk
