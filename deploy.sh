#!/bin/bash

# Get the build number from the argument
buildNumber=$1

# Log the Injected build number
echo " Injected buildNumber is: $buildNumber"

# Define the path to your environment file
envFile="/root/srj/cliemr/env/.env"

# Pull the latest Docker image with the provided build number
docker pull snirala1995/cliemr:${buildNumber}

# Stop and remove the existing container if it exists
docker stop cliemr-container
docker rm cliemr-container

# Run the Docker container with the environment file
# docker run -dt --name cliemr-container -p 9000:80 --env-file=${envFile} snirala1995/cliemr:${buildNumber}
docker run -dt --name cliemr-container -p 9000:80 --env-file=${envFile} -v /root/srj/cliemr/custom_data:/var/www/html/public/custom_data snirala1995/cliemr:${buildNumber}