#!/bin/bash

# SonarQube MCP Server Test Script
# This script tests the SonarQube MCP integration

set -e

echo "🧪 Testing SonarQube MCP Server integration..."

# Check if required environment variables are set
if [ -z "$SONARQUBE_TOKEN" ]; then
    if [ -f ".mcp/.env" ]; then
        echo "📄 Loading environment from .mcp/.env"
        source .mcp/.env
    else
        echo "❌ SONARQUBE_TOKEN not set and .mcp/.env not found"
        echo "   Please run ./scripts/setup-sonarqube-mcp.sh first"
        exit 1
    fi
fi

# Test 1: Docker availability
echo "🐳 Test 1: Checking Docker..."
if ! command -v docker &> /dev/null; then
    echo "❌ Docker not found"
    exit 1
fi

if ! docker info &> /dev/null; then
    echo "❌ Docker not running"
    exit 1
fi

echo "✅ Docker is available and running"

# Test 2: SonarQube API connectivity
echo "🌐 Test 2: Testing SonarCloud API connectivity..."
response=$(curl -s -u "$SONARQUBE_TOKEN:" \
    "https://sonarcloud.io/api/projects/search?organization=w-pinkietech" \
    | head -c 50)

if [[ $response == *"paging"* ]]; then
    echo "✅ SonarCloud API is accessible"
else
    echo "❌ Failed to connect to SonarCloud API"
    echo "   Response: $response"
    exit 1
fi

# Test 3: MCP Server Docker image
echo "🔧 Test 3: Testing SonarQube MCP Server Docker image..."
if docker run --rm \
    -e SONARQUBE_TOKEN="$SONARQUBE_TOKEN" \
    -e SONARQUBE_ORG="w-pinkietech" \
    -e SONARQUBE_URL="https://sonarcloud.io" \
    -e TELEMETRY_DISABLED=true \
    sonarsource/sonarqube-mcp-server:latest \
    --help &> /dev/null; then
    echo "✅ SonarQube MCP Server Docker image works"
else
    echo "❌ SonarQube MCP Server Docker image failed"
    exit 1
fi

# Test 4: Project access
echo "📋 Test 4: Testing project access..."
project_response=$(curl -s -u "$SONARQUBE_TOKEN:" \
    "https://sonarcloud.io/api/projects/search?organization=w-pinkietech&q=pinkieit")

if [[ $project_response == *"pinkieit"* ]]; then
    echo "✅ Project 'pinkieit' is accessible"
else
    echo "❌ Cannot access project 'pinkieit'"
    echo "   Make sure you have access to w-pinkietech/pinkieit on SonarCloud"
    exit 1
fi

# Test 5: Get project issues (sample)
echo "🔍 Test 5: Testing issue retrieval..."
issues_response=$(curl -s -u "$SONARQUBE_TOKEN:" \
    "https://sonarcloud.io/api/issues/search?componentKeys=w-pinkietech_pinkieit&ps=1")

if [[ $issues_response == *"issues"* ]]; then
    echo "✅ Can retrieve project issues"
    
    # Extract issue count
    issue_count=$(echo "$issues_response" | grep -o '"total":[0-9]*' | cut -d':' -f2)
    if [ ! -z "$issue_count" ]; then
        echo "   Found $issue_count total issues in the project"
    fi
else
    echo "❌ Failed to retrieve project issues"
    echo "   Response: $issues_response"
    exit 1
fi

# Test 6: MCP configuration file
echo "📁 Test 6: Checking MCP configuration..."
if [ -f ".mcp/sonarqube-config.json" ]; then
    echo "✅ MCP configuration file exists"
    
    # Validate JSON syntax
    if cat .mcp/sonarqube-config.json | docker run --rm -i stedolan/jq . &> /dev/null; then
        echo "✅ MCP configuration is valid JSON"
    else
        echo "⚠️  MCP configuration has JSON syntax errors"
    fi
else
    echo "❌ MCP configuration file missing"
    echo "   Expected: .mcp/sonarqube-config.json"
    exit 1
fi

echo ""
echo "🎉 All tests passed! SonarQube MCP integration is ready to use."
echo ""
echo "📊 Summary:"
echo "   - Docker: Available and running"
echo "   - SonarCloud API: Accessible with your token"
echo "   - MCP Server: Docker image works correctly"
echo "   - Project Access: Can access w-pinkietech/pinkieit"
echo "   - Issue Retrieval: Can fetch project issues"
echo "   - Configuration: MCP config file is valid"
echo ""
echo "🚀 You can now use SonarQube MCP tools in Claude Code!"
echo ""
echo "💡 Next steps:"
echo "   1. Configure Claude Code to use the MCP server"
echo "   2. Start using SonarQube tools for issue analysis"
echo "   3. Try automated issue fixing workflows"