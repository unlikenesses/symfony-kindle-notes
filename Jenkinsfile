pipeline {
  agent any
  environment {
    PATH = '~/.composer/vendor/bin:$PATH'
  }
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
        sh 'docker exec -w /app symfony_kindle php ./bin/phpunit --coverage-clover=\'reports/coverage/coverage.xml\' --coverage-html=\'reports/coverage\''
      }
    }
    stage('Coverage') {
      steps {
        step([$class: 'CloverPublisher', cloverReportDir: '/reports/coverage', cloverReportFileName: 'coverage.xml'])
      }
    }
    stage('Lines of Code') { 
      steps { 
        sh 'phploc --count-tests --exclude vendor/ --log-csv reports/phploc.csv --log-xml reports/phploc.xml .' 
      } 
    }
  }  
  post {
    cleanup {
      cleanWs()
    }
  }
}
