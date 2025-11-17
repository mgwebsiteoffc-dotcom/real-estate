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
</head>
<body class="bg-white text-gray-900">
    <!-- Minimalist Header -->
    <header class="border-b py-6 bg-white sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-xl font-light tracking-wide">{{ $microsite->title }}</h1>
            <a href="#contact" class="text-sm border-b-2 border-black pb-1 hover:text-gray-600 transition">
                Contact
            </a>
        </div>
    </header>

    <!-- Hero - Large Image -->
    <section class="h-screen relative">
        <img src="{{ $microsite->property->images && $microsite->property->images->first() ? asset('storage/' . $microsite->property->images->first()->path) : 'https://via.placeholder.com/1920x1080' }}" 
             alt="{{ $microsite->property->title }}"
             class="w-full h-full object-cover">
        
        <div class="absolute bottom-20 left-0 right-0">
            <div class="max-w-6xl mx-auto px-4">
                <div class="bg-white/95 backdrop-blur-sm p-12 max-w-2xl">
                    <h1 class="text-5xl font-light mb-4">{{ $microsite->property->title }}</h1>
                    <p class="text-xl text-gray-600 mb-6">{{ $microsite->property->city }}, {{ $microsite->property->state }}</p>
                    <p class="text-3xl font-light">₹{{ number_format($microsite->property->price) }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-24">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-16 mb-24">
                <div class="text-center">
                    <p class="text-5xl font-light mb-2">{{ $microsite->property->bedrooms }}</p>
                    <p class="text-sm uppercase tracking-wider text-gray-500">Bedrooms</p>
                </div>
                <div class="text-center">
                    <p class="text-5xl font-light mb-2">{{ $microsite->property->bathrooms }}</p>
                    <p class="text-sm uppercase tracking-wider text-gray-500">Bathrooms</p>
                </div>
                <div class="text-center">
                    <p class="text-5xl font-light mb-2">{{ $microsite->property->area }}</p>
                    <p class="text-sm uppercase tracking-wider text-gray-500">SQ FT</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-24">
                <h2 class="text-3xl font-light mb-8 border-b pb-4">Overview</h2>
                <p class="text-lg leading-relaxed text-gray-700">{{ $microsite->property->description }}</p>
            </div>

            <!-- Gallery -->
            @if($microsite->property->images && $microsite->property->images->count() > 0)
            <div class="mb-24">
                <h2 class="text-3xl font-light mb-8 border-b pb-4">Gallery</h2>
                <div class="space-y-4">
                    @foreach($microsite->property->images as $image)
                    <img src="{{ asset('storage/' . $image->path) }}" 
                         alt="{{ $microsite->property->title }}"
                         class="w-full h-96 object-cover">
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Location -->
            <div class="mb-24">
                <h2 class="text-3xl font-light mb-8 border-b pb-4">Location</h2>
                <p class="text-lg text-gray-700">{{ $microsite->property->address }}, {{ $microsite->property->city }}, {{ $microsite->property->state }} - {{ $microsite->property->pincode }}</p>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section id="contact" class="py-24 bg-gray-50">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-3xl font-light mb-12 text-center">Inquire About This Property</h2>

            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('microsite.capture', $microsite->slug) }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <input type="text" name="name" required
                           class="w-full border-0 border-b-2 border-gray-300 px-0 py-3 focus:border-black focus:outline-none focus:ring-0"
                           placeholder="Name">
                </div>

                <div>
                    <input type="email" name="email" required
                           class="w-full border-0 border-b-2 border-gray-300 px-0 py-3 focus:border-black focus:outline-none focus:ring-0"
                           placeholder="Email">
                </div>

                <div>
                    <input type="tel" name="phone" required
                           class="w-full border-0 border-b-2 border-gray-300 px-0 py-3 focus:border-black focus:outline-none focus:ring-0"
                           placeholder="Phone">
                </div>

                <div>
                    <textarea name="message" rows="4"
                              class="w-full border-0 border-b-2 border-gray-300 px-0 py-3 focus:border-black focus:outline-none focus:ring-0"
                              placeholder="Message"></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-black text-white py-4 hover:bg-gray-800 transition text-sm uppercase tracking-wider">
                    Submit
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t py-12">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} {{ $microsite->company->name }}</p>
        </div>
    </footer>
</body>
</html>
