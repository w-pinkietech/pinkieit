# GitHub Actions Workflows

This directory contains automated workflows for the PinkieIT project.

## Workflows

### sonarqube.yml
Automated test coverage generation and SonarQube Cloud analysis.

**Triggers:**
- Push to main branch
- Pull requests

**Features:**
- PHPUnit test execution with PCOV coverage
- Coverage report upload to SonarQube Cloud
- Quality gate enforcement
- Automated feedback on PRs

**Required Secrets:**
- `SONAR_TOKEN`: SonarQube authentication token

See [CI/CD Workflow Documentation](/docs/CI_CD_WORKFLOW.md) for detailed information.