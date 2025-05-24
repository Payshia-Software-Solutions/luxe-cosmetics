// app/(auth)/layout.tsx
import { ReactNode } from 'react';

export default function AuthLayout({ children }: { children: ReactNode }) {
    return (
        <html lang="en">
            <body className="bg-gray-100">{children}</body>
        </html>
    );
}