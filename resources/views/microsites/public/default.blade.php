<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>{{ $microsite->meta_title ?? $microsite->title }}</title>
    <meta name="description" content="{{ $microsite->meta_description ?? Str::limit($microsite->description, 160) }}">
    <meta name="keywords" content="{{ $microsite->meta_keywords }}">
    <meta name="author" content="{{ $microsite->company->name }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $microsite->url }}">
    <meta property="og:title" content="{{ $microsite->meta_title ?? $microsite->title }}">
    <meta property="og:description" content="{{ $microsite->meta_description ?? Str::limit($microsite->description, 160) }}">
    @if($microsite->property->images && $microsite->property->images->first())
    <meta property="og:image" content="{{ asset('storage/' . $microsite->property->images->first()->path) }}">
    @endif
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $microsite->url }}">
    <meta property="twitter:title" content="{{ $microsite->meta_title ?? $microsite->title }}">
    <meta property="twitter:description" content="{{ $microsite->meta_description ?? Str::limit($microsite->description, 160) }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $microsite->url }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "RealEstateListing",
      "name": "{{ $microsite->property->title }}",
      "description": "{{ $microsite->description }}",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ $microsite->property->address }}",
        "addressLocality": "{{ $microsite->property->city }}",
        "addressRegion": "{{ $microsite->property->state }}",
        "postalCode": "{{ $microsite->property->pincode }}",
        "addressCountry": "{{ $microsite->property->country ?? 'India' }}"
      },
      "offers": {
        "@type": "Offer",
        "price": "{{ $microsite->property->price }}",
        "priceCurrency": "INR"
      }
    }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">{{ $microsite->title }}</h1>
            <a href="#contact" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-phone"></i> Contact Us
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative h-[600px] bg-cover bg-center" 
             style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ $microsite->property->images && $microsite->property->images->first() ? asset('storage/' . $microsite->property->images->first()->path) : 'https://via.placeholder.com/1920x1080' }}');">
        <div class="absolute inset-0 flex items-center justify-center text-white text-center">
            <div class="max-w-4xl px-4">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">{{ $microsite->property->title }}</h1>
                <p class="text-xl md:text-2xl mb-8">{{ $microsite->description }}</p>
                <div class="flex gap-4 justify-center">
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg">
                        <i class="fas fa-bed text-2xl mb-2"></i>
                        <p class="text-sm">{{ $microsite->property->bedrooms }} Bedrooms</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg">
                        <i class="fas fa-bath text-2xl mb-2"></i>
                        <p class="text-sm">{{ $microsite->property->bathrooms }} Bathrooms</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg">
                        <i class="fas fa-ruler-combined text-2xl mb-2"></i>
                        <p class="text-sm">{{ $microsite->property->area }} sq ft</p>
                    </div>
                </div>
                <div class="mt-8">
                    <span class="bg-blue-600 text-white px-8 py-4 rounded-lg text-3xl font-bold">
                        ₹{{ number_format($microsite->property->price) }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Details -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left Column - Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Description -->
                <div class="bg-white p-8 rounded-lg shadow">
                    <h2 class="text-3xl font-bold mb-4">About This Property</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $microsite->property->description }}</p>
                </div>

                <!-- Features -->
                <div class="bg-white p-8 rounded-lg shadow">
                    <h2 class="text-3xl font-bold mb-6">Features & Amenities</h2>
                    <div class="grid grid-cols-2 gap-4">
                        @if($microsite->property->features)
                            @foreach(explode(',', $microsite->property->features) as $feature)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span>{{ trim($feature) }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span>Modern Kitchen</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span>Parking Available</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span>24/7 Security</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span>Power Backup</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Gallery -->
                @if($microsite->property->images && $microsite->property->images->count() > 0)
                <div class="bg-white p-8 rounded-lg shadow">
                    <h2 class="text-3xl font-bold mb-6">Photo Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($microsite->property->images as $image)
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             alt="{{ $microsite->property->title }}"
                             class="w-full h-48 object-cover rounded-lg hover:scale-105 transition cursor-pointer">
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Location -->
                <div class="bg-white p-8 rounded-lg shadow">
                    <h2 class="text-3xl font-bold mb-6">Location</h2>
                    <div class="space-y-3">
                        <p class="flex items-center gap-3">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            <span>{{ $microsite->property->address }}</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-city text-blue-600"></i>
                            <span>{{ $microsite->property->city }}, {{ $microsite->property->state }}</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-map-pin text-green-600"></i>
                            <span>PIN: {{ $microsite->property->pincode }}</span>
                        </p>
                    </div>
                    
                    <!-- Google Maps Embed -->
                    <div class="mt-6 h-64 bg-gray-200 rounded-lg overflow-hidden">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0"
                            src="https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_KEY&q={{ urlencode($microsite->property->address . ', ' . $microsite->property->city) }}"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Right Column - Contact Form (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-lg shadow sticky top-24" id="contact">
                    <h2 class="text-2xl font-bold mb-6">Interested? Get in Touch</h2>
                    
                    @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('microsite.capture', $microsite->slug) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium mb-1">Full Name *</label>
                            <input type="text" name="name" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="John Doe">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Email *</label>
                            <input type="email" name="email" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="john@example.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Phone *</label>
                            <input type="tel" name="phone" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="+91 9876543210">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Message</label>
                            <textarea name="message" rows="4"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="I'm interested in this property..."></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 transition font-semibold text-lg">
                            <i class="fas fa-paper-plane"></i> Send Inquiry
                        </button>
                    </form>

                    <!-- Contact Info -->
                    <div class="mt-8 pt-8 border-t space-y-3">
                        <p class="flex items-center gap-3 text-sm">
                            <i class="fas fa-phone text-blue-600"></i>
                            <a href="tel:{{ $microsite->company->phone ?? '' }}" class="hover:text-blue-600">
                                {{ $microsite->company->phone ?? 'Contact us' }}
                            </a>
                        </p>
                        <p class="flex items-center gap-3 text-sm">
                            <i class="fas fa-envelope text-blue-600"></i>
                            <a href="mailto:{{ $microsite->company->email ?? '' }}" class="hover:text-blue-600">
                                {{ $microsite->company->email ?? 'Email us' }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 mb-4">© {{ date('Y') }} {{ $microsite->company->name }}. All rights reserved.</p>
            <p class="text-sm text-gray-500">Powered by {{ config('app.name') }}</p>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $microsite->company->phone ?? '') }}?text=Hi, I'm interested in {{ $microsite->property->title }}" 
       target="_blank"
       class="fixed bottom-6 right-6 bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition z-50">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
</body>
</html>
