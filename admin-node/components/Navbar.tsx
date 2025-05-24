// components/Navbar.tsx
'use client';

import { signOut, useSession } from 'next-auth/react';

export default function Navbar() {
    const { data: session } = useSession();

    return (
        <header className="bg-white shadow p-4 flex justify-between items-center">
            <h1 className="text-xl font-semibold text-gray-800">Dashboard</h1>
            <div className="flex items-center space-x-4">
                <span className="text-gray-600">{session?.user?.name || 'Admin'}</span>
                <button
                    onClick={() => signOut({ callbackUrl: '/login' })}
                    className="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                >
                    Logout
                </button>
            </div>
        </header>
    );
}