#!/bin/bash
npx serve -s . -p ${PORT:-3000}

---chmod +x start.sh

## 🎯 **Estructura final del proyecto:**
```
CLASEAOP/
├── package.json          ← NUEVO
├── start.sh              ← NUEVO (opcional)
├── assets/
│   └── logo.png
├── encuesta/
│   ├── encuesta.html
│   └── encuesta.css
└── inicio/
    ├── index.html
    └── styles.css