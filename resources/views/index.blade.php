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
    <style>
        /* Custom scrollbar styles */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #2d3748;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #4a5568;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }
        
        /* Terminal scrollbar styles */
        .bg-terminal::-webkit-scrollbar-track {
            background: #2d2d2d;
        }
        
        .bg-terminal::-webkit-scrollbar-thumb {
            background: #4d4d4d;
        }
        
        .bg-terminal::-webkit-scrollbar-thumb:hover {
            background: #5d5d5d;
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="flex flex-col h-screen p-4">
        <div class="flex-none mb-4">
            <div class="flex flex-row justify-between items-center">
                <div class="flex flex-row gap-3 items-center text-2xl font-semibold text-gray-800 dark:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="text-gray-800 dark:text-gray-200" fill="none">
                    <path d="M11.2019 4.17208C11.711 3.94264 12.289 3.94264 12.7981 4.17208L21.3982 8.04851C22.2006 8.41016 22.2006 9.58984 21.3982 9.95149L12.7981 13.8279C12.289 14.0574 11.711 14.0574 11.2019 13.8279L2.60175 9.95149C1.79941 9.58984 1.79942 8.41016 2.60176 8.04851L11.2019 4.17208Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M20.1813 13.5L21.3982 14.0485C22.2006 14.4102 22.2006 15.5898 21.3982 15.9515L12.7981 19.8279C12.289 20.0574 11.711 20.0574 11.2019 19.8279L2.60175 15.9515C1.79941 15.5898 1.79942 14.4102 2.60176 14.0485L3.81867 13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>

                <span>
                    Artisan Commands: {{ config('app.env') }}
                </span>
                </div>
                
                <!-- Dark mode toggle -->
                <button id="theme-toggle" class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors">
                    <!-- Sun icon for dark mode -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon for light mode -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="output-global" class="fixed top-4 right-4 z-50 max-w-sm hidden"></div>
    
    <!-- Command Input Modal -->
    <div id="command-input-modal" class="fixed inset-0 z-50 items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title">Command Input</h3>
            <div class="mb-4">
                <label id="input-label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Input</label>
                <input type="text" id="command-input-field" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" id="current-command-display"></p>
            </div>
            <div class="flex justify-end space-x-3">
                <button id="cancel-input" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button id="submit-input" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Run Command
                </button>
            </div>
        </div>
    </div>

        <div class="flex-none h-[45vh] mb-4">
            <div class="w-full overflow-x-auto whitespace-nowrap">
                <div class="inline-flex gap-4">
                    @foreach($commands as $group => $groupCommands)
                        @if(count($groupCommands) > 0)
                        <div class="flex-none w-[300px] mb-3 bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 h-[43vh] overflow-y-auto">
                            <div class="text-base font-semibold mb-3 text-gray-700 dark:text-gray-300 sticky top-0 bg-white dark:bg-gray-800 py-2 border-b-2 border-gray-200 dark:border-gray-700">
                                {{ $group }}
                            </div>
                            <div class="space-y-2">
                                @foreach($groupCommands as $command)
                                    <div>
                                        <button
                                            class="w-full px-2 py-1 text-sm text-left border border-blue-500 text-blue-500 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded truncate transition-colors command-btn flex items-center justify-between"
                                            data-command="{{ $command['command'] }}"
                                            data-tooltip="{{ $command['description'] }}"
                                        >
                                            <span>{{ $command['command'] }}</span>
                                            <span class="input-icon hidden ml-1 flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" class="text-blue-500 dark:text-blue-400" stroke="currentColor">
                                                    <path d="M19 7H5C3.34315 7 2 8.34315 2 10V19C2 20.6569 3.34315 22 5 22H19C20.6569 22 22 20.6569 22 19V10C22 8.34315 20.6569 7 19 7Z" stroke-width="1" stroke-linejoin="round"></path>
                                                    <path d="M12 7V5.53078C12 4.92498 12.4123 4.39693 13 4.25V4.25C13.5877 4.10307 14 3.57502 14 2.96922V2" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M7 12L8 12M11.5 12L12.5 12M16 12L17 12" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M7 17L17 17" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </span>
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

    <div id="tooltip" class="absolute hidden z-50 max-w-xs bg-gray-900 dark:bg-gray-700 text-white text-sm rounded-md px-3 py-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store commands that require input
            let commandsWithInput = {};
            
            // Fetch commands with input requirements
            axios.get('{{ route("artisan-command-palette.commands") }}')
                .then(response => {
                    if (response.data && response.data.commands_with_input) {
                        commandsWithInput = response.data.commands_with_input;
                        
                        // Mark commands that require input with an icon
                        Object.keys(commandsWithInput).forEach(commandName => {
                            const commandButtons = document.querySelectorAll(`.command-btn[data-command="${commandName}"]`);
                            commandButtons.forEach(button => {
                                const iconElement = button.querySelector('.input-icon');
                                if (iconElement) {
                                    iconElement.classList.remove('hidden');
                                    iconElement.classList.add('inline-block');
                                }
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching commands with input:', error);
                });
                
            // Command input modal elements
            const commandInputModal = document.getElementById('command-input-modal');
            const commandInputField = document.getElementById('command-input-field');
            const inputLabel = document.getElementById('input-label');
            const modalTitle = document.getElementById('modal-title');
            const currentCommandDisplay = document.getElementById('current-command-display');
            const submitInputBtn = document.getElementById('submit-input');
            const cancelInputBtn = document.getElementById('cancel-input');
            
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
                    const originalHTML = this.innerHTML;
                    
                    // Check if command requires input
                    if (commandsWithInput[command]) {
                        // Show input modal
                        const inputConfig = commandsWithInput[command];
                        modalTitle.textContent = `Input for ${command}`;
                        inputLabel.textContent = inputConfig.label || 'Input';
                        commandInputField.placeholder = inputConfig.placeholder || 'Enter input value';
                        currentCommandDisplay.textContent = `Command: ${command}`;
                        
                        // Show modal with flex display
                        commandInputModal.classList.remove('hidden');
                        commandInputModal.classList.add('flex');
                        commandInputField.focus();
                        
                        // Store the command button reference for later use
                        submitInputBtn.dataset.commandButton = button.dataset.command;
                        return;
                    }
                    
                    // For commands without input, proceed as usual
                    await executeCommand(command, this);
                });
            });
            
            // Handle input submission
            submitInputBtn.addEventListener('click', async function() {
                const command = this.dataset.commandButton;
                const inputValue = commandInputField.value.trim();
                const buttonElement = document.querySelector(`.command-btn[data-command="${command}"]`);
                
                // Validate input if required
                if (commandsWithInput[command]?.required && !inputValue) {
                    showAlert('error', 'Input is required for this command');
                    return;
                }
                
                // Hide modal
                commandInputModal.classList.add('hidden');
                commandInputModal.classList.remove('flex');
                
                // Execute command with input
                await executeCommand(command, buttonElement, inputValue);
                
                // Reset input field
                commandInputField.value = '';
            });
            
            // Handle cancel button
            cancelInputBtn.addEventListener('click', function() {
                commandInputModal.classList.add('hidden');
                commandInputModal.classList.remove('flex');
                commandInputField.value = '';
            });
            
            // Handle Enter key in input field
            commandInputField.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    submitInputBtn.click();
                } else if (e.key === 'Escape') {
                    cancelInputBtn.click();
                }
            });
            
            // Execute command function
            async function executeCommand(command, buttonElement, inputValue = null) {
                const originalHTML = buttonElement.innerHTML;

                // Disable button and show loading
                buttonElement.disabled = true;
                buttonElement.innerHTML = `
                    <svg class="animate-spin h-4 w-4 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Running...
                `;

                // Update command status
                document.getElementById('current-command').innerHTML = `
                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-400 mr-2"></span>
                    Running: ${command}${inputValue ? ' ' + inputValue : ''}
                `;

                try {
                    const payload = { command: command };
                    if (inputValue) {
                        payload.input_value = inputValue;
                    }
                    
                    const response = await axios.post('{{ route("artisan-command-palette.execute") }}', payload);

                    document.getElementById('logs-output').innerHTML = response.data.output || 'Command executed successfully with no output.';
                    showAlert('success', 'Command executed successfully');
                    document.getElementById('current-command').innerHTML = `
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                        ${command}${inputValue ? ' ' + inputValue : ''}
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
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalHTML;
                }
            }

            // Clear logs
            document.getElementById('clear-logs').addEventListener('click', function() {
                document.getElementById('logs-output').innerHTML = '';
                document.getElementById('current-command').textContent = 'Command Output';
                document.getElementById('command-status').innerHTML = '';
            });

            // Dark mode toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            
            // Check for saved theme preference or use the system preference
            if (localStorage.getItem('color-theme') === 'dark' || 
                (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Toggle dark mode on button click
            themeToggle.addEventListener('click', function() {
                // Toggle dark class on html element
                document.documentElement.classList.toggle('dark');
                
                // Update localStorage
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    localStorage.setItem('color-theme', 'light');
                }
            });
            
            // Alert function
            function showAlert(type, message) {
                const alertDiv = document.getElementById('output-global');
                alertDiv.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg ${
                    type === 'success' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800' :
                    'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800'
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
