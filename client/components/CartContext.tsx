"use client";

import React, { createContext, useState, useContext, ReactNode, useEffect } from 'react';
import { CartItem } from '@/types/CartItem'; // Adjust the import path as necessary
import { CartContextType } from '@/types/CartContextType'; // Adjust the import path as necessary


const CART_STORAGE_KEY = 'shopping-cart';

const CartContext = createContext<CartContextType | undefined>(undefined);

export const CartProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  // Initialize state with data from localStorage (if available)
  const [cartItems, setCartItems] = useState<CartItem[]>(() => {
    // Only run in browser environment
    if (typeof window !== 'undefined') {
      const savedCart = localStorage.getItem(CART_STORAGE_KEY);
      return savedCart ? JSON.parse(savedCart) : [];
    }
    return [];
  });
  
  const [isCartOpen, setIsCartOpen] = useState(false);

  // Save cart items to localStorage whenever they change
  useEffect(() => {
    if (typeof window !== 'undefined') {
      localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartItems));
    }
  }, [cartItems]);

  // Add item to cart
  const addToCart = (itemToAdd: CartItem) => {
    setCartItems(prevItems => {
      const existingItemIndex = prevItems.findIndex(item => item.id === itemToAdd.id);
      
      if (existingItemIndex >= 0) {
        // Item already exists, increase quantity
        const updatedItems = [...prevItems];
        updatedItems[existingItemIndex].quantity += itemToAdd.quantity || 1;
        return updatedItems;
      } else {
        // Add new item
        return [...prevItems, { ...itemToAdd, quantity: itemToAdd.quantity || 1 }];
      }
    });
  };

  // Remove item from cart
  const removeFromCart = (id: number) => {
    setCartItems(prevItems => prevItems.filter(item => item.id !== id));
  };

  // Update item quantity
  const updateQuantity = (id: number, delta: number) => {
    setCartItems(prevItems => 
      prevItems.map(item => 
        item.id === id 
          ? { ...item, quantity: Math.max(1, item.quantity + delta) } 
          : item
      )
    );
  };

  // Clear the entire cart
  const clearCart = () => {
    setCartItems([]);
  };

  // Toggle cart visibility
  const toggleCart = () => {
    setIsCartOpen(prev => !prev);
  };

  // Open cart
  const openCart = () => {
    setIsCartOpen(true);
  };

  // Close cart
  const closeCart = () => {
    setIsCartOpen(false);
  };

  // Get total items count
  const getCartCount = () => {
    return cartItems.reduce((total, item) => total + item.quantity, 0);
  };

  // Get total cart amount
  const getTotalAmount = () => {
    return cartItems.reduce((total, item) => total + (item.price * item.quantity), 0);
  };

  const value: CartContextType = {
    cartItems,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart,
    isCartOpen,
    toggleCart,
    openCart,
    closeCart,
    getCartCount,
    getTotalAmount,
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
};

// Custom hook to use cart context
export const useCart = (): CartContextType => {
  const context = useContext(CartContext);
  if (context === undefined) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
};