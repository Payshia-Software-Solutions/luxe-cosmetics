import React, { useState } from 'react';
import { X, Minus, Plus, ShoppingBag } from 'lucide-react';

interface CartProps {
  onClose: () => void;
}

const initialCartItems = [
  {
    id: '1',
    name: 'Natural Glow Serum',
    price: 49.99,
    quantity: 1,
    image: 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: '2',
    name: 'Hydrating Face Cream',
    price: 39.99,
    quantity: 2,
    image: 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
  }
];

const Cart: React.FC<CartProps> = ({ onClose }) => {
  const [cartItems, setCartItems] = useState(initialCartItems);

  const handleQuantityChange = (id: string, delta: number) => {
    setCartItems((prevItems) =>
      prevItems.map((item) =>
        item.id === id ? { ...item, quantity: Math.max(1, item.quantity + delta) } : item
      )
    );
  };

  const handleRemoveItem = (id: string) => {
    setCartItems((prevItems) => prevItems.filter((item) => item.id !== id));
  };

  const subtotal = cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
  const shipping = cartItems.length > 0 ? 5.99 : 0;
  const total = subtotal + shipping;

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
              <div key={item.id} className="flex items-center space-x-4">
                <img src={item.image} alt={item.name} className="w-20 h-20 object-cover rounded-lg" />
                <div className="flex-1">
                  <h3 className="text-sm font-medium text-gray-900 dark:text-white">{item.name}</h3>
                  <p className="text-sm text-gray-500 dark:text-gray-400">${item.price.toFixed(2)}</p>
                  <div className="flex items-center mt-2 space-x-2">
                    <button onClick={() => handleQuantityChange(item.id, -1)} className="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                      <Minus className="h-4 w-4" />
                    </button>
                    <span className="text-gray-600 dark:text-gray-300">{item.quantity}</span>
                    <button onClick={() => handleQuantityChange(item.id, 1)} className="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                      <Plus className="h-4 w-4" />
                    </button>
                  </div>
                </div>
                <button onClick={() => handleRemoveItem(item.id)} className="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                  <X className="h-5 w-5" />
                </button>
              </div>
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
              <div className="flex justify-between text-sm">
                <span className="text-gray-500 dark:text-gray-400">Shipping</span>
                <span className="text-gray-900 dark:text-white">${shipping.toFixed(2)}</span>
              </div>
              <div className="flex justify-between text-lg font-medium">
                <span className="text-gray-900 dark:text-white">Total</span>
                <span className="text-gray-900 dark:text-white">${total.toFixed(2)}</span>
              </div>
              <button className="w-full bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-full font-medium flex items-center justify-center space-x-2 transition-colors">
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
