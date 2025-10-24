#!/bin/bash

# Script de inicio para Creatividad Laser
echo "🚀 Iniciando Creatividad Laser..."

if ! command -v npx &> /dev/null; then
    echo "❌ Error: npx no está instalado. Instala Node.js primero."
    exit 1
fi

if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependencias..."
    npm install
fi

PORT=${PORT:-3000}

echo "✅ Servidor iniciando en puerto $PORT"
echo "🌐 Abre: http://localhost:$PORT"

npx serve -s . -p $PORT -c serve.json