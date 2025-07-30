pipeline {
    agent any
    
    environment {
        DOCKER_IMAGE = 'kasir-app'
        DOCKER_TAG = "${BUILD_NUMBER}"
        GITHUB_REPO = 'https://github.com/KhafidzHaikal/new-ui-kasir.git'
        APP_PORT = '8000'
        DB_PORT = '3306'
        PHPMYADMIN_PORT = '8080'
    }
    
    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: "${GITHUB_REPO}"
            }
        }
        
        stage('Environment Setup') {
            steps {
                script {
                    // Copy environment file
                    sh 'cp .env.example .env'
                    
                    // Update environment variables for production
                    sh '''
                        sed -i "s/APP_ENV=local/APP_ENV=production/" .env
                        sed -i "s/APP_DEBUG=true/APP_DEBUG=false/" .env
                        sed -i "s/DB_HOST=127.0.0.1/DB_HOST=db/" .env
                        sed -i "s/DB_DATABASE=laravel/DB_DATABASE=kasir/" .env
                        sed -i "s/DB_USERNAME=root/DB_USERNAME=kasir_user/" .env
                        sed -i "s/DB_PASSWORD=/DB_PASSWORD=kasir_password/" .env
                    '''
                }
            }
        }
        
        stage('Build Docker Image') {
            steps {
                script {
                    // Build the Docker image
                    sh "docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} ."
                    sh "docker tag ${DOCKER_IMAGE}:${DOCKER_TAG} ${DOCKER_IMAGE}:latest"
                }
            }
        }
        
        stage('Stop Existing Containers') {
            steps {
                script {
                    // Stop and remove existing containers
                    sh '''
                        docker-compose down || true
                        docker container prune -f || true
                    '''
                }
            }
        }
        
        stage('Deploy') {
            steps {
                script {
                    // Deploy using docker-compose
                    sh 'docker-compose up -d'
                    
                    // Wait for containers to be ready
                    sh 'sleep 30'
                    
                    // Run Laravel migrations and optimizations
                    sh '''
                        docker exec kasir-app php artisan key:generate --force
                        docker exec kasir-app php artisan migrate --force
                        docker exec kasir-app php artisan config:cache
                        docker exec kasir-app php artisan route:cache
                        docker exec kasir-app php artisan view:cache
                    '''
                }
            }
        }
        
        stage('Health Check') {
            steps {
                script {
                    // Check if application is running
                    sh '''
                        echo "Checking application health..."
                        curl -f http://localhost:${APP_PORT} || exit 1
                        echo "Application is running successfully!"
                    '''
                }
            }
        }
        
        stage('Cleanup') {
            steps {
                script {
                    // Clean up old Docker images
                    sh '''
                        docker image prune -f
                        docker system prune -f --volumes
                    '''
                }
            }
        }
    }
    
    post {
        success {
            echo 'Deployment successful!'
            echo "Application is running on: http://localhost:${APP_PORT}"
            echo "PhpMyAdmin is available on: http://localhost:${PHPMYADMIN_PORT}"
        }
        
        failure {
            echo 'Deployment failed!'
            sh 'docker-compose logs'
        }
        
        always {
            // Clean up workspace
            cleanWs()
        }
    }
}