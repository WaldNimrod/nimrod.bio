#!/bin/bash
# WordPress Development Environment Manager
# ===========================================
# ניהול סביבת פיתוח וורדפרס עם Docker

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Project directory
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_DIR"

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if Docker is running
check_docker() {
    if ! docker info >/dev/null 2>&1; then
        print_error "Docker is not running. Please start Docker Desktop first."
        exit 1
    fi
}

# Function to show help
show_help() {
    echo "WordPress Development Environment Manager"
    echo "=========================================="
    echo ""
    echo "Usage: $0 [COMMAND]"
    echo ""
    echo "Commands:"
    echo "  start     - Start WordPress development environment"
    echo "  stop      - Stop WordPress development environment"
    echo "  restart   - Restart WordPress development environment"
    echo "  status    - Show status of all services"
    echo "  logs      - Show logs from all services"
    echo "  backup    - Create backup of WordPress data"
    echo "  restore   - Restore WordPress from backup"
    echo "  clean     - Clean up containers and volumes"
    echo "  shell     - Open shell in WordPress container"
    echo "  db        - Open MySQL shell"
    echo "  phpmyadmin - Open phpMyAdmin in browser"
    echo "  help      - Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0 start"
    echo "  $0 logs -f"
    echo "  $0 backup my-backup"
}

# Function to start WordPress
start_wordpress() {
    print_status "Starting WordPress development environment..."
    check_docker
    
    # Pull latest images
    print_status "Pulling latest Docker images..."
    docker-compose pull
    
    # Start services
    print_status "Starting services..."
    docker-compose up -d
    
    # Wait for services to be ready
    print_status "Waiting for services to be ready..."
    sleep 10
    
    # Check if services are running
    if docker-compose ps | grep -q "Up"; then
        print_success "WordPress development environment started successfully!"
        echo ""
        echo "🌐 WordPress: http://localhost:8081"
        echo "🗄️  phpMyAdmin: http://localhost:8082"
        echo "📊 MySQL: localhost:3307"
        echo ""
        print_status "Use '$0 logs' to view logs"
    else
        print_error "Failed to start WordPress environment"
        exit 1
    fi
}

# Function to stop WordPress
stop_wordpress() {
    print_status "Stopping WordPress development environment..."
    docker-compose down
    print_success "WordPress development environment stopped"
}

# Function to restart WordPress
restart_wordpress() {
    print_status "Restarting WordPress development environment..."
    stop_wordpress
    sleep 2
    start_wordpress
}

# Function to show status
show_status() {
    print_status "WordPress Development Environment Status:"
    echo ""
    docker-compose ps
    echo ""
    
    # Show resource usage
    print_status "Resource Usage:"
    docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.NetIO}}\t{{.BlockIO}}" $(docker-compose ps -q) 2>/dev/null || echo "No containers running"
}

# Function to show logs
show_logs() {
    if [ "$1" = "-f" ]; then
        print_status "Following logs (Ctrl+C to stop)..."
        docker-compose logs -f
    else
        print_status "Recent logs:"
        docker-compose logs --tail=50
    fi
}

# Function to create backup
create_backup() {
    local backup_name=${1:-"backup-$(date +%Y%m%d-%H%M%S)"}
    local backup_dir="./backups"
    
    print_status "Creating backup: $backup_name"
    
    # Create backup directory
    mkdir -p "$backup_dir"
    
    # Backup WordPress files
    print_status "Backing up WordPress files..."
    docker-compose exec -T wordpress tar czf - /var/www/html > "$backup_dir/${backup_name}-files.tar.gz"
    
    # Backup database
    print_status "Backing up database..."
    docker-compose exec -T mysql mysqldump -u wordpress -pwordpress123 wordpress > "$backup_dir/${backup_name}-database.sql"
    
    print_success "Backup created: $backup_dir/$backup_name"
}

# Function to restore backup
restore_backup() {
    local backup_name=$1
    
    if [ -z "$backup_name" ]; then
        print_error "Please specify backup name"
        echo "Available backups:"
        ls -la ./backups/ 2>/dev/null || echo "No backups found"
        exit 1
    fi
    
    print_warning "This will overwrite current WordPress data. Continue? (y/N)"
    read -r response
    if [[ ! "$response" =~ ^[Yy]$ ]]; then
        print_status "Restore cancelled"
        exit 0
    fi
    
    print_status "Restoring backup: $backup_name"
    
    # Restore files
    if [ -f "./backups/${backup_name}-files.tar.gz" ]; then
        print_status "Restoring WordPress files..."
        docker-compose exec -T wordpress tar xzf - < "./backups/${backup_name}-files.tar.gz"
    fi
    
    # Restore database
    if [ -f "./backups/${backup_name}-database.sql" ]; then
        print_status "Restoring database..."
        docker-compose exec -T mysql mysql -u wordpress -pwordpress123 wordpress < "./backups/${backup_name}-database.sql"
    fi
    
    print_success "Backup restored: $backup_name"
}

# Function to clean up
clean_wordpress() {
    print_warning "This will remove all containers, volumes, and data. Continue? (y/N)"
    read -r response
    if [[ ! "$response" =~ ^[Yy]$ ]]; then
        print_status "Cleanup cancelled"
        exit 0
    fi
    
    print_status "Cleaning up WordPress environment..."
    docker-compose down -v --remove-orphans
    docker system prune -f
    print_success "WordPress environment cleaned up"
}

# Function to open shell
open_shell() {
    print_status "Opening shell in WordPress container..."
    docker-compose exec wordpress bash
}

# Function to open database shell
open_db_shell() {
    print_status "Opening MySQL shell..."
    docker-compose exec mysql mysql -u wordpress -pwordpress123 wordpress
}

# Function to open phpMyAdmin
open_phpmyadmin() {
    print_status "Opening phpMyAdmin in browser..."
    open http://localhost:8082
}

# Main script logic
case "${1:-help}" in
    "start")
        start_wordpress
        ;;
    "stop")
        stop_wordpress
        ;;
    "restart")
        restart_wordpress
        ;;
    "status")
        show_status
        ;;
    "logs")
        show_logs "$2"
        ;;
    "backup")
        create_backup "$2"
        ;;
    "restore")
        restore_backup "$2"
        ;;
    "clean")
        clean_wordpress
        ;;
    "shell")
        open_shell
        ;;
    "db")
        open_db_shell
        ;;
    "phpmyadmin")
        open_phpmyadmin
        ;;
    "help"|*)
        show_help
        ;;
esac
