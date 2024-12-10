pipeline {
    agent any
    
    environment {
        DOCKER_REGISTRY_CREDENTIALS = 'credentials-snirala-dockerhub'
        IMAGE_NAME = 'cliemr'
        IMAGE_TAG = 'latest-cliemr' 
        DOCKER_USERNAME = 'snirala1995'
        CREDENTIAL_SNIRALA_DOCKERHUB = 'credentials-snirala-dockerhub'
    }
    
    stages {
        stage('Checkout') {
            steps {
                checkout([$class: 'GitSCM', branches: [[name: '*/dev'], [name: '*/staging'], [name: '*/main']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[url: 'git@github.com:surajNirala/cliemr.git']]])
            }
        }
        
        stage('Build Docker Image') {
            when {
                expression { 
                    return env.BRANCH_NAME == 'main'
                }
            }
            steps {
                script {
                    def buildNumber = sh(script: 'echo $BUILD_NUMBER', returnStdout: true).trim()
                    writeFile file: 'build_number.txt', text: buildNumber
                    docker.build("${IMAGE_NAME}:${buildNumber}", ".")
                }
            }
        }
		
        stage('Image Tagged') {
            when {
                expression { 
                    return env.BRANCH_NAME == 'main'
                }
            }
            steps {
                script {
                    try {
                        def buildNumber = readFile 'build_number.txt'.trim()
                        def taggedImage = "${DOCKER_USERNAME}/${IMAGE_NAME}:${buildNumber}"
                        
                        println "Build Number: ${buildNumber}"
                        println "Tagged Image: ${taggedImage}"
                        
                        sh "docker tag ${IMAGE_NAME}:${buildNumber} ${taggedImage}"
                        // sh "docker push ${taggedImage}"
                        
                    } catch (Exception e) {
                        println "Error occurred: ${e.getMessage()}"
                        error "Failed to tag or push Docker image."
                    }
                }
            }
        }

         stage('Push Docker Image To Docker Hub') {
            steps {
                script {
                    try {
                        echo "Pushing Docker image to DockerHub."
                        docker.withRegistry('https://registry.hub.docker.com', CREDENTIAL_SNIRALA_DOCKERHUB) {
                            docker.image("${DOCKER_USERNAME}/${IMAGE_NAME}:${buildNumber}").push()
                        }
                        echo "Docker image pushed to DockerHub successfully."
                    } catch (Exception e) {
                        echo "Failed to push Docker image: ${e.message}"
                    }
                }
            }
        }
       
      stage('Deploy to GCP') {
        when {
          expression { 
            return env.BRANCH_NAME == 'main'
        }
    }
    steps {
        script {
            // Get build number
            def buildNumber = sh(script: 'echo $BUILD_NUMBER', returnStdout: true).trim()
            println "Build Number: ${buildNumber}" // Print the build number for verification
            
            // Replace placeholder with build number in deploy.sh
            sh "sed -i 's/BUILD_NUMBER_PLACEHOLDER/${buildNumber}/' deploy.sh"
            
            // Copy deploy.sh to the deployment server
            //ssh -i /home/srj/.ssh/id_rsa srj@34.131.166.50
            sh "scp -i /home/srj/.ssh/id_rsa  deploy.sh srj@34.131.166.50:/home/srj/cliemr/script_cliemr_container_deployment/deploy.sh"
            
            // Execute deploy.sh on the deployment server with buildNumber as argument
            sh "ssh -i /home/srj/.ssh/id_rsa  srj@34.131.166.50 'chmod +x /home/srj/cliemr/script_cliemr_container_deployment/deploy.sh && /home/srj/cliemr/script_cliemr_container_deployment/deploy.sh ${buildNumber}'"
        }
    }
}

    }
}