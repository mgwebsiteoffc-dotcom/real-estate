<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>{{ $microsite->meta_title ?? $microsite->title }}</title>
    <meta name="description" content="{{ $microsite->meta_description ?? Str::limit($microsite->description, 160) }}">
    <meta name="keywords" content="{{ $microsite->meta_keywords }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
        .luxury-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
    </style>
</head>
<body class="bg-black text-white">
    <!-- Elegant Header -->
    <header class="bg-black/50 backdrop-blur-sm text-white py-6 sticky top-0 z-50 border-b border-gold-500/20">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-3xl font-serif">{{ $microsite->title }}</h1>
            <a href="#contact" class="border border-amber-500 text-amber-500 px-8 py-3 rounded hover:bg-amber-500 hover:text-black transition">
                INQUIRE NOW
            </a>
        </div>
    </header>

    <!-- Full-Screen Video/Image Hero -->
    <section class="relative h-screen">
        <div class="absolute inset-0 bg-cover bg-center" 
             style="background-image: url('{{ $microsite->property->images && $microsite->property->images->first() ? asset('storage/' . $microsite->property->images->first()->path) : 'https://via.placeholder.com/1920x1080' }}');">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        
        <div class="relative z-10 h-full flex items-center justify-center text-center px-4">
            <div class="max-w-5xl">
                <p class="text-amber-500 text-sm uppercase tracking-widest mb-4">Exclusive Property</p>
                <h1 class="text-6xl md:text-7xl font-serif mb-6">{{ $microsite->property->title }}</h1>
                <p class="text-2xl text-gray-300 mb-12 max-w-3xl mx-auto">{{ $microsite->description }}</p>
                
                <div class="flex gap-8 justify-center text-amber-500">
                    <div class="border-l-2 border-amber-500 pl-4">
                        <p class="text-4xl font-serif">{{ $microsite->property->bedrooms }}</p>
                        <p class="text-sm uppercase tracking-wide">Bedrooms</p>
                    </div>
                    <div class="border-l-2 border-amber-500 pl-4">
                        <p class="text-4xl font-serif">{{ $microsite->property->bathrooms }}</p>
                        <p class="text-sm uppercase tracking-wide">Bathrooms</p>
                    </div>
                    <div class="border-l-2 border-amber-500 pl-4">
                        <p class="text-4xl font-serif">{{ $microsite->property->area }}</p>
                        <p class="text-sm uppercase tracking-wide">SQ FT</p>
                    </div>
                </div>

                <div class="mt-12">
                    <p class="text-5xl font-serif text-amber-500">₹{{ number_format($microsite->property->price) }}</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2">
            <a href="#details" class="text-white animate-bounce">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Details Section -->
    <section id="details" class="py-20 bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Description -->
                <div>
                    <p class="text-amber-500 text-sm uppercase tracking-widest mb-4">About This Property</p>
                    <h2 class="text-4xl font-serif mb-6">Timeless Elegance</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">{{ $microsite->property->description }}</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 border-b border-gray-800 pb-4">
                            <i class="fas fa-map-marker-alt text-amber-500"></i>
                            <span class="text-gray-300">{{ $microsite->property->address }}</span>
                        </div>
                        <div class="flex items-center gap-4 border-b border-gray-800 pb-4">
                            <i class="fas fa-home text-amber-500"></i>
                            <span class="text-gray-300">{{ $microsite->property->type }}</span>
                        </div>
                        <div class="flex items-center gap-4 border-b border-gray-800 pb-4">
                            <i class="fas fa-calendar text-amber-500"></i>
                            <span class="text-gray-300">Immediate Possession</span>
                        </div>
                    </div>
                </div>

                <!-- Image Grid -->
                @if($microsite->property->images && $microsite->property->images->count() > 1)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($microsite->property->images->take(4) as $image)
                    <img src="{{ asset('storage/' . $image->path) }}" 
                         alt="{{ $microsite->property->title }}"
                         class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section id="contact" class="py-20 bg-black">
        <div class="max-w-3xl mx-auto px-4">
            <div class="text-center mb-12">
                <p class="text-amber-500 text-sm uppercase tracking-widest mb-4">Get In Touch</p>
                <h2 class="text-4xl font-serif">Schedule Your Private Tour</h2>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/50 text-green-300 rounded border border-green-500">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('microsite.capture', $microsite->slug) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <input type="text" name="name" required
                               class="w-full bg-zinc-900 border border-gray-700 rounded px-4 py-4 text-white focus:border-amber-500 focus:outline-none"
                               placeholder="Your Name">
                    </div>
                    <div>
                        <input type="tel" name="phone" required
                               class="w-full bg-zinc-900 border border-gray-700 rounded px-4 py-4 text-white focus:border-amber-500 focus:outline-none"
                               placeholder="Phone Number">
                    </div>
                </div>

                <div>
                    <input type="email" name="email" required
                           class="w-full bg-zinc-900 border border-gray-700 rounded px-4 py-4 text-white focus:border-amber-500 focus:outline-none"
                           placeholder="Email Address">
                </div>

                <div>
                    <textarea name="message" rows="5"
                              class="w-full bg-zinc-900 border border-gray-700 rounded px-4 py-4 text-white focus:border-amber-500 focus:outline-none"
                              placeholder="Your Message"></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-amber-500 text-black py-4 rounded font-semibold hover:bg-amber-600 transition text-lg">
                    REQUEST VIEWING
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="luxury-bg text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-amber-500 font-serif text-xl mb-2">{{ $microsite->company->name }}</p>
            <p class="text-sm">© {{ date('Y') }} All Rights Reserved</p>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $microsite->company->phone ?? '') }}" 
       target="_blank"
       class="fixed bottom-8 right-8 bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:bg-green-600 z-50">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
</body>
</html>
