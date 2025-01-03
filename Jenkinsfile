pipeline {
    agent any 
    environment {
        DOCKER_HUB_REPO = 'snirala1995/cliemr'
        DOCKER_IMAGE_TAG = "${DOCKER_HUB_REPO}:${env.BUILD_NUMBER}"
        CONTAINER_NAME = 'cliemr' // Name for your Docker container
        CONTAINER_PORT = '80' // Port inside the Docker container
        CREDENTIAL_SNIRALA_DOCKERHUB = 'credentials-snirala-dockerhub'
        CREDENTIALS_COREVISTA_SERVER = 'credentials-corevista-server'
        JENKINS_SERVER = '34.131.218.39'
        COREVISTA_SERVER = '34.131.119.202'
        ENV_FINAL_LIVE = '/home/srj/cliemr/env/.env'
        CUSTOM_VOLUME_DATA = '/home/srj/cliemr/custom_data:/var/www/html/public/custom_data'
    }
    parameters {
        choice(name: 'ENVIRONMENT', choices: ['Dev', 'Live'], description: 'Select The Environment')
    }
    stages {
        stage('Set Ports') {
            steps {
                script {
                    // Set ports based on the selected environment
                    if (params.ENVIRONMENT == 'Dev') {
                        env.HOST_PORT = '9001'
                        env.SERVER_IP = "http://${COREVISTA_SERVER}:${env.HOST_PORT}"
                    } else if (params.ENVIRONMENT == 'Live') {
                        env.HOST_PORT = '9000'
                        env.SERVER_IP = "http://${COREVISTA_SERVER}:${env.HOST_PORT}"
                    }
                }
            }
        }

        stage('Check Existing Container') {
            steps {
                script {
                    echo "Checking if the container already exists"
                    def existingContainer = sh(script: "docker ps -aqf name=${CONTAINER_NAME}-${env.HOST_PORT}", returnStdout: true).trim()
                    if (existingContainer) {
                        echo "Stopping and removing the existing container: ${CONTAINER_NAME}-${env.HOST_PORT}"
                        sh "docker rm -f ${CONTAINER_NAME}-${env.HOST_PORT}"
                    }
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    echo "Building the Docker image"
                    docker.build(DOCKER_IMAGE_TAG)
                    echo "Docker image built successfully."
                }
            }
        }

        stage('Push Docker Image To Docker Hub') {
            steps {
                script {
                    try {
                        echo "Pushing Docker image to DockerHub."
                        docker.withRegistry('https://registry.hub.docker.com', CREDENTIAL_SNIRALA_DOCKERHUB) {
                            docker.image(DOCKER_IMAGE_TAG).push()
                        }
                        echo "Docker image pushed to DockerHub successfully."
                    } catch (Exception e) {
                        echo "Failed to push Docker image: ${e.message}"
                    }
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    if (params.ENVIRONMENT == 'Dev' || params.ENVIRONMENT == 'Live') {
                        echo "Deploying to ================= SRJ-SERVER ============== (${COREVISTA_SERVER})"
                        sshagent([CREDENTIALS_COREVISTA_SERVER]) {
                            echo "Deploying to ${COREVISTA_SERVER} on port ${HOST_PORT} with image ${DOCKER_IMAGE_TAG}"
                            withCredentials([usernamePassword(credentialsId: CREDENTIAL_SNIRALA_DOCKERHUB, usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]){
                            sh """
                                echo "Connecting to ${COREVISTA_SERVER}..."
                                ssh -o StrictHostKeyChecking=no srj@${COREVISTA_SERVER} <<EOF
                                echo "Remote server connected successfully!"

                                echo "Logging into DockerHub"
                                echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin

                                echo "Pulling Docker image from DockerHub: ${DOCKER_IMAGE_TAG}"
                                docker pull ${DOCKER_IMAGE_TAG}

                                echo "Stopping and removing any existing container"
                                docker rm -f ${CONTAINER_NAME}-${HOST_PORT} || true

                                echo "Running the Docker container"

                                # docker run -d --init -p ${HOST_PORT}:${CONTAINER_PORT} -v ${ENV_FINAL_LIVE} -v ${CUSTOM_VOLUME_DATA} --name ${CONTAINER_NAME}-${HOST_PORT} ${DOCKER_IMAGE_TAG}
                                docker run -d --init -p ${HOST_PORT}:${CONTAINER_PORT} --env-file=${ENV_FINAL_LIVE} -v ${CUSTOM_VOLUME_DATA} --name ${CONTAINER_NAME}-${HOST_PORT} ${DOCKER_IMAGE_TAG}
                                
                                echo "Docker image ${DOCKER_IMAGE_TAG} run successfully."
                                exit
                            """
                            // docker run --env-file ${ENV_FINAL_LIVE} -d --init -p ${HOST_PORT}:${CONTAINER_PORT} --name ${CONTAINER_NAME}-${HOST_PORT} ${DOCKER_IMAGE_TAG}
                            }
                        }
                    } else {
                        echo "Deploying image in non-Dev environment"
                        sh "docker run -d --init -p ${HOST_PORT}:${CONTAINER_PORT} --name ${CONTAINER_NAME}-${HOST_PORT} ${DOCKER_IMAGE_TAG}"
                        echo "Docker image ${DOCKER_IMAGE_TAG} run successfully."
                    }   
                }
            }
        }
    }

    post {
        success {
            script {
                echo "Docker image ${DOCKER_IMAGE_TAG} successfully pushed to Docker Hub."
                echo "Container running on port: ${HOST_PORT}"
                echo "Pipeline completed successfully."
                echo "Click the following link to check the website live: ${env.SERVER_IP}"
            }
        }
        failure {
            script {
                echo "Pipeline failed. Check logs for details."
            }
        }
    }
}
