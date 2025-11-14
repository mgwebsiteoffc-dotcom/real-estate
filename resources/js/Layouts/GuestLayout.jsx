import React from 'react';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-primary-50 to-primary-100">
            <div>
                <Link href="/">
                    <div className="flex items-center justify-center mb-6">
                        <div className="w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center">
                            <span className="text-white text-2xl font-bold">RE</span>
                        </div>
                        <span className="ml-3 text-2xl font-bold text-gray-800">
                            Real Estate CRM
                        </span>
                    </div>
                </Link>
            </div>

            <div className="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-2xl">
                {children}
            </div>

            <div className="mt-6 text-center text-sm text-gray-600">
                © 2025 Real Estate CRM. All rights reserved.
            </div>
        </div>
    );
}
