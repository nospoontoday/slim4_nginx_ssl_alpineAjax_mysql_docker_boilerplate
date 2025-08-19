<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test - Sugar Clone</title>
    <script src="https://unpkg.com/htmx.org@2.0.3"></script>
    <script src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .status-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .status-healthy { border-left: 4px solid #10b981; }
        .status-degraded { border-left: 4px solid #f59e0b; }
        .status-unhealthy { border-left: 4px solid #ef4444; }
        .status-unknown { border-left: 4px solid #6b7280; }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-healthy .status-indicator { background: #10b981; }
        .status-degraded .status-indicator { background: #f59e0b; }
        .status-unhealthy .status-indicator { background: #ef4444; }
        .status-unknown .status-indicator { background: #6b7280; }
        
        .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 12px;
        }
        .btn:hover { background: #1d4ed8; }
        .btn:disabled { background: #9ca3af; cursor: not-allowed; }
        
        .metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin: 20px 0;
        }
        .metric {
            background: #f9fafb;
            padding: 16px;
            border-radius: 6px;
            text-align: center;
        }
        .metric-value {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        .metric-label {
            color: #6b7280;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .recommendations {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 16px;
            margin-top: 20px;
        }
        .recommendations h3 {
            margin-top: 0;
            color: #92400e;
        }
        .recommendations ul {
            margin: 0;
            padding-left: 20px;
        }
        .recommendations li {
            margin-bottom: 8px;
            color: #92400e;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #2563eb;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container" x-data="databaseTest()">
        <div class="header">
            <h1>🔍 Database Connection Test</h1>
            <p>Monitor and test your database connections</p>
        </div>
        
        <div class="content">
            <div class="actions">
                <button class="btn" @click="runHealthCheck()" :disabled="loading">
                    🏥 Run Health Check
                </button>
                <button class="btn" @click="testConnections()" :disabled="loading">
                    🔌 Test Connections
                </button>
                <button class="btn" @click="clearResults()" :disabled="loading">
                    🗑️ Clear Results
                </button>
            </div>
            
            <div x-show="loading" class="loading">
                <div class="spinner"></div>
                <p>Running health check...</p>
            </div>
            
            <div x-show="!loading && results" x-transition>
                <div class="status-card" :class="'status-' + results.data.status">
                    <h2>
                        <span class="status-indicator"></span>
                        Overall Status: <span x-text="results.data.status.toUpperCase()"></span>
                    </h2>
                    <p>Last checked: <span x-text="formatTimestamp(results.data.timestamp)"></span></p>
                </div>
                
                <div class="metrics">
                    <div class="metric">
                        <div class="metric-value" x-text="results.data.performance.response_time_ms + 'ms'"></div>
                        <div class="metric-label">Response Time</div>
                    </div>
                    <div class="metric">
                        <div class="metric-value" x-text="results.data.performance.memory_usage_mb + 'MB'"></div>
                        <div class="metric-label">Memory Usage</div>
                    </div>
                    <div class="metric">
                        <div class="metric-value" x-text="results.data.performance.peak_memory_mb + 'MB'"></div>
                        <div class="metric-label">Peak Memory</div>
                    </div>
                </div>
                
                <div class="status-card" :class="'status-' + results.data.mysql.status">
                    <h3>MySQL Database</h3>
                    <p><strong>Status:</strong> <span x-text="results.data.mysql.status.toUpperCase()"></span></p>
                    <p><strong>Response Time:</strong> <span x-text="results.data.mysql.response_time_ms + 'ms'"></span></p>
                    <p><strong>Max Connections:</strong> <span x-text="results.data.mysql.max_connections"></span></p>
                    <p><strong>Current Connections:</strong> <span x-text="results.data.mysql.current_connections"></span></p>
                    <p><strong>Usage:</strong> <span x-text="results.data.mysql.connection_usage_percent + '%'"></span></p>
                </div>
                
                <div class="status-card" :class="'status-' + results.data.redis.status">
                    <h3>Redis Cache</h3>
                    <p><strong>Status:</strong> <span x-text="results.data.redis.status.toUpperCase()"></span></p>
                    <p><strong>Response Time:</strong> <span x-text="results.data.redis.response_time_ms + 'ms'"></span></p>
                    <p><strong>Version:</strong> <span x-text="results.data.redis.version"></span></p>
                    <p><strong>Uptime:</strong> <span x-text="formatUptime(results.data.redis.uptime_seconds)"></span></p>
                    <p><strong>Connected Clients:</strong> <span x-text="results.data.redis.connected_clients"></span></p>
                    <p><strong>Memory Usage:</strong> <span x-text="results.data.redis.used_memory_human"></span></p>
                </div>
                
                <div x-show="results.data.recommendations && results.data.recommendations.length > 0" class="recommendations">
                    <h3>💡 Recommendations</h3>
                    <ul>
                        <template x-for="rec in results.data.recommendations" :key="rec">
                            <li x-text="rec"></li>
                        </template>
                    </ul>
                </div>
            </div>
            
            <div x-show="!loading && !results" class="loading">
                <p>Click "Run Health Check" to start monitoring your database connections.</p>
            </div>
        </div>
    </div>
    
    <script>
        function databaseTest() {
            return {
                loading: false,
                results: null,
                
                async runHealthCheck() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/v1/health/database');
                        const data = await response.json();
                        
                        if (data.success) {
                            this.results = data;
                        } else {
                            alert('Health check failed: ' + data.message);
                        }
                    } catch (error) {
                        alert('Error running health check: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async testConnections() {
                    this.loading = true;
                    try {
                        // Test basic connectivity
                        const mysqlResponse = await fetch('/api/v1/health/database');
                        const data = await mysqlResponse.json();
                        
                        if (data.success) {
                            alert('✅ All connections are working properly!');
                        } else {
                            alert('❌ Connection test failed: ' + data.message);
                        }
                    } catch (error) {
                        alert('❌ Connection test failed: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },
                
                clearResults() {
                    this.results = null;
                },
                
                formatTimestamp(timestamp) {
                    return new Date(timestamp).toLocaleString();
                },
                
                formatUptime(seconds) {
                    if (seconds === 'unknown') return 'Unknown';
                    const hours = Math.floor(seconds / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    return `${hours}h ${minutes}m`;
                }
            }
        }
    </script>
</body>
</html>
