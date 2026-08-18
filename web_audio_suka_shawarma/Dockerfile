# ==========================================
# STAGE 1 - BUILD REACT / VITE
# ==========================================
FROM node:22-alpine AS builder

WORKDIR /app

# Copy dependency
COPY package.json package-lock.json* ./

# Install dependency
RUN npm install

# Copy source code
COPY . .

# Build production
RUN npm run build


# ==========================================
# STAGE 2 - NGINX PRODUCTION
# ==========================================
FROM nginx:alpine

# Hapus config default
RUN rm /etc/nginx/conf.d/default.conf

# Copy hasil build Vite
COPY --from=builder /app/dist /usr/share/nginx/html

# Copy config nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Port internal container
EXPOSE 80

# Jalankan nginx
CMD ["nginx", "-g", "daemon off;"]