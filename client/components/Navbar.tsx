"use client";

import React, { useState, useEffect, useCallback, JSX } from "react";
import { User, Sun, Moon, Search, ShoppingBag, Menu } from "lucide-react";
import { useTheme } from "next-themes";
import { useCart } from "./CartContext";
import Cart from "./Cart";
import MobileNavbar from "./MobileNavbar";
import TopBar from "./TopBar";

import Link from "next/link";
import Image from "next/image"; // Add this import

export default function Navbar(): JSX.Element {
  const { theme, setTheme, systemTheme } = useTheme();
  const { getCartCount, toggleCart } = useCart();
  const [showCart, setShowCart] = useState(false);
  const [showMobileMenu, setShowMobileMenu] = useState(false);
  const [isToggling, setIsToggling] = useState(false);
  const [mounted, setMounted] = useState(false);
  const [scrolling, setScrolling] = useState(false);
  const [showTopBar, setShowTopBar] = useState(true);

  useEffect(() => {
    setMounted(true);
  }, []);

  // Throttled scroll handler for better performance
  const handleScroll = useCallback(() => {
    const scrollY = window.scrollY;

    if (scrollY > 50) {
      setShowTopBar(false);
    } else {
      setShowTopBar(true);
    }
    setScrolling(scrollY > 0);
  }, []);

  useEffect(() => {
    let ticking = false;

    const throttledHandleScroll = () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          handleScroll();
          ticking = false;
        });
        ticking = true;
      }
    };

    window.addEventListener("scroll", throttledHandleScroll, { passive: true });
    return () => {
      window.removeEventListener("scroll", throttledHandleScroll);
    };
  }, [handleScroll]);

  const handleCartToggle = () => {
    toggleCart(); // Use the toggleCart from context
    setShowCart(!showCart);
  };

  const handleMobileMenuToggle = (): void => {
    if (isToggling) return; // Prevent multiple rapid clicks

    setIsToggling(true);
    setShowMobileMenu(!showMobileMenu);

    // Reset toggle state after animation completes
    setTimeout(() => {
      setIsToggling(false);
    }, 300);
  };

  const closeMobileMenu = (): void => {
    if (isToggling) return; // Prevent closing during animation

    setIsToggling(true);
    setShowMobileMenu(false);

    setTimeout(() => {
      setIsToggling(false);
    }, 300);
  };

  const currentTheme = mounted
    ? theme === "system"
      ? systemTheme
      : theme
    : "light";

  return (
    <>
      {/* Top Bar with better transition handling */}
      <TopBar
        showTopBar={showTopBar}
        message="Get amaizing Discounts | Limited time offer."
      />

      {/* Navbar with dynamic top positioning */}
      <nav
        className={`fixed w-full bg-white dark:bg-[#1e1e1e] z-50 transition-all duration-300 ease-in-out ${
          scrolling ? "shadow-lg" : "shadow-none"
        }`}
        style={{
          top: showTopBar ? "36px" : "0px", // Adjust based on top bar height
        }}
      >
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex justify-center items-center">
              {/* Logo */}
              <div className="flex-shrink-0 flex items-center">
                <Link href="/">
                  <Image
                    src="/assets/content/LogoHorizontal-optimized.png"
                    alt="Company Logo"
                    width={96}
                    height={32}
                    className="w-24"
                    priority
                  />
                </Link>
              </div>

              {/* Navigation Links - Hidden on mobile */}
              <div className="hidden md:flex space-x-6 ml-10">
                <Link
                  href="/"
                  className="text-gray-700 dark:text-gray-300 hover:text-pink-500 transition-colors"
                >
                  Home
                </Link>
                <Link
                  href="/about"
                  className="text-gray-700 dark:text-gray-300 hover:text-pink-500 transition-colors"
                >
                  About Us
                </Link>
                <Link
                  href="/shop"
                  className="text-gray-700 dark:text-gray-300 hover:text-pink-500 transition-colors"
                >
                  Shop
                </Link>
                <Link
                  href="/contact"
                  className="text-gray-700 dark:text-gray-300 hover:text-pink-500 transition-colors"
                >
                  Contact Us
                </Link>
              </div>
            </div>

            {/* Search Bar - Hidden on mobile */}
            <div className="hidden md:block">
              <div className="relative">
                <input
                  type="text"
                  placeholder="Search products..."
                  className="w-96 px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all"
                />
                <Search className="absolute right-3 top-2.5 h-5 w-5 text-gray-400" />
              </div>
            </div>

            {/* Icons */}
            <div className="flex items-center space-x-2 md:space-x-4">
              {/* Theme Switch */}
              <button
                onClick={() =>
                  setTheme(currentTheme === "dark" ? "light" : "dark")
                }
                className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                aria-label="Toggle theme"
              >
                {currentTheme === "dark" ? (
                  <Sun className="h-6 w-6 text-gray-600 dark:text-gray-300" />
                ) : (
                  <Moon className="h-6 w-6 text-gray-600 dark:text-gray-300" />
                )}
              </button>

              {/* User Icon - Hidden on mobile */}
              <button
                className="hidden md:flex p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                aria-label="User account"
              >
                <User className="h-6 w-6 text-gray-600 dark:text-gray-300" />
              </button>

              {/* Cart Button */}
              <button
                onClick={handleCartToggle}
                className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-2 text-gray-700 hover:text-pink-600 dark:text-gray-300 dark:hover:text-pink-500 cursor-pointer"
                aria-label="Shopping cart"
              >
                <span className="relative">
                  <ShoppingBag className="h-6 w-6" />
                  {getCartCount() > 0 && (
                    <span className="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">
                      {getCartCount()}
                    </span>
                  )}
                </span>
              </button>

              <div className="md:hidden flex items-center">
                <button
                  onClick={handleMobileMenuToggle}
                  className={`p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 ${
                    isToggling ? "opacity-50 cursor-not-allowed" : ""
                  }`}
                  disabled={isToggling}
                  aria-label="Toggle mobile menu"
                >
                  <Menu className="h-6 w-6 text-gray-600 dark:text-gray-300" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </nav>

      {/* Spacer to prevent content from being hidden under fixed navbar */}
      <div
        className={`transition-all duration-300 ${
          showTopBar ? "h-16" : "h-16"
        }`}
      ></div>

      {/* Mobile Navigation Menu */}
      <MobileNavbar isOpen={showMobileMenu} onClose={closeMobileMenu} />

      {/* Cart Modal */}
      {showCart && <Cart onClose={() => setShowCart(false)} />}
    </>
  );
}
