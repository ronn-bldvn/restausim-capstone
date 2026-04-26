import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import os from "os";

function getLocalNetworkIP() {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === "IPv4" && !iface.internal) {
                return iface.address;
            }
        }
    }
    return "127.0.0.1";
}

const localIP = getLocalNetworkIP();

export default defineConfig({
    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            host: localIP,
        },
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        {
            // Custom plugin to log local access URLs when Vite starts
            name: "show-local-ip",
            configureServer(server) {
                server.httpServer?.once("listening", () => {
                    const port = server.config.server.port;
                    console.log(
                        "\n📱  Your app is available on your local network:"
                    );
                    console.log(`   👉  http://${localIP}:${port}/`);
                    console.log("💻  And locally at:");
                    console.log(`   👉  http://localhost:${port}/\n`);
                });
            },
        },
    ],
});
