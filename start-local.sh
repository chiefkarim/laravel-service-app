#!/bin/bash

# Check for required tools
if ! command -v composer &>/dev/null; then
    echo "❌ Composer is not installed. Please install Composer."
    exit 1
fi

if ! command -v pnpm &>/dev/null; then
    echo "❌ pnpm is not installed. Please install pnpm."
    exit 1
fi

# Start backend
echo "🚀 Starting backend (Laravel API)..."
(cd ./api && composer run dev) &

# Start frontend
echo "🚀 Starting frontend (Vue SPA)..."
(cd ./client && pnpm run dev) &

# Wait for both processes to keep the script running
wait