"use client";

import React, { useState, useEffect } from "react";
import ProductCard from "../common/ProductCard";
import { products } from "@/data/products";
import SideBar from "./SideBar";
import { motion } from "framer-motion";

const Shop: React.FC = () => {
  const [wishlist, setWishlist] = useState<string[]>([]);
  const [filterActive, setFilterActive] = useState(false);
  const [filteredProducts, setFilteredProducts] = useState(products);

  useEffect(() => {
    setFilteredProducts(products);
  }, []);

  const handleAddToCart = (productId: string) => {
    console.log(`Add to cart: ${productId}`);
    // Add animation flash or notification here
  };

  const handleToggleWishlist = (productId: string) => {
    setWishlist((prev) =>
      prev.includes(productId)
        ? prev.filter((id) => id !== productId)
        : [...prev, productId]
    );
  };

  const toggleFilters = () => {
    setFilterActive(!filterActive);
  };

  // Staggered animation for product cards
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1
      }
    }
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.1 }
    }
  };

  return (
    <div className="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      {/* Hero Section */}
      <motion.div 
        initial={{ opacity: 0, y: -20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6 }}
        className="mb-12 text-center"
      >
        <h1 className="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl mb-4">
          <span className="block">Discover Your</span>
          <span className="block text-rose-500">Perfect Beauty</span>
        </h1>
        <p className="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
          Explore our curated collection of premium cosmetics for your self-care routine&apos;s essentials.
        </p>
      </motion.div>

      {/* Mobile Filter Toggle */}
      <div className="lg:hidden mb-6">
        <button
          onClick={toggleFilters}
          className="flex items-center justify-center w-full px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200"
        >
          <span className="mr-2">{filterActive ? "Hide Filters" : "Show Filters"}</span>
          <svg className={`w-5 h-5 transform transition-transform duration-200 ${filterActive ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {/* Sidebar - hidden on mobile unless toggled */}
        <motion.div 
          className={`lg:col-span-3 ${filterActive ? 'block' : 'hidden lg:block'}`}
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.5 }}
        >
          <div className="bg-white p-6 rounded-lg shadow-md sticky top-24">
            <h2 className="text-xl font-semibold mb-6 text-gray-800">Filters</h2>
            <SideBar />
          </div>
        </motion.div>

        {/* Product Grid */}
        <div className="lg:col-span-9">
          {filteredProducts.length === 0 ? (
            <div className="text-center py-12">
              <h3 className="text-lg font-medium text-gray-900">No products found</h3>
              <p className="mt-2 text-sm text-gray-500">Try adjusting your filter to find what you&apos;re looking for.</p>
            </div>
          ) : (
            <motion.div 
              className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
              variants={containerVariants}
              initial="hidden"
              animate="visible"
            >
              {filteredProducts.map((product) => (
                <motion.div key={product.id} variants={itemVariants}>
                  <ProductCard
                    product={product}
                    onAddToCart={handleAddToCart}
                    onToggleWishlist={handleToggleWishlist}
                    isInWishlist={wishlist.includes(product.id)}
                  />
                </motion.div>
              ))}
            </motion.div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Shop;
