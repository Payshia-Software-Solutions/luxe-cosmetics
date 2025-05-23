"use client";

import React, { useEffect, useState } from "react";
import { ThemeProvider } from "next-themes";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import "./globals.css";
import { Geist, Geist_Mono, Inter } from "next/font/google";
import { CartProvider } from '@/components/CartContext';



const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
});

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  return (
    <html lang="en" suppressHydrationWarning>
      <head>{/* Meta content can go here */}</head>
      <body
        className={`${geistSans.variable} ${geistMono.variable} ${inter.variable} antialiased transition-colors`}
      > <CartProvider>
        <ThemeProvider attribute="class" defaultTheme="light" enableSystem>
          {mounted && (
            <div className="min-h-screen">
              <Navbar />
              <main className="">
                {" "}
               {children}
              </main>
              <Footer />
            </div>
          )}
        </ThemeProvider>
        </CartProvider>
      </body>
    </html>
  );
}
