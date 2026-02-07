pipeline {
    agent any

    environment {
        DEPLOY_PATH = "/var/www/laravel-app"
    }

    stages {

        stage('Checkout') {
            steps {
                git branch: 'main',
                    url: 'git@github.com:rameshmp2/dms.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh '''
                  composer install --no-interaction
                  npm install
                  npm run build
                '''
            }
        }

        stage('Run Tests') {
            steps {
                sh '''
                  cp .env.ci .env
                  php artisan key:generate
                  php artisan test
                '''
            }
        }

        stage('Deploy') {
            steps {
                sh 'bash deploy.sh'
            }
        }
    }
}
