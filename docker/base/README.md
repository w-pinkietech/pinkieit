# Base Image Creation with Docker

This README provides instructions on how to use the provided Dockerfile to create a base image.

## Prerequisites

- Docker installed on your machine
- Docker Buildx installed and set up

## Steps to Create the Base Image

1. **Set up Docker Buildx**

   First, you need to create and use a new builder instance with Docker Buildx. Run the following command:

   ```sh
   docker buildx create --name multiarch --driver docker-container --use
   ```

2. **Build and Push the Base Image**

   Use the following command to build the base image for multiple platforms and push it to the specified container registry:

   ```sh
   docker buildx build --platform linux/amd64,linux/arm64,linux/arm/v7 -t ghcr.io/w-pinkietech/pinkieit/base-image:latest -f docker/base/Dockerfile --push .
   ```

   This command will:
   - Build the image for `linux/amd64`, `linux/arm64`, and `linux/arm/v7` platforms.
   - Tag the image as `ghcr.io/w-pinkietech/pinkieit/base-image:latest`.
   - Use the Dockerfile located at `docker/base/Dockerfile`.
   - Push the built image to the specified container registry.

## Installed PHP extensions
- pdo_mysql
- mysqli
- mbstring
- exif
- pcntl
- bcmath
- gd
- zip

## Supported architectures
- linux/amd64
- linux/arm64
- linux/arm/v7

## Additional Information

- Ensure that you have the necessary permissions to push images to the specified container registry.
- You can customize the Dockerfile as per your requirements before building the image.

For more information on Docker Buildx, refer to the [official documentation](https://docs.docker.com/buildx/working-with-buildx/).