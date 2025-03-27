// app/layout.tsx
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';
import SessionProvider from '@/components/SessionProvider';
import { getServerSession } from 'next-auth';
import { authOptions } from '@/lib/auth'; // Update import path
import '@/app/globals.css';

export const metadata = {
    title: 'E-commerce Admin',
    description: 'Admin platform for managing e-commerce store',
};

export default async function RootLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const session = await getServerSession(authOptions); // Pass authOptions directly

    return (
        <html lang="en">
            <body className="flex h-screen bg-gray-100">
                <SessionProvider session={session}>
                    {session ? (
                        <>
                            <Sidebar />
                            <div className="flex-1 flex flex-col">
                                <Navbar />
                                <main className="p-6 overflow-y-auto">{children}</main>
                            </div>
                        </>
                    ) : (
                        <>{children}</>
                    )}
                </SessionProvider>
            </body>
        </html>
    );
}