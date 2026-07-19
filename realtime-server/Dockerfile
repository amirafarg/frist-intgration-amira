FROM node:20-alpine

WORKDIR /app

# Copy dependency manifests first for layer-cache efficiency
COPY package.json ./

RUN npm install --omit=dev

COPY . .

EXPOSE 3000

CMD ["node", "server.js"]
