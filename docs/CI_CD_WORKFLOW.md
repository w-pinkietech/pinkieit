# CI/CD Workflow Documentation

This document describes the automated test coverage and SonarQube analysis workflow for the PinkieIT project.

## Overview

The GitHub Actions workflow automatically:
1. Runs PHPUnit tests with coverage on every push to main and on pull requests
2. Uploads coverage reports to SonarQube Cloud
3. Checks quality gates to ensure code meets standards

## Workflow Configuration

### File Location
`.github/workflows/sonarqube.yml`

### Triggers
- **Push to main branch**: Runs full analysis on merged code
- **Pull requests**: Runs analysis on proposed changes (opened, synchronize, reopened)

### Workflow Steps

1. **Environment Setup**
   - Ubuntu latest runner
   - MySQL 8.0 service container for tests
   - PHP 8.0 with PCOV extension for coverage
   - Node.js 16 for asset building

2. **Dependency Installation**
   - Composer dependencies with caching
   - NPM dependencies with caching
   - Laravel Mix asset compilation

3. **Test Execution**
   - Database migrations
   - PHPUnit tests with coverage report generation
   - Coverage report path fixing for SonarQube

4. **SonarQube Analysis**
   - Scan with coverage report upload
   - Quality gate check (fails pipeline if standards not met)

## Required Secrets

The following secrets must be configured in GitHub repository settings:

- `SONAR_TOKEN`: SonarQube authentication token
  - Get from: SonarCloud → My Account → Security → Generate Token
  - Set in: GitHub repo → Settings → Secrets and variables → Actions

## Quality Gates

SonarQube enforces quality standards:
- Minimum code coverage percentage
- No new bugs
- No new vulnerabilities
- No new code smells above threshold
- Technical debt ratio within limits

## Monitoring

### Pipeline Status
- Check Actions tab in GitHub repository
- Green checkmark = passed, Red X = failed

### Coverage Trends
- View in SonarCloud dashboard
- Project → Measures → Coverage

### Notifications
- GitHub will notify on PR checks
- SonarQube can send additional notifications if configured

## Troubleshooting

### Common Issues

1. **Coverage not appearing in SonarQube**
   - Check coverage report was generated: Look for "Run tests with coverage" step
   - Verify path in sonar-project.properties matches generated report location

2. **Quality gate failing**
   - Check SonarQube dashboard for specific issues
   - Review new code coverage percentage
   - Fix identified bugs/vulnerabilities

3. **Database connection errors**
   - Ensure MySQL service is healthy in workflow logs
   - Check database credentials match in workflow

### Debugging Steps

1. Enable debug logging:
   ```yaml
   - name: SonarQube Scan
     uses: SonarSource/sonarqube-scan-action@master
     env:
       SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}
       SONAR_HOST_URL: https://sonarcloud.io
       SONAR_SCANNER_OPTS: -Dsonar.verbose=true
   ```

2. Check workflow logs in GitHub Actions tab

3. Review SonarQube analysis logs in SonarCloud

## Local Testing

To test the workflow locally before pushing:

```bash
# Install act (GitHub Actions local runner)
brew install act  # or appropriate package manager

# Run workflow locally
act -j sonarqube --secret-file .secrets
```

Create `.secrets` file (don't commit!):
```
SONAR_TOKEN=your_token_here
GITHUB_TOKEN=your_github_token
```