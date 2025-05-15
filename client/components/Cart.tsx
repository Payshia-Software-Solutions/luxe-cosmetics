"use client";

import React from 'react';
import { X, ShoppingBag } from 'lucide-react';
import { useRouter } from 'next/navigation';
import CartRow from './CartRow';
import { useCart } from './CartContext';

const Cart: React.FC<{ onClose: () => void }> = ({ onClose }) => {
  const router = useRouter();
  const { cartItems, updateQuantity, removeFromCart } = useCart();
  
  const subtotal = cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
  // const shipping = cartItems.length > 0 ? 5.99 : 0;
  const total = subtotal;

  const handleCheckout = () => {
    // Close the cart drawer
    onClose();
    // Navigate to checkout page
    router.push('/checkout');
  };

  return (
    <div className="fixed inset-y-0 right-0 w-full md:w-96 bg-white dark:bg-gray-900 shadow-xl transform transition-transform duration-300 ease-in-out z-50">
      <div className="flex flex-col h-full">
        {/* Cart Header */}
        <div className="p-6 border-b dark:border-gray-700 flex items-center justify-between">
          <h2 className="text-2xl font-bold text-gray-900 dark:text-white">Shopping Cart</h2>
          <button onClick={onClose} className="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
            <X className="h-6 w-6" />
          </button>
        </div>

        {/* Cart Items */}
        <div className="flex-1 overflow-y-auto p-6 space-y-6">
          {cartItems.length === 0 ? (
            <p className="text-gray-500 dark:text-gray-400 text-center">Your cart is empty.</p>
          ) : (
            cartItems.map((item) => (
              <CartRow 
                key={item.id} 
                item={item} 
                onQuantityChange={(id, delta) => updateQuantity(id, delta)} 
                onRemove={removeFromCart} 
              />
            ))
          )}
        </div>

        {/* Cart Footer */}
        {cartItems.length > 0 && (
          <div className="p-6 border-t dark:border-gray-700">
            <div className="space-y-4">
              <div className="flex justify-between text-sm">
                <span className="text-gray-500 dark:text-gray-400">Subtotal</span>
                <span className="text-gray-900 dark:text-white">${subtotal.toFixed(2)}</span>
              </div>
              {/* <div className="flex justify-between text-sm">
                <span className="text-gray-500 dark:text-gray-400">Shipping</span>
                <span className="text-gray-900 dark:text-white">${shipping.toFixed(2)}</span>
              </div> */}
              <div className="flex justify-between text-lg font-medium">
                <span className="text-gray-900 dark:text-white">Total</span>
                <span className="text-gray-900 dark:text-white">${total.toFixed(2)}</span>
              </div>
              <button 
                onClick={handleCheckout}
                className="w-full bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-full font-medium flex items-center justify-center space-x-2 transition-colors"
              >
                <ShoppingBag className="h-5 w-5" />
                <span>Checkout</span>
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Cart;