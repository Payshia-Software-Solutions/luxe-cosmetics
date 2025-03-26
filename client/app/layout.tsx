'use client';

import React, { useEffect, useState } from 'react';
import { ThemeProvider } from 'next-themes';
import Navbar from '@/components/Navbar';
import Footer from '@/components/Footer';
import './globals.css';

export default function RootLayout({ children }: { children: React.ReactNode }) {
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  return (
    <html lang="en" suppressHydrationWarning>
      <head>
        {/* Meta content can go here */}
      </head>
      <body className="antialiased transition-colors">
        <ThemeProvider attribute="class" defaultTheme="light" enableSystem>
          {mounted && (
            <div className="min-h-screen">
              <Navbar />
              <main className="pt-16">{children}</main>
              <Footer />
            </div>
          )}
        </ThemeProvider>
      </body>
    </html>
  );
}
