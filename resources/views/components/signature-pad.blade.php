@props([
    'name', // 'instalador' o 'cliente'
    'label', // 'Instalador' o 'Cliente'
    'nombre' => '',
    'path' => ''
])

<div x-data="signaturePad('{{ $name }}', '{{ $path }}')" class="bg-gray-900/50 p-4 rounded-lg border border-gray-700 space-y-4">
    <h3 class="font-semibold text-lg text-gray-200">Firma del {{ $label }}</h3>

    {{-- CAMPO PARA EL NOMBRE --}}
    <div>
        <label for="{{ $name }}_nombre" class="block text-sm font-semibold mb-2">Nombre del {{ $label }}:</label>
        <input 
            type="text" 
            id="{{ $name }}_nombre" 
            name="{{ $name }}_nombre" 
            value="{{ $nombre }}"
            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-sm"
            placeholder="Introduce el nombre completo...">
    </div>

    {{-- BOTONES PARA CAMBIAR MODO --}}
    <div class="flex gap-2">
        <button type="button" @click="mode = 'draw'" :class="mode === 'draw' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300'" class="px-3 py-1 rounded-md text-sm font-semibold">
            Trazar Firma
        </button>
        <button type="button" @click="mode = 'upload'" :class="mode === 'upload' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300'" class="px-3 py-1 rounded-md text-sm font-semibold">
            Subir Archivo
        </button>
    </div>

    {{-- MODO: TRAZAR FIRMA --}}
    <div x-show="mode === 'draw'" x-transition>
        <div class="relative w-full h-48 bg-white rounded-md shadow-inner">
            <canvas x-ref="canvas" class="w-full h-full"></canvas>
            
            {{-- Muestra la firma guardada si existe y estamos en modo trazar --}}
            <template x-if="path && !isDirty">
                <img :src="path" class="absolute top-0 left-0 w-full h-full object-contain pointer-events-none">
            </template>
        </div>
        <button type="button" @click="clearPad()" class="text-xs text-blue-400 hover:underline mt-1">Limpiar</button>
    </div>

    {{-- MODO: SUBIR ARCHIVO --}}
    <div x-show="mode === 'upload'" x-transition>
        <input 
            type="file" 
            name="{{ $name }}_firma_file" 
            @change="fileChosen = true"
            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
            accept="image/png, image/jpeg, image/jpg"
        >
        {{-- Muestra la firma guardada si existe y estamos en modo subir --}}
        <template x-if="path && !fileChosen">
            <div class="mt-2">
                <p class="text-xs text-gray-400">Firma actual:</p>
                <img :src="path" class="h-20 w-auto border border-gray-600 rounded-md mt-1">
            </div>
        </template>
    </div>

    {{-- Inputs ocultos para enviar los datos --}}
    <input type="hidden" name="{{ $name }}_firma_data" x-ref="dataInput">
</div>

{{-- SCRIPT DE ALPINE.JS PARA ESTE COMPONENTE --}}
{{-- Este script debe estar fuera del x-data --}}
<script>
    if (typeof window.signaturePad !== 'function') {
        window.signaturePad = (name, path) => ({
            pad: null,
            mode: 'draw',
            isDirty: false,
            fileChosen: false,
            path: path ? `/storage/${path}` : null,

            // ***** FUNCIÓN init() ACTUALIZADA *****
            init() {
                this.$nextTick(() => {
                    const canvas = this.$refs.canvas;
                    // --- Inicio: Cálculo del Ratio ---
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                    // --- Fin: Cálculo del Ratio ---

                    this.pad = new SignaturePad(canvas);
                    this.pad.addEventListener("beginStroke", () => {
                        this.isDirty = true;
                    });

                    // Si hay una firma guardada, dibujarla inicialmente
                    if(this.path){
                        const img = new Image();
                        img.onload = () => {
                             // Limpia antes de dibujar para evitar superposiciones si se limpia y luego cambia modo
                             this.pad.clear(); 
                             this.drawImageScaled(img, this.pad.canvas);
                        }
                        img.src = this.path;
                    }

                    // Reajustar al cambiar tamaño de ventana
                    window.addEventListener('resize', this.resizeCanvas.bind(this));
                });

                this.$watch('mode', (newMode) => {
                    this.$nextTick(() => {
                        if (newMode === 'draw') {
                            this.resizeCanvas(); // Asegura que el canvas se redibuje al volver al modo trazar
                             // Si había una firma y no se ha modificado, volver a dibujarla
                             if (this.path && !this.isDirty) {
                                const img = new Image();
                                img.onload = () => {
                                    this.drawImageScaled(img, this.pad.canvas);
                                }
                                img.src = this.path;
                             }
                        }
                    });
                });

                this.$root.closest('form').addEventListener('submit', () => {
                    if (this.mode === 'draw' && this.isDirty && !this.pad.isEmpty()) {
                        // Guardamos la imagen con fondo transparente
                        this.$refs.dataInput.value = this.pad.toDataURL('image/png'); 
                    } else if (this.mode === 'draw' && !this.isDirty && this.path) {
                         // Si no está sucio pero hay path, no enviamos dataURL nueva
                         this.$refs.dataInput.value = ''; 
                    } else if (this.mode === 'upload' && this.fileChosen) {
                         // Si se subió archivo, limpiamos dataURL (si la hubiera)
                         this.$refs.dataInput.value = ''; 
                    } else if (this.mode === 'upload' && !this.fileChosen && this.path){
                         // Si no se eligió archivo y había path, no enviamos dataURL
                         this.$refs.dataInput.value = '';
                    }
                });
            },

            clearPad() {
                this.pad.clear();
                this.isDirty = true;
                this.$refs.dataInput.value = '';
            },

            // ***** FUNCIÓN resizeCanvas() ACTUALIZADA *****
            resizeCanvas() {
                 if (this.pad) {
                    const canvas = this.$refs.canvas;
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    const currentData = this.isDirty ? this.pad.toDataURL() : null; // Guarda la firma actual si se modificó

                    // Guarda el estado actual antes de redimensionar
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);

                    // Restaura la firma si había algo dibujado
                    if (currentData) {
                        const img = new Image();
                        img.onload = () => {
                            this.drawImageScaled(img, canvas); // Usa la función auxiliar
                        }
                        img.src = currentData;
                    } else if (this.path && !this.isDirty) {
                         // Si no estaba sucio pero había una firma original, la redibuja
                         const img = new Image();
                         img.onload = () => {
                             this.drawImageScaled(img, canvas);
                         }
                         img.src = this.path;
                    } else {
                         // Si no había nada, simplemente limpia
                         this.pad.clear();
                    }
                }
            },
            
            // ***** NUEVA FUNCIÓN AUXILIAR *****
            // Dibuja la imagen escalada para que quepa en el canvas manteniendo la proporción
            drawImageScaled(img, canvas) {
                const ctx = canvas.getContext('2d');
                const hRatio = canvas.width / img.width / (window.devicePixelRatio || 1); // Ajusta por ratio al dibujar
                const vRatio = canvas.height / img.height / (window.devicePixelRatio || 1);
                const ratio = Math.min(hRatio, vRatio);
                const centerShift_x = (canvas.width / (window.devicePixelRatio || 1) - img.width * ratio) / 2;
                const centerShift_y = (canvas.height / (window.devicePixelRatio || 1) - img.height * ratio) / 2;
                ctx.clearRect(0, 0, canvas.width, canvas.height); // Limpia antes de dibujar
                ctx.drawImage(img, 0, 0, img.width, img.height,
                              centerShift_x, centerShift_y, img.width * ratio, img.height * ratio);
            }
        });
    }
</script>