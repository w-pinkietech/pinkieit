# SonarQube Cloud Setup Guide

This guide explains how to configure SonarQube Cloud for the PinkieIT project to import and display test coverage reports.

## Prerequisites

- PHPUnit test coverage generation must be working (configured in `phpunit.xml`)
- SonarQube Cloud account and project created
- Coverage reports generated in Clover XML format at `app/laravel/coverage/clover.xml`

## Configuration

### 1. SonarQube Project Properties

The `sonar-project.properties` file in the root directory contains the configuration for SonarQube Cloud:

```properties
# Project identification
sonar.organization=w-pinkietech
sonar.projectKey=w-pinkietech_pinkieit
sonar.projectName=PinkieIT

# Source and test directories
sonar.sources=app/laravel/app
sonar.tests=app/laravel/tests

# PHP Coverage report path
sonar.php.coverage.reportPaths=app/laravel/coverage/clover.xml
```

### 2. Generating Coverage Reports

Before running SonarQube analysis, generate the coverage report:

```bash
cd app/laravel
php artisan test --coverage
```

This will create the `coverage/clover.xml` file that SonarQube will import.

### 3. Running SonarQube Analysis

#### Local Analysis
If you have SonarScanner installed locally:

```bash
# From project root
sonar-scanner \
  -Dsonar.host.url=https://sonarcloud.io \
  -Dsonar.login=YOUR_SONARQUBE_TOKEN
```

#### CI/CD Integration
The SonarQube analysis will be automatically run in CI/CD pipeline (to be configured in issue #19).

## Quality Gates

SonarQube Cloud will enforce quality gates based on:
- Code coverage percentage
- Code smells
- Security vulnerabilities
- Bugs
- Technical debt

## Troubleshooting

### Coverage Not Showing
1. Ensure coverage report exists: `app/laravel/coverage/clover.xml`
2. Check that the path in `sonar.php.coverage.reportPaths` is correct
3. Verify coverage report format is Clover XML

### Files Not Analyzed
Check the exclusions in `sonar-project.properties`:
- Vendor directories are excluded
- Blade templates are excluded (not PHP files)
- Migration files are excluded

## Additional Resources

- [SonarQube PHP Documentation](https://docs.sonarqube.org/latest/analysis/languages/php/)
- [SonarCloud Documentation](https://sonarcloud.io/documentation)
- [PHPUnit Coverage Documentation](https://phpunit.de/manual/current/en/code-coverage-analysis.html)