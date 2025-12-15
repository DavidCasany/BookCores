<!-- COLUMNA DRETA (Versió Final i Correcta) -->
        <div class="relative w-full h-[400px] lg:h-auto lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-slate-900 overflow-hidden z-0 group">
            
            {{-- 1. PREPARACIÓ DE DADES --}}
            @php
                $llibresJS = $llibresRecents->map(function($llibre) {
                    return [
                        'id' => $llibre->id_llibre,
                        'titol' => $llibre->titol,
                        'img' => $llibre->img_portada ? asset('img/' . $llibre->img_portada) : null,
                    ];
                })->values();
            @endphp

            {{-- 2. EL CARRUSEL --}}
            <div class="w-full h-full"
                 x-data="{
                    books: {{ $llibresJS }},
                    isAnimating: false,
                    autoplay: null,

                    startAutoplay() {
                        if (this.books.length > 1) {
                            this.autoplay = setInterval(() => { this.slide(); }, 5000); 
                        }
                    },

                    slide() {
                        this.isAnimating = true;
                    },

                    handleTransitionEnd() {
                        if (!this.isAnimating) return;
                        this.isAnimating = false;
                        
                        // Rotació Circular (El primer passa al final)
                        const firstBook = this.books.shift();
                        this.books.push(firstBook);
                    },

                    stopAutoplay() {
                        if (this.autoplay) { clearInterval(this.autoplay); this.autoplay = null; }
                    },
                    
                    init() { this.startAutoplay(); }
                 }"
                 x-init="init()"
                 @mouseenter="stopAutoplay()"
                 @mouseleave="startAutoplay()">

                <!-- PISTA VISUAL -->
                <div class="flex h-full w-full will-change-transform"
                     :class="isAnimating ? 'transition-transform duration-1000 ease-in-out -translate-x-full' : ''"
                     @transitionend="handleTransitionEnd()">

                    <!-- NODE 1 (Llibre Actual) -->
                    <div class="relative w-full h-full flex-shrink-0 bg-slate-900 flex items-center justify-center p-8 md:p-16">
                        <template x-if="books[0]">
                            <!-- 'contents' per no trencar el flex del pare -->
                            <div class="contents">
                                <template x-if="books[0].img">
                                    <!-- LA CLAU DE L'ÈXIT: max-h-full + max-w-full + object-contain -->
                                    <img :src="books[0].img" 
                                         :alt="books[0].titol" 
                                         class="max-h-full max-w-full object-contain drop-shadow-2xl shadow-black/50 rounded-sm">
                                </template>
                                
                                <!-- Fallback si no hi ha foto -->
                                <template x-if="!books[0].img">
                                    <div class="flex flex-col items-center text-slate-600">
                                        <span class="text-6xl mb-2">📘</span>
                                        <span class="text-sm font-bold text-center text-slate-400" x-text="books[0].titol"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- NODE 2 (Llibre Següent) -->
                    <div class="relative w-full h-full flex-shrink-0 bg-slate-900 flex items-center justify-center p-8 md:p-16">
                        <template x-if="books[1]">
                            <div class="contents">
                                <template x-if="books[1].img">
                                    <img :src="books[1].img" 
                                         :alt="books[1].titol" 
                                         class="max-h-full max-w-full object-contain drop-shadow-2xl shadow-black/50 rounded-sm">
                                </template>
                                
                                <template x-if="!books[1].img">
                                    <div class="flex flex-col items-center text-slate-600">
                                        <span class="text-6xl mb-2">📘</span>
                                        <span class="text-sm font-bold text-center text-slate-400" x-text="books[1].titol"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </div>
```

**Què he canviat?**
1.  **Contenidor principal:** `h-[400px] lg:h-auto lg:absolute lg:inset-y-0`. Això fa que en mòbil tingui una alçada fixa decent (400px) i en escriptori ocupi tota l'alçada disponible a la dreta. He tret el `aspect-ratio` conflictiu.
2.  **Imatge:** `max-h-full max-w-full object-contain`. Això garanteix que la imatge mai es surti del contenidor ni es talli, mantenint la seva proporció original.
3.  **Padding:** `p-8 md:p-16`. He afegit un bon marge negre al voltant perquè la imatge "respiri" i quedi elegant al centre.

Prova aquest codi i fes **CTRL + F5**. Hauria de veure's perfecte.