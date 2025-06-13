# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Common Development Commands

### Build and Development
```bash
# Frontend development (Laravel Mix)
npm run dev              # Development build
npm run prod             # Production build  
npm run watch            # Watch for changes

# PHP dependencies
composer install         # Install dependencies
composer update          # Update dependencies
```

### Code Quality and Testing
```bash
# JavaScript linting
npm run lint             # Run semistandard linter with snazzy output

# PHP code quality
./vendor/bin/pint        # Laravel Pint for code formatting
./vendor/bin/phpstan analyse --memory-limit=512M  # Larastan static analysis

# Run pint to ensure coding styles
./vendor/bin/pint

# Static Analysis with Larastan (PHPStan for Laravel)
# Configuration file: phpstan.neon (rule level 5)
./vendor/bin/phpstan analyse  # Run static analysis
./vendor/bin/phpstan analyse --memory-limit=512M  # Run with increased memory
./vendor/bin/phpstan analyse app/  # Analyze specific directory
./vendor/bin/phpstan analyse --level=max  # Run with maximum rule level

# Generate reports for SonarQube integration
mkdir -p reports
./vendor/bin/phpstan analyse --memory-limit=512M --error-format=json > reports/phpstan.json

# Note: When using Docker, prefix commands with:
# docker compose exec web-app [command]

# Testing
php artisan test         # Run PHPUnit tests (now available)
php artisan test --coverage  # Run tests with coverage report
php artisan dusk         # Run Laravel Dusk browser tests

# SonarQube Analysis
# Generate coverage report first, then run analysis
php artisan test --coverage
sonar-scanner -Dsonar.host.url=https://sonarcloud.io -Dsonar.login=YOUR_TOKEN
```

### Database Operations
```bash
php artisan migrate              # Run database migrations
php artisan migrate:rollback     # Rollback last migration
php artisan db:seed              # Seed database with test data
```

### Laravel Commands
```bash
php artisan optimize:clear       # Clear all caches
php artisan config:cache         # Cache configuration
php artisan route:cache          # Cache routes
php artisan make:user [role] [email] [password]  # Create new user
```

### Docker Operations
```bash
docker compose up -d --build     # Build and start all services
docker compose exec web-app [command]  # Execute command in web container
docker compose logs -f web-app   # View application logs
docker compose stats             # Monitor container resources
```

## Architecture Overview

### System Purpose
PinkieIT is a Production Management System (MES) designed for factory floor monitoring with real-time production tracking, visual management (Andon boards), and IoT device integration via MQTT.

### Key Technologies
- **Backend**: Laravel 9.x with PHP 8.0.2+
- **Database**: MariaDB 10.11.4
- **Real-time**: Laravel WebSockets + Pusher protocol
- **IoT Communication**: MQTT (Eclipse Mosquitto)
- **Frontend**: Bootstrap 5, jQuery, Chart.js, AdminLTE 3 theme
- **Asset Building**: Laravel Mix with Sass
- **Code Quality**: PHPUnit with PCOV for coverage, Larastan (PHPStan) for static analysis, SonarQube Cloud for analysis

### Core Architecture Patterns

1. **Repository Pattern**: Business logic is separated from data access
   - Models in `app/Models/`
   - Repositories in `app/Repositories/`
   - Services in `app/Services/`

2. **Event-Driven Broadcasting**: Real-time updates via WebSockets
   - Events in `app/Events/`
   - Broadcast on channels for production updates
   - Frontend listens via Laravel Echo

3. **Job Queue System**: Asynchronous processing
   - Jobs in `app/Jobs/` for production tracking
   - Handles MQTT messages and production calculations

4. **Data Transfer Objects**: Type-safe data handling
   - DTOs in `app/Data/` using spatie/laravel-data
   - Used for API responses and data validation

5. **MQTT Integration**: IoT device communication
   - Console command `app/Console/Commands/MqttSubscriber.php`
   - Subscribes to production/# topics
   - Processes device messages for real-time counting

### Key Business Concepts
- **Production Lines**: Manufacturing lines with real-time monitoring
- **Andon Boards**: Visual management displays for production status
- **Production Counting**: Automated counting via MQTT messages
- **Efficiency Metrics**: OEE, cycle time, production rate calculations
- **Defect Tracking**: Quality control with defective product monitoring

## Updating this File

If you think you need to update this file or the programmer ask to do so, update this rules to adapt to the new changes. This file is meant to be a living document that evolves with the project.

## Development Guidelines
- **Always read and follow editorconfig.**