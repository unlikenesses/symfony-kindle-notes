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
        sh 'phpcs --standard=PSR2 --report=checkstyle --report-file=reports/checkstyle.xml src || exit 0'
        recordIssues(tools: [checkStyle(pattern: '**/reports/checkstyle.xml')])
      }
    }
    stage('Lines of Code') { 
      steps { 
        sh 'phploc --count-tests --exclude vendor/ --log-csv reports/phploc.csv --log-xml reports/phploc.xml .' 
        script {
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d900.csv',
            group: 'phploc',
            title: 'A - Lines of code', useDescr: false,
            yaxis: 'Lines of Code',
            csvSeries: [[
                exclusionValues: 'Lines of Code (LOC),Comment Lines of Code (CLOC),Non-Comment Lines of Code (NCLOC),Logical Lines of Code (LLOC)',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d901.csv',
            group: 'phploc',
            title: 'B - Structures Containers',
            yaxis: 'Count',
            csvSeries: [[
                exclusionValues: 'Directories,Files,Namespaces',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d902.csv',
            group: 'phploc',
            title: 'C - Average Length',
            yaxis: 'Average Lines of Code',
            csvSeries: [[
                exclusionValues: 'Average Class Length (LLOC),Average Method Length (LLOC),Average Function Length (LLOC)',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d903.csv',
            group: 'phploc',
            title: 'D - Relative Cyclomatic Complexity',
            yaxis: 'Cyclomatic Complexity by Structure',
            csvSeries: [[
                exclusionValues: 'Cyclomatic Complexity / Lines of Code,Cyclomatic Complexity / Number of Methods',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d904.csv',
            group: 'phploc',
            title: 'E - Types of Classes',
            yaxis: 'Count',
            csvSeries: [[
                exclusionValues: 'Classes,Abstract Classes,Concrete Classes',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d905.csv',
            group: 'phploc',
            title: 'F - Types of Methods',
            yaxis: 'Count',
            csvSeries: [[
                exclusionValues: 'Methods,Non-Static Methods,Static Methods,Public Methods,Non-Public Methods',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d906.csv',
            group: 'phploc',
            title: 'G - Types of Constants',
            yaxis: 'Count',
            csvSeries: [[
                exclusionValues: 'Constants,Global Constants,Class Constants',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d909.csv',
            group: 'phploc',
            title: 'H - Types of Functions',
            yaxis: 'Count',
            csvSeries: [[
                exclusionValues: 'Functions,Named Functions,Anonymous Functions',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d907.csv',
            group: 'phploc',
            title: 'I - Testing',
            yaxis: 'Count',
            csvSeries: [[
                exclusionValues: 'Test Classes,Test Methods',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d908.csv',
            group: 'phploc',
            title: 'AB - Code Structure by Logical Lines of Code',
            yaxis: 'Logical Lines of Code',
            csvSeries: [[
                exclusionValues: 'Logical Lines of Code (LLOC),Classes Length (LLOC),Functions Length (LLOC),LLOC outside functions or classes',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
          plot csvFileName: 'plot-7cc376ec-b513-455f-84d2-97e68468d90a.csv',
            group: 'phploc',
            title: 'BB - Structure Objects',
            yaxis: 'Count',
            csvSeries: [[
                exclusionValues: 'Interfaces,Traits,Classes,Methods,Functions,Constants',
                file: 'build/logs/phploc.csv',
                inclusionFlag: 'INCLUDE_BY_STRING', displayTableFlag: false, url: ''
            ]],
            numBuilds: '100', style: 'line', exclZero: false, keepRecords: false, logarithmic: false, yaxisMaximum: '', yaxisMinimum: ''
        }
      } 
    }
  }  
}
