<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags (Same as default) -->
    <title>{{ $microsite->meta_title ?? $microsite->title }}</title>
    <meta name="description" content="{{ $microsite->meta_description ?? Str::limit($microsite->description, 160) }}">
    <meta name="keywords" content="{{ $microsite->meta_keywords }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="font-sans">
    <!-- Modern Header with Gradient -->
    <header class="gradient-bg text-white py-4 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">{{ $microsite->title }}</h1>
            <a href="#contact" class="bg-white text-purple-600 px-6 py-2 rounded-full hover:bg-gray-100 transition font-semibold">
                Inquire Now
            </a>
        </div>
    </header>

    <!-- Split Screen Hero -->
    <section class="h-screen flex flex-col md:flex-row">
        <!-- Left - Image -->
        <div class="md:w-1/2 h-full bg-cover bg-center" 
             style="background-image: url('{{ $microsite->property->images && $microsite->property->images->first() ? asset('storage/' . $microsite->property->images->first()->path) : 'https://via.placeholder.com/1920x1080' }}');">
        </div>

        <!-- Right - Content -->
        <div class="md:w-1/2 flex items-center justify-center p-12 bg-gray-50">
            <div class="max-w-lg">
                <span class="text-purple-600 font-semibold text-sm uppercase tracking-wide">Premium Property</span>
                <h1 class="text-5xl font-bold mt-4 mb-6">{{ $microsite->property->title }}</h1>
                <p class="text-gray-600 text-lg mb-8">{{ $microsite->description }}</p>
                
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-4 bg-white rounded-lg shadow">
                        <i class="fas fa-bed text-3xl text-purple-600 mb-2"></i>
                        <p class="font-semibold">{{ $microsite->property->bedrooms }}</p>
                        <p class="text-xs text-gray-500">Bedrooms</p>
                    </div>
                    <div class="text-center p-4 bg-white rounded-lg shadow">
                        <i class="fas fa-bath text-3xl text-purple-600 mb-2"></i>
                        <p class="font-semibold">{{ $microsite->property->bathrooms }}</p>
                        <p class="text-xs text-gray-500">Bathrooms</p>
                    </div>
                    <div class="text-center p-4 bg-white rounded-lg shadow">
                        <i class="fas fa-ruler-combined text-3xl text-purple-600 mb-2"></i>
                        <p class="font-semibold">{{ $microsite->property->area }}</p>
                        <p class="text-xs text-gray-500">sq ft</p>
                    </div>
                </div>

                <div class="text-4xl font-bold text-purple-600 mb-8">
                    ₹{{ number_format($microsite->property->price) }}
                </div>

                <a href="#contact" class="inline-block gradient-bg text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition">
                    Schedule a Visit
                </a>
            </div>
        </div>
    </section>

    <!-- Rest of the content similar to default template -->
    <!-- Contact Form Section (Same as default) -->
    <section class="py-16 bg-white" id="contact">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12">Get in Touch</h2>
            
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('microsite.capture', $microsite->slug) }}" method="POST" class="max-w-2xl mx-auto space-y-6">
                @csrf
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Name *</label>
                        <input type="text" name="name" required
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-purple-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Phone *</label>
                        <input type="tel" name="phone" required
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-purple-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email *</label>
                    <input type="email" name="email" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Message</label>
                    <textarea name="message" rows="5"
                              class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-purple-600"></textarea>
                </div>

                <button type="submit" 
                        class="w-full gradient-bg text-white py-4 rounded-lg text-lg font-semibold hover:shadow-xl transition">
                    Submit Inquiry
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-8 text-center">
        <p>© {{ date('Y') }} {{ $microsite->company->name }}</p>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $microsite->company->phone ?? '') }}" 
       target="_blank"
       class="fixed bottom-6 right-6 bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 z-50">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
</body>
</html>
