import React from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function LeadsIndex() {
    return (
        <AppLayout header="Leads Management">
            <Head title="Leads" />
            
            <div className="bg-white rounded-lg shadow-sm p-6">
                <div className="flex justify-between items-center mb-6">
                    <h2 className="text-xl font-semibold text-gray-800">All Leads</h2>
                    <button className="btn-primary">+ Add New Lead</button>
                </div>
                
                <p className="text-gray-600">
                    Lead management module coming in Phase 3...
                </p>
            </div>
        </AppLayout>
    );
}
