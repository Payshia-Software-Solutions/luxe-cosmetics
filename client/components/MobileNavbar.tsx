"use client";

import React, { useEffect, useState } from "react";
import { X, User, Search } from "lucide-react";
import Link from "next/link";
import Image from "next/image";

interface MobileNavbarProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function MobileNavbar({ isOpen, onClose }: MobileNavbarProps) {
  const [isAnimating, setIsAnimating] = useState(false);
  const [shouldRender, setShouldRender] = useState(false);

  useEffect(() => {
    if (isOpen) {
      setShouldRender(true);
      // Small delay to ensure DOM is ready before animation
      setTimeout(() => setIsAnimating(true), 10);
    } else {
      setIsAnimating(false);
      // Wait for animation to complete before unmounting
      setTimeout(() => setShouldRender(false), 300);
    }
  }, [isOpen]);

  const handleClose = () => {
    if (isAnimating) {
      onClose();
    }
  };

  if (!shouldRender) return null;

  return (
    <>
      {/* Backdrop */}
      <div
        className={`fixed inset-0 bg-black z-40 md:hidden transition-opacity duration-300 ease-in-out ${
          isAnimating ? "bg-opacity-50" : "bg-opacity-0"
        }`}
        onClick={handleClose}
      />

      {/* Mobile Menu - Changed from left-0 to right-0 */}
      <div
        className={`fixed top-0 right-0 h-full w-80 bg-white dark:bg-[#1e1e1e] z-50 transform transition-transform duration-300 ease-in-out md:hidden ${
          isAnimating ? "translate-x-0" : "translate-x-full"
        }`}
      >
        {/* Header */}
        <div className="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
          <Link href="/" onClick={handleClose}>
            <Image
              src="/assets/content/LogoHorizontal-optimized.png"
              alt="Company Logo"
              width={96}
              height={32}
              className="w-24"
              priority
            />
          </Link>
          <button
            onClick={handleClose}
            className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            aria-label="Close menu"
          >
            <X className="h-6 w-6 text-gray-600 dark:text-gray-300" />
          </button>
        </div>

        {/* Search Bar */}
        <div className="p-4 border-b border-gray-200 dark:border-gray-700">
          <div className="relative">
            <input
              type="text"
              placeholder="Search products..."
              className="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all"
            />
            <Search className="absolute right-3 top-3.5 h-5 w-5 text-gray-400" />
          </div>
        </div>

        {/* Navigation Links */}
        <div className="flex flex-col p-4 space-y-4">
          <Link
            href="/about"
            onClick={handleClose}
            className="flex items-center py-3 px-2 text-gray-700 dark:text-gray-300 hover:text-pink-500 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-all"
          >
            About Us
          </Link>
          <Link
            href="/shop"
            onClick={handleClose}
            className="flex items-center py-3 px-2 text-gray-700 dark:text-gray-300 hover:text-pink-500 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-all"
          >
            Shop
          </Link>
          <Link
            href="/contactus"
            onClick={handleClose}
            className="flex items-center py-3 px-2 text-gray-700 dark:text-gray-300 hover:text-pink-500 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-all"
          >
            Contact Us
          </Link>
        </div>

        {/* User Section */}
        <div className="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
          <button
            onClick={handleClose}
            className="flex items-center w-full py-3 px-2 text-gray-700 dark:text-gray-300 hover:text-pink-500 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-all"
          >
            <User className="h-5 w-5 mr-3" />
            My Account
          </button>
        </div>
      </div>
    </>
  );
}