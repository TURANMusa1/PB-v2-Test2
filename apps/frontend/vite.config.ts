import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  define: {
    'process.env.VITE_API_GATEWAY_URL': JSON.stringify('http://localhost:8000/api'),
    'process.env.VITE_AUTH_SERVICE_URL': JSON.stringify('http://localhost:8002/api'),
    'process.env.VITE_CANDIDATE_SERVICE_URL': JSON.stringify('http://localhost:8003/api'),
    'process.env.VITE_NOTIFICATION_SERVICE_URL': JSON.stringify('http://localhost:8004/api'),
  }
})
