# SonarQube MCP Integration

This document describes the SonarQube Model Context Protocol (MCP) integration for autonomous code quality management.

## Overview

The SonarQube MCP integration enables Claude Code to:
- Fetch SonarQube issues directly from SonarCloud
- Analyze and prioritize code quality issues
- Generate automated fixes for common problems
- Create pull requests with quality improvements

## Architecture

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Claude Code   │───▶│  SonarQube MCP   │───▶│   SonarCloud    │
│                 │    │     Server       │    │                 │
└─────────────────┘    └──────────────────┘    └─────────────────┘
                              │
                              ▼
                       ┌──────────────────┐
                       │  Docker Container │
                       │  (MCP Runtime)    │
                       └──────────────────┘
```

## Setup Instructions

### Prerequisites

1. **Docker**: Ensure Docker is installed and running
2. **SonarCloud Token**: Generate a token from [SonarCloud Security Page](https://sonarcloud.io/account/security)
3. **Project Access**: Must have access to the `w-pinkietech/pinkieit` project on SonarCloud

### Installation

1. **Run the setup script**:
   ```bash
   # Set your SonarQube token
   export SONARQUBE_TOKEN="your_token_here"
   
   # Run setup
   ./scripts/setup-sonarqube-mcp.sh
   ```

2. **Configure environment** (alternative to export):
   ```bash
   # Copy template and edit
   cp .mcp/.env.template .mcp/.env
   # Edit .mcp/.env with your SonarQube token
   ```

3. **Verify installation**:
   ```bash
   docker run --rm \
     -e SONARQUBE_TOKEN="$SONARQUBE_TOKEN" \
     -e SONARQUBE_ORG="w-pinkietech" \
     -e SONARQUBE_URL="https://sonarcloud.io" \
     sonarsource/sonarqube-mcp-server:latest \
     --help
   ```

## Configuration Files

### MCP Server Configuration
- **Location**: `.mcp/sonarqube-config.json`
- **Purpose**: Defines MCP server connection settings
- **Environment Variables**:
  - `SONARQUBE_TOKEN`: Authentication token for SonarCloud
  - `SONARQUBE_ORG`: Organization key (`w-pinkietech`)
  - `SONARQUBE_URL`: SonarCloud URL (`https://sonarcloud.io`)

### Project Configuration
- **Location**: `sonar-project.properties`
- **Purpose**: Defines project-specific SonarQube settings
- **Key Settings**:
  - Project key: `w-pinkietech_pinkieit`
  - Source directories: `app/laravel/app`
  - Test directories: `app/laravel/tests`
  - Coverage reports: `app/laravel/coverage/clover.xml`

## Available MCP Tools

Once configured, Claude Code can use these SonarQube tools:

### Issue Management
- **`search_issues`**: Find issues by severity, type, or component
- **`get_issue_details`**: Get detailed information about specific issues
- **`get_project_issues`**: List all project issues with filters

### Code Analysis
- **`analyze_code`**: Analyze code snippets for quality issues
- **`get_supported_languages`**: List supported programming languages
- **`validate_code`**: Check code against quality rules

### Project Metrics
- **`get_project_metrics`**: Retrieve project-level metrics (coverage, duplications, etc.)
- **`get_quality_gate`**: Check quality gate status
- **`get_project_measures`**: Get specific project measures

### Quality Management
- **`get_quality_profiles`**: List active quality profiles
- **`get_rules`**: Search and retrieve quality rules
- **`get_hotspots`**: Find security hotspots

## Automation Workflows

### Issue Prioritization
1. **Security Issues**: Critical vulnerabilities and security hotspots
2. **Bug Issues**: Functional bugs that could cause runtime errors
3. **Code Smells**: Maintainability issues and technical debt
4. **Coverage Issues**: Areas with insufficient test coverage

### Automated Fix Generation
The MCP integration can generate fixes for:
- **Simple code smells** (unused variables, imports)
- **Formatting issues** (code style violations)
- **Basic security issues** (SQL injection patterns)
- **Performance issues** (inefficient loops, memory leaks)

### PR Creation Workflow
1. Fetch issues from SonarQube
2. Prioritize by severity and impact
3. Generate fixes for automatable issues
4. Create feature branch with fixes
5. Submit PR with detailed description
6. Run CI/CD to validate fixes

