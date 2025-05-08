import Alpine from 'alpinejs';
import '../css/app.css';

window.Alpine = Alpine;

document.addEventListener('DOMContentLoaded', () => {
    Alpine.data('commandPalette', () => ({
        commands: [],
        filteredCommands: [],
        searchTerm: '',
        selectedCommand: '',
        commandOutput: 'No command executed yet.',
        isLoading: false,
        showCommandList: false,
        
        init() {
            this.fetchCommands();
            
            // Close command list when clicking outside
            document.addEventListener('click', (e) => {
                if (!this.$refs.searchInput.contains(e.target) && !this.$refs.commandList.contains(e.target)) {
                    this.showCommandList = false;
                }
            });
        },
        
        fetchCommands() {
            this.isLoading = true;
            
            fetch(window.artisanCommandPalette.routes.commands)
                .then(response => response.json())
                .then(data => {
                    this.commands = data;
                    this.filterCommands();
                    this.isLoading = false;
                })
                .catch(error => {
                    console.error('Error fetching commands:', error);
                    this.isLoading = false;
                });
        },
        
        filterCommands() {
            const searchTerm = this.searchTerm.toLowerCase();
            
            this.filteredCommands = this.commands.filter(cmd => 
                cmd.name.toLowerCase().includes(searchTerm) || 
                cmd.description.toLowerCase().includes(searchTerm)
            );
            
            this.showCommandList = this.filteredCommands.length > 0 && searchTerm.length > 0;
        },
        
        selectCommand(command) {
            this.selectedCommand = command.name;
            this.showCommandList = false;
        },
        
        executeCommand() {
            if (!this.selectedCommand) {
                this.commandOutput = 'Please enter a command to execute.';
                return;
            }
            
            this.isLoading = true;
            this.commandOutput = 'Executing command...';
            
            fetch(window.artisanCommandPalette.routes.execute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.artisanCommandPalette.csrfToken
                },
                body: JSON.stringify({ command: this.selectedCommand })
            })
            .then(response => response.json())
            .then(data => {
                this.isLoading = false;
                
                if (data.success) {
                    this.commandOutput = data.output || 'Command executed successfully with no output.';
                } else {
                    this.commandOutput = 'Error: ' + data.error;
                }
            })
            .catch(error => {
                this.isLoading = false;
                this.commandOutput = 'Error executing command: ' + error;
            });
        }
    }));
    
    Alpine.start();
});
