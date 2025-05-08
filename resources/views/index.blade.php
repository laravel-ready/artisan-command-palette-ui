<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisan Commands</title>
    <link rel="icon" href="https://fav.farm/👻" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'terminal': {
                            DEFAULT: '#1e1e1e',
                            'header': '#2d2d2d',
                            'border': '#3d3d3d'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-screen overflow-hidden flex flex-col bg-gray-50">
    <div class="flex flex-col h-screen p-4">
        <div class="flex-none mb-4">
            <div class="flex flex-row gap-3 items-center text-2xl font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">
                    <path d="M11.2019 4.17208C11.711 3.94264 12.289 3.94264 12.7981 4.17208L21.3982 8.04851C22.2006 8.41016 22.2006 9.58984 21.3982 9.95149L12.7981 13.8279C12.289 14.0574 11.711 14.0574 11.2019 13.8279L2.60175 9.95149C1.79941 9.58984 1.79942 8.41016 2.60176 8.04851L11.2019 4.17208Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M20.1813 13.5L21.3982 14.0485C22.2006 14.4102 22.2006 15.5898 21.3982 15.9515L12.7981 19.8279C12.289 20.0574 11.711 20.0574 11.2019 19.8279L2.60175 15.9515C1.79941 15.5898 1.79942 14.4102 2.60176 14.0485L3.81867 13.5" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>

                <span>
                    Artisan Commands: {{ config('app.env') }}
                </span>
            </div>
        </div>

        <div id="output-global" class="fixed top-4 right-4 z-50 max-w-sm hidden"></div>

        <div class="flex-none h-[45vh] mb-4">
            <div class="w-full overflow-x-auto whitespace-nowrap scrollbar-thin">
                <div class="inline-flex gap-4">
                    @foreach($commands as $group => $groupCommands)
                        @if(count($groupCommands) > 0)
                        <div class="flex-none w-[300px] bg-white p-4 rounded-lg border border-gray-200 h-[43vh] overflow-y-auto">
                            <div class="text-base font-semibold mb-3 text-gray-700 sticky top-0 bg-white py-2 border-b-2 border-gray-200">
                                {{ $group }}
                            </div>
                            <div class="space-y-2">
                                @foreach($groupCommands as $command)
                                    <div>
                                        <button
                                            class="w-full px-2 py-1 text-sm text-left border border-blue-500 text-blue-500 hover:bg-blue-50 rounded truncate transition-colors command-btn"
                                            data-command="{{ $command['command'] }}"
                                            data-tooltip="{{ $command['description'] }}"
                                        >
                                            {{ $command['command'] }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex-1 bg-terminal rounded-lg overflow-hidden flex flex-col min-h-[200px]">
            <div class="bg-terminal-header px-4 py-3 flex justify-between items-center border-b border-terminal-border">
                <div class="text-gray-200">
                    <span id="current-command">Command Output</span>
                    <span id="command-status"></span>
                </div>
                <button class="bg-terminal-border hover:bg-gray-600 text-gray-200 px-3 py-1 rounded text-sm transition-colors" id="clear-logs">
                    Clear Logs
                </button>
            </div>
            <div class="p-4 font-mono text-sm overflow-y-auto flex-1 whitespace-pre-wrap text-gray-200" id="logs-output">No command executed yet.</div>
        </div>
    </div>

    <div id="tooltip" class="absolute hidden z-50 max-w-xs bg-gray-900 text-white text-sm rounded-md px-3 py-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom tooltip implementation
            const tooltip = document.getElementById('tooltip');
            document.querySelectorAll('[data-tooltip]').forEach(element => {
                element.addEventListener('mouseenter', (e) => {
                    tooltip.textContent = e.target.dataset.tooltip;
                    tooltip.style.display = 'block';

                    const rect = e.target.getBoundingClientRect();
                    tooltip.style.left = `${rect.right + 10}px`;
                    tooltip.style.top = `${rect.top}px`;
                });

                element.addEventListener('mouseleave', () => {
                    tooltip.style.display = 'none';
                });
            });

            // Command execution
            document.querySelectorAll('.command-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const command = this.dataset.command;
                    const originalText = this.textContent;

                    // Disable button and show loading
                    this.disabled = true;
                    this.innerHTML = `
                        <svg class="animate-spin h-4 w-4 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Running...
                    `;

                    // Update command status
                    document.getElementById('current-command').innerHTML = `
                        <span class="inline-block w-2 h-2 rounded-full bg-yellow-400 mr-2"></span>
                        Running: ${command}
                    `;

                    try {
                        const response = await axios.post('{{ route("artisan-command-palette.execute") }}', {
                            command: command
                        });

                        document.getElementById('logs-output').innerHTML = response.data.output || 'Command executed successfully with no output.';
                        showAlert('success', 'Command executed successfully');
                        document.getElementById('current-command').innerHTML = `
                            <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            ${command}
                        `;
                        // Scroll to bottom of output div
                        const logsOutput = document.getElementById('logs-output');
                        logsOutput.scrollTop = logsOutput.scrollHeight;
                    } catch (error) {
                        const response = error.response?.data;
                        document.getElementById('logs-output').innerHTML = response?.error || 'Command execution failed.';
                        showAlert('error', `${response?.message}: ${response?.error}`);
                        document.getElementById('current-command').innerHTML = `
                            <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                            ${command}
                        `;
                        // Scroll to bottom of output div
                        const logsOutput = document.getElementById('logs-output');
                        logsOutput.scrollTop = logsOutput.scrollHeight;
                    } finally {
                        this.disabled = false;
                        this.textContent = originalText;
                    }
                });
            });

            // Clear logs
            document.getElementById('clear-logs').addEventListener('click', function() {
                document.getElementById('logs-output').innerHTML = '';
                document.getElementById('current-command').textContent = 'Command Output';
                document.getElementById('command-status').innerHTML = '';
            });

            // Alert function
            function showAlert(type, message) {
                const alertDiv = document.getElementById('output-global');
                alertDiv.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg ${
                    type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
                    'bg-red-100 text-red-800 border border-red-200'
                }`;
                alertDiv.innerHTML = message;
                alertDiv.style.display = 'block';

                setTimeout(() => {
                    alertDiv.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