## Safety Features

### Automated Fix Constraints
- **File Size Limit**: Only fix files under 1000 lines
- **Change Scope**: Maximum 10% of file can be modified
- **Test Requirements**: Must not break existing tests
- **Review Requirements**: All automated PRs require human review

### Configuration Options
```json
{
  "auto_fix": {
    "enabled": true,
    "max_files_per_pr": 5,
    "severity_threshold": "MINOR",
    "exclude_patterns": ["*.blade.php", "vendor/*"],
    "require_tests": true
  }
}
```

## Integration with CI/CD

### GitHub Actions Integration
The SonarQube MCP works alongside existing CI/CD:

```yaml
# .github/workflows/sonarqube.yml (existing)
- name: SonarQube Scan
  uses: SonarSource/sonarqube-scan-action@master
  
# Enhanced with MCP integration
- name: Auto-fix SonarQube Issues
  run: claude-code mcp sonarqube auto-fix
```

### Quality Gate Integration
- **Blocking**: Quality gate failures prevent deployment
- **Notification**: Teams notified of quality issues
- **Auto-remediation**: MCP can fix issues before they fail quality gates

## Usage Examples

### Fetch Project Issues
```javascript
// Get all high-severity issues
const issues = await mcp.call('search_issues', {
  componentKeys: 'w-pinkietech_pinkieit',
  severities: 'CRITICAL,MAJOR'
});
```

### Analyze Code Snippet
```javascript
// Analyze a specific piece of code
const analysis = await mcp.call('analyze_code', {
  code: `<?php
function getUserData($id) {
    return mysql_query("SELECT * FROM users WHERE id = " . $id);
}`,
  language: 'php'
});
```

### Get Project Metrics
```javascript
// Get project quality metrics
const metrics = await mcp.call('get_project_metrics', {
  component: 'w-pinkietech_pinkieit',
  metricKeys: 'coverage,duplicated_lines_density,code_smells'
});
```

## Troubleshooting

### Common Issues

1. **Authentication Failed**
   - Verify SONARQUBE_TOKEN is correct
   - Check token permissions on SonarCloud
   - Ensure organization access (`w-pinkietech`)

2. **Docker Connection Issues**
   - Verify Docker is running: `docker info`
   - Check network connectivity
   - Pull latest image: `docker pull sonarsource/sonarqube-mcp-server:latest`

3. **MCP Server Not Found**
   - Verify Claude Code MCP support
   - Check MCP configuration in `.mcp/sonarqube-config.json`
   - Ensure environment variables are set

### Debug Commands

```bash
# Test SonarQube API connectivity
curl -u "$SONARQUBE_TOKEN:" \
  "https://sonarcloud.io/api/projects/search?organization=w-pinkietech"

# Test MCP server manually
docker run --rm -it \
  -e SONARQUBE_TOKEN="$SONARQUBE_TOKEN" \
  -e SONARQUBE_ORG="w-pinkietech" \
  -e SONARQUBE_URL="https://sonarcloud.io" \
  sonarsource/sonarqube-mcp-server:latest

# Check Docker logs
docker logs $(docker ps -q --filter ancestor=sonarsource/sonarqube-mcp-server)
```

## Security Considerations

### Token Management
- **Scope**: Use minimum required permissions
- **Rotation**: Rotate tokens regularly (every 90 days)
- **Storage**: Never commit tokens to version control
- **Environment**: Use environment variables or secure vaults

### Access Control
- **Project Access**: Limit to specific projects
- **Organization**: Restrict to `w-pinkietech` organization
- **API Limits**: Respect SonarCloud API rate limits

### Automated Changes
- **Review Required**: All automated PRs require human review
- **Testing**: Automated fixes must pass all tests
- **Rollback**: Easy rollback mechanism for problematic changes

## References

- [SonarQube MCP Server](https://github.com/SonarSource/sonarqube-mcp-server)
- [SonarCloud Web API](https://docs.sonarqube.org/latest/extend/web-api/)
- [Model Context Protocol](https://modelcontextprotocol.io/)
- [Claude Code Documentation](https://docs.anthropic.com/en/docs/claude-code)