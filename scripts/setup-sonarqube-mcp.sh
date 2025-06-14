#!/bin/bash

# SonarQube MCP Server Setup Script
# This script helps set up SonarQube MCP integration for Claude Code

set -e

echo "🔧 Setting up SonarQube MCP Server integration..."

# Create MCP directory if it doesn't exist
mkdir -p .mcp

# Check if Docker is available
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is required but not installed. Please install Docker first."
    exit 1
fi

echo "✅ Docker is available"

# Check if SonarQube token is set
if [ -z "$SONARQUBE_TOKEN" ]; then
    echo "⚠️  SONARQUBE_TOKEN environment variable is not set"
    echo "📋 To get your token:"
    echo "   1. Go to https://sonarcloud.io/account/security"
    echo "   2. Generate a new token"
    echo "   3. Export it: export SONARQUBE_TOKEN='your_token_here'"
    echo ""
    echo "📝 Or copy .mcp/.env.template to .mcp/.env and fill in your token"
    
    if [ ! -f ".mcp/.env" ]; then
        echo "📄 Creating .env template..."
        cp .mcp/.env.template .mcp/.env
        echo "✅ Created .mcp/.env - please edit it with your SonarQube token"
    fi
    
    exit 1
fi

echo "✅ SonarQube token is configured"

# Test Docker connectivity
echo "🐳 Testing Docker connectivity..."
if ! docker info &> /dev/null; then
    echo "❌ Cannot connect to Docker. Make sure Docker is running."
    exit 1
fi

echo "✅ Docker is running"

# Pull the latest SonarQube MCP server image
echo "📥 Pulling SonarQube MCP server image..."
docker pull sonarsource/sonarqube-mcp-server:latest

echo "✅ SonarQube MCP server image pulled successfully"

# Test the MCP server connection
echo "🔌 Testing SonarQube MCP server connection..."
if docker run --rm \
    -e SONARQUBE_TOKEN="$SONARQUBE_TOKEN" \
    -e SONARQUBE_ORG="w-pinkietech" \
    -e SONARQUBE_URL="https://sonarcloud.io" \
    -e TELEMETRY_DISABLED=true \
    sonarsource/sonarqube-mcp-server:latest \
    --help &> /dev/null; then
    echo "✅ SonarQube MCP server is working"
else
    echo "❌ Failed to run SonarQube MCP server. Please check your configuration."
    exit 1
fi

echo ""
echo "🎉 SonarQube MCP Server setup completed successfully!"
echo ""
echo "📋 Next steps:"
echo "   1. Make sure your Claude Code client supports MCP"
echo "   2. Configure Claude Code to use the MCP server from .mcp/sonarqube-config.json"
echo "   3. Start using SonarQube tools in your Claude Code sessions"
echo ""
echo "🔧 Available SonarQube MCP tools:"
echo "   - Analyze code snippets"
echo "   - Search project issues" 
echo "   - Get project metrics"
echo "   - Check quality gates"
echo "   - List supported languages"
echo ""
echo "📖 For more information, see: https://github.com/SonarSource/sonarqube-mcp-server"