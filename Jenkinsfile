pipeline {
  agent any
  stages {
    stage('Build') {
      agent any
      steps {
        sh 'docker-compose up -d'
        sh 'docker exec -w /app symfony_kindle composer install'
      }
    }
    stage('Test') {
      steps {
        sh 'docker exec -w /app symfony_kindle php ./bin/phpunit --coverage-clover=\'reports/coverage/coverage.xml\' --coverage-html=\'reports/coverage\' --coverage-crap4j=\'reports/crap4j.xml\''
      }
    }
    stage('Coverage') {
      steps {
        step([$class: 'CloverPublisher', cloverReportDir: '/reports/coverage', cloverReportFileName: 'coverage.xml'])
      }
    }
    post {
      cleanup {
        sh 'docker-compose down -v'
        cleanWs()
      }
    }
  }
}
