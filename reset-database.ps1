# Reset Database Script
# This script will drop and recreate the database with fresh data

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Computer Shop - Database Reset" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if Docker containers are running
Write-Host "Checking Docker containers..." -ForegroundColor Yellow
$containerStatus = docker ps --filter "name=computer_shop_mysql" --format "{{.Status}}"

if (-not $containerStatus) {
    Write-Host "Error: MySQL container is not running!" -ForegroundColor Red
    Write-Host "Please start Docker containers first with: docker-compose up -d" -ForegroundColor Yellow
    exit 1
}

Write-Host "MySQL container is running" -ForegroundColor Green
Write-Host ""

# Confirm with user
Write-Host "WARNING: This will DELETE all existing data!" -ForegroundColor Red
$confirmation = Read-Host "Are you sure you want to reset the database? (yes/no)"

if ($confirmation -ne "yes") {
    Write-Host "Operation cancelled." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "Resetting database..." -ForegroundColor Yellow
Write-Host "Step 1: Copying schema file to container..." -ForegroundColor Cyan

# Copy schema.sql to container
docker cp src/Database/schema.sql computer_shop_mysql:/tmp/schema.sql

if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to copy schema file!" -ForegroundColor Red
    exit 1
}

Write-Host "Step 2: Importing database..." -ForegroundColor Cyan

# Import schema.sql from inside the container
docker exec computer_shop_mysql mysql -uroot -proot --default-character-set=utf8mb4 -e "source /tmp/schema.sql"

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "  Database reset successful!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Database has been reset with:" -ForegroundColor Cyan
    Write-Host "  - 10 Product categories" -ForegroundColor White
    Write-Host "  - 15 Sample products with full image URLs" -ForegroundColor White
    Write-Host "  - 2 Admin users (admin, admin_bao)" -ForegroundColor White
    Write-Host "  - 1 Customer user" -ForegroundColor White
    Write-Host ""
    Write-Host "You can now access the application at: http://localhost:8080" -ForegroundColor Cyan
}
else {
    Write-Host ""
    Write-Host "Error: Failed to reset database!" -ForegroundColor Red
    Write-Host "Please check the error messages above." -ForegroundColor Yellow
    exit 1
}
