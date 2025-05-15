"use client";

import React from 'react';
import { ShoppingBag } from 'lucide-react';
import { useCart } from './CartContext';

const CartButton: React.FC = () => {
  const { toggleCart, getCartCount } = useCart();

  return (
    <button
      onClick={toggleCart}
      className="flex items-center gap-2 text-gray-700 hover:text-pink-600 dark:text-gray-300 dark:hover:text-pink-500 transition-colors"
      aria-label="Shopping cart"
    >
      <span className="relative">
        <ShoppingBag className="h-6 w-6" />
        {getCartCount() > 0 && (
          <span className="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            {getCartCount()}
          </span>
        )}
      </span>
      <span className="hidden md:inline">Cart</span>
    </button>
  );
};

export default CartButton;