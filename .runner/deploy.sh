#!/bin/bash
set -e

echo "Deployment started ..."

# Get the branch name
branch=$(git rev-parse --abbrev-ref HEAD)

# Define the project directory based on the branch
if [ "$branch" == "prod" ]; then
    project_dir=/var/www/html/Plus-91-backend
elif [ "$branch" == "staging" ]; then
    project_dir=/home/u682428483/domains/91.marioxsoftwaredevelopmentagency.com/public_html
elif [ "$branch" == "qa" ]; then
    project_dir=/home/u682428483/domains/91-qa.marioxsoftwaredevelopmentagency.com/public_html
else
    echo "Unsupported branch: $branch"
    exit 1
fi

# Check if the directory exists
if [ ! -d "$project_dir" ]; then
    echo "Project directory not found: $project_dir"
    exit 1
fi

# Move to the project directory
cd "$project_dir"

# Enter maintenance mode or return true if already in maintenance mode
php artisan down &>/dev/null || true

# Check for local changes and stash them
if [ -n "$(git status --porcelain)" ]; then
    echo "Stashing local changes..."
    git stash push -m "Auto-stash before pull"
fi

# Pull the latest version of the app based on the branch
git pull origin "$branch"

# Apply stashed changes, if any
if git stash list | grep -q "Auto-stash before pull"; then
    echo "Applying stashed changes..."
    git stash pop
fi

# Check if .env file exists
if [ ! -f .env ]; then
    # If .env file does not exist, create it
    cp .env.example .env
    php artisan key:generate
fi

# Check if composer.json file exists before running composer update
if [ -f composer.json ]; then
    # update composer dependencies
    composer update
fi

# Clear the old cache
php artisan optimize:clear

# Exit maintenance mode
php artisan up
php artisan route:clear
php artisan config:clear

echo "Deployment finished!"