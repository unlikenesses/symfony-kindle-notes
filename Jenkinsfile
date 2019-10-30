pipeline {
  agent any
  stages {
    stage('Prepare directories') {
      steps {
        sh 'mkdir -p reports'
      }
    }
    stage('Build') {
      agent any
      steps {
        sh 'docker-compose up -d'
        sh 'docker exec -w /app symfony_kindle composer install'
      }
    }
    stage('PHP CS Fixer') {
      steps {
        sh 'php-cs-fixer fix --dry-run --no-interaction --diff -vvv src/'
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
    stage('PHP_CodeSniffer') {
      steps {
        sh 'phpcs --standard=PSR2 --report=checkstyle --report-file=reports/checkstyle.xml src'
        recordIssues(tools: [checkStyle()])
      }
    }
    stage('Lines of Code') { 
      steps { 
        sh 'phploc --count-tests --exclude vendor/ --log-csv reports/phploc.csv --log-xml reports/phploc.xml .' 
        plot csvFileName: 'plot-1a09c728-5906-428b-b39b-0bd28feda868.csv', csvSeries: [[displayTableFlag: true, exclusionValues: '', file: 'reports/phploc.csv', inclusionFlag: 'OFF', url: '']], group: 'LOC', style: 'line', title: 'PHP LOC'
      } 
    }
  }  
}
