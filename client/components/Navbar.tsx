"use client";

import React, { useState, useEffect } from 'react';
import { ShoppingCart, User, Sun, Moon, Search } from 'lucide-react';
import { useTheme } from 'next-themes';
import Cart from './Cart';
import Link from 'next/link';

export default function Navbar() {
  const { theme, setTheme, systemTheme } = useTheme();
  const [showCart, setShowCart] = useState(false);
  const [mounted, setMounted] = useState(false);
  const [scrolling, setScrolling] = useState(false);
  const [showTopBar, setShowTopBar] = useState(true);

  useEffect(() => {
    setMounted(true);
  }, []);

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 50) {
        setShowTopBar(false);
      } else {
        setShowTopBar(true);
      }
      setScrolling(window.scrollY > 0);
    };

    window.addEventListener('scroll', handleScroll);
    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  }, []);

  const handleCartToggle = () => {
    setShowCart(!showCart);
  };

  const currentTheme = mounted ? (theme === 'system' ? systemTheme : theme) : 'light';

  return (
    <>
      {/* Top Bar */}
      {showTopBar && (
        <div className="bg-black text-white text-center py-2 text-sm transition-opacity duration-300 ease-in-out">
          <p>Free shipping on orders over $50! Limited time offer.</p>
        </div>
      )}

      {/* Navbar */}
      <nav
        className={`fixed w-full bg-white dark:bg-[#1e1e1e] z-50 transition-all duration-300 ease-in-out ${
          scrolling ? 'shadow-lg' : 'shadow-none'
        }`}
      >
        <div className="max-w-7xl mx-auto ">
          <div className="flex justify-between items-center h-16">
            
            {/* Logo */}
            <div className="flex-shrink-0 flex items-center">
              <Link href="/">
                <h1 className="text-2xl font-bold uppercase text-pink-600 dark:text-pink-400">paris beauty</h1>
              </Link>
            </div>

            {/* Navigation Links */}
            <div className="hidden md:flex space-x-6 ml-10">
              <Link href="/about" className="text-gray-700 dark:text-gray-300 hover:text-pink-500 transition">
                About Us
              </Link>
              <Link href="/shop" className="text-gray-700 dark:text-gray-300 hover:text-pink-500 transition">
                Shop
              </Link>
              <Link href="/contactus" className="text-gray-700 dark:text-gray-300 hover:text-pink-500 transition">
                Contact Us
              </Link>
            </div>

            {/* Search Bar */}
            <div className="hidden md:block">
              <div className="relative">
                <input
                  type="text"
                  placeholder="Search products..."
                  className="w-96 px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-500"
                />
                <Search className="absolute right-3 top-2.5 h-5 w-5 text-gray-400" />
              </div>
            </div>

            {/* Icons */}
            <div className="flex items-center space-x-4">
              {/* Theme Switch */}
              <button
                onClick={() => setTheme(currentTheme === 'dark' ? 'light' : 'dark')}
                className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
              >
                {currentTheme === 'dark' ? (
                  <Sun className="h-6 w-6 text-gray-600 dark:text-gray-300" />
                ) : (
                  <Moon className="h-6 w-6 text-gray-600 dark:text-gray-300" />
                )}
              </button>

              {/* User Icon */}
              <button className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <User className="h-6 w-6 text-gray-600 dark:text-gray-300" />
              </button>

              {/* Cart Icon */}
              <button
                onClick={handleCartToggle}
                className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
              >
                <ShoppingCart className="h-6 w-6 text-gray-600 dark:text-gray-300" />
              </button>
            </div>

          </div>
        </div>
      </nav>

      {/* Cart Modal */}
      {showCart && <Cart onClose={() => setShowCart(false)} />}
    </>
  );
}
